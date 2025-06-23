@php
    $isLoggedIn = auth()->check();
@endphp

<img 
    src="{{ asset('images/logo.png') }}" 
    alt="Logo"
    style="height: {{ $isLoggedIn ? '60px' : '100px' }};"
>
