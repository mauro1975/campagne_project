@extends('layouts.admin')

@section('title', 'Consensi Cookie')
@section('page_title', 'Raccolta Dati – Consensi Cookie')

@section('content')
@php
    $pctAnalytics = $total ? round($analytics / $total * 100) : 0;
    $pctMarketing = $total ? round($marketing / $total * 100) : 0;
    $pctAll       = $total ? round(($byChoice['all'] ?? 0) / $total * 100) : 0;
    $pctNecessary = $total ? round(($byChoice['necessary'] ?? 0) / $total * 100) : 0;
    $pctCustom    = $total ? round(($byChoice['custom'] ?? 0) / $total * 100) : 0;
    $maxDay       = $byDay->max('count') ?: 1;
@endphp

{{-- Stats cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Totale consensi</div>
            <div class="stat-value">{{ number_format($total) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Accetta tutti</div>
            <div class="stat-value" style="color:var(--green-dark);">{{ $byChoice['all'] ?? 0 }}</div>
            <div style="font-size:12px;color:#999;margin-top:4px;">{{ $pctAll }}% del totale</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Analytics attivi</div>
            <div class="stat-value">{{ $analytics }}</div>
            <div style="font-size:12px;color:#999;margin-top:4px;">{{ $pctAnalytics }}% del totale</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Marketing attivi</div>
            <div class="stat-value">{{ $marketing }}</div>
            <div style="font-size:12px;color:#999;margin-top:4px;">{{ $pctMarketing }}% del totale</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    {{-- Scelta consenso --}}
    <div class="col-md-4">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;height:100%;">
            <h6 style="font-weight:700;margin-bottom:20px;">Scelta consenso</h6>
            @foreach([
                'all'       => ['label' => 'Accetta tutti',    'color' => '#9bc3b1'],
                'custom'    => ['label' => 'Personalizzato',   'color' => '#6fa398'],
                'necessary' => ['label' => 'Solo necessari',   'color' => '#ddd'],
            ] as $key => $meta)
            @php $cnt = $byChoice[$key] ?? 0; $pct = $total ? round($cnt / $total * 100) : 0; @endphp
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                    <span style="font-weight:500;">{{ $meta['label'] }}</span>
                    <span style="color:#888;">{{ $cnt }} <span style="font-size:11px;">({{ $pct }}%)</span></span>
                </div>
                <div style="background:#f0f0f0;border-radius:20px;height:8px;overflow:hidden;">
                    <div style="width:{{ $pct }}%;background:{{ $meta['color'] }};height:100%;border-radius:20px;transition:width .4s;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Lingua --}}
    <div class="col-md-3">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;height:100%;">
            <h6 style="font-weight:700;margin-bottom:20px;">Lingua</h6>
            @foreach($byLocale as $locale => $cnt)
            @php $pct = $total ? round($cnt / $total * 100) : 0; @endphp
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f5f5f5;">
                <span style="font-size:13px;font-weight:500;">{{ strtoupper($locale) }}</span>
                <span style="font-size:13px;color:#555;">{{ $cnt }} <span style="font-size:11px;color:#999;">({{ $pct }}%)</span></span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Mini bar chart per giorno --}}
    <div class="col-md-5">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;height:100%;">
            <h6 style="font-weight:700;margin-bottom:16px;">Consensi per giorno <span style="font-size:11px;font-weight:400;color:#999;">(ultimi 30 gg)</span></h6>
            @if($byDay->isEmpty())
                <p style="color:#bbb;font-size:13px;text-align:center;padding:20px 0;">Nessun dato ancora.</p>
            @else
            <div style="display:flex;align-items:flex-end;gap:3px;height:80px;">
                @foreach($byDay as $day)
                @php $h = max(4, round($day->count / $maxDay * 80)); @endphp
                <div style="flex:1;min-width:4px;background:var(--green-dark);border-radius:3px 3px 0 0;height:{{ $h }}px;opacity:.8;"
                     title="{{ $day->day }}: {{ $day->count }}"></div>
                @endforeach
            </div>
            <div style="display:flex;justify-content:space-between;font-size:10px;color:#bbb;margin-top:4px;">
                <span>{{ $byDay->first()->day ?? '' }}</span>
                <span>{{ $byDay->last()->day ?? '' }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Tabella recenti --}}
<div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:0;overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;">
        <h6 style="font-weight:700;margin:0;">Ultimi 50 consensi</h6>
        <span style="font-size:12px;color:#aaa;">Le scelte dei visitatori vengono registrate anonimamente</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="admin-table" style="border-radius:0;border:none;">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>IP (anonimizzato)</th>
                    <th>Lingua</th>
                    <th>Scelta</th>
                    <th>Analytics</th>
                    <th>Marketing</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $r)
                @php
                    // Anonymize last octet of IP (GDPR)
                    $ip = $r->ip_address ? preg_replace('/\.\d+$/', '.***', $r->ip_address) : '—';
                    $choiceLabel = ['all' => 'Accetta tutti', 'necessary' => 'Solo necessari', 'custom' => 'Personalizzato'][$r->choice] ?? $r->choice;
                    $choiceColor = ['all' => '#d1e7dd;color:#0f5132', 'necessary' => '#f5f5f5;color:#666', 'custom' => '#fff3cd;color:#664d03'][$r->choice] ?? '#eee;color:#333';
                @endphp
                <tr>
                    <td style="font-size:12px;color:#666;">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-size:12px;font-family:monospace;">{{ $ip }}</td>
                    <td><span style="font-size:11px;font-weight:700;letter-spacing:1px;">{{ strtoupper($r->locale) }}</span></td>
                    <td><span class="badge-status" style="background:{{ $choiceColor }};">{{ $choiceLabel }}</span></td>
                    <td>
                        @if($r->analytics)
                            <i class="bi bi-check-circle-fill" style="color:#0f5132;"></i>
                        @else
                            <i class="bi bi-x-circle" style="color:#ccc;"></i>
                        @endif
                    </td>
                    <td>
                        @if($r->marketing)
                            <i class="bi bi-check-circle-fill" style="color:#0f5132;"></i>
                        @else
                            <i class="bi bi-x-circle" style="color:#ccc;"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:#bbb;">Nessun consenso registrato ancora.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
