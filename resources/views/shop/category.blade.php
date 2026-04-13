@extends('layouts.app')

@section('title', $category->name . ' – .rosmarino')
@section('meta_description', $category->description ?? 'Browse our ' . $category->name . ' collection.')

@section('head')
<style>
    /* ── Hero layout ── */
    .cat-hero { display:grid; grid-template-columns:1fr 1fr; gap:56px; align-items:start; margin-bottom:72px; }
    @media(max-width:1100px){ .cat-hero { grid-template-columns:1fr 1fr; gap:36px; } }
    @media(max-width:900px) { .cat-hero { grid-template-columns:1fr; gap:0; } }

    /* ── Left: photo grid ── */
    .cat-img-col { position:sticky; top:24px; }
    @media(max-width:900px){ .cat-img-col { position:static; margin-bottom:32px; } }
    .cat-img-grid { display:grid; grid-template-columns:1fr 1fr; gap:6px;
                    transition:opacity .18s ease; }
    .cat-img-grid.single { grid-template-columns:1fr; }
    .cat-img-tile { display:block; aspect-ratio:3/4; overflow:hidden; }
    .cat-img-tile img { width:100%; height:100%; object-fit:cover; display:block;
                        transition:transform .35s ease; }
    .cat-img-tile:hover img { transform:scale(1.04); }

    /* ── Right panel ── */
    .cat-panel { position:sticky; top:24px; }
    @media(max-width:900px){ .cat-panel { position:static; } }

    .cat-coll-label { font-size:11px; letter-spacing:3px; text-transform:uppercase;
                      color:var(--green-dark); font-weight:700; margin-bottom:10px; }
    .cat-pdname { font-size:clamp(22px,2.8vw,34px); font-weight:700; margin-bottom:6px; line-height:1.2; }
    .cat-price { font-size:18px; margin-bottom:28px; }
    .cat-price-orig { text-decoration:line-through; color:#aaa; font-size:15px; margin-right:8px; }
    .cat-price-curr { font-weight:700; color:var(--black); }

    .cat-sect { margin-bottom:24px; }
    .cat-sect-label { font-size:11px; letter-spacing:2px; text-transform:uppercase;
                      font-weight:700; color:var(--black); margin-bottom:12px; }

    /* Size squares */
    .cat-sizes { display:flex; flex-wrap:wrap; gap:8px; }
    .cat-size-sq { width:46px; height:46px; display:inline-flex; align-items:center; justify-content:center;
                   border:1.5px solid #ddd; border-radius:0; background:#fff;
                   font-size:13px; font-weight:600; cursor:pointer; color:var(--black);
                   transition:all .15s; padding:0; }
    .cat-size-sq:hover { border-color:var(--black); }
    .cat-size-sq.active { background:var(--black); color:#fff; border-color:var(--black); }

    /* Color photo thumbnails */
    .cat-color-row { display:flex; flex-wrap:wrap; gap:10px; }
    .cct { background:none; border:none; padding:0; cursor:pointer; text-align:center;
           opacity:.55; transition:opacity .2s; width:72px; }
    .cct:hover { opacity:.85; }
    .cct.active { opacity:1; }
    .cct img { width:72px; height:72px; object-fit:cover; border-radius:6px;
               border:2px solid transparent; transition:border-color .2s; display:block; }
    .cct.active img { border-color:var(--black); }
    .cct span { font-size:11px; display:block; margin-top:5px; color:var(--black);
                white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

    /* Shop button */
    .cat-shop-btn { display:flex; align-items:center; justify-content:center; gap:8px;
                    background:#9BC3B1; color:#fff; padding:14px 28px;
                    font-size:14px; font-weight:600; letter-spacing:1px;
                    text-decoration:none; transition:background .2s; margin-top:32px;
                    border-radius:0; border:none; width:100%; }
    .cat-shop-btn:hover { background:#6fa398; color:#fff; }

    /* ── Filter bar ── */
    .cat-filter-bar { display:flex; align-items:center; justify-content:space-between;
                      flex-wrap:wrap; gap:12px; margin-bottom:28px; }
    .active-tag { display:inline-flex; align-items:center; gap:6px; background:var(--green-light);
                  border:1px solid var(--green); color:var(--green-dark); border-radius:20px;
                  padding:4px 12px; font-size:12px; font-weight:600; }
    .active-tag a { color:inherit; text-decoration:none; font-size:15px; line-height:1; }
    .sort-select { border:1.5px solid #ddd; border-radius:8px; padding:8px 14px;
                   font-size:14px; background:#fff; cursor:pointer; outline:none; }
    .sort-select:focus { border-color:var(--black); }

    /* ── Product grid ── */
    .products-grid-filter { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
    @media(max-width:1200px){ .products-grid-filter { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:768px) { .products-grid-filter { grid-template-columns:repeat(2,1fr); gap:12px; } }
</style>
@endsection

@section('content')
<div style="max-width:1400px; margin:0 auto; padding:40px 24px 80px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" style="margin-bottom:36px;">
        <ol class="breadcrumb" style="font-size:13px; background:none; padding:0; margin:0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--green-dark);">{{ __('front.cat_breadcrumb_home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('collection') }}" style="color:var(--green-dark);">{{ __('front.cat_breadcrumb_collection') }}</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- HERO SECTION --}}
    @php
        $heroProducts = $allProducts->flatMap(function($p) {
            $imgs = is_array($p->images) ? $p->images : (json_decode($p->images ?? '[]', true) ?? []);
            $cols = is_array($p->available_colors) ? $p->available_colors : (json_decode($p->available_colors ?? '[]', true) ?? []);
            $szs  = is_array($p->available_sizes)  ? $p->available_sizes  : (json_decode($p->available_sizes ?? '[]', true) ?? []);
            $baseImgUrl  = !empty($imgs) ? asset($imgs[0]) : null;
            $imgsUrls    = array_map(fn($i) => asset($i), $imgs);
            $base = [
                'id'      => $p->id,
                'name'    => $p->name,
                'price'   => number_format($p->price, 2),
                'compare' => $p->compare_price ? number_format($p->compare_price, 2) : null,
                'sizes'   => $szs,
                'url'     => route('product.show', $p->slug),
            ];
            if (empty($cols)) {
                return [array_merge($base, ['img' => $baseImgUrl, 'imgs' => $imgsUrls, 'color' => '', 'colors' => []])];
            }
            return array_map(function($col) use ($base, $baseImgUrl, $imgsUrls) {
                // Use color-specific images array; fall back to all product images
                $colImgs = !empty($col['images'])
                    ? array_map(fn($i) => asset($i), $col['images'])
                    : (!empty($col['image']) ? [asset($col['image'])] : $imgsUrls);
                $colImg  = !empty($colImgs) ? $colImgs[0] : $baseImgUrl;
                return array_merge($base, [
                    'img'    => $colImg,
                    'imgs'   => $colImgs,
                    'color'  => $col['name'] ?? '',
                    'colors' => [$col],
                ]);
            }, $cols);
        })->values();
        $first = $heroProducts->first();
        $fallbackImg = $category->image
            ? asset($category->image)
            : 'https://placehold.co/600x750/f8f8f6/9ad7a0?text=' . urlencode($category->name);
    @endphp

    <div class="cat-hero">

        {{-- LEFT: all photos for the active color as a grid --}}
        @php $firstImgs = $first ? ($first['imgs'] ?? ($first['img'] ? [$first['img']] : [])) : []; @endphp
        <div class="cat-img-col">
            <div id="heroImgGrid" class="cat-img-grid {{ count($firstImgs) <= 1 ? 'single' : '' }}">
                @forelse($firstImgs as $src)
                    <a href="{{ $first ? $first['url'] : '#' }}" class="cat-img-tile">
                        <img src="{{ $src }}" alt="{{ $first ? $first['name'] : $category->name }}">
                    </a>
                @empty
                    <a href="{{ $first ? $first['url'] : '#' }}" class="cat-img-tile">
                        <img src="{{ $fallbackImg }}" alt="{{ $category->name }}">
                    </a>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: product name + size squares + color photo thumbs --}}
        <div class="cat-panel">

            <p class="cat-coll-label">Collection · {{ $category->name }}</p>

            <h2 id="heroName" class="cat-pdname">{{ $first ? $first['name'] : $category->name }}</h2>

            <p id="heroPrice" class="cat-price">
                @if($first)
                    @if($first['compare'])
                        <span class="cat-price-orig">€{{ $first['compare'] }}</span>
                    @endif
                    <span class="cat-price-curr">€{{ $first['price'] }}</span>
                @endif
            </p>

            {{-- Size selector --}}
            <div class="cat-sect" id="heroSizeSection" style="{{ $first && count($first['sizes']) ? '' : 'display:none;' }}">
                <p class="cat-sect-label">{{ __('front.cat_size_label') }}</p>
                <div class="cat-sizes" id="heroSizes">
                    @if($first)
                        @foreach($first['sizes'] as $sz)
                            <button type="button"
                                    class="cat-size-sq {{ $loop->first ? 'active' : '' }}"
                                    onclick="selectHeroSize(this)">{{ $sz }}</button>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Color selector: miniature product photos --}}
            @if($heroProducts->count() > 0)
            <div class="cat-sect">
                <p class="cat-sect-label">{{ __('front.cat_color_label') }}: <span id="heroColorLabel" style="font-weight:400;text-transform:none;letter-spacing:0;">{{ $first ? $first['color'] : '' }}</span></p>
                <div class="cat-color-row">
                    @foreach($heroProducts as $i => $hp)
                    <button type="button"
                            class="cct {{ $i === 0 ? 'active' : '' }}"
                            onclick="selectHeroProduct({{ $i }})"
                            title="{{ $hp['name'] }}">
                        <img src="{{ $hp['img'] ?? $fallbackImg }}" alt="{{ $hp['name'] }}">
                        <span>{{ $hp['color'] ?: $hp['name'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- CTA --}}
            <form id="heroCartForm" style="display:none;">
                @csrf
                <input type="hidden" name="product_id" id="heroProductId" value="{{ $first ? $first['id'] : '' }}">
                <input type="hidden" name="color"      id="heroCartColor" value="{{ $first ? $first['color'] : '' }}">
                <input type="hidden" name="size"       id="heroCartSize"  value="{{ $first && !empty($first['sizes']) ? $first['sizes'][0] : '' }}">
                <input type="hidden" name="quantity"   value="1">
            </form>

            <button type="button" id="heroAddToCart" class="cat-shop-btn"
                    onclick="heroAddToCart()">
                <i class="bi bi-bag-plus"></i> {{ __('front.cat_add_to_cart') }}
            </button>

            {{-- Payment icons --}}
            <div style="display:flex;flex-direction:column;gap:8px;margin-top:14px; text-align:center;">
                <p style="font-size:11px;letter-spacing:1px;color:#aaa;margin:0;">{{ __('front.cat_secure_payments') }}</p>
                <div style="align-items:center;gap:6px;flex-wrap:wrap;">   
                    <svg xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="pi-visa" viewBox="0 0 38 24" width="38" height="24"><title id="pi-visa">Visa</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"/></svg>
                    <svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-master"><title id="pi-master">Mastercard</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><circle fill="#EB001B" cx="15" cy="12" r="7"/><circle fill="#F79E1B" cx="23" cy="12" r="7"/><path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z"/></svg>
                    <svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-paypal"><title id="pi-paypal">PayPal</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path fill="#003087" d="M23.9 8.3c.2-1 0-1.7-.6-2.3-.6-.7-1.7-1-3.1-1h-4.1c-.3 0-.5.2-.6.5L14 15.6c0 .2.1.4.3.4H17l.4-3.4 1.8-2.2 4.7-2.1z"/><path fill="#3086C8" d="M23.9 8.3l-.2.2c-.5 2.8-2.2 3.8-4.6 3.8H18c-.3 0-.5.2-.6.5l-.6 3.9-.2 1c0 .2.1.4.3.4H19c.3 0 .5-.2.5-.4v-.1l.4-2.4v-.1c0-.2.3-.4.5-.4h.3c2.1 0 3.7-.8 4.1-3.2.2-1 .1-1.8-.4-2.4-.1-.5-.3-.7-.5-.8z"/><path fill="#012169" d="M23.3 8.1c-.1-.1-.2-.1-.3-.1-.1 0-.2 0-.3-.1-.3-.1-.7-.1-1.1-.1h-3c-.1 0-.2 0-.2.1-.2.1-.3.2-.3.4l-.7 4.4v.1c0-.3.3-.5.6-.5h1.3c2.5 0 4.1-1 4.6-3.8v-.2c-.1-.1-.3-.2-.5-.2h-.1z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="pi-american_express" viewBox="0 0 38 24" width="38" height="24"><title id="pi-american_express">American Express</title><path fill="#000" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3Z" opacity=".07"/><path fill="#006FCF" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32Z"/><path fill="#FFF" d="M22.012 19.936v-8.421L37 11.528v2.326l-1.732 1.852L37 17.573v2.375h-2.766l-1.47-1.622-1.46 1.628-9.292-.02Z"/><path fill="#006FCF" d="M23.013 19.012v-6.57h5.572v1.513h-3.768v1.028h3.678v1.488h-3.678v1.01h3.768v1.531h-5.572Z"/><path fill="#006FCF" d="m28.557 19.012 3.083-3.289-3.083-3.282h2.386l1.884 2.083 1.89-2.082H37v.051l-3.017 3.23L37 18.92v.093h-2.307l-1.917-2.103-1.898 2.104h-2.321Z"/><path fill="#FFF" d="M22.71 4.04h3.614l1.269 2.881V4.04h4.46l.77 2.159.771-2.159H37v8.421H19l3.71-8.421Z"/><path fill="#006FCF" d="m23.395 4.955-2.916 6.566h2l.55-1.315h2.98l.55 1.315h2.05l-2.904-6.566h-2.31Zm.25 3.777.875-2.09.873 2.09h-1.748Z"/><path fill="#006FCF" d="M28.581 11.52V4.953l2.811.01L32.84 9l1.456-4.046H37v6.565l-1.74.016v-4.51l-1.644 4.494h-1.59L30.35 7.01v4.51h-1.768Z"/></svg>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" role="img" x="0" y="0" width="38" height="24" viewBox="0 0 165.521 105.965" xml:space="preserve" aria-labelledby="pi-apple_pay"><title id="pi-apple_pay">Apple Pay</title><path fill="#000" d="M150.698 0H14.823c-.566 0-1.133 0-1.698.003-.477.004-.953.009-1.43.022-1.039.028-2.087.09-3.113.274a10.51 10.51 0 0 0-2.958.975 9.932 9.932 0 0 0-4.35 4.35 10.463 10.463 0 0 0-.975 2.96C.113 9.611.052 10.658.024 11.696a70.22 70.22 0 0 0-.022 1.43C0 13.69 0 14.256 0 14.823v76.318c0 .567 0 1.132.002 1.699.003.476.009.953.022 1.43.028 1.036.09 2.084.275 3.11a10.46 10.46 0 0 0 .974 2.96 9.897 9.897 0 0 0 1.83 2.52 9.874 9.874 0 0 0 2.52 1.83c.947.483 1.917.79 2.96.977 1.025.183 2.073.245 3.112.273.477.011.953.017 1.43.02.565.004 1.132.004 1.698.004h135.875c.565 0 1.132 0 1.697-.004.476-.002.952-.009 1.431-.02 1.037-.028 2.085-.09 3.113-.273a10.478 10.478 0 0 0 2.958-.977 9.955 9.955 0 0 0 4.35-4.35c.483-.947.789-1.917.974-2.96.186-1.026.246-2.074.274-3.11.013-.477.02-.954.022-1.43.004-.567.004-1.132.004-1.699V14.824c0-.567 0-1.133-.004-1.699a63.067 63.067 0 0 0-.022-1.429c-.028-1.038-.088-2.085-.274-3.112a10.4 10.4 0 0 0-.974-2.96 9.94 9.94 0 0 0-4.35-4.35A10.52 10.52 0 0 0 156.939.3c-1.028-.185-2.076-.246-3.113-.274a71.417 71.417 0 0 0-1.431-.022C151.83 0 151.263 0 150.698 0z"/><path fill="#FFF" d="M150.698 3.532l1.672.003c.452.003.905.008 1.36.02.793.022 1.719.065 2.583.22.75.135 1.38.34 1.984.648a6.392 6.392 0 0 1 2.804 2.807c.306.6.51 1.226.645 1.983.154.854.197 1.783.218 2.58.013.45.019.9.02 1.36.005.557.005 1.113.005 1.671v76.318c0 .558 0 1.114-.004 1.682-.002.45-.008.9-.02 1.35-.022.796-.065 1.725-.221 2.589a6.855 6.855 0 0 1-.645 1.975 6.397 6.397 0 0 1-2.808 2.807c-.6.306-1.228.511-1.971.645-.881.157-1.847.2-2.574.22-.457.01-.912.017-1.379.019-.555.004-1.113.004-1.669.004H14.801c-.55 0-1.1 0-1.66-.004a74.993 74.993 0 0 1-1.35-.018c-.744-.02-1.71-.064-2.584-.22a6.938 6.938 0 0 1-1.986-.65 6.337 6.337 0 0 1-1.622-1.18 6.355 6.355 0 0 1-1.178-1.623 6.935 6.935 0 0 1-.646-1.985c-.156-.863-.2-1.788-.22-2.578a66.088 66.088 0 0 1-.02-1.355l-.003-1.327V14.474l.002-1.325a66.7 66.7 0 0 1 .02-1.357c.022-.792.065-1.717.222-2.587a6.924 6.924 0 0 1 .646-1.981c.304-.598.7-1.144 1.18-1.623a6.386 6.386 0 0 1 1.624-1.18 6.96 6.96 0 0 1 1.98-.646c.865-.155 1.792-.198 2.586-.22.452-.012.905-.017 1.354-.02l1.677-.003h135.875"/><g><g><path fill="#000" d="M43.508 35.77c1.404-1.755 2.356-4.112 2.105-6.52-2.054.102-4.56 1.355-6.012 3.112-1.303 1.504-2.456 3.959-2.156 6.266 2.306.2 4.61-1.152 6.063-2.858"/><path fill="#000" d="M45.587 39.079c-3.35-.2-6.196 1.9-7.795 1.9-1.6 0-4.049-1.8-6.698-1.751-3.447.05-6.645 2-8.395 5.1-3.598 6.2-.95 15.4 2.55 20.45 1.699 2.5 3.747 5.25 6.445 5.151 2.55-.1 3.549-1.65 6.647-1.65 3.097 0 3.997 1.65 6.696 1.6 2.798-.05 4.548-2.5 6.247-5 1.95-2.85 2.747-5.6 2.797-5.75-.05-.05-5.396-2.101-5.446-8.251-.05-5.15 4.198-7.6 4.398-7.751-2.399-3.548-6.147-3.948-7.447-4.048"/></g><g><path fill="#000" d="M78.973 32.11c7.278 0 12.347 5.017 12.347 12.321 0 7.33-5.173 12.373-12.529 12.373h-8.058V69.62h-5.822V32.11h14.062zm-8.24 19.807h6.68c5.07 0 7.954-2.729 7.954-7.46 0-4.73-2.885-7.434-7.928-7.434h-6.706v14.894z"/><path fill="#000" d="M92.764 61.847c0-4.809 3.665-7.564 10.423-7.98l7.252-.442v-2.08c0-3.04-2.001-4.704-5.562-4.704-2.938 0-5.07 1.507-5.51 3.82h-5.252c.157-4.86 4.731-8.395 10.918-8.395 6.654 0 10.995 3.483 10.995 8.89v18.663h-5.38v-4.497h-.13c-1.534 2.937-4.914 4.782-8.579 4.782-5.406 0-9.175-3.222-9.175-8.057zm17.675-2.417v-2.106l-6.472.416c-3.64.234-5.536 1.585-5.536 3.95 0 2.288 1.975 3.77 5.068 3.77 3.95 0 6.94-2.522 6.94-6.03z"/><path fill="#000" d="M120.975 79.652v-4.496c.364.051 1.247.103 1.715.103 2.573 0 4.029-1.09 4.913-3.899l.52-1.663-9.852-27.293h6.082l6.863 22.146h.13l6.862-22.146h5.927l-10.216 28.67c-2.34 6.577-5.017 8.735-10.683 8.735-.442 0-1.872-.052-2.261-.157z"/></g></g></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-google_pay"><title id="pi-google_pay">Google Pay</title><path d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z" fill="#000" opacity=".07"/><path d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32" fill="#FFF"/><path d="M18.093 11.976v3.2h-1.018v-7.9h2.691a2.447 2.447 0 0 1 1.747.692 2.28 2.28 0 0 1 .11 3.224l-.11.116c-.47.447-1.098.69-1.747.674l-1.673-.006zm0-3.732v2.788h1.698c.377.012.741-.135 1.005-.404a1.391 1.391 0 0 0-1.005-2.354l-1.698-.03zm6.484 1.348c.65-.03 1.286.188 1.778.613.445.43.682 1.03.65 1.649v3.334h-.969v-.766h-.049a1.93 1.93 0 0 1-1.673.931 2.17 2.17 0 0 1-1.496-.533 1.667 1.667 0 0 1-.613-1.324 1.606 1.606 0 0 1 .613-1.336 2.746 2.746 0 0 1 1.698-.515c.517-.02 1.03.093 1.49.331v-.208a1.134 1.134 0 0 0-.417-.901 1.416 1.416 0 0 0-.98-.368 1.545 1.545 0 0 0-1.319.717l-.895-.564a2.488 2.488 0 0 1 2.182-1.06zM23.29 13.52a.79.79 0 0 0 .337.662c.223.176.5.269.785.263.429-.001.84-.17 1.146-.472.305-.286.478-.685.478-1.103a2.047 2.047 0 0 0-1.324-.374 1.716 1.716 0 0 0-1.03.294.883.883 0 0 0-.392.73zm9.286-3.75l-3.39 7.79h-1.048l1.281-2.728-2.224-5.062h1.103l1.612 3.885 1.569-3.885h1.097z" fill="#5F6368"/><path d="M13.986 11.284c0-.308-.024-.616-.073-.92h-4.29v1.747h2.451a2.096 2.096 0 0 1-.9 1.373v1.134h1.464a4.433 4.433 0 0 0 1.348-3.334z" fill="#4285F4"/><path d="M9.629 15.721a4.352 4.352 0 0 0 3.01-1.097l-1.466-1.14a2.752 2.752 0 0 1-4.094-1.44H5.577v1.17a4.53 4.53 0 0 0 4.052 2.507z" fill="#34A853"/><path d="M7.079 12.05a2.709 2.709 0 0 1 0-1.735v-1.17H5.577a4.505 4.505 0 0 0 0 4.075l1.502-1.17z" fill="#FBBC04"/><path d="M9.629 8.44a2.452 2.452 0 0 1 1.74.68l1.3-1.293a4.37 4.37 0 0 0-3.065-1.183 4.53 4.53 0 0 0-4.027 2.5l1.502 1.171a2.715 2.715 0 0 1 2.55-1.875z" fill="#EA4335"/></svg>
                    <svg viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-maestro"><title id="pi-maestro">Maestro</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><circle fill="#EB001B" cx="15" cy="12" r="7"/><circle fill="#00A2E5" cx="23" cy="12" r="7"/><path fill="#7375CF" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z"/></svg>
                </div>
            </div>

        </div>
    </div>

    {{-- PRODUCTS GRID --}}
    <div id="productsSection">
        <div style="height:1px;background:#eee;margin-bottom:32px;"></div>

        {{-- Filter / sort bar --}}
        @php
            $activeSize  = request('size');
            $activeColor = request('color');
            $activeSort  = request('sort');
        @endphp
        <div class="cat-filter-bar">
            <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                <span style="font-size:14px;color:var(--text-muted);">
                    <strong style="color:var(--black);">{{ $products->total() }}</strong> {{ $products->total() === 1 ? __('front.cat_product_singular') : __('front.cat_product_plural') }}
                </span>
                @if($activeColor)
                    <span class="active-tag">{{ $activeColor }} <a href="{{ request()->fullUrlWithoutQuery(['color']) }}">×</a></span>
                @endif
                @if($activeSize)
                    <span class="active-tag">{{ __('front.cat_size_label') }} {{ $activeSize }} <a href="{{ request()->fullUrlWithoutQuery(['size']) }}">×</a></span>
                @endif
                @if($activeColor || $activeSize)
                    <a href="{{ route('category.show', $category->slug) }}" style="font-size:12px;color:var(--text-muted);text-decoration:underline;">{{ __('front.cat_remove_filters') }}</a>
                @endif
            </div>
            <form method="GET" action="{{ route('category.show', $category->slug) }}" style="display:flex;gap:8px;align-items:center;">
                @if($activeSize)  <input type="hidden" name="size"  value="{{ $activeSize }}"> @endif
                @if($activeColor) <input type="hidden" name="color" value="{{ $activeColor }}"> @endif
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="">{{ __('front.cat_sort_label') }}: {{ __('front.cat_sort_featured') }}</option>
                    <option value="price_asc"  {{ $activeSort === 'price_asc'  ? 'selected' : '' }}>{{ __('front.cat_sort_price_asc') }}</option>
                    <option value="price_desc" {{ $activeSort === 'price_desc' ? 'selected' : '' }}>{{ __('front.cat_sort_price_desc') }}</option>
                    <option value="newest"     {{ $activeSort === 'newest'     ? 'selected' : '' }}>{{ __('front.cat_sort_newest') }}</option>
                </select>
            </form>
        </div>

        @if($products->count() > 0)
            <div class="products-grid-filter">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size:48px; color:var(--green);"></i>
                <h5 class="mt-3">{{ __('front.cat_empty_title') }}</h5>
                <p class="text-muted mb-4">{{ __('front.cat_empty_text') }}</p>
                <a href="{{ route('category.show', $category->slug) }}" class="btn-black d-inline-block">{{ __('front.cat_remove_filters') }}</a>
            </div>
        @endif
    </div>

</div>

@section('scripts')
<script>
const heroData = {!! json_encode($heroProducts) !!};
const cartAddUrl = '{{ route("cart.add") }}';
const csrfToken  = '{{ csrf_token() }}';

function selectHeroProduct(idx) {
    const p = heroData[idx];
    const imgs = (p.imgs && p.imgs.length) ? p.imgs : (p.img ? [p.img] : []);
    // Rebuild image grid
    const grid = document.getElementById('heroImgGrid');
    if (grid) {
        grid.className = 'cat-img-grid' + (imgs.length <= 1 ? ' single' : '');
        grid.style.opacity = '0.35';
        setTimeout(() => {
            grid.innerHTML = imgs.map(src =>
                `<a href="${p.url}" class="cat-img-tile"><img src="${src}" alt="${p.name}"></a>`
            ).join('');
            grid.style.opacity = '1';
        }, 180);
    }
    document.getElementById('heroName').textContent = p.name;
    let priceHtml = '';
    if (p.compare) priceHtml += `<span class="cat-price-orig">€${p.compare}</span> `;
    priceHtml += `<span class="cat-price-curr">€${p.price}</span>`;
    document.getElementById('heroPrice').innerHTML = priceHtml;
    const sizeSection = document.getElementById('heroSizeSection');
    const sizesEl     = document.getElementById('heroSizes');
    if (p.sizes && p.sizes.length > 0) {
        sizesEl.innerHTML = p.sizes.map((sz, i) =>
            `<button type="button" class="cat-size-sq ${i===0?'active':''}" onclick="selectHeroSize(this)">${sz}</button>`
        ).join('');
        sizeSection.style.display = '';
    } else {
        sizesEl.innerHTML = '';
        sizeSection.style.display = 'none';
    }
    document.getElementById('heroColorLabel').textContent = p.color || p.name;
    document.getElementById('heroShopBtn') && (document.getElementById('heroShopBtn').href = p.url);
    document.querySelectorAll('.cct').forEach((el, i) => el.classList.toggle('active', i === idx));
    // sync hidden form
    document.getElementById('heroProductId').value = p.id;
    document.getElementById('heroCartColor').value = p.color || '';
    document.getElementById('heroCartSize').value  = sizesEl.querySelector('.cat-size-sq.active')?.textContent.trim() || '';
}

function selectHeroSize(el) {
    document.querySelectorAll('#heroSizes .cat-size-sq').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('heroCartSize').value = el.textContent.trim();
}

function heroAddToCart() {
    const btn = document.getElementById('heroAddToCart');
    const productId = document.getElementById('heroProductId').value;
    const color     = document.getElementById('heroCartColor').value;
    const size      = document.getElementById('heroCartSize').value;
    if (!productId) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Aggiunta...';

    fetch(cartAddUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ product_id: productId, color, size, quantity: 1 }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Refresh cart sidebar by fetching the current page
            return fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc    = parser.parseFromString(html, 'text/html');
                    const newSidebar = doc.getElementById('cartSidebar');
                    if (newSidebar) {
                        document.getElementById('cartSidebar').innerHTML = newSidebar.innerHTML;
                    }
                    // update nav badge
                    const newBadge = doc.querySelector('.cart-badge');
                    const cartToggle = document.getElementById('cartToggle');
                    if (cartToggle && newBadge) {
                        let oldBadge = cartToggle.querySelector('.cart-badge');
                        if (oldBadge) oldBadge.textContent = newBadge.textContent;
                        else { const b = document.createElement('span'); b.className = 'cart-badge'; b.textContent = newBadge.textContent; cartToggle.appendChild(b); }
                    }
                    openCart();
                });
        }
    })
    .catch(() => {})
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-bag-plus"></i> {{ __("front.cat_add_to_cart") }}';
    });
}
</script>
@endsection

@endsection
