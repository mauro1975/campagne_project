@extends('layouts.app')

@section('title', 'Order Confirmed – .rosmarino')

@section('content')
<div style="max-width:700px;margin:0 auto;padding:80px 40px;text-align:center;">

    <div style="width:80px;height:80px;background:var(--green-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
        <i class="bi bi-check-lg" style="font-size:36px;color:var(--green-dark);"></i>
    </div>

    <h1 style="font-size:36px;margin-bottom:12px;">{{ __('front.confirm_title') }}</h1>
    <p style="font-size:17px;color:var(--text-muted);margin-bottom:40px;">
        {{ __('front.confirm_thanks', ['email' => $order->email]) }}
    </p>

    <div style="background:var(--gray);border-radius:16px;padding:32px;text-align:left;margin-bottom:32px;">
        <div class="d-flex justify-content-between mb-3">
            <span style="font-size:13px;color:var(--text-muted);">{{ __('front.confirm_order_number') }}</span>
            <span style="font-weight:700;font-size:15px;">{{ $order->order_number }}</span>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <span style="font-size:13px;color:var(--text-muted);">{{ __('front.confirm_date') }}</span>
            <span style="font-size:15px;">{{ $order->created_at->format('d M Y') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <span style="font-size:13px;color:var(--text-muted);">{{ __('front.confirm_payment_method') }}</span>
            <span style="font-size:15px;text-transform:capitalize;">{{ $order->payment_method ?? 'Card' }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span style="font-size:13px;color:var(--text-muted);">{{ __('front.confirm_total_paid') }}</span>
            <span style="font-weight:700;font-size:18px;">€{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    {{-- Items --}}
    <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:24px;text-align:left;margin-bottom:32px;">
        <h5 style="font-weight:700;margin-bottom:16px;">{{ __('front.confirm_items_ordered') }}</h5>
        @foreach($order->items as $item)
        <div class="d-flex justify-content-between align-items-center py-2" style="{{ !$loop->last ? 'border-bottom:1px solid #f0f0f0;' : '' }}">
            <div>
                <p style="margin:0;font-weight:600;font-size:14px;">{{ $item->product_name }}</p>
                @if($item->color || $item->size)
                    <p style="margin:2px 0 0;font-size:12px;color:var(--text-muted);">{{ $item->color }}{{ $item->color && $item->size ? ' / ' : '' }}{{ $item->size }} &times; {{ $item->quantity }}</p>
                @else
                    <p style="margin:2px 0 0;font-size:12px;color:var(--text-muted);">&times; {{ $item->quantity }}</p>
                @endif
            </div>
            <span style="font-weight:600;">€{{ number_format($item->price * $item->quantity, 2) }}</span>
        </div>
        @endforeach
    </div>

    {{-- Shipping --}}
    <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:24px;text-align:left;margin-bottom:40px;">
        <h5 style="font-weight:700;margin-bottom:12px;">{{ __('front.confirm_shipping_to') }}</h5>
        <p style="margin:0;font-size:15px;">{{ $order->first_name }} {{ $order->last_name }}</p>
        <p style="margin:4px 0 0;font-size:14px;color:var(--text-muted);">{{ $order->address }}, {{ $order->city }}, {{ $order->postal_code }}</p>
    </div>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('collection') }}" class="btn-black">{{ __('front.confirm_continue') }}</a>
        @auth
            <a href="{{ route('account.orders') }}" class="btn-outline-green">{{ __('front.confirm_view_orders') }}</a>
        @endauth
    </div>
</div>
@endsection

