@extends('layouts.app')

@section('title', __('front.orders_title') . ' – .rosmarino')

@section('content')
<div style="padding:60px 40px;max-width:1000px;margin:0 auto;">
    <h1 style="margin-bottom:8px;">{{ __('front.orders_heading') }}</h1>
    <p style="color:var(--text-muted);margin-bottom:40px;">{{ __('front.orders_sub') }}</p>

    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-md-3">
            <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:24px;">
                <div style="text-align:center;margin-bottom:20px;">
                    <div style="width:64px;height:64px;background:var(--green-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="bi bi-person" style="font-size:28px;color:var(--green-dark);"></i>
                    </div>
                    <p style="font-weight:700;margin:0;">{{ auth()->user()->name }}</p>
                    <p style="font-size:12px;color:var(--text-muted);margin:4px 0 0;">{{ auth()->user()->email }}</p>
                </div>
                <nav style="display:flex;flex-direction:column;gap:4px;">
                    <a href="{{ route('account') }}" style="padding:10px 14px;border-radius:8px;font-size:14px;color:var(--text-muted);text-decoration:none;">
                        <i class="bi bi-person me-2"></i>{{ __('front.orders_nav_profile') }}
                    </a>
                    <a href="{{ route('account.orders') }}" style="padding:10px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--black);background:var(--gray);text-decoration:none;">
                        <i class="bi bi-bag me-2"></i>{{ __('front.orders_nav_orders') }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top:8px;">
                        @csrf
                        <button type="submit" style="width:100%;padding:10px 14px;border-radius:8px;font-size:14px;color:#e74c3c;background:none;border:none;text-align:left;cursor:pointer;font-weight:600;">
                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('front.orders_sign_out') }}
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Orders List --}}
        <div class="col-md-9">
            @if($orders->count() > 0)
                @foreach($orders as $order)
                <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:24px;margin-bottom:16px;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <p style="font-weight:700;font-size:16px;margin-bottom:4px;">{{ $order->order_number }}</p>
                            <p style="font-size:13px;color:var(--text-muted);margin:0;">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <div style="text-align:right;">
                            @php
                                $statusColors = ['pending'=>'#e67e22','processing'=>'#3498db','shipped'=>'#9b59b6','delivered'=>'#27ae60','cancelled'=>'#e74c3c'];
                                $statusColor = $statusColors[$order->status] ?? '#666';
                            @endphp
                            <span style="background:{{ $statusColor }}15;color:{{ $statusColor }};border-radius:20px;padding:4px 12px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div style="border-top:1px solid #f0f0f0;padding-top:16px;">
                        @foreach($order->items->take(3) as $item)
                        <div class="d-flex justify-content-between py-1">
                            <span style="font-size:14px;">{{ $item->product_name }}
                                @if($item->color || $item->size)
                                    <span style="color:var(--text-muted);">({{ $item->color }}{{ $item->color && $item->size ? '/' : '' }}{{ $item->size }})</span>
                                @endif
                                &times; {{ $item->quantity }}
                            </span>
                            <span style="font-size:14px;font-weight:600;">€{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                        @endforeach
                        @if($order->items->count() > 3)
                            <p style="font-size:13px;color:var(--text-muted);margin:4px 0 0;">{{ __('front.orders_more_items', ['count' => $order->items->count() - 3]) }}</p>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center" style="border-top:1.5px solid #eee;margin-top:16px;padding-top:16px;">
                        <span style="font-size:15px;font-weight:700;">{{ __('front.orders_total', ['amount' => number_format($order->total_amount, 2)]) }}</span>
                        <a href="{{ route('order.confirmation', $order->id) }}" class="btn-outline-green" style="font-size:13px;padding:8px 16px;">
                            {{ __('front.orders_view') }}
                        </a>
                    </div>
                </div>
                @endforeach

                <div class="mt-4">{{ $orders->links() }}</div>
            @else
                <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:60px;text-align:center;">
                    <i class="bi bi-bag" style="font-size:48px;color:var(--green);"></i>
                    <h4 style="margin:16px 0 8px;">{{ __('front.orders_empty_title') }}</h4>
                    <p style="color:var(--text-muted);margin-bottom:24px;">{{ __('front.orders_empty_text') }}</p>
                    <a href="{{ route('collection') }}" class="btn-black">{{ __('front.orders_start_shop') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

