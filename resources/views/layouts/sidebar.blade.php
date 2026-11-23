@use('App\Helpers\RoleHelper')

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('home.index') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Esconder lo que no se puede usar es cortesia con el usuario.
             La seguridad de verdad esta en el middleware de cada ruta. --}}
        @if (RoleHelper::can('matches.show'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('matches.index') }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Partidos</span>
                </a>
            </li>
        @endif

        @if (RoleHelper::can('teams.show'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('teams.index') }}">
                    <i class="bi bi-shield-fill"></i>
                    <span>Equipos</span>
                </a>
            </li>
        @endif

        {{-- SANTI: esta entrada apunta a 'bets.index' (Mis Apuestas).
             Queda comentada hasta que exista esa ruta; al crearla,
             basta con descomentarla. --}}
        {{--
        @if (RoleHelper::can('bets.show'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('bets.index') }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Mis Apuestas</span>
                </a>
            </li>
        @endif
        --}}

        @if (RoleHelper::can('users.show'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </li>
        @endif

        {{-- NICO: esta entrada apunta a 'profile.show' (Mi Perfil).
             Queda comentada hasta que exista esa ruta. --}}
        {{--
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('profile.show') }}">
                <i class="bi bi-person-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </li>
        --}}

    </ul>

</aside>
