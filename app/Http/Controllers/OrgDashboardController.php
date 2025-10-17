<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Organization;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
            $petQuery->whereIn('pets.organization_id', $orgIds);
        } else {
            // Force empty set
            $petQuery->whereRaw('1=0');
        }
        if ($orgIds && count($orgIds) > 0 && Schema::hasColumn('adoption_applications', 'organization_id')) {
            $appQuery->whereIn('adoption_applications.organization_id', $orgIds);
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

        // --------------------
        // KPIs and analytics
        // --------------------
        $now = Carbon::now();
        $rangeStart = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : $now->copy()->subDays(90)->startOfDay();
        $rangeEnd = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : $now->copy()->endOfDay();

        // KPI: Mascotas publicadas (published/available)
        $publishedStatuses = ['published','available'];
        $kpi_pets_published = (clone $petQuery)
            ->whereIn('status', $publishedStatuses)
            ->count();

        // KPI: Solicitudes nuevas (últimos 7 días)
        $kpi_new_apps_7d = (clone $appQuery)
            ->where('adoption_applications.created_at', '>=', $now->copy()->subDays(7))
            ->count();

        // KPI: Solicitudes pendientes (submitted/pending/under_review)
        $pendingStatuses = ['submitted','pending','under_review'];
        $kpi_pending_apps = (clone $appQuery)->whereIn('status', $pendingStatuses)->count();

        // KPI: Adopciones confirmadas (30 días) -> adopted o approved
        $confirmedStatuses = ['adopted','approved'];
        $kpi_confirmed_30d = (clone $appQuery)
            ->whereIn('adoption_applications.status', $confirmedStatuses)
            ->where('adoption_applications.updated_at', '>=', $now->copy()->subDays(30))
            ->count();

        // KPI: Mascotas +30 días publicadas
        $kpi_pets_30d_published = (clone $petQuery)
            ->whereIn('pets.status', $publishedStatuses)
            ->where('pets.created_at', '<', $now->copy()->subDays(30))
            ->count();

        $kpis = [
            'pets_published' => $kpi_pets_published,
            'new_apps_7d' => $kpi_new_apps_7d,
            'pending_apps' => $kpi_pending_apps,
            'confirmed_30d' => $kpi_confirmed_30d,
            'pets_published_30d' => $kpi_pets_30d_published,
        ];

        // Support series for micro-charts
        // Daily applications last 7 days
        $from7 = $now->copy()->subDays(6)->startOfDay();
        $apps7dRaw = (clone $appQuery)
            ->whereBetween('adoption_applications.created_at', [$from7, $now->copy()->endOfDay()])
            ->selectRaw("DATE(adoption_applications.created_at) as d, COUNT(*) as total")
            ->groupBy('d')
            ->pluck('total','d')
            ->toArray();
        $seriesDailyApps7d = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $from7->copy()->addDays($i)->toDateString();
            $seriesDailyApps7d[$day] = (int)($apps7dRaw[$day] ?? 0);
        }

        // Confirmed (approved/adopted) per day last 30 days by updated_at
        $from30 = $now->copy()->subDays(29)->startOfDay();
        $conf30Raw = (clone $appQuery)
            ->whereIn('adoption_applications.status', $confirmedStatuses)
            ->whereBetween('adoption_applications.updated_at', [$from30, $now->copy()->endOfDay()])
            ->selectRaw("DATE(adoption_applications.updated_at) as d, COUNT(*) as total")
            ->groupBy('d')
            ->pluck('total','d')
            ->toArray();
        $seriesConfirmed30d = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $from30->copy()->addDays($i)->toDateString();
            $seriesConfirmed30d[$day] = (int)($conf30Raw[$day] ?? 0);
        }

        // Pending breakdown (submitted, pending, under_review)
        $pendingBreakdownRaw = (clone $appQuery)
            ->whereIn('adoption_applications.status', $pendingStatuses)
            ->selectRaw('adoption_applications.status, COUNT(*) as total')
            ->groupBy('adoption_applications.status')
            ->pluck('total','adoption_applications.status')
            ->toArray();
        $pendingBreakdown = [];
        foreach ($pendingStatuses as $ps) {
            $pendingBreakdown[$ps] = (int)($pendingBreakdownRaw[$ps] ?? 0);
        }

        // Published breakdown (published, available) if available
        $publishedBreakdown = [];
        try {
            $pbRaw = (clone $petQuery)
                ->whereIn('pets.status', $publishedStatuses)
                ->selectRaw('pets.status, COUNT(*) as total')
                ->groupBy('pets.status')
                ->pluck('total','pets.status')
                ->toArray();
        } catch (\Throwable $e) {
            $pbRaw = [];
        }
        foreach ($publishedStatuses as $ps) {
            $publishedBreakdown[$ps] = (int)($pbRaw[$ps] ?? 0);
        }

        // Aging ratio for published pets (>30 days)
        $publishedAging = [
            'old' => $kpi_pets_30d_published,
            'total' => $kpi_pets_published,
            'percentOld' => $kpi_pets_published ? round(($kpi_pets_30d_published / $kpi_pets_published) * 100) : 0,
        ];

        // Funnel (by applications created in range)
    $appsInRange = (clone $appQuery)->whereBetween('adoption_applications.created_at', [$rangeStart, $rangeEnd]);
        $distinctPetIdsWithApps = (clone $appsInRange)->distinct('pet_id')->pluck('pet_id');

        $funnel_published = (clone $petQuery)
            ->whereIn('status', $publishedStatuses)
            ->whereIn('id', $distinctPetIdsWithApps)
            ->count();
        $funnel_with_apps = $distinctPetIdsWithApps->count();
    $funnel_under_review = (clone $appsInRange)->where('adoption_applications.status', 'under_review')->count();
    $funnel_interview = (clone $appsInRange)->where('adoption_applications.status', 'interview')->count(); // may be 0 if not used
    $funnel_approved = (clone $appsInRange)->where('adoption_applications.status', 'approved')->count();
    $funnel_adopted = (clone $appsInRange)->where('adoption_applications.status', 'adopted')->count();
        $funnel = [
            'published' => $funnel_published,
            'con_solicitudes' => $funnel_with_apps,
            'under_review' => $funnel_under_review,
            'interview' => $funnel_interview,
            'approved' => $funnel_approved,
            'adopted' => $funnel_adopted,
        ];

        // Trend (month by month) within range
        $trendRaw = (clone $appsInRange)
            ->selectRaw("DATE_FORMAT(adoption_applications.created_at, '%Y-%m-01') as month_key, COUNT(*) as total")
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->pluck('total', 'month_key')
            ->toArray();
        // Fill missing months
        $trend = [];
        $cursor = $rangeStart->copy()->startOfMonth();
        $endMonth = $rangeEnd->copy()->startOfMonth();
        while ($cursor <= $endMonth) {
            $key = $cursor->format('Y-m-01');
            $trend[$key] = (int)($trendRaw[$key] ?? 0);
            $cursor->addMonth();
        }

        // Inventory by pet status (current)
        $inventory = (clone $petQuery)
            ->selectRaw('pets.status, COUNT(*) as total')
            ->groupBy('pets.status')
            ->pluck('total', 'pets.status')
            ->toArray();

        // Recent activity cards
        $recentPets = (clone $petQuery)->latest()->limit(5)->get();
        $recentApps = (clone $appQuery)->with(['pet','user'])->latest()->limit(5)->get();

        // Status breakdown for sidebar widget (kept)
        $statusBreakdown = [];
        if (Schema::hasColumn('adoption_applications', 'status')) {
            $statusBreakdown = (clone $appQuery)
                ->selectRaw('adoption_applications.status, COUNT(*) as total')
                ->groupBy('adoption_applications.status')
                ->pluck('total', 'adoption_applications.status')
                ->toArray();
        }

        // Tables: Pets without applications
        $petsWithoutApps = (clone $petQuery)
            ->whereIn('pets.status', $publishedStatuses)
            ->leftJoin('adoption_applications as aa', 'aa.pet_id', '=', 'pets.id')
            ->groupBy('pets.id','pets.name','pets.created_at')
            ->havingRaw('COUNT(aa.id) = 0')
            ->orderBy('pets.created_at', 'asc')
            ->limit(15)
            ->get(['pets.id','pets.name','pets.created_at']);
        $petsWithoutApps = $petsWithoutApps->map(function ($p) use ($now) {
            $p->days_published = Carbon::parse($p->created_at)->diffInDays($now);
            return $p;
        });

        // Tables: Stuck applications (>7 days without update)
        $stuckStatuses = ['submitted','pending','under_review','interview'];
        $stuckApps = (clone $appQuery)
            ->whereIn('adoption_applications.status', $stuckStatuses)
            ->where('adoption_applications.updated_at', '<', $now->copy()->subDays(7))
            ->with(['pet','user'])
            ->orderBy('updated_at','asc')
            ->limit(15)
            ->get();
        $stuckApps = $stuckApps->map(function ($a) use ($now) {
            $a->days_stuck = Carbon::parse($a->updated_at)->diffInDays($now);
            return $a;
        });

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
            'user', 'recentPets', 'recentApps', 'statusBreakdown',
            'primaryOrg', 'orgProfileIncomplete', 'orgMissingLabels',
            'kpis', 'funnel', 'trend', 'inventory', 'rangeStart', 'rangeEnd',
            'petsWithoutApps', 'stuckApps',
            'seriesDailyApps7d', 'seriesConfirmed30d', 'pendingBreakdown', 'publishedBreakdown', 'publishedAging'
        ));
    }
}
