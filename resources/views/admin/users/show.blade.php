@extends('layouts.admin')

@section('title', $user->name ?? $user->email)
@section('page_title', 'Dettaglio utente')

@section('content')

<a href="{{ route('admin.users') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#9bc3b1;text-decoration:none;margin-bottom:20px;">
    <i class="bi bi-arrow-left"></i> Torna agli utenti
</a>

{{-- Header card --}}
<div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;margin-bottom:24px;display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
    <div style="width:64px;height:64px;border-radius:50%;background:{{ $isGuest ? '#fdf3ea' : '#eef6f2' }};display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0;">
        <i class="bi {{ $isGuest ? 'bi-bag-check' : 'bi-person-circle' }}" style="color:{{ $isGuest ? '#d4a574' : '#9bc3b1' }};"></i>
    </div>
    <div style="flex:1;min-width:200px;">
        <h4 style="margin:0;font-size:20px;font-weight:700;">{{ $user->name ?: '—' }}</h4>
        <div style="color:#888;font-size:14px;margin-top:4px;">{{ $user->email }}</div>
        @if(!$isGuest)
        <div style="margin-top:6px;">
            <span style="background:#eef6f2;color:#6fa398;font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;text-transform:uppercase;">Iscritto</span>
            <span style="color:#bbb;font-size:12px;margin-left:8px;">dal {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
        </div>
        @else
        <div style="margin-top:6px;">
            <span style="background:#fdf3ea;color:#c9874d;font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;text-transform:uppercase;">Ospite</span>
        </div>
        @endif
    </div>
    <div style="display:flex;gap:16px;flex-wrap:wrap;">
        <div style="text-align:center;padding:16px 24px;background:#f8f8f8;border-radius:12px;">
            <div style="font-size:24px;font-weight:700;color:#333;">{{ $orderCount }}</div>
            <div style="font-size:12px;color:#aaa;margin-top:2px;">Ordini totali</div>
        </div>
        <div style="text-align:center;padding:16px 24px;background:#f8f8f8;border-radius:12px;">
            <div style="font-size:24px;font-weight:700;color:#9bc3b1;">€ {{ number_format($totalSpent, 2) }}</div>
            <div style="font-size:12px;color:#aaa;margin-top:2px;">Speso (pagato)</div>
        </div>
        <div style="text-align:center;padding:16px 24px;background:#f8f8f8;border-radius:12px;">
            <div style="font-size:24px;font-weight:700;color:#555;">{{ $boughtProducts->count() }}</div>
            <div style="font-size:12px;color:#aaa;margin-top:2px;">Prodotti acquistati</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Orders list --}}
    <div class="col-lg-8">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;">
            <h6 style="font-weight:700;margin-bottom:16px;">Cronologia ordini</h6>
            @forelse($orders as $order)
            <div style="border:1px solid #f0f0f0;border-radius:12px;padding:16px;margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                    <div>
                        <a href="{{ route('admin.orders.show', $order->id) }}" style="font-weight:700;color:#333;text-decoration:none;font-size:14px;">
                            {{ $order->order_number }}
                        </a>
                        <span style="font-size:12px;color:#bbb;margin-left:10px;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;">
                        @php
                            $stColors = ['pending'=>'#f0a500','processing'=>'#5b9bd5','shipped'=>'#9bc3b1','delivered'=>'#6fa398','cancelled'=>'#e57373','refunded'=>'#aaa'];
                            $sc = $stColors[$order->status] ?? '#aaa';
                        @endphp
                        <span style="background:{{ $sc }}22;color:{{ $sc }};padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;text-transform:uppercase;">{{ $order->status }}</span>
                        @if($order->payment_status === 'paid')
                        <span style="background:#eef6f2;color:#6fa398;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;">Pagato</span>
                        @endif
                        <span style="font-weight:700;font-size:15px;">€ {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
                <div style="font-size:12px;color:#888;margin-bottom:8px;">{{ $order->address }}, {{ $order->city }} {{ $order->postal_code }}</div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach($order->items as $item)
                    <div style="display:flex;align-items:center;gap:8px;background:#f8f8f8;border-radius:8px;padding:6px 10px;">
                        @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" style="width:32px;height:32px;object-fit:cover;border-radius:6px;" alt="">
                        @endif
                        <div>
                            <div style="font-size:12px;font-weight:600;color:#444;">{{ $item->product_name }}</div>
                            <div style="font-size:11px;color:#aaa;">{{ $item->color }} / {{ $item->size }} × {{ $item->quantity }} — € {{ number_format($item->price, 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <p style="color:#ccc;text-align:center;padding:30px 0;">Nessun ordine trovato.</p>
            @endforelse
        </div>
    </div>

    {{-- Products bought + quick campaign --}}
    <div class="col-lg-4">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;margin-bottom:16px;">
            <h6 style="font-weight:700;margin-bottom:14px;">Prodotti acquistati</h6>
            @forelse($boughtProducts as $product)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid #f5f5f5;">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" style="width:40px;height:40px;object-fit:cover;border-radius:8px;" alt="">
                @else
                <div style="width:40px;height:40px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-image" style="color:#ccc;"></i></div>
                @endif
                <div>
                    <div style="font-size:13px;font-weight:600;color:#333;">{{ $product->name }}</div>
                    <div style="font-size:11px;color:#9bc3b1;">€ {{ number_format($product->price, 2) }}</div>
                </div>
            </div>
            @empty
            <p style="color:#ccc;font-size:13px;">Nessun prodotto.</p>
            @endforelse
        </div>

        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;">
            <h6 style="font-weight:700;margin-bottom:14px;"><i class="bi bi-envelope" style="color:#9bc3b1;margin-right:6px;"></i>Invia email</h6>
            <p style="font-size:12px;color:#888;margin-bottom:14px;">Puoi creare una campagna mirando a questo utente tramite i suoi prodotti acquistati.</p>
            <a href="{{ route('admin.campaigns.create') }}" style="display:block;text-align:center;padding:10px;background:#9bc3b1;color:#fff;border-radius:10px;text-decoration:none;font-size:13px;font-weight:600;">
                <i class="bi bi-plus-lg me-1"></i> Nuova campagna
            </a>
        </div>
    </div>
</div>
@endsection
