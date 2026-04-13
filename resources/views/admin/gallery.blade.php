@extends('layouts.admin')

@section('title', 'Gallery Home')
@section('page_title', 'Gallery – Foto Homepage')

@section('content')
<div style="max-width:1100px;">

    {{-- Upload form --}}
    <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;margin-bottom:32px;">
        <h5 style="font-weight:700;margin-bottom:6px;">Aggiungi foto</h5>
        <p style="font-size:13px;color:#888;margin-bottom:20px;">Le foto vengono mostrate nel carosello hero della homepage. Formato consigliato: orizzontale, min 1600px.</p>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#dc2626;">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="row g-3 align-items-end">
                <div class="col-md-7">
                    <label class="form-label">Foto (puoi selezionarne più di una)</label>
                    <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required id="photoInput">
                    <div id="previewStrip" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Alt text <span style="font-weight:400;color:#999;">(opzionale)</span></label>
                    <input type="text" name="alt" class="form-control" placeholder="es. cane con guinzaglio rosso">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-admin-primary w-100" style="justify-content:center;padding:11px;">
                        <i class="bi bi-cloud-upload"></i> Carica
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Gallery grid --}}
    <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div>
                <h5 style="font-weight:700;margin-bottom:4px;">Foto in gallery <span style="font-size:14px;font-weight:400;color:#999;">({{ $photos->count() }})</span></h5>
                <p style="font-size:13px;color:#888;margin:0;">Trascina per cambiare l'ordine. La prima foto è quella del banner principale.</p>
            </div>
            <button id="saveOrderBtn" onclick="saveOrder()" class="btn-admin-secondary" style="font-size:13px;display:none;">
                <i class="bi bi-save"></i> Salva ordine
            </button>
        </div>

        @if($photos->isEmpty())
            <div style="text-align:center;padding:60px;color:#bbb;">
                <i class="bi bi-images" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                <p>Nessuna foto ancora. Carica la prima!</p>
            </div>
        @else
            <div id="galleryGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
                @foreach($photos as $photo)
                <div class="gallery-item" data-id="{{ $photo->id }}" style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:16/9;background:#f0f0f0;cursor:grab;border:2px solid transparent;transition:border-color .2s;">
                    <img src="{{ asset($photo->path) }}" alt="{{ $photo->alt }}"
                         style="width:100%;height:100%;object-fit:cover;display:block;pointer-events:none;">
                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0);transition:background .2s;"
                         class="gallery-overlay">
                        <div style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.5);color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;" class="sort-badge">{{ $loop->iteration }}</div>
                        <form action="{{ route('admin.gallery.destroy', $photo->id) }}" method="POST"
                              style="position:absolute;top:8px;right:8px;"
                              onsubmit="return confirm('Eliminare questa foto?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:rgba(220,38,38,.85);border:none;color:#fff;width:28px;height:28px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;backdrop-filter:blur(4px);">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
// ── Preview uploaded files ──────────────────────────────────────────────────
document.getElementById('photoInput').addEventListener('change', function(){
    const strip = document.getElementById('previewStrip');
    strip.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:80px;height:52px;object-fit:cover;border-radius:6px;border:1px solid #ddd;';
            strip.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});

// ── Drag-to-reorder ─────────────────────────────────────────────────────────
const grid = document.getElementById('galleryGrid');
if (grid) {
    let dragging = null;

    grid.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('dragstart', e => {
            dragging = item;
            item.style.opacity = '0.4';
        });
        item.addEventListener('dragend', () => {
            dragging.style.opacity = '1';
            dragging = null;
            updateBadges();
            document.getElementById('saveOrderBtn').style.display = 'flex';
        });
        item.addEventListener('dragover', e => {
            e.preventDefault();
            if (item !== dragging) {
                const rect = item.getBoundingClientRect();
                const mid  = rect.left + rect.width / 2;
                if (e.clientX < mid) {
                    grid.insertBefore(dragging, item);
                } else {
                    grid.insertBefore(dragging, item.nextSibling);
                }
            }
        });
        item.setAttribute('draggable', 'true');
    });

    function updateBadges() {
        grid.querySelectorAll('.sort-badge').forEach((b, i) => b.textContent = i + 1);
    }
}

function saveOrder() {
    const ids = Array.from(grid.querySelectorAll('.gallery-item')).map(el => el.dataset.id);
    fetch('{{ route('admin.gallery.reorder') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify({ order: ids }),
    }).then(r => r.json()).then(() => {
        document.getElementById('saveOrderBtn').style.display = 'none';
        const btn = document.getElementById('saveOrderBtn');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Salvato!';
        btn.style.display = 'flex';
        setTimeout(() => { btn.style.display = 'none'; btn.innerHTML = '<i class="bi bi-save"></i> Salva ordine'; }, 1500);
    });
}

// ── Hover overlay ────────────────────────────────────────────────────────────
document.querySelectorAll('.gallery-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
        item.querySelector('.gallery-overlay').style.background = 'rgba(0,0,0,.3)';
        item.style.borderColor = 'var(--green-dark)';
    });
    item.addEventListener('mouseleave', () => {
        item.querySelector('.gallery-overlay').style.background = 'rgba(0,0,0,0)';
        item.style.borderColor = 'transparent';
    });
});
</script>
@endsection
