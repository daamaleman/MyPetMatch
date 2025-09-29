<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Organization;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class OrgDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Try to resolve organization IDs for the user if relation exists; else null (global fallback)
    $orgIds = null;
        if ($user) {
            // Prefer single organization() relation if defined
            if (method_exists($user, 'organization')) {
                try {
                    $org = $user->organization;
                    if ($org && isset($org->id)) {
                        $orgIds = [$org->id];
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }
            // Fallback to organizations() relation if a pivot is used elsewhere
            if (!$orgIds && method_exists($user, 'organizations')) {
                try {
                    $relation = call_user_func([$user, 'organizations']);
                    $orgIds = $relation->pluck('organizations.id')->all();
                } catch (\Throwable $e) {
                    $orgIds = null;
                }
            }
        }

        // Resolve single organization (principal) to assess profile completeness
        $primaryOrg = null;
        if ($orgIds && count($orgIds) > 0) {
            $primaryOrg = Organization::query()->find($orgIds[0]);
        }

        $petQuery = Pet::query();
        $appQuery = AdoptionApplication::query();

        // Scope by organization strictly; if none is linked, return no results
        if ($orgIds && count($orgIds) > 0 && Schema::hasColumn('pets', 'organization_id')) {
            $petQuery->whereIn('organization_id', $orgIds);
        } else {
            // Force empty set
            $petQuery->whereRaw('1=0');
        }
        if ($orgIds && count($orgIds) > 0 && Schema::hasColumn('adoption_applications', 'organization_id')) {
            $appQuery->whereIn('organization_id', $orgIds);
        } elseif ($orgIds && count($orgIds) > 0 && Schema::hasColumn('adoption_applications', 'pet_id') && Schema::hasColumn('pets', 'organization_id')) {
            // Attempt to scope via pet relation if it exists
            try {
                $appQuery->whereHas('pet', function ($q) use ($orgIds) {
                    $q->whereIn('organization_id', $orgIds);
                });
            } catch (\Throwable $e) {
                // If relation not defined yet, fall back to empty
                $appQuery->whereRaw('1=0');
            }
        } else {
            $appQuery->whereRaw('1=0');
        }

        $stats = [
            'pets_total' => (clone $petQuery)->count(),
            'applications_total' => (clone $appQuery)->count(),
            'pets_published' => null,
            'applications_pending' => null,
        ];

        if (Schema::hasColumn('pets', 'status')) {
            $stats['pets_published'] = (clone $petQuery)->where('status', 'published')->count();
        }
        if (Schema::hasColumn('adoption_applications', 'status')) {
            $stats['applications_pending'] = (clone $appQuery)->where('status', 'pending')->count();
        }

        $recentPets = (clone $petQuery)->latest()->limit(5)->get();
        $recentApps = (clone $appQuery)->latest()->limit(5)->get();

        $statusBreakdown = [];
        if (Schema::hasColumn('adoption_applications', 'status')) {
            $statusBreakdown = (clone $appQuery)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        }

        // Compute organization profile completeness
        $requiredFields = ['email','phone','city','state','country'];
        $missingFields = [];
        if ($primaryOrg) {
            foreach ($requiredFields as $field) {
                $value = $primaryOrg->{$field} ?? null;
                if (blank($value)) {
                    $missingFields[] = $field;
                }
            }
        } else {
            // If user has no organization linked yet, consider it incomplete
            $missingFields = $requiredFields;
        }
        $labels = [
            'email' => 'Email',
            'phone' => 'Teléfono',
            'city' => 'Municipio',
            'state' => 'Departamento',
            'country' => 'País',
            'description' => 'Descripción',
        ];
        $orgMissingLabels = array_map(function($f) use ($labels) {
            return $labels[$f] ?? ucfirst($f);
        }, $missingFields);
        $orgProfileIncomplete = !$primaryOrg || count($missingFields) > 0;

        return view('orgs.dashboard', compact(
            'user', 'stats', 'recentPets', 'recentApps', 'statusBreakdown',
            'primaryOrg', 'orgProfileIncomplete', 'orgMissingLabels'
        ));
    }
}
