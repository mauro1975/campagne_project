<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $campaign->subject }}</title>
<style>
  body { margin:0; padding:0; background:#f4f4f0; font-family:'Helvetica Neue',Arial,sans-serif; color:#333; }
  a { color:#9bc3b1; }
  .wrapper { max-width:600px; margin:0 auto; padding:32px 16px; }
  .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,.06); }
  .header { background:#2d3a2e; padding:32px 36px; text-align:center; }
  .header .logo { font-size:28px; font-weight:700; color:#9bc3b1; letter-spacing:-0.5px; }
  .header .tagline { font-size:11px; color:rgba(255,255,255,0.4); margin-top:4px; letter-spacing:3px; text-transform:uppercase; }
  .body { padding:36px; }
  .greeting { font-size:18px; font-weight:600; margin-bottom:20px; color:#2d3a2e; }
  .content { font-size:14px; line-height:1.7; color:#555; }
  .divider { border:none; border-top:1px solid #f0f0f0; margin:28px 0; }
  .footer { padding:24px 36px; background:#f8f8f4; border-top:1px solid #eee; text-align:center; }
  .footer p { font-size:11px; color:#aaa; margin:4px 0; }
  .unsubscribe { font-size:11px; color:#ccc; }
  .unsubscribe a { color:#ccc; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    {{-- Header --}}
    <div class="header">
      <div class="logo">.rosmarino</div>
      <div class="tagline">Accessori per cani</div>
    </div>

    {{-- Body --}}
    <div class="body">
      <div class="greeting">
        Ciao{{ $recipientName ? ', ' . e($recipientName) : '' }}! 🐾
      </div>
      <div class="content">
        {!! $body !!}
      </div>
      <hr class="divider">
      <p style="text-align:center;font-size:13px;color:#888;">
        Visita il nostro negozio per scoprire tutte le novità.
      </p>
      <p style="text-align:center;margin-top:16px;">
        <a href="{{ url('/') }}" style="display:inline-block;padding:12px 32px;background:#9bc3b1;color:#fff;border-radius:99px;text-decoration:none;font-size:14px;font-weight:600;">
          Vai al negozio →
        </a>
      </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
      <p><strong style="color:#555;">.rosmarino</strong> · Accessori artigianali per cani</p>
      <p>Stai ricevendo questa email perché hai effettuato un acquisto sul nostro sito.</p>
      <p class="unsubscribe" style="margin-top:12px;">
        Se non desideri ricevere comunicazioni promozionali,
        <a href="mailto:{{ config('mail.from.address', 'info@rosmarino.it') }}?subject=Cancella iscrizione">clicca qui</a>.
      </p>
    </div>
  </div>
  <p style="text-align:center;font-size:11px;color:#c0c0c0;margin-top:20px;">
    © {{ date('Y') }} .rosmarino · Tutti i diritti riservati
  </p>
</div>
</body>
</html>
