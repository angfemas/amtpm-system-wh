@php
    $faviconLogo = \App\Models\AppLogo::getActiveLogo('favicon');
@endphp

@if($faviconLogo && $faviconLogo->image_path)
    <link rel="icon" href="{{ $faviconLogo->image_url }}" type="image/png">
    <link rel="shortcut icon" href="{{ $faviconLogo->image_url }}">
@else
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
@endif
