@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('styles')
<style>
    .chart-card { background:#fff; border-radius:14px; padding:1.4rem; box-shadow:0 2px 12px rgba(0,0,0,.05); }
    .chart-card .chart-title { font-weight:700; color:#1a3c6e; font-size:.95rem; margin-bottom:1rem; }
</style>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-primary bg-opacity-10">
                <i class="bi bi-people-fill text-primary"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-success bg-opacity-10">
                <i class="bi bi-book-fill text-success"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalSubjects) }}</div>
                <div class="stat-label">Total Subjects</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-warning bg-opacity-10">
                <i class="bi bi-bookmark-fill text-warning"></i>
            </div>
            <div>
                <div class="stat-value">{{ Auth::user()->subjects()->count() }}</div>
                <div class="stat-label">My Subjects</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-info bg-opacity-10">
                <i class="bi bi-calendar3 text-info"></i>
            </div>
            <div>
                <div class="stat-value">{{ now()->format('M Y') }}</div>
                <div class="stat-label">Current Period</div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-title"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Subjects by Semester</div>
            <canvas id="semesterChart" height="240"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-title"><i class="bi bi-bar-chart-fill me-2 text-success"></i>Subjects by Units</div>
            <canvas id="unitsChart" height="240"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-title"><i class="bi bi-graph-up me-2 text-warning"></i>Subjects Added (Last 6 Months)</div>
            <canvas id="subjectsLineChart" height="240"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-title"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Users Registered (Last 6 Months)</div>
            <canvas id="usersLineChart" height="240"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const palette = ['#2756a8','#f4a623','#22c55e','#ef4444','#8b5cf6','#06b6d4'];

// Semester Doughnut
const semesterData = @json($subjectBySemester);
new Chart(document.getElementById('semesterChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(semesterData).length ? Object.keys(semesterData) : ['No Data'],
        datasets: [{ data: Object.keys(semesterData).length ? Object.values(semesterData) : [1],
            backgroundColor: palette, borderWidth: 0, hoverOffset: 8 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } } }
    }
});

// Units Bar
const unitsData = @json($subjectByUnits);
new Chart(document.getElementById('unitsChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(unitsData).map(u => u + ' Unit' + (u > 1 ? 's' : '')),
        datasets: [{ label: 'Subjects', data: Object.values(unitsData),
            backgroundColor: '#2756a8', borderRadius: 6, borderSkipped: false }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
    }
});

// Subjects Line
const subjectsMonths = @json($subjectsPerMonth);
new Chart(document.getElementById('subjectsLineChart'), {
    type: 'line',
    data: {
        labels: Object.keys(subjectsMonths).length ? Object.keys(subjectsMonths) : ['No Data'],
        datasets: [{ label: 'Subjects Added', data: Object.values(subjectsMonths),
            borderColor: '#f4a623', backgroundColor: 'rgba(244,166,35,.12)',
            borderWidth: 2.5, pointRadius: 5, pointBackgroundColor: '#f4a623', fill: true, tension: .35 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Users Line
const usersMonths = @json($usersPerMonth);
new Chart(document.getElementById('usersLineChart'), {
    type: 'line',
    data: {
        labels: Object.keys(usersMonths).length ? Object.keys(usersMonths) : ['No Data'],
        datasets: [{ label: 'Users Registered', data: Object.values(usersMonths),
            borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.12)',
            borderWidth: 2.5, pointRadius: 5, pointBackgroundColor: '#22c55e', fill: true, tension: .35 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endsection
