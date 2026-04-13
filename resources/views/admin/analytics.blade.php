@extends('layouts.admin')

@section('title', 'Analytics')
@section('page_title', 'Analytics – Traffico e pagine')

@section('content')
@php
    $deviceMobile  = $devices['mobile']  ?? 0;
    $deviceTablet  = $devices['tablet']  ?? 0;
    $deviceDesktop = $devices['desktop'] ?? 0;
    $deviceTotal   = max($deviceMobile + $deviceTablet + $deviceDesktop, 1);
    $pctMobile     = round($deviceMobile  / $deviceTotal * 100);
    $pctTablet     = round($deviceTablet  / $deviceTotal * 100);
    $pctDesktop    = round($deviceDesktop / $deviceTotal * 100);
    $maxViews      = max(max($viewsData ?: [1]), 1);
@endphp

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-label">Visualizzazioni totali</div>
            <div class="stat-value">{{ number_format($totalViews) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-label">Sessioni uniche totali</div>
            <div class="stat-value">{{ number_format($totalSessions) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-label">Visualizzazioni oggi</div>
            <div class="stat-value" style="color:var(--green-dark);">{{ number_format($todayViews) }}</div>
        </div>
    </div>
</div>

{{-- Visualizzazioni e sessioni per giorno --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;">
            <h6 style="font-weight:700;margin-bottom:4px;">Visualizzazioni e sessioni (ultimi 30 giorni)</h6>
            <p style="font-size:12px;color:#aaa;margin-bottom:20px;">
                <span style="display:inline-block;width:12px;height:12px;background:#9bc3b1;border-radius:3px;margin-right:4px;"></span> Visualizzazioni &nbsp;
                <span style="display:inline-block;width:12px;height:12px;background:#d4a574;border-radius:3px;margin-right:4px;"></span> Sessioni
            </p>
            <div style="position:relative;height:200px;display:flex;align-items:flex-end;gap:3px;overflow-x:auto;padding-bottom:24px;">
                @foreach($dayLabels as $i => $day)
                @php
                    $v = $viewsData[$i] ?? 0;
                    $s = $sessionsData[$i] ?? 0;
                    $hv = $maxViews > 0 ? round($v / $maxViews * 160) : 0;
                    $hs = $maxViews > 0 ? round($s / $maxViews * 160) : 0;
                    $label = \Carbon\Carbon::parse($day)->format('d/m');
                @endphp
                <div style="flex:1;min-width:18px;display:flex;flex-direction:column;align-items:center;gap:2px;position:relative;" title="{{ $day }}: {{ $v }} views, {{ $s }} sessioni">
                    <div style="display:flex;align-items:flex-end;gap:1px;height:160px;">
                        <div style="width:8px;height:{{ $hv }}px;background:#9bc3b1;border-radius:3px 3px 0 0;transition:height .3s;" title="{{ $v }} views"></div>
                        <div style="width:8px;height:{{ $hs }}px;background:#d4a574;border-radius:3px 3px 0 0;transition:height .3s;" title="{{ $s }} sessioni"></div>
                    </div>
                    @if($i % 5 === 0)
                    <div style="position:absolute;bottom:0;font-size:9px;color:#bbb;white-space:nowrap;">{{ $label }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Dispositivi --}}
    <div class="col-md-4">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;height:100%;">
            <h6 style="font-weight:700;margin-bottom:20px;">Dispositivi</h6>
            @foreach([
                'desktop' => ['label' => 'Desktop', 'color' => '#9bc3b1', 'icon' => 'bi-display', 'pct' => $pctDesktop, 'cnt' => $deviceDesktop],
                'mobile'  => ['label' => 'Mobile',  'color' => '#d4a574', 'icon' => 'bi-phone',   'pct' => $pctMobile,  'cnt' => $deviceMobile],
                'tablet'  => ['label' => 'Tablet',  'color' => '#6fa398', 'icon' => 'bi-tablet',  'pct' => $pctTablet,  'cnt' => $deviceTablet],
            ] as $key => $meta)
            <div style="margin-bottom:18px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                    <span><i class="bi {{ $meta['icon'] }}" style="color:{{ $meta['color'] }};margin-right:6px;"></i>{{ $meta['label'] }}</span>
                    <span>{{ $meta['cnt'] }} <span style="color:#aaa;">({{ $meta['pct'] }}%)</span></span>
                </div>
                <div style="background:#f3f3f3;border-radius:99px;height:8px;overflow:hidden;">
                    <div style="width:{{ $meta['pct'] }}%;height:100%;background:{{ $meta['color'] }};border-radius:99px;transition:width .4s;"></div>
                </div>
            </div>
            @endforeach

            {{-- Small donut visual --}}
            <div style="margin-top:24px;display:flex;justify-content:center;">
                <svg viewBox="0 0 36 36" width="120" height="120" style="transform:rotate(-90deg);">
                    @php
                        $segments = [
                            ['pct' => $pctDesktop, 'color' => '#9bc3b1'],
                            ['pct' => $pctMobile,  'color' => '#d4a574'],
                            ['pct' => $pctTablet,  'color' => '#6fa398'],
                        ];
                        $offset = 0;
                        $circumference = 2 * M_PI * 15.9155; // r=15.9155
                    @endphp
                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#eee" stroke-width="3.5" />
                    @foreach($segments as $seg)
                    @if($seg['pct'] > 0)
                    <circle cx="18" cy="18" r="15.9155" fill="none"
                        stroke="{{ $seg['color'] }}" stroke-width="3.5"
                        stroke-dasharray="{{ $seg['pct'] }} {{ 100 - $seg['pct'] }}"
                        stroke-dashoffset="{{ -$offset }}"
                        pathLength="100" />
                    @php $offset += $seg['pct']; @endphp
                    @endif
                    @endforeach
                </svg>
            </div>
        </div>
    </div>

    {{-- Top pagine --}}
    <div class="col-md-8">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px;height:100%;">
            <h6 style="font-weight:700;margin-bottom:16px;">Pagine più visitate</h6>
            @if($topPages->isEmpty())
                <p style="color:#aaa;font-size:14px;">Nessun dato disponibile.</p>
            @else
            @php $maxPageViews = $topPages->first()->views ?: 1; @endphp
            <div style="overflow-x:auto;">
                <table style="width:100%;font-size:13px;border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid #f0f0f0;">
                            <th style="text-align:left;padding:6px 8px;font-weight:600;color:#555;">#</th>
                            <th style="text-align:left;padding:6px 8px;font-weight:600;color:#555;">Pagina</th>
                            <th style="text-align:left;padding:6px 8px;font-weight:600;color:#555;">Path</th>
                            <th style="text-align:right;padding:6px 8px;font-weight:600;color:#555;">Visualizzazioni</th>
                            <th style="padding:6px 8px;min-width:120px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPages as $i => $page)
                        <tr style="border-bottom:1px solid #f8f8f8;">
                            <td style="padding:7px 8px;color:#aaa;">{{ $i + 1 }}</td>
                            <td style="padding:7px 8px;font-weight:500;">{{ $page->page_title ?: '—' }}</td>
                            <td style="padding:7px 8px;color:#888;font-family:monospace;font-size:12px;">
                                <a href="{{ url($page->path) }}" target="_blank" style="color:#6fa398;text-decoration:none;">/{{ $page->path }}</a>
                            </td>
                            <td style="padding:7px 8px;text-align:right;font-weight:600;">{{ number_format($page->views) }}</td>
                            <td style="padding:7px 8px;">
                                @php $w = round($page->views / $maxPageViews * 100); @endphp
                                <div style="background:#f3f3f3;border-radius:99px;height:6px;overflow:hidden;">
                                    <div style="width:{{ $w }}%;height:100%;background:#9bc3b1;border-radius:99px;"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
