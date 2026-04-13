@extends('layouts.app')

@section('title', __('front.account_title') . ' – .rosmarino')

@section('content')
<div style="padding:60px 40px;max-width:1000px;margin:0 auto;">
    <h1 style="margin-bottom:8px;">{{ __('front.account_heading') }}</h1>
    <p style="color:var(--text-muted);margin-bottom:40px;">{{ __('front.account_sub') }}</p>

    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-md-3">
            <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:24px;">
                <div style="text-align:center;margin-bottom:20px;">
                    <div style="width:64px;height:64px;background:var(--green-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="bi bi-person" style="font-size:28px;color:var(--green-dark);"></i>
                    </div>
                    <p style="font-weight:700;margin:0;">{{ auth()->user()->name }}</p>
                    <p style="font-size:12px;color:var(--text-muted);margin:4px 0 0;">{{ auth()->user()->email }}</p>
                </div>
                <nav style="display:flex;flex-direction:column;gap:4px;">
                    <a href="{{ route('account') }}" style="padding:10px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--black);background:var(--gray);text-decoration:none;">
                        <i class="bi bi-person me-2"></i>{{ __('front.account_nav_profile') }}
                    </a>
                    <a href="{{ route('account.orders') }}" style="padding:10px 14px;border-radius:8px;font-size:14px;color:var(--text-muted);text-decoration:none;">
                        <i class="bi bi-bag me-2"></i>{{ __('front.account_nav_orders') }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top:8px;">
                        @csrf
                        <button type="submit" style="width:100%;padding:10px 14px;border-radius:8px;font-size:14px;color:#e74c3c;background:none;border:none;text-align:left;cursor:pointer;font-weight:600;">
                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('front.account_sign_out') }}
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Profile Form --}}
        <div class="col-md-9">
            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:14px;color:#15803d;">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:32px;margin-bottom:20px;">
                <h4 style="font-weight:700;margin-bottom:24px;">{{ __('front.account_personal_info') }}</h4>
                <form action="{{ route('account') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.account_full_name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   style="border:1.5px solid #ddd;border-radius:8px;padding:12px 14px;font-size:15px;" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.account_email') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', auth()->user()->email) }}"
                                   style="border:1.5px solid #ddd;border-radius:8px;padding:12px 14px;font-size:15px;" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-black" style="padding:12px 28px;">{{ __('front.account_save') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div style="background:#fff;border:1.5px solid #eee;border-radius:16px;padding:32px;">
                <h4 style="font-weight:700;margin-bottom:24px;">{{ __('front.account_change_pw') }}</h4>
                <form action="{{ route('account') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.account_current_pw') }}</label>
                            <input type="password" name="current_password" class="form-control"
                                   style="border:1.5px solid #ddd;border-radius:8px;padding:12px 14px;font-size:15px;" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.account_new_pw') }}</label>
                            <input type="password" name="password" class="form-control"
                                   style="border:1.5px solid #ddd;border-radius:8px;padding:12px 14px;font-size:15px;" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.account_confirm_pw') }}</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   style="border:1.5px solid #ddd;border-radius:8px;padding:12px 14px;font-size:15px;" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-outline-green" style="padding:12px 28px;">{{ __('front.account_update_pw') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

