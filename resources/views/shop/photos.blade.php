@extends('layouts.app')

@section('title', 'Dog Photos – .rosmarino')
@section('meta_description', 'Our community of happy dogs wearing .rosmarino accessories.')

@section('content')
<div style="padding:60px 40px; max-width:1400px; margin:0 auto;">
    <div class="section-title text-center">
        <p style="font-size:12px;letter-spacing:4px;text-transform:uppercase;color:var(--green-dark);font-weight:600;">{{ __('front.photos_eyebrow') }}</p>
        <h1>{{ __('front.photos_heading') }}</h1>
        <p style="color:var(--text-muted);max-width:500px;margin:0 auto 48px;">{!! __('front.photos_sub') !!}</p>
    </div>

    @php
        $photos = [
            ['url'=>'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=600&auto=format&fit=crop','caption'=>'Max wearing our Leather Collar'],
            ['url'=>'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=600&auto=format&fit=crop','caption'=>'Bella on her morning walk'],
            ['url'=>'https://images.unsplash.com/photo-1506792006827-cd291f4930d2?w=600&auto=format&fit=crop','caption'=>'Charlie loves his new harness'],
            ['url'=>'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=600&auto=format&fit=crop','caption'=>'Luna looking stylish'],
            ['url'=>'https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?w=600&auto=format&fit=crop','caption'=>'Rocky in his winter coat'],
            ['url'=>'https://images.unsplash.com/photo-1552053831-71594a27632d?w=600&auto=format&fit=crop','caption'=>'Daisy all dressed up'],
        ];
    @endphp

    <div style="columns:3;column-gap:16px;" class="photos-masonry">
        @foreach($photos as $photo)
            <div style="break-inside:avoid;margin-bottom:16px;border-radius:12px;overflow:hidden;position:relative;cursor:pointer;" class="photo-item">
                <img src="{{ $photo['url'] }}" alt="{{ $photo['caption'] }}" loading="lazy" style="width:100%;display:block;">
                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.5));padding:24px 16px 12px;color:#fff;opacity:0;transition:opacity .3s;" class="photo-caption">
                    <p style="margin:0;font-size:14px;font-weight:500;">{{ $photo['caption'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-5" style="padding:60px;background:var(--green-light);border-radius:16px;">
        <h3>{{ __('front.photos_share_title') }}</h3>
        <p style="color:var(--text-muted);margin-bottom:24px;">{!! __('front.photos_share_desc') !!}</p>
        <a href="https://instagram.com" target="_blank" rel="noopener" class="btn-black">
            <i class="bi bi-instagram me-2"></i>{{ __('front.photos_instagram') }}
        </a>
    </div>
</div>

<style>
    .photo-item:hover .photo-caption { opacity:1 !important; }
    @media(max-width:768px){ .photos-masonry { columns:2 !important; } }
    @media(max-width:480px){ .photos-masonry { columns:1 !important; } }
</style>
@endsection

