@extends('layouts.app')

@section('title', 'Payment – .rosmarino')

@section('head')
<style>
    .payment-wrap { max-width:800px; margin:0 auto; padding:60px 40px; }
    .payment-method-btn { border:2px solid #eee; border-radius:12px; padding:20px 24px; cursor:pointer; transition:all .2s; background:#fff; display:flex; align-items:center; gap:16px; width:100%; text-align:left; }
    .payment-method-btn.active { border-color:var(--green); background:var(--green-light); }
    .payment-method-btn:hover { border-color:var(--green-dark); }
    .payment-method-icon { font-size:28px; color:var(--green-dark); }
    .card-field-wrap { position:relative; }
    .card-field-wrap i { position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--text-muted); }
    .form-control { border:1.5px solid #ddd;border-radius:8px;padding:12px 14px;font-size:15px; }
    .form-control:focus { border-color:var(--green);box-shadow:0 0 0 3px rgba(154,215,160,0.2); }
</style>
@endsection

@section('content')
<div class="payment-wrap">
    <div style="font-size:12px;letter-spacing:3px;text-transform:uppercase;color:var(--green-dark);font-weight:600;margin-bottom:6px;">{{ __('front.payment_step') }}</div>
    <h1 style="font-size:28px;margin-bottom:8px;">{{ __('front.payment_title') }}</h1>
    <p style="color:var(--text-muted);margin-bottom:32px;">
        Order <strong>{{ $order->order_number }}</strong> &middot; Total: <strong>€{{ number_format($order->total_amount, 2) }}</strong>
    </p>

    {{-- Payment method selector --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:32px;" class="payment-method-grid">
        <button type="button" class="payment-method-btn active" id="btnCard" onclick="selectMethod('card')">
            <i class="bi bi-credit-card payment-method-icon"></i>
            <div>
                <p style="margin:0;font-weight:700;font-size:15px;">{{ __('front.payment_card_title') }}</p>
                <p style="margin:0;font-size:12px;color:var(--text-muted);">{{ __('front.payment_card_sub') }}</p>
            </div>
        </button>
        <button type="button" class="payment-method-btn" id="btnPaypal" onclick="selectMethod('paypal')">
            <i class="bi bi-paypal payment-method-icon"></i>
            <div>
                <p style="margin:0;font-weight:700;font-size:15px;">{{ __('front.payment_paypal_title') }}</p>
                <p style="margin:0;font-size:12px;color:var(--text-muted);">{{ __('front.payment_paypal_sub') }}</p>
            </div>
        </button>
    </div>

    {{-- Card Form --}}
    <div id="cardForm">
        <form action="{{ route('checkout.pay', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="card">

            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.payment_card_name') }}</label>
                <input type="text" name="card_name" class="form-control" placeholder="{{ __('front.payment_card_name_ph') }}" required>
            </div>
            <div class="mb-3 card-field-wrap">
                <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.payment_card_number') }}</label>
                <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456"
                       maxlength="19" oninput="formatCard(this)" required>
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.payment_expiry') }}</label>
                    <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY"
                           maxlength="5" oninput="formatExpiry(this)" required>
                </div>
                <div class="col-6">
                    <label class="form-label" style="font-size:13px;font-weight:600;">{{ __('front.payment_cvv') }}</label>
                    <input type="password" name="card_cvv" class="form-control" placeholder="•••" maxlength="4" required>
                </div>
            </div>

            <div style="background:var(--gray);border-radius:12px;padding:16px;margin-bottom:24px;font-size:13px;color:var(--text-muted);">
                <i class="bi bi-shield-lock me-2" style="color:var(--green-dark);"></i>
                {{ __('front.payment_security') }}
            </div>

            <button type="submit" class="btn-black w-100" style="padding:16px;font-size:16px;">
                <i class="bi bi-lock me-2"></i>{{ __('front.payment_pay_btn', ['amount' => number_format($order->total_amount, 2)]) }}
            </button>
        </form>
    </div>

    {{-- PayPal Form --}}
    <div id="paypalForm" style="display:none;">
        <form action="{{ route('checkout.pay', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="paypal">

            <div style="background:var(--gray);border-radius:12px;padding:32px;text-align:center;margin-bottom:24px;">
                <i class="bi bi-paypal" style="font-size:48px;color:#003087;margin-bottom:16px;display:block;"></i>
                <p style="color:var(--text-muted);margin-bottom:0;">
                    {!! __('front.payment_paypal_redirect', ['amount' => number_format($order->total_amount, 2)]) !!}
                </p>
            </div>

            <button type="submit" style="background:#003087;color:#fff;border:none;border-radius:30px;padding:16px;width:100%;font-size:16px;font-weight:700;cursor:pointer;">
                <i class="bi bi-paypal me-2"></i>{{ __('front.payment_pay_paypal') }}
            </button>
        </form>
    </div>

    <p style="text-align:center;margin-top:20px;font-size:13px;color:var(--text-muted);">
        <a href="{{ route('checkout') }}" style="color:var(--text-muted);">{{ __('front.payment_back') }}</a>
    </p>
</div>

@push('scripts')
<script>
function selectMethod(method){
    document.getElementById('cardForm').style.display = method==='card'?'block':'none';
    document.getElementById('paypalForm').style.display = method==='paypal'?'block':'none';
    document.getElementById('btnCard').classList.toggle('active', method==='card');
    document.getElementById('btnPaypal').classList.toggle('active', method==='paypal');
}
function formatCard(input){
    let val = input.value.replace(/\D/g,'').substring(0,16);
    input.value = val.replace(/(.{4})/g,'$1 ').trim();
}
function formatExpiry(input){
    let val = input.value.replace(/\D/g,'');
    if(val.length >= 2) val = val.substring(0,2)+'/'+val.substring(2,4);
    input.value = val;
}
</script>
@endpush

<style>
    @media(max-width:576px){ .payment-method-grid { grid-template-columns:1fr !important; } }
</style>
@endsection

