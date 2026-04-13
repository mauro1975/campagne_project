@extends('layouts.admin')

@section('title', 'Report')
@section('page_title', 'Report e Analisi')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')

{{-- Period Selector --}}
<form method="GET" style="display:flex;gap:12px;margin-bottom:28px;">
    <select name="period" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
        <option value="7" {{ request('period','30')=='7'?'selected':'' }}>Ultimi 7 giorni</option>
        <option value="30" {{ request('period','30')=='30'?'selected':'' }}>Ultimi 30 giorni</option>
        <option value="90" {{ request('period','30')=='90'?'selected':'' }}>Ultimi 90 giorni</option>
        <option value="365" {{ request('period','30')=='365'?'selected':'' }}>Ultimi 12 mesi</option>
    </select>
</form>

{{-- KPI Cards --}}
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <p class="stat-label">Fatturato totale</p>
            <p class="stat-value">€{{ number_format($kpis['revenue'] ?? 0, 0) }}</p>
            <p style="font-size:13px;color:var(--green-dark);margin:4px 0 0;"><i class="bi bi-arrow-up"></i> totale periodo</p>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <p class="stat-label">Ordini</p>
            <p class="stat-value">{{ $kpis['orders'] ?? 0 }}</p>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <p class="stat-label">Valore medio ordine</p>
            <p class="stat-value">€{{ number_format($kpis['avg_order'] ?? 0, 2) }}</p>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <p class="stat-label">Pagine visualizzate</p>
            <p class="stat-value">{{ $kpis['page_views'] ?? 0 }}</p>
        </div>
    </div>
</div>

{{-- Revenue Chart --}}
<div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;margin-bottom:24px;">
    <h5 style="font-weight:700;margin-bottom:24px;">Fatturato nel tempo</h5>
    <canvas id="revenueLineChart" height="60"></canvas>
</div>

<div class="row g-4 mb-4">
    {{-- Orders by Status (Bar) --}}
    <div class="col-lg-6">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;">
            <h5 style="font-weight:700;margin-bottom:20px;">Ordini per Stato</h5>
            <canvas id="ordersBarChart" height="200"></canvas>
        </div>
    </div>
    {{-- Revenue by Category (Pie) --}}
    <div class="col-lg-6">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;">
            <h5 style="font-weight:700;margin-bottom:20px;">Fatturato per Categoria</h5>
            <canvas id="categoryPieChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Top Products Table --}}
<div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;">
    <h5 style="font-weight:700;margin-bottom:20px;">Prodotti Top</h5>
    <table class="admin-table" style="border:none;border-radius:0;">
        <thead><tr><th>Prodotto</th><th>Unità vendute</th><th>Fatturato</th></tr></thead>
        <tbody>
            @forelse($topProducts ?? [] as $tp)
            <tr>
                <td style="font-weight:600;">{{ $tp->product_name }}</td>
                <td>{{ $tp->total_sold }}</td>
                <td style="font-weight:700;">€{{ number_format($tp->total_revenue, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;padding:32px;color:#999;">Nessun dato di vendita ancora.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script>
const revenueData   = @json($revenueData ?? []);
const ordersData    = @json($ordersData ?? []);
const categoryData  = @json($categoryData ?? []);

// Revenue Line
new Chart(document.getElementById('revenueLineChart'), {
    type: 'line',
    data: {
        labels: revenueData.labels ?? [],
        datasets: [{
            label: 'Fatturato (€)',
            data: revenueData.values ?? [],
            borderColor: '#9BC3B1',
            backgroundColor: 'rgba(154,215,160,0.1)',
            borderWidth: 2, fill: true, tension: 0.4,
            pointBackgroundColor: '#9BC3B1',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '€'+v }, grid: { color: '#f5f5f5' } },
            x: { grid: { display: false } }
        }
    }
});

// Orders Bar
if(ordersData && ordersData.labels){
    new Chart(document.getElementById('ordersBarChart'), {
        type: 'bar',
        data: {
            labels: ordersData.labels,
            datasets: [{
                label: 'Ordini',
                data: ordersData.values,
                backgroundColor: ['#fff3cd','#cfe2ff','#e2d9f3','#d1e7dd','#f8d7da'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f5f5f5' } }, x: { grid: { display: false } } }
        }
    });
}

// Category Pie
if(categoryData && categoryData.labels && categoryData.labels.length){
    new Chart(document.getElementById('categoryPieChart'), {
        type: 'doughnut',
        data: {
            labels: categoryData.labels,
            datasets: [{
                data: categoryData.values,
                backgroundColor: ['#9BC3B1','#6fa398','#4d8c7a','#2d6b5a','#1a4a3d','#c8e2db'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font:{size:12}, padding:12 } } },
            cutout: '60%',
        }
    });
}
</script>
@endsection

