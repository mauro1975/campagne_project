@extends('layouts.admin')

@section('title', 'Prodotti')
@section('page_title', 'Prodotti')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p style="font-size:14px;color:#666;margin:0;">{{ $products->total() }} prodotti totali</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-admin-primary">
        <i class="bi bi-plus-lg"></i> Aggiungi Prodotto
    </a>
</div>

{{-- Filters --}}
<form method="GET" style="background:#fff;border:1px solid #eee;border-radius:12px;padding:16px;margin-bottom:20px;display:flex;gap:12px;flex-wrap:wrap;">
    <input type="text" name="search" class="form-control" placeholder="Cerca prodotti..." value="{{ request('search') }}" style="max-width:280px;">
    <select name="category_id" class="form-select" style="max-width:180px;">
        <option value="">Tutte le Categorie</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-admin-secondary"><i class="bi bi-search"></i> Filtra</button>
    <a href="{{ route('admin.products') }}" class="btn-admin-secondary" style="padding:10px 16px;">Reimposta</a>
</form>

<table class="admin-table">
    <thead>
        <tr>
            <th>Prodotto</th>
            <th>Categoria</th>
            <th>Prezzo</th>
            <th>Giacenza</th>
            <th>Stato</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        @php
            $imgs = is_array($product->images) ? $product->images : (json_decode($product->images??'[]',true)??[]);
            $thumb = !empty($imgs) ? asset($imgs[0]) : null;
        @endphp
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:12px;">
                    <img src="{{ $thumb ?? 'https://placehold.co/48x48/f8f8f6/9ad7a0?text=Dog' }}"
                         style="width:48px;height:48px;border-radius:8px;object-fit:cover;" alt="">
                    <div>
                        <p style="margin:0;font-weight:600;">{{ $product->name }}</p>
                        <p style="margin:0;font-size:12px;color:#999;">{{ $product->sku ?? '' }}</p>
                    </div>
                </div>
            </td>
            <td>{{ $product->category->name ?? '—' }}</td>
            <td style="font-weight:600;">
                @if($product->discount_percent > 0)
                    <span style="text-decoration:line-through;color:#999;">€{{ number_format($product->price,2) }}</span><br>
                    €{{ number_format($product->final_price,2) }}
                @else
                    €{{ number_format($product->price,2) }}
                @endif
            </td>
            <td>
                @if($product->stock <= 0)
                    <span class="badge-status" style="background:#fef2f2;color:#dc2626;">Esaurito</span>
                @elseif($product->stock < 10)
                    <span class="badge-status" style="background:#fff7ed;color:#ea580c;">Scorte basse: {{ $product->stock }}</span>
                @else
                    <span style="font-size:14px;">{{ $product->stock }}</span>
                @endif
            </td>
            <td>
                @if($product->is_active)
                    <span class="badge-status" style="background:#d1e7dd;color:#0f5132;">Attivo</span>
                @else
                    <span class="badge-status" style="background:#f8d7da;color:#842029;">Nascosto</span>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-admin-secondary" style="padding:6px 12px;font-size:13px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Eliminare questo prodotto?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-admin-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:48px;color:#999;">Nessun prodotto trovato.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">{{ $products->withQueryString()->links() }}</div>
@endsection

