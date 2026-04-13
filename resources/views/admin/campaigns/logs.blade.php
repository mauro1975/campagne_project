@extends('layouts.admin')

@section('title', 'Log – ' . $campaign->name)
@section('page_title', 'Log invii – ' . $campaign->name)

@section('content')

@if(session('success'))
<div style="background:#eef6f2;color:#6fa398;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:14px;font-weight:500;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

<a href="{{ route('admin.campaigns') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#9bc3b1;text-decoration:none;margin-bottom:20px;">
    <i class="bi bi-arrow-left"></i> Torna alle campagne
</a>

{{-- Summary cards --}}
@php
    $sentCount  = $logs->getCollection()->where('status','sent')->count();
    $failCount  = $logs->getCollection()->where('status','failed')->count();
    $totalLogs  = $campaign->total_recipients ?? $logs->total();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Destinatari totali</div>
            <div class="stat-value">{{ number_format($totalLogs) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Inviati con successo</div>
            <div class="stat-value" style="color:var(--green-dark);">{{ number_format($campaign->sent_count) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Falliti (questa pagina)</div>
            <div class="stat-value" style="color:#e57373;">{{ $failCount }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Inviata il</div>
            <div style="font-size:15px;font-weight:600;margin-top:8px;">
                {{ $campaign->sent_at ? $campaign->sent_at->format('d/m/Y H:i') : '—' }}
            </div>
        </div>
    </div>
</div>

{{-- Errors callout --}}
@if($campaign->logs()->where('status','failed')->count() > 0)
<div style="background:#fff5f5;border:1px solid #fccaca;border-radius:12px;padding:16px 20px;margin-bottom:20px;font-size:13px;color:#c0392b;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>Attenzione:</strong> alcune email non sono state consegnate.
    @php $driver = config('mail.default'); @endphp
    @if($driver === 'log')
        Il mail driver è impostato su <code>log</code> — le email vengono scritte in <code>storage/logs/laravel.log</code> invece di essere inviate. Configura il vero SMTP nel file <code>.env</code>.
    @elseif($driver === 'smtp')
        Verifica le credenziali SMTP nel file <code>.env</code> e che il server sia raggiungibile.
    @endif
</div>
@endif

{{-- Log table --}}
<div style="background:#fff;border:1px solid #eee;border-radius:16px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f8f8f8;border-bottom:2px solid #eee;">
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Email</th>
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Nome</th>
                <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Stato</th>
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#555;">Errore</th>
                <th style="padding:12px 16px;text-align:center;font-weight:600;color:#555;">Orario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr style="border-bottom:1px solid #f5f5f5;">
                <td style="padding:10px 16px;font-family:monospace;font-size:12px;">{{ $log->email }}</td>
                <td style="padding:10px 16px;color:#666;">{{ $log->name ?: '—' }}</td>
                <td style="padding:10px 16px;text-align:center;">
                    @if($log->status === 'sent')
                        <span style="background:#eef6f2;color:#6fa398;padding:3px 12px;border-radius:99px;font-size:11px;font-weight:700;">✓ Inviato</span>
                    @else
                        <span style="background:#fff5f5;color:#e57373;padding:3px 12px;border-radius:99px;font-size:11px;font-weight:700;">✗ Fallito</span>
                    @endif
                </td>
                <td style="padding:10px 16px;color:#e57373;font-size:12px;max-width:300px;">
                    {{ $log->error ?: '' }}
                </td>
                <td style="padding:10px 16px;text-align:center;color:#aaa;font-size:12px;">
                    {{ $log->created_at->format('H:i:s') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:40px;text-align:center;color:#ccc;">Nessun log disponibile.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:16px;">{{ $logs->links() }}</div>
@endsection
