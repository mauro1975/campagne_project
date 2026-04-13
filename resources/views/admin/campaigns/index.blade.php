@extends('layouts.admin')

@section('title', 'Campagne Email')
@section('page_title', 'Campagne Email')

@section('content')

@if(session('success'))
<div style="background:#eef6f2;color:#6fa398;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:14px;font-weight:500;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
    <p style="color:#888;font-size:14px;margin:0;">Crea e invia campagne email promozionali segmentate per prodotto o categoria.</p>
    <a href="{{ route('admin.campaigns.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#9bc3b1;color:#fff;border-radius:10px;text-decoration:none;font-size:14px;font-weight:600;">
        <i class="bi bi-plus-lg"></i> Nuova campagna
    </a>
</div>

<div style="background:#fff;border:1px solid #eee;border-radius:16px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f8f8f8;border-bottom:2px solid #eee;">
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Campagna</th>
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Oggetto</th>
                <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Stato</th>
                <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Destinatari</th>
                <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Inviata</th>
                <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaigns as $campaign)
            @php
                $stMap = ['draft' => ['Bozza', '#f0a500'], 'sending' => ['Invio…', '#5b9bd5'], 'sent' => ['Inviata', '#6fa398']];
                [$stLabel, $stColor] = $stMap[$campaign->status] ?? ['—', '#aaa'];
            @endphp
            <tr style="border-bottom:1px solid #f5f5f5;">
                <td style="padding:12px 16px;font-weight:600;">{{ $campaign->name }}</td>
                <td style="padding:12px 16px;color:#666;max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $campaign->subject }}</td>
                <td style="padding:12px 16px;text-align:center;">
                    <span style="background:{{ $stColor }}22;color:{{ $stColor }};padding:3px 12px;border-radius:99px;font-size:11px;font-weight:700;text-transform:uppercase;">{{ $stLabel }}</span>
                </td>
                <td style="padding:12px 16px;text-align:center;">
                    @if($campaign->total_recipients !== null)
                        <span style="font-weight:600;">{{ $campaign->sent_count }} / {{ $campaign->total_recipients }}</span>
                    @else
                        <span style="color:#ccc;">—</span>
                    @endif
                </td>
                <td style="padding:12px 16px;text-align:center;color:#aaa;font-size:12px;">
                    {{ $campaign->sent_at ? $campaign->sent_at->format('d/m/Y H:i') : '—' }}
                </td>
                <td style="padding:12px 16px;text-align:right;white-space:nowrap;">
                    @if($campaign->status !== 'sent')
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" style="font-size:12px;color:#9bc3b1;text-decoration:none;margin-right:10px;">
                        <i class="bi bi-pencil"></i> Modifica
                    </a>
                    <form method="POST" action="{{ route('admin.campaigns.send', $campaign) }}" style="display:inline;"
                        onsubmit="return confirm('Inviare questa campagna a tutti i destinatari selezionati?')">
                        @csrf
                        <button type="submit" style="font-size:12px;color:#5b9bd5;background:none;border:none;cursor:pointer;font-family:inherit;padding:0;margin-right:10px;">
                            <i class="bi bi-send"></i> Invia
                        </button>
                    </form>
                    @else
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" style="font-size:12px;color:#aaa;text-decoration:none;margin-right:10px;">
                        <i class="bi bi-eye"></i> Visualizza
                    </a>
                    <a href="{{ route('admin.campaigns.logs', $campaign) }}" style="font-size:12px;color:#9bc3b1;text-decoration:none;margin-right:10px;">
                        <i class="bi bi-list-check"></i> Log invii
                    </a>
                    @endif
                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" style="display:inline;"
                        onsubmit="return confirm('Eliminare questa campagna?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="font-size:12px;color:#e57373;background:none;border:none;cursor:pointer;font-family:inherit;padding:0;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:40px;text-align:center;color:#ccc;">Nessuna campagna creata.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:16px;">{{ $campaigns->links() }}</div>
@endsection
