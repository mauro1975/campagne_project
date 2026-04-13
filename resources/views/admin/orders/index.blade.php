@extends('layouts.admin')

@section('title', 'Ordini')
@section('page_title', 'Ordini')

@section('content')

{{-- Quick stats bar --}}
<div class="row g-3 mb-4">
    @php
        $statusCounts = $orders->groupBy('status')->map->count();
    @endphp
    @foreach(['pending'=>['#fff3cd','#856404'],'processing'=>['#cfe2ff','#084298'],'shipped'=>['#e2d9f3','#59359a'],'delivered'=>['#d1e7dd','#0f5132'],'cancelled'=>['#f8d7da','#842029']] as $st => $colors)
    <div class="col">
        <div style="background:#fff;border:1px solid #eee;border-radius:12px;padding:16px;text-align:center;">
            <span class="badge-status" style="background:{{ $colors[0] }};color:{{ $colors[1] }};">{{ ucfirst($st) }}</span>
            <p style="font-size:22px;font-weight:700;margin:8px 0 0;">{{ $statusCounts[$st] ?? 0 }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" style="background:#fff;border:1px solid #eee;border-radius:12px;padding:16px;margin-bottom:20px;display:flex;gap:12px;flex-wrap:wrap;">
    <input type="text" name="search" class="form-control" placeholder="Cerca ordine o cliente..." value="{{ request('search') }}" style="max-width:260px;">
    <select name="status" class="form-select" style="max-width:160px;">
        <option value="">Tutti gli stati</option>
        @foreach(['pending','processing','shipped','delivered','cancelled'] as $st)
            <option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>{{ ucfirst($st) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-admin-secondary"><i class="bi bi-search"></i> Filtra</button>
    <a href="{{ route('admin.orders') }}" class="btn-admin-secondary">Reimposta</a>
</form>

<table class="admin-table">
    <thead>
        <tr><th>Ordine</th><th>Cliente</th><th>Articoli</th><th>Totale</th><th>Stato</th><th>Data</th><th>Azioni</th></tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        @php
            $sc=['pending'=>['#fff3cd','#856404'],'processing'=>['#cfe2ff','#084298'],'shipped'=>['#e2d9f3','#59359a'],'delivered'=>['#d1e7dd','#0f5132'],'cancelled'=>['#f8d7da','#842029']];
            $colors=$sc[$order->status]??['#eee','#666'];
        @endphp
        <tr>
            <td style="font-weight:700;">{{ $order->order_number }}</td>
            <td>
                {{ $order->first_name }} {{ $order->last_name }}<br>
                <span style="font-size:12px;color:#999;">{{ $order->email }}</span>
            </td>
            <td>{{ $order->items->count() }}</td>
            <td style="font-weight:600;">€{{ number_format($order->total_amount,2) }}</td>
            <td>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select" style="font-size:12px;padding:4px 8px;min-width:120px;" onchange="this.form.submit()">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $st)
                            <option value="{{ $st }}" {{ $order->status==$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td style="font-size:13px;color:#666;">{{ $order->created_at->format('d M Y') }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-admin-secondary" style="padding:6px 12px;font-size:13px;">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:48px;color:#999;">Nessun ordine trovato.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">{{ $orders->withQueryString()->links() }}</div>

@endsection

