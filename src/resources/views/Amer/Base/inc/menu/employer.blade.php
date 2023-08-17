<li class="nav-item">
        <a onclick="popitup('{{route('qrcode',$current_url) ?? 'qrcode'}}','qrcode');" class="white-text nav-link">
            <span class="fa fa-qrcode"></span>
        </a>
</li>
<li class="nav-item">
        <a onclick="popitup('{{url('rss')}}','rss');" class="white-text nav-link">
            <span class="fa fa-rss"></span>
        </a>
    </li>
    <li class="nav-item">
        <a onclick="window.print();" class="white-text nav-link">
            <span class="fa fa-print"></span>
        </a>
    </li>
<li class="nav-item">
    <a class="nav-link" role="button" aria-expanded="true">
    <i class="fa fa-toggle-on" id="bd-theme" aria-hidden="true" data-bs-theme-value="light"></i>
    <i class="fa fa-toggle-off" id="bd-theme" aria-hidden="true" data-bs-theme-value="dark"></i>
    </a>
</li>