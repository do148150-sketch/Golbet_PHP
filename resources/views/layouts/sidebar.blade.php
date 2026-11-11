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
        @if (RoleHelper::can('teams.show'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('teams.index') }}">
                    <i class="bi bi-shield-fill"></i>
                    <span>Equipos</span>
                </a>
            </li>
        @endif

        @if (RoleHelper::can('users.show'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </li>
        @endif

    </ul>

</aside>
