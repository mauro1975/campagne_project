@extends('layouts.app')

@section('title', __('front.login_title') . ' – .rosmarino')

@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:60px 20px;">
    <div style="width:100%;max-width:440px;">
        <div style="text-align:center;margin-bottom:36px;">
            <h1 style="font-size:32px;margin-bottom:8px;">{{ __('front.login_heading') }}</h1>
            <p style="color:var(--text-muted);">{{ __('front.login_sub') }}</p>
        </div>

        <div style="background:#fff;border:1.5px solid #eee;border-radius:20px;padding:36px;">
            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:14px;color:#dc2626;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label" style="font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">{{ __('front.login_email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com"
                           style="border:1.5px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px;" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label" style="font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;">{{ __('front.login_password') }}</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••"
                           style="border:1.5px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px;" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label style="font-size:14px;display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="remember"> {{ __('front.login_remember') }}
                    </label>
                </div>
                <button type="submit" class="btn-black w-100" style="padding:14px;font-size:15px;">
                    {{ __('front.login_btn') }}
                </button>
            </form>
        </div>

        <p style="text-align:center;margin-top:20px;font-size:14px;color:var(--text-muted);">
            {{ __('front.login_no_account') }} <a href="{{ route('register') }}" style="color:var(--green-dark);font-weight:600;">{{ __('front.login_create') }}</a>
        </p>
    </div>
</div>
@endsection

