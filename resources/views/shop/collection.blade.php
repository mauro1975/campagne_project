@extends('layouts.app')

@section('title', 'Our Collection – .rosmarino')
@section('meta_description', 'Browse all categories of premium dog accessories.')

@section('content')
<div style="padding:60px 40px; max-width:1400px; margin:0 auto;">
    <div class="section-title">
        <p style="font-size:12px;letter-spacing:4px;text-transform:uppercase;color:var(--green-dark);font-weight:600;">{{ __('front.collection_eyebrow') }}</p>
        <h1>{{ __('front.collection_heading') }}</h1>
    </div>

    @php
        $catImages = [
            'collars'     => 'https://images.unsplash.com/photo-1506792006827-cd291f4930d2?w=600&auto=format&fit=crop',
            'leashes'     => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=600&auto=format&fit=crop',
            'coats'       => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=600&auto=format&fit=crop',
            'harnesses'   => 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=600&auto=format&fit=crop',
            'bag-holders' => 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=600&auto=format&fit=crop',
        ];
    @endphp

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;" class="cat-collection-grid">
        @foreach($categories as $cat)
        <a href="{{ route('category.show', $cat->slug) }}" class="cat-card" style="aspect-ratio:4/5;">
            <img src="{{ $cat->image ? asset($cat->image) : ($catImages[$cat->slug] ?? 'https://placehold.co/600x750/9ad7a0/fff?text='.urlencode($cat->name)) }}"
                 alt="{{ $cat->name }}" loading="lazy">
            <div class="cat-card-label">
                <h3>{{ $cat->name }}</h3>
                @if($cat->description)
                    <p style="margin:6px 0 0;font-size:14px;opacity:0.85;">{{ Str::limit($cat->description, 60) }}</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>

<style>
    @media(max-width:768px){ .cat-collection-grid { grid-template-columns:1fr 1fr !important; } }
    @media(max-width:480px){ .cat-collection-grid { grid-template-columns:1fr !important; } }
</style>
@endsection

