@extends('layouts.admin')

@section('title', $campaign ? 'Modifica campagna' : 'Nuova campagna')
@section('page_title', $campaign ? 'Modifica campagna' : 'Nuova campagna email')

@section('content')

<a href="{{ route('admin.campaigns') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#9bc3b1;text-decoration:none;margin-bottom:20px;">
    <i class="bi bi-arrow-left"></i> Torna alle campagne
</a>

@php
    $isSent    = $campaign && $campaign->status === 'sent';
    $formAction = $campaign
        ? route('admin.campaigns.update', $campaign)
        : route('admin.campaigns.store');
@endphp

<form method="POST" action="{{ $formAction }}" id="campaignForm">
    @csrf
    @if($campaign) @method('PUT') @endif

    <div class="row g-4">
        {{-- Left: content --}}
        <div class="col-lg-7">
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;margin-bottom:16px;">
                <h6 style="font-weight:700;margin-bottom:20px;">Contenuto email</h6>

                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:6px;">Nome campagna *</label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name ?? '') }}"
                        placeholder="Es: Promo Primavera 2026 – Collari" required {{ $isSent ? 'disabled' : '' }}
                        style="width:100%;padding:10px 14px;border:1px solid #e0e0e0;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box;">
                    @error('name')<div style="color:#e57373;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:6px;">Oggetto email *</label>
                    <input type="text" name="subject" value="{{ old('subject', $campaign->subject ?? '') }}"
                        placeholder="Es: 🐾 Offerta speciale per i tuoi amici a 4 zampe!" required {{ $isSent ? 'disabled' : '' }}
                        style="width:100%;padding:10px 14px;border:1px solid #e0e0e0;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box;">
                    @error('subject')<div style="color:#e57373;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:6px;">
                        Corpo email * <span style="font-weight:400;color:#aaa;">(HTML supportato)</span>
                    </label>
                    <textarea name="body" rows="16" required {{ $isSent ? 'disabled' : '' }}
                        placeholder="Scrivi il contenuto dell'email. Puoi usare HTML. Usa {name} per il nome del destinatario."
                        style="width:100%;padding:10px 14px;border:1px solid #e0e0e0;border-radius:10px;font-size:13px;font-family:'Inter',sans-serif;outline:none;resize:vertical;box-sizing:border-box;">{{ old('body', $campaign->body ?? '') }}</textarea>
                    <div style="font-size:11px;color:#aaa;margin-top:4px;">Usa <code>{name}</code> per personalizzare con il nome del destinatario.</div>
                    @error('body')<div style="color:#e57373;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Preview button --}}
            @if(!$isSent)
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:20px;">
                <h6 style="font-weight:700;margin-bottom:12px;">Anteprima destinatari</h6>
                <p style="font-size:13px;color:#888;margin-bottom:14px;">Calcola quante persone riceveranno questa campagna in base ai filtri impostati.</p>
                <button type="button" id="previewBtn"
                    style="padding:10px 20px;background:#f8f8f8;border:1px solid #e0e0e0;border-radius:10px;font-size:13px;cursor:pointer;font-family:'Inter',sans-serif;">
                    <i class="bi bi-people me-1"></i> Calcola destinatari
                </button>
                <div id="previewResult" style="display:none;margin-top:14px;padding:14px;background:#eef6f2;border-radius:10px;font-size:13px;color:#449;">
                </div>
            </div>
            @endif
        </div>

        {{-- Right: targeting --}}
        <div class="col-lg-5">
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;margin-bottom:16px;">
                <h6 style="font-weight:700;margin-bottom:20px;"><i class="bi bi-funnel" style="color:#9bc3b1;margin-right:6px;"></i>Segmentazione destinatari</h6>

                {{-- Tipo utenti --}}
                <div style="margin-bottom:20px;">
                    <label style="font-size:13px;font-weight:600;color:#555;display:block;margin-bottom:10px;">Tipo di destinatari</label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:8px;cursor:pointer;">
                        <input type="checkbox" name="target_registered" value="1" {{ old('target_registered', ($campaign->target_registered ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} {{ $isSent ? 'disabled' : '' }}>
                        <span><i class="bi bi-person-check" style="color:#9bc3b1;"></i> Utenti iscritti</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="target_guests" value="1" {{ old('target_guests', ($campaign->target_guests ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} {{ $isSent ? 'disabled' : '' }}>
                        <span><i class="bi bi-bag-check" style="color:#d4a574;"></i> Acquirenti ospite</span>
                    </label>
                </div>

                {{-- Min orders --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:6px;">Numero minimo di ordini</label>
                    <input type="number" name="min_orders" min="0" value="{{ old('min_orders', $campaign->min_orders ?? 0) }}" {{ $isSent ? 'disabled' : '' }}
                        style="width:100px;padding:9px 12px;border:1px solid #e0e0e0;border-radius:10px;font-size:14px;outline:none;">
                    <span style="font-size:12px;color:#aaa;margin-left:8px;">0 = tutti</span>
                </div>

                {{-- Filter by products --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:8px;">
                        Filtra per prodotti acquistati
                        <span style="font-weight:400;color:#aaa;">(lascia vuoto = tutti)</span>
                    </label>
                    <div style="max-height:180px;overflow-y:auto;border:1px solid #eee;border-radius:10px;padding:10px;">
                        @foreach($products as $product)
                        @php $checked = in_array($product->id, old('filter_product_ids', $campaign->filter_product_ids ?? [])); @endphp
                        <label style="display:flex;align-items:center;gap:8px;font-size:12px;margin-bottom:6px;cursor:pointer;">
                            <input type="checkbox" name="filter_product_ids[]" value="{{ $product->id }}" {{ $checked ? 'checked' : '' }} {{ $isSent ? 'disabled' : '' }}>
                            {{ $product->name }}
                            <small style="color:#aaa;margin-left:auto;">€ {{ number_format($product->price, 2) }}</small>
                        </label>
                        @endforeach
                        @if($products->isEmpty())
                        <p style="color:#ccc;font-size:12px;margin:0;">Nessun prodotto attivo.</p>
                        @endif
                    </div>
                </div>

                {{-- Filter by categories --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:8px;">
                        Filtra per categorie acquistate
                        <span style="font-weight:400;color:#aaa;">(lascia vuoto = tutte)</span>
                    </label>
                    <div style="border:1px solid #eee;border-radius:10px;padding:10px;">
                        @foreach($categories as $cat)
                        @php $checked = in_array($cat->id, old('filter_category_ids', $campaign->filter_category_ids ?? [])); @endphp
                        <label style="display:flex;align-items:center;gap:8px;font-size:12px;margin-bottom:6px;cursor:pointer;">
                            <input type="checkbox" name="filter_category_ids[]" value="{{ $cat->id }}" {{ $checked ? 'checked' : '' }} {{ $isSent ? 'disabled' : '' }}>
                            {{ $cat->name }}
                        </label>
                        @endforeach
                        @if($categories->isEmpty())
                        <p style="color:#ccc;font-size:12px;margin:0;">Nessuna categoria attiva.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if(!$isSent)
            <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:20px;display:flex;flex-direction:column;gap:10px;">
                <button type="submit" style="padding:12px;background:#9bc3b1;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;">
                    <i class="bi bi-floppy me-1"></i> Salva bozza
                </button>
                @if($campaign && $campaign->status === 'draft')
                <form method="POST" action="{{ route('admin.campaigns.send', $campaign) }}"
                    onsubmit="return confirm('Sei sicuro di voler inviare questa campagna? L\'operazione non può essere annullata.')">
                    @csrf
                    <button type="submit" style="width:100%;padding:12px;background:#5b9bd5;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;">
                        <i class="bi bi-send me-1"></i> Invia ora
                    </button>
                </form>
                @endif
            </div>
            @else
            <div style="background:#eef6f2;border-radius:16px;padding:20px;text-align:center;">
                <i class="bi bi-check-circle-fill" style="font-size:28px;color:#6fa398;"></i>
                <p style="margin:8px 0 0;font-size:14px;font-weight:600;color:#6fa398;">Campagna inviata</p>
                <p style="margin:4px 0 0;font-size:12px;color:#aaa;">{{ $campaign->sent_at->format('d/m/Y H:i') }} — {{ $campaign->sent_count }} destinatari</p>
            </div>
            @endif
        </div>
    </div>
</form>

@if(!$isSent)
<script>
document.getElementById('previewBtn')?.addEventListener('click', function () {
    const form = document.getElementById('campaignForm');
    const fd   = new FormData(form);
    const data = new URLSearchParams();
    fd.forEach((v, k) => data.append(k, v));

    this.disabled = true;
    this.textContent = 'Calcolo…';

    fetch('{{ route('admin.campaigns.preview-recipients') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                         || '{{ csrf_token() }}'
        },
        body: data.toString()
    })
    .then(r => r.json())
    .then(d => {
        const el = document.getElementById('previewResult');
        el.style.display = 'block';
        el.innerHTML = `
            <strong style="color:#333">${d.count} destinatari</strong>
            &nbsp;·&nbsp; ${d.registered} iscritti, ${d.guests} ospiti
            ${d.sample.length ? '<div style="margin-top:8px;font-size:11px;color:#888;">Esempio: ' + d.sample.join(', ') + (d.count > 5 ? ' …' : '') + '</div>' : ''}
        `;
    })
    .catch(() => {
        document.getElementById('previewResult').style.display = 'block';
        document.getElementById('previewResult').textContent = 'Errore nel calcolo.';
    })
    .finally(() => {
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-people me-1"></i> Ricalcola';
    });
});
</script>
@endif
@endsection
