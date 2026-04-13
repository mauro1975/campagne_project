@extends('layouts.admin')

@section('title', 'Utenti')
@section('page_title', 'Utenti – Iscritti e acquirenti')

@section('content')

{{-- Search --}}
<form method="GET" action="{{ route('admin.users') }}" style="margin-bottom:20px;display:flex;gap:10px;max-width:420px;">
    <input type="text" name="q" value="{{ $search }}" placeholder="Cerca per nome o email…"
        style="flex:1;padding:9px 14px;border:1px solid #e0e0e0;border-radius:10px;font-size:14px;outline:none;">
    <button type="submit" style="padding:9px 18px;background:#9bc3b1;color:#fff;border:none;border-radius:10px;font-size:14px;cursor:pointer;">
        <i class="bi bi-search"></i>
    </button>
    @if($search)
    <a href="{{ route('admin.users') }}" style="padding:9px 14px;border:1px solid #e0e0e0;border-radius:10px;font-size:14px;color:#888;text-decoration:none;">✕</a>
    @endif
</form>

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4" id="usersTabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" id="reg-tab" data-bs-toggle="tab" href="#tab-registered">
        <i class="bi bi-person-check me-1"></i> Iscritti ({{ $registeredUsers->total() }})
    </a></li>
    <li class="nav-item"><a class="nav-link" id="guest-tab" data-bs-toggle="tab" href="#tab-guests">
        <i class="bi bi-bag-check me-1"></i> Acquirenti ospite ({{ $guestBuyers->total() }})
    </a></li>
</ul>

<div class="tab-content">
    {{-- Registered --}}
    <div class="tab-pane fade show active" id="tab-registered">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f8f8f8;border-bottom:2px solid #eee;">
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Nome</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Email</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Ordini</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:600;color:#555;">Totale speso</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Iscritto</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registeredUsers as $user)
                    <tr style="border-bottom:1px solid #f5f5f5;">
                        <td style="padding:11px 16px;font-weight:500;">{{ $user->name }}</td>
                        <td style="padding:11px 16px;color:#666;">{{ $user->email }}</td>
                        <td style="padding:11px 16px;text-align:center;">
                            <span style="background:#eef6f2;color:#6fa398;padding:2px 10px;border-radius:99px;font-weight:600;">{{ $user->orders_count }}</span>
                        </td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;">€ {{ number_format($user->orders_sum_total ?? 0, 2) }}</td>
                        <td style="padding:11px 16px;text-align:center;color:#aaa;font-size:12px;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="padding:11px 16px;text-align:center;">
                            <a href="{{ route('admin.users.show', $user->id) }}" style="font-size:13px;color:#9bc3b1;text-decoration:none;font-weight:500;">
                                <i class="bi bi-eye"></i> Dettaglio
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="padding:30px;text-align:center;color:#ccc;">Nessun utente trovato.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">
            {{ $registeredUsers->appends(['q' => $search, 'gp' => request('gp')])->links() }}
        </div>
    </div>

    {{-- Guests --}}
    <div class="tab-pane fade" id="tab-guests">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f8f8f8;border-bottom:2px solid #eee;">
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Nome</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Email</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Ordini</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:600;color:#555;">Totale speso</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Ultimo ordine</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guestBuyers as $guest)
                    <tr style="border-bottom:1px solid #f5f5f5;">
                        <td style="padding:11px 16px;font-weight:500;">{{ $guest->name }}</td>
                        <td style="padding:11px 16px;color:#666;">{{ $guest->email }}</td>
                        <td style="padding:11px 16px;text-align:center;">
                            <span style="background:#fdf3ea;color:#c9874d;padding:2px 10px;border-radius:99px;font-weight:600;">{{ $guest->orders_count }}</span>
                        </td>
                        <td style="padding:11px 16px;text-align:right;font-weight:600;">€ {{ number_format($guest->total_spent ?? 0, 2) }}</td>
                        <td style="padding:11px 16px;text-align:center;color:#aaa;font-size:12px;">{{ \Carbon\Carbon::parse($guest->last_order_at)->format('d/m/Y') }}</td>
                        <td style="padding:11px 16px;text-align:center;">
                            <a href="{{ route('admin.users.show', urlencode($guest->email)) }}" style="font-size:13px;color:#d4a574;text-decoration:none;font-weight:500;">
                                <i class="bi bi-eye"></i> Dettaglio
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="padding:30px;text-align:center;color:#ccc;">Nessun acquirente ospite.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">
            {{ $guestBuyers->appends(['q' => $search, 'rp' => request('rp')])->links() }}
        </div>
    </div>
</div>
@endsection
