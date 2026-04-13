@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4;color:var(--green-dark);">
                <i class="bi bi-currency-euro"></i>
            </div>
            <div>
                <p class="stat-label">Fatturato (30g)</p>
                <p class="stat-value">€{{ number_format($stats['revenue_30d'] ?? 0, 0) }}</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eff6ff;color:#3b82f6;">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <p class="stat-label">Ordini (30g)</p>
                <p class="stat-value">{{ $stats['orders_30d'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fdf4ff;color:#a855f7;">
                <i class="bi bi-bag"></i>
            </div>
            <div>
                <p class="stat-label">Prodotti</p>
                <p class="stat-value">{{ $stats['total_products'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fff7ed;color:#f97316;">
                <i class="bi bi-eye"></i>
            </div>
            <div>
                <p class="stat-label">Visitatori (30g)</p>
                <p class="stat-value">{{ $stats['visitors_30d'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-5">
    <div class="col-lg-8">
        <div style="background:#fff;border-radius:16px;padding:28px;border:1px solid #eee;">
            <h5 style="font-weight:700;margin-bottom:24px;">Fatturato — Ultimi 30 Giorni</h5>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div style="background:#fff;border-radius:16px;padding:28px;border:1px solid #eee;">
            <h5 style="font-weight:700;margin-bottom:24px;">Vendite per Categoria</h5>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Recent Orders + Top Products --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div style="background:#fff;border-radius:16px;padding:28px;border:1px solid #eee;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 style="font-weight:700;margin:0;">Ordini Recenti</h5>
                <a href="{{ route('admin.orders') }}" style="color:var(--green-dark);font-size:13px;font-weight:600;">Vedi tutti →</a>
            </div>
            @if(isset($recentOrders) && $recentOrders->count() > 0)
            <table class="admin-table" style="border-radius:0;border:none;">
                <thead><tr>
                    <th>Ordine</th><th>Cliente</th><th>Totale</th><th>Stato</th>
                </tr></thead>
                <tbody>
                @foreach($recentOrders as $order)
                @php
                    $sColors=['pending'=>['#fff3cd','#856404'],'processing'=>['#cfe2ff','#084298'],'shipped'=>['#e2d9f3','#59359a'],'delivered'=>['#d1e7dd','#0f5132'],'cancelled'=>['#f8d7da','#842029']];
                    $sc=$sColors[$order->status]??['#eee','#666'];
                @endphp
                <tr>
                    <td style="font-weight:600;">{{ $order->order_number }}</td>
                    <td>{{ $order->first_name }} {{ $order->last_name }}<br><span style="font-size:12px;color:#999;">{{ $order->email }}</span></td>
                    <td style="font-weight:600;">€{{ number_format($order->total_amount,2) }}</td>
                    <td><span class="badge-status" style="background:{{ $sc[0] }};color:{{ $sc[1] }};">{{ ucfirst($order->status) }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p style="color:#999;text-align:center;padding:32px 0;">Nessun ordine ancora.</p>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div style="background:#fff;border-radius:16px;padding:28px;border:1px solid #eee;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 style="font-weight:700;margin:0;">Prodotti Top</h5>
                <a href="{{ route('admin.products') }}" style="color:var(--green-dark);font-size:13px;font-weight:600;">Vedi tutti →</a>
            </div>
            @if(isset($topProducts) && count($topProducts) > 0)
            @foreach($topProducts as $tp)
            <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div>
                    <p style="margin:0;font-size:14px;font-weight:600;">{{ $tp->name ?? $tp->product_name }}</p>
                    <p style="margin:0;font-size:12px;color:#999;">{{ $tp->total_sold ?? 0 }} venduti</p>
                </div>
                <span style="font-size:14px;font-weight:700;">€{{ number_format(($tp->total_revenue ?? 0),0) }}</span>
            </div>
            @endforeach
            @else
                <p style="color:#999;text-align:center;padding:32px 0;">Nessun dato di vendita ancora.</p>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const revenueData = @json($revenueChart ?? []);
const categoryData = @json($categoryChart ?? []);

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueData.labels ?? [],
        datasets: [{
            label: 'Fatturato (€)',
            data: revenueData.values ?? [],
            borderColor: '#9BC3B1',
            backgroundColor: 'rgba(154,215,160,0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#9BC3B1',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f0f0' }, ticks: { callback: v => '€'+v } },
            x: { grid: { display: false } }
        }
    }
});

if(categoryData && categoryData.labels && categoryData.labels.length > 0){
    new Chart(document.getElementById('categoryChart'), {
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
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 12 } }
            },
            cutout: '65%',
        }
    });
}
</script>
@endsection

