@extends('layouts.app')

@section('title', 'Best Sellers – .rosmarino')
@section('meta_description', 'Our most popular dog accessories, loved by customers.')

@section('content')
<div style="padding:60px 40px; max-width:1400px; margin:0 auto;">
    <div class="section-title">
        <p style="font-size:12px;letter-spacing:4px;text-transform:uppercase;color:var(--green-dark);font-weight:600;">{{ __('front.bs_eyebrow') }}</p>
        <h1>{{ __('front.bs_heading') }}</h1>
    </div>

    @if($products->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;" class="best-grid">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-5 d-flex justify-content-center">{{ $products->links() }}</div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-star" style="font-size:48px;color:var(--green);"></i>
            <p class="mt-3 text-muted">{{ __('front.bs_empty') }}</p>
        </div>
    @endif
</div>
<style>
    @media(max-width:992px){ .best-grid { grid-template-columns:repeat(2,1fr) !important; } }
    @media(max-width:576px){ .best-grid { gap:12px !important; } }
</style>
@endsection

