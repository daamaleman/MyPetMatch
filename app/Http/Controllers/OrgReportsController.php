<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Pet;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrgReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:organizacion,admin']);
    }

    // Reports landing: separate filters for charts and tables
    public function index(Request $request)
    {
        $user = Auth::user();
        $orgId = $user->organization_id ?? null;
        if (!$orgId) {
            return view('orgs.reports', [
                'charts' => [], 'tables' => [],
                'filtersCharts' => [], 'filtersTables' => [],
                'org' => null,
            ]);
        }

        $org = Organization::find($orgId);

        // Filters for charts
        $cFrom = $request->input('c_from');
        $cTo = $request->input('c_to');
    $cStatus = (array) $request->input('c_status', []);
    $cInterval = $request->input('c_interval', 'week');
    if (!in_array($cInterval, ['week','month'])) { $cInterval = 'week'; }
        $cRangeStart = $cFrom ? Carbon::parse($cFrom)->startOfDay() : Carbon::now()->subDays(90)->startOfDay();
        $cRangeEnd = $cTo ? Carbon::parse($cTo)->endOfDay() : Carbon::now()->endOfDay();

        // Base query scoped to org
        $appBase = AdoptionApplication::query()->where('organization_id', $orgId);

        $appsForCharts = (clone $appBase)
            ->whereBetween('created_at', [$cRangeStart, $cRangeEnd]);
        if (!empty($cStatus)) {
            $appsForCharts->whereIn('status', $cStatus);
        }

        // Chart: trend aggregated by selected interval (week or month)
        $trend = [];
        if ($cInterval === 'month') {
            // Raw counts keyed by YYYY-MM
            $trendRaw = (clone $appsForCharts)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as p, COUNT(*) as total")
                ->groupBy('p')
                ->orderBy('p')
                ->pluck('total','p')
                ->toArray();

            $cursor = $cRangeStart->copy()->startOfMonth();
            $end = $cRangeEnd->copy()->endOfMonth();
            while ($cursor <= $end) {
                $key = $cursor->format('Y-m'); // e.g., 2025-10
                $label = $cursor->isoFormat('MMM YYYY'); // e.g., Oct 2025 (localized)
                $trend[$label] = (int)($trendRaw[$key] ?? 0);
                $cursor->addMonthNoOverflow()->startOfMonth();
            }
        } else {
            // week (ISO week): use YEARWEEK(..., 3) to align with ISO-8601
            $trendRaw = (clone $appsForCharts)
                ->selectRaw("YEARWEEK(created_at, 3) as p, COUNT(*) as total")
                ->groupBy('p')
                ->orderBy('p')
                ->pluck('total','p')
                ->toArray();

            $cursor = $cRangeStart->copy()->startOfWeek();
            $end = $cRangeEnd->copy()->endOfWeek();
            while ($cursor <= $end) {
                // Build numeric key like 202541 to match YEARWEEK(mode 3)
                $numericKey = (int)($cursor->format('o') . $cursor->format('W'));
                $label = $cursor->format('o') . '-W' . $cursor->format('W'); // e.g., 2025-W41
                $trend[$label] = (int)($trendRaw[$numericKey] ?? 0);
                $cursor->addWeek()->startOfWeek();
            }
        }

        // Chart: status distribution
        $statusDist = (clone $appsForCharts)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status')
            ->toArray();

        // Filters for tables (independent)
        $tFrom = $request->input('t_from');
        $tTo = $request->input('t_to');
        $tStatus = $request->input('t_status');
        $tSearch = $request->input('t_q');
        $tRangeStart = $tFrom ? Carbon::parse($tFrom)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $tRangeEnd = $tTo ? Carbon::parse($tTo)->endOfDay() : Carbon::now()->endOfDay();

        $appsForTable = (clone $appBase)
            ->with(['pet','user'])
            ->whereBetween('created_at', [$tRangeStart, $tRangeEnd]);
        if (!empty($tStatus)) {
            $appsForTable->where('status', $tStatus);
        }
        if (!empty($tSearch)) {
            $q = $tSearch;
            $appsForTable->where(function($sub) use ($q) {
                $sub->whereHas('pet', fn($p) => $p->where('name','like',"%$q%"))
                    ->orWhereHas('user', fn($u) => $u->where('name','like',"%$q%"));
            });
        }
        $tableRows = $appsForTable->latest()->paginate(15)->withQueryString();

        return view('orgs.reports', [
            'org' => $org,
            'charts' => [
                'trend' => $trend,
                'status' => $statusDist,
                'range' => [$cRangeStart->toDateString(), $cRangeEnd->toDateString()],
            ],
            'filtersCharts' => [
                'from' => $cRangeStart->toDateString(),
                'to' => $cRangeEnd->toDateString(),
                'status' => $cStatus,
                'interval' => $cInterval,
            ],
            'tables' => [
                'rows' => $tableRows,
            ],
            'filtersTables' => [
                'from' => $tRangeStart->toDateString(),
                'to' => $tRangeEnd->toDateString(),
                'status' => $tStatus,
                'q' => $tSearch,
            ],
        ]);
    }
}
