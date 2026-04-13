@extends('layouts.app')

@section('title', __('front.register_title') . ' – .rosmarino')

@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:60px 20px;">
    <div style="width:100%;max-width:440px;">
        <div style="text-align:center;margin-bottom:36px;">
            <h1 style="font-size:32px;margin-bottom:8px;">{{ __('front.register_heading') }}</h1>
            <p style="color:var(--text-muted);">{{ __('front.register_sub') }}</p>
        </div>

        <div style="background:#fff;border:1.5px solid #eee;border-radius:20px;padding:36px;">
            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:14px;color:#dc2626;">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label" style="font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">{{ __('front.register_full_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="{{ __('front.register_full_name_ph') }}"
                           style="border:1.5px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px;" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">{{ __('front.register_email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com"
                           style="border:1.5px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px;" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">{{ __('front.register_password') }}</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ __('front.register_password_ph') }}"
                           style="border:1.5px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px;" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" style="font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">{{ __('front.register_confirm_pw') }}</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="{{ __('front.register_confirm_pw_ph') }}"
                           style="border:1.5px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px;" required>
                </div>
                <div class="mb-4">
                    <label style="font-size:13px;display:flex;gap:8px;align-items:flex-start;cursor:pointer;">
                        <input type="checkbox" name="agree_terms" required style="margin-top:3px;flex-shrink:0;">
                        <span>{!! __('front.register_terms', [
                            'terms'   => '<a href="#" style="color:var(--green-dark);">' . __('front.register_terms_link') . '</a>',
                            'privacy' => '<a href="#" style="color:var(--green-dark);">' . __('front.register_privacy_link') . '</a>',
                        ]) !!}</span>
                    </label>
                </div>
                <button type="submit" class="btn-black w-100" style="padding:14px;font-size:15px;">
                    {{ __('front.register_btn') }}
                </button>
            </form>
        </div>

        <p style="text-align:center;margin-top:20px;font-size:14px;color:var(--text-muted);">
            {{ __('front.register_have_account') }} <a href="{{ route('login') }}" style="color:var(--green-dark);font-weight:600;">{{ __('front.register_sign_in') }}</a>
        </p>
    </div>
</div>
@endsection

