<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers    = User::count();
        $totalSubjects = Subject::count();

        // Subject statistics by semester (all users, for admin view)
        $subjectBySemester = Subject::select('semester', DB::raw('count(*) as count'))
            ->groupBy('semester')
            ->pluck('count', 'semester');

        // Subject statistics by units
        $subjectByUnits = Subject::select('units', DB::raw('count(*) as count'))
            ->groupBy('units')
            ->orderBy('units')
            ->pluck('count', 'units');

        // Users registered per month (last 6 months)
        $usersPerMonth = User::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%b %Y')"))
            ->orderBy('created_at')
            ->pluck('count', 'month');

        // Subjects added per month (last 6 months)
        $subjectsPerMonth = Subject::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%b %Y')"))
            ->orderBy('created_at')
            ->pluck('count', 'month');

        return view('dashboard.index', compact(
            'totalUsers',
            'totalSubjects',
            'subjectBySemester',
            'subjectByUnits',
            'usersPerMonth',
            'subjectsPerMonth'
        ));
    }
}
