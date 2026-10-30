<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — Sajilo Bachat</title>
    <meta name="robots" content="noindex">
    {{-- Self-contained on purpose: error pages must render even when the database or build is unavailable. --}}
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:system-ui,"Noto Sans Devanagari",sans-serif;background:#effaf2;color:#14231a;padding:1.5rem}
        .box{max-width:30rem;text-align:center}.code{font-size:6rem;font-weight:800;color:#1f7a43;line-height:1}
        h1{font-size:1.5rem;margin:.75rem 0 .5rem}p{color:#475569;line-height:1.6;margin:0 0 1.5rem}
        a{display:inline-block;background:#1f7a43;color:#fff;text-decoration:none;font-weight:700;padding:.7rem 1.4rem;border-radius:.6rem}a:hover{background:#1a6138}
    </style>
</head>
<body>
    <div class="box">
        <div class="code">@yield('code')</div>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <a href="{{ url('/') }}">{{ __('site.errors.home') }}</a>
    </div>
</body>
</html>
