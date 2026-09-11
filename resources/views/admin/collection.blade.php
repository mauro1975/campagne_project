@extends('layouts.admin')

@section('title', 'La Nostra Collezione')
@section('page_title', 'La Nostra Collezione – Immagini')

@section('content')
<div style="max-width:1200px;">
    <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:24px 28px;margin-bottom:28px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
        <div>
            <h5 style="font-weight:700;margin-bottom:6px;">Pagina /collection</h5>
            <p style="font-size:13px;color:#888;margin:0;max-width:640px;">
                Carica un’immagine di copertina per ogni categoria: viene mostrata nella griglia «La Nostra Collezione» e nella sezione categorie in homepage.
                Formato consigliato: verticale 4:5, min. 800×1000 px, JPG o WebP.
            </p>
        </div>
        <a href="{{ route('collection') }}" target="_blank" rel="noopener" class="btn-admin-secondary" style="font-size:13px;">
            <i class="bi bi-box-arrow-up-right"></i> Anteprima sito
        </a>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#dc2626;">
            {{ $errors->first() }}
        </div>
    @endif

    @if($categories->isEmpty())
        <div style="text-align:center;padding:60px;background:#fff;border:1px solid #eee;border-radius:16px;color:#999;">
            <i class="bi bi-grid-3x3-gap" style="font-size:48px;display:block;margin-bottom:12px;color:#ccc;"></i>
            <p style="margin-bottom:16px;">Nessuna categoria. Creane una prima di caricare le immagini.</p>
            <a href="{{ route('admin.categories') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Vai alle categorie</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
            @foreach($categories as $cat)
                @php
                    $preview = $cat->image
                        ? asset($cat->image)
                        : 'https://placehold.co/600x750/e0f0eb/6fa398?text=' . urlencode($cat->getRawOriginal('name'));
                @endphp
                <div style="background:#fff;border:1px solid #eee;border-radius:16px;overflow:hidden;">
                    <div style="aspect-ratio:4/5;background:#f5f5f5;position:relative;">
                        <img src="{{ $preview }}" alt="{{ $cat->getRawOriginal('name') }}"
                             style="width:100%;height:100%;object-fit:cover;display:block;">
                        @if(!$cat->is_active)
                            <span style="position:absolute;top:10px;left:10px;background:rgba(0,0,0,.65);color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;text-transform:uppercase;">Nascosta</span>
                        @endif
                    </div>
                    <div style="padding:18px;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:14px;">
                            <div>
                                <p style="margin:0;font-weight:700;font-size:15px;">{{ $cat->getRawOriginal('name') }}</p>
                                <p style="margin:2px 0 0;font-size:12px;color:#999;">/{{ $cat->slug }} · {{ $cat->products_count }} prodotti</p>
                            </div>
                            <span style="font-size:11px;color:#888;white-space:nowrap;">Ordine {{ $cat->sort_order }}</span>
                        </div>

                        <form action="{{ route('admin.collection.image', $cat) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-label" style="margin-bottom:6px;">{{ $cat->image ? 'Sostituisci immagine' : 'Carica immagine' }}</label>
                            <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/jpeg,image/png,image/webp,image/gif" required>
                            <button type="submit" class="btn-admin-primary w-100" style="justify-content:center;padding:10px;font-size:13px;">
                                <i class="bi bi-cloud-upload"></i> Salva
                            </button>
                        </form>

                        @if($cat->image)
                            <form action="{{ route('admin.collection.image.destroy', $cat) }}" method="POST" style="margin-top:10px;"
                                  onsubmit="return confirm('Rimuovere l\'immagine di {{ addslashes($cat->getRawOriginal('name')) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-admin-danger w-100" style="justify-content:center;display:flex;align-items:center;gap:6px;padding:8px;">
                                    <i class="bi bi-trash"></i> Rimuovi immagine
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
