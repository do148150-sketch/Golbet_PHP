<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('home.index') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- PARTE 2 (CRUD de Equipos): descomentar este bloque cuando exista routes/web/teams.php --}}
        {{--
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('teams.index') }}">
                <i class="bi bi-shield-fill"></i>
                <span>Equipos</span>
            </a>
        </li>
        --}}

    </ul>

</aside>
