@extends('layouts.admin')

@section('title', 'Categorie')
@section('page_title', 'Categorie')

@section('content')
<div class="row g-4">
    {{-- Add/Edit Form --}}
    <div class="col-lg-4">
        <div style="background:#fff;border:1px solid #eee;border-radius:16px;padding:28px;position:sticky;top:80px;">
            <h5 style="font-weight:700;margin-bottom:20px;" id="formTitle">Aggiungi Categoria</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="catForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="category_id" id="formCategoryId" value="">

                @if($errors->any())
                <div style="background:#fef2f2;border-radius:8px;padding:12px;margin-bottom:16px;font-size:13px;color:#dc2626;">
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- Italian --}}
                <div style="background:#f9fafb;border:1px solid #eee;border-radius:10px;padding:14px 16px;margin-bottom:14px;">
                    <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#888;margin-bottom:12px;">🇮🇹 Italiano</div>
                    <div class="mb-3">
                        <label class="form-label">Nome *</label>
                        <input type="text" name="name" id="catName" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Descrizione</label>
                        <textarea name="description" id="catDesc" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- English --}}
                <div style="background:#f0f7ff;border:1px solid #d0e8ff;border-radius:10px;padding:14px 16px;margin-bottom:14px;">
                    <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#5a8fc4;margin-bottom:12px;">🇬🇧 English</div>
                    <div class="mb-3">
                        <label class="form-label">Name <span style="font-weight:400;color:#999;">(optional)</span></label>
                        <input type="text" name="name_en" id="catNameEn" class="form-control" value="{{ old('name_en') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Description</label>
                        <textarea name="description_en" id="catDescEn" class="form-control" rows="2">{{ old('description_en') }}</textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" id="catSlug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated">
                </div>
                <div class="mb-3">
                    <label class="form-label">Immagine</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div id="currentImageWrap" style="margin-top:8px;display:none;">
                        <img id="currentImage" src="" style="width:80px;height:80px;object-fit:cover;border-radius:8px;" alt="">
                        <p style="font-size:12px;color:#999;margin-top:4px;">Immagine attuale (lascia vuoto per mantenerla)</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Ordinamento</label>
                    <input type="number" name="sort_order" id="catSort" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="mb-4">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                        <input type="checkbox" name="is_active" id="catActive" value="1" checked> Attiva
                    </label>
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn-admin-primary flex-fill" style="justify-content:center;padding:12px;">
                        <i class="bi bi-check-lg"></i> <span id="submitLabel">Aggiungi Categoria</span>
                    </button>
                    <button type="button" onclick="resetForm()" class="btn-admin-secondary" style="padding:12px 16px;">Reimposta</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Categories List --}}
    <div class="col-lg-8">
        <table class="admin-table">
            <thead>
                <tr><th>Categoria</th><th>Prodotti</th><th>Stato</th><th>Ordine</th><th>Azioni</th></tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($cat->image)
                                <img src="{{ asset($cat->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;" alt="">
                            @else
                                <div style="width:48px;height:48px;background:var(--green-light);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-tag" style="color:var(--green-dark);"></i>
                                </div>
                            @endif
                            <div>
                                <p style="margin:0;font-weight:600;">{{ $cat->name }}</p>
                                <p style="margin:0;font-size:12px;color:#999;">/{{ $cat->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{ $cat->products_count ?? 0 }}</td>
                    <td>
                        @if($cat->is_active)
                            <span class="badge-status" style="background:#d1e7dd;color:#0f5132;">Attiva</span>
                        @else
                            <span class="badge-status" style="background:#f8d7da;color:#842029;">Nascosta</span>
                        @endif
                    </td>
                    <td>{{ $cat->sort_order }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button type="button" class="btn-admin-secondary" style="padding:6px 10px;font-size:13px;"
                                onclick="editCat({{ $cat->id }}, '{{ addslashes($cat->getRawOriginal('name')) }}', '{{ $cat->slug }}', {{ $cat->sort_order }}, {{ $cat->is_active?1:0 }}, '{{ $cat->image ? asset($cat->image) : '' }}', '{{ addslashes($cat->getRawOriginal('description') ?? '') }}', '{{ route('admin.categories.update', $cat->id) }}', '{{ addslashes($cat->getRawOriginal('name_en') ?? '') }}', '{{ addslashes($cat->getRawOriginal('description_en') ?? '') }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($cat->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-admin-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:48px;color:#999;">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
function editCat(id, name, slug, sort, active, imgUrl, desc, updateUrl, nameEn, descEn){
    document.getElementById('formTitle').textContent = 'Modifica Categoria';
    document.getElementById('submitLabel').textContent = 'Aggiorna Categoria';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('formCategoryId').value = id;
    document.getElementById('catForm').action = updateUrl;
    document.getElementById('catName').value = name;
    document.getElementById('catSlug').value = slug;
    document.getElementById('catSort').value = sort;
    document.getElementById('catActive').checked = active == 1;
    document.getElementById('catDesc').value = desc;
    document.getElementById('catNameEn').value = nameEn || '';
    document.getElementById('catDescEn').value = descEn || '';
    if(imgUrl){
        document.getElementById('currentImage').src = imgUrl;
        document.getElementById('currentImageWrap').style.display = 'block';
    }
    window.scrollTo({top:0, behavior:'smooth'});
}
function resetForm(){
    document.getElementById('formTitle').textContent = 'Aggiungi Categoria';
    document.getElementById('submitLabel').textContent = 'Aggiungi Categoria';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('formCategoryId').value = '';
    document.getElementById('catForm').action = '{{ route("admin.categories.store") }}';
    document.getElementById('catForm').reset();
    document.getElementById('catNameEn').value = '';
    document.getElementById('catDescEn').value = '';
    document.getElementById('currentImageWrap').style.display = 'none';
}
</script>
@endsection
@endsection

