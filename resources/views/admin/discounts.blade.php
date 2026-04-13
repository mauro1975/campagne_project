@extends('layouts.admin')

@section('title', 'Sconti')
@section('page_title', 'Codici Sconto')

@section('content')
<div class="row g-4">
    {{-- Add Discount Form --}}
    <div class="col-lg-4">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;position:sticky;top:80px;">
            <h5 style="font-weight:700;margin-bottom:20px;">Crea Codice Sconto</h5>
            <form action="{{ route('admin.discounts.store') }}" method="POST">
                @csrf

                @if($errors->any())
                <div style="background:#fef2f2;border-radius:8px;padding:12px;margin-bottom:16px;font-size:13px;color:#dc2626;">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Codice *</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                           placeholder="e.g. SUMMER20" style="text-transform:uppercase;" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo *</label>
                    <select name="type" class="form-select" id="discountType" onchange="toggleTypeFields(this.value)" required>
                        <option value="percent" {{ old('type')=='percent'?'selected':'' }}>Percentuale (%)</option>
                        <option value="fixed" {{ old('type')=='fixed'?'selected':'' }}>Importo fisso (€)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Valore *</label>
                    <div style="position:relative;">
                        <input type="number" name="value" class="form-control" step="0.01" min="0"
                               value="{{ old('value') }}" required style="padding-right:36px;">
                        <span id="typeSymbol" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#666;">%</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ordine minimo (€)</label>
                    <input type="number" name="min_order_amount" class="form-control" step="0.01" min="0" value="{{ old('min_order_amount', 0) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Utilizzi massimi (vuoto = illimitato)</label>
                    <input type="number" name="max_uses" class="form-control" min="1" value="{{ old('max_uses') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label">Scade il</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                </div>
                <div class="mb-4">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active',1)?'checked':'' }}> Attivo
                    </label>
                </div>
                <button type="submit" class="btn-admin-primary w-100" style="padding:12px;justify-content:center;">
                    <i class="bi bi-plus-lg"></i> Crea Codice
                </button>
            </form>
        </div>
    </div>

    {{-- Discounts List --}}
    <div class="col-lg-8">
        <table class="admin-table">
            <thead>
                <tr><th>Codice</th><th>Tipo</th><th>Valore</th><th>Utilizzi</th><th>Scadenza</th><th>Stato</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($discounts as $discount)
                <tr>
                    <td style="font-weight:700;font-family:'Courier New',monospace;font-size:15px;">{{ $discount->code }}</td>
                    <td>{{ $discount->type === 'percent' ? 'Percentuale' : 'Fisso' }}</td>
                    <td style="font-weight:600;">
                        {{ $discount->type === 'percent' ? $discount->value.'%' : '€'.number_format($discount->value,2) }}
                        @if($discount->min_order_amount > 0)
                            <br><span style="font-size:11px;color:#999;">min €{{ number_format($discount->min_order_amount,2) }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $discount->uses_count ?? 0 }}
                        @if($discount->max_uses)
                            / {{ $discount->max_uses }}
                        @endif
                    </td>
                    <td style="font-size:13px;">
                        @if($discount->expires_at)
                            {{ $discount->expires_at->format('d M Y') }}
                            @if($discount->expires_at->isPast())
                                <br><span style="color:#dc2626;font-size:11px;">Scaduto</span>
                            @endif
                        @else
                            <span style="color:#999;">Mai</span>
                        @endif
                    </td>
                    <td>
                        @if($discount->is_active)
                            <span class="badge-status" style="background:#d1e7dd;color:#0f5132;">Attivo</span>
                        @else
                            <span class="badge-status" style="background:#f8d7da;color:#842029;">Non attivo</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.discounts.delete', $discount->id) }}" method="POST" onsubmit="return confirm('Eliminare questo codice?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-admin-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:48px;color:#999;">Nessun codice sconto ancora.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
function toggleTypeFields(type){
    document.getElementById('typeSymbol').textContent = type === 'percent' ? '%' : '€';
}
</script>
@endsection
@endsection

