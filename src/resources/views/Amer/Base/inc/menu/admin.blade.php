@php
    $currentUrl = \Illuminate\Support\Facades\URL::current();
    $current_url = urlencode($currentUrl);
@endphp

<li class="nav-item">
    <a onclick="popitup('{{ route('qrcode', ['element' => $current_url]) }}','qrcode');" 
       class="white-text nav-link">
        aaaaaa
    </a>
</li>
