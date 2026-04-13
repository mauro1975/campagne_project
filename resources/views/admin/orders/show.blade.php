@extends('layouts.admin')

@section('title', 'Ordine ' . $order->order_number)
@section('page_title', 'Ordine: ' . $order->order_number)

@section('content')
<div style="max-width:900px;">
    <a href="{{ route('admin.orders') }}" style="color:var(--green-dark);font-size:14px;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px;">
        <i class="bi bi-arrow-left"></i> Torna agli Ordini
    </a>

    <div class="row g-4">
        {{-- Order Details --}}
        <div class="col-lg-8">
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;margin-bottom:20px;">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 style="font-weight:700;margin-bottom:4px;">Articoli ordinati</h5>
                        <p style="font-size:13px;color:#999;margin:0;">{{ $order->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    @php
                        $sc=['pending'=>['#fff3cd','#856404'],'processing'=>['#cfe2ff','#084298'],'shipped'=>['#e2d9f3','#59359a'],'delivered'=>['#d1e7dd','#0f5132'],'cancelled'=>['#f8d7da','#842029']];
                        $colors=$sc[$order->status]??['#eee','#666'];
                    @endphp
                    <span class="badge-status" style="background:{{ $colors[0] }};color:{{ $colors[1] }};font-size:13px;padding:6px 14px;">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                @foreach($order->items as $item)
                <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div>
                        <p style="margin:0;font-weight:600;font-size:15px;">{{ $item->product_name }}</p>
                        @if($item->color || $item->size)
                        <p style="margin:4px 0 0;font-size:13px;color:#666;">
                            {{ $item->color }}{{ $item->color && $item->size ? ' / ' : '' }}{{ $item->size }}
                        </p>
                        @endif
                        <p style="margin:2px 0 0;font-size:13px;color:#999;">Qtà: {{ $item->quantity }}</p>
                    </div>
                    <p style="font-weight:700;font-size:15px;margin:0;">€{{ number_format($item->price * $item->quantity, 2) }}</p>
                </div>
                @endforeach

                <div style="border-top:2px solid #eee;margin-top:20px;padding-top:20px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#666;">Subtotale</span>
                        <span style="font-weight:600;">€{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2" style="color:var(--green-dark);">
                        <span>Sconto</span>
                        <span style="font-weight:600;">−€{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#666;">Spedizione</span>
                        <span style="font-weight:600;">€{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:18px;font-weight:700;">
                        <span>Totale</span>
                        <span>€{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer & Status --}}
        <div class="col-lg-4">
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;margin-bottom:16px;">
                <h5 style="font-weight:700;margin-bottom:16px;">Cliente</h5>
                <p style="margin:0;font-weight:600;">{{ $order->first_name }} {{ $order->last_name }}</p>
                <p style="margin:4px 0;font-size:14px;color:#666;">{{ $order->email }}</p>
                @if($order->phone)
                    <p style="margin:4px 0;font-size:14px;color:#666;">{{ $order->phone }}</p>
                @endif
            </div>

            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;margin-bottom:16px;">
                <h5 style="font-weight:700;margin-bottom:16px;">Indirizzo di spedizione</h5>
                <p style="margin:0;font-size:14px;line-height:1.8;">
                    {{ $order->address }}<br>
                    {{ $order->city }}, {{ $order->postal_code }}<br>
                    {{ $order->country }}
                </p>
            </div>

            @if($order->notes)
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;margin-bottom:16px;">
                <h5 style="font-weight:700;margin-bottom:12px;">Note</h5>
                <p style="font-size:14px;color:#666;margin:0;">{{ $order->notes }}</p>
            </div>
            @endif

            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;">
                <h5 style="font-weight:700;margin-bottom:16px;">Aggiorna Stato</h5>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $st)
                            <option value="{{ $st }}" {{ $order->status==$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-admin-primary w-100" style="justify-content:center;">
                        Aggiorna Stato
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

