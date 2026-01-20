<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use App\Models\Ipress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $ipressWithUsers = Ipress::whereHas('users')->count();
        $usersTotal = User::whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['paciente', 'superadmin']);
        })->count();
        $patientsTotal = User::whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })->count();

        $followupsTotal = Diagnostic::count();
        $followupsMonth = Diagnostic::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();

        $patientsWithoutFollowup = User::whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })->whereDoesntHave('diagnostics')->count();

        $recentFollowups = Diagnostic::with(['patient:id,name,lastname', 'cie10:id,name', 'ipress:id,name'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function ($diagnostic) {
                return [
                    'id' => $diagnostic->id,
                    'patient' => trim($diagnostic->patient->name . ' ' . $diagnostic->patient->lastname),
                    'cie10' => $diagnostic->cie10 ? $diagnostic->cie10->name : '',
                    'ipress' => $diagnostic->ipress ? $diagnostic->ipress->name : '',
                    'created_at' => $diagnostic->created_at,
                ];
            });

        $topCie10 = Diagnostic::select('cie10_id', DB::raw('count(*) as total'))
            ->with('cie10:id,name')
            ->groupBy('cie10_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->cie10 ? $row->cie10->name : '',
                    'total' => (int) $row->total,
                ];
            });

        $monthLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trendLabels[] = $monthLabels[$month->month - 1];
            $trendData[] = Diagnostic::whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth(),
            ])->count();
        }

        return response()->json([
            'ipress_with_users' => $ipressWithUsers,
            'users_total' => $usersTotal,
            'patients_total' => $patientsTotal,
            'followups_total' => $followupsTotal,
            'followups_month' => $followupsMonth,
            'patients_without_followup' => $patientsWithoutFollowup,
            'recent_followups' => $recentFollowups,
            'top_cie10' => $topCie10,
            'followups_trend' => [
                'labels' => $trendLabels,
                'data' => $trendData,
            ],
        ]);
    }
}
