@extends('layouts.app')

@section('title', 'About Us – .rosmarino')
@section('meta_description', 'Learn about .rosmarino – premium dog accessories made with love.')

@section('content')

{{-- Hero --}}
<section style="padding:80px 40px;background:var(--green-light);text-align:center;">
    <div style="max-width:700px;margin:0 auto;">
        <p style="font-size:12px;letter-spacing:4px;text-transform:uppercase;color:var(--green-dark);font-weight:600;">{{ __('front.about_hero_eyebrow') }}</p>
        <h1 style="font-size:clamp(36px,5vw,56px);margin-bottom:20px;">{!! __('front.about_hero_title') !!}</h1>
        <p style="font-size:17px;color:var(--text-muted);line-height:1.8;">
            {{ __('front.about_hero_text') }}
        </p>
    </div>
</section>

{{-- Values --}}
<section style="padding:80px 40px;max-width:1400px;margin:0 auto;">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=700&auto=format&fit=crop"
                 alt="Our dogs" style="width:100%;border-radius:16px;height:500px;object-fit:cover;">
        </div>
        <div class="col-lg-6">
            <p style="font-size:12px;letter-spacing:4px;text-transform:uppercase;color:var(--green-dark);font-weight:600;">{{ __('front.about_values_eyebrow') }}</p>
            <h2 style="font-size:36px;margin-bottom:24px;">{{ __('front.about_values_heading') }}</h2>
            <p style="color:var(--text-muted);line-height:1.8;margin-bottom:20px;">
                {{ __('front.about_values_p1') }}
            </p>
            <p style="color:var(--text-muted);line-height:1.8;margin-bottom:32px;">
                {{ __('front.about_values_p2') }}
            </p>
            <div class="row g-3">
                <div class="col-6">
                    <div style="padding:20px;background:var(--gray);border-radius:12px;text-align:center;">
                        <p style="font-size:32px;font-weight:700;color:var(--green-dark);margin-bottom:4px;">500+</p>
                        <p style="font-size:13px;color:var(--text-muted);margin:0;">{{ __('front.about_stat_customers') }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div style="padding:20px;background:var(--gray);border-radius:12px;text-align:center;">
                        <p style="font-size:32px;font-weight:700;color:var(--green-dark);margin-bottom:4px;">50+</p>
                        <p style="font-size:13px;color:var(--text-muted);margin:0;">{{ __('front.about_stat_products') }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div style="padding:20px;background:var(--gray);border-radius:12px;text-align:center;">
                        <p style="font-size:32px;font-weight:700;color:var(--green-dark);margin-bottom:4px;">5â˜…</p>
                        <p style="font-size:13px;color:var(--text-muted);margin:0;">{{ __('front.about_stat_rating') }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div style="padding:20px;background:var(--gray);border-radius:12px;text-align:center;">
                        <p style="font-size:32px;font-weight:700;color:var(--green-dark);margin-bottom:4px;">100%</p>
                        <p style="font-size:13px;color:var(--text-muted);margin:0;">{{ __('front.about_stat_approved') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission --}}
<section style="padding:80px 40px;background:var(--black);color:#fff;text-align:center;">
    <div style="max-width:700px;margin:0 auto;">
        <h2 style="font-size:36px;margin-bottom:20px;color:#fff;">{{ __('front.about_mission_heading') }}</h2>
        <p style="font-size:17px;line-height:1.8;color:rgba(255,255,255,0.7);margin-bottom:36px;">
            {{ __('front.about_mission_text') }}
        </p>
        <a href="{{ route('collection') }}" style="background:var(--green);color:#fff;" class="btn-black">{{ __('front.about_mission_btn') }}</a>
    </div>
</section>

@endsection

