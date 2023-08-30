<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('kmls.index') }}" class="nav-link {{ Request::is('kmls*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>K M L S</p>
    </a>
</li>
