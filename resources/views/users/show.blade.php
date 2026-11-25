@extends('layouts.app')

@use('App\Helpers\RoleHelper')

@section('content')
    <div class="pagetitle">

        <h1>Usuarios</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="m-0">{{ $user->name }}</h3>

                @if ($user->is_active)
                    <span class="badge bg-success fs-6">Activo</span>
                @else
                    <span class="badge bg-secondary fs-6">Inactivo</span>
                @endif
            </div>

            <div class="card-body mt-3">

                <table class="table">
                    <tr>
                        <th>Nombre</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Rol</th>
                        <td>{{ $user->role?->name ?? 'Sin rol' }}</td>
                    </tr>
                    <tr>
                        <th>Saldo</th>
                        <td>{{ number_format($user->balance, 0, ',', '.') }} FutCoins</td>
                    </tr>
                    <tr>
                        <th>Apuestas realizadas</th>
                        <td>{{ $totalBets }}</td>
                    </tr>
                </table>

                <div class="text-center">

                    @if (RoleHelper::can('users.update'))
                        <a class="btn btn-warning" href="{{ route('users.edit', $user->id) }}">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                    @endif

                    {{-- Desactivar: solo si sigue activo y no es el usuario de la sesion --}}
                    @if (RoleHelper::can('users.delete') && $user->is_active && $user->id !== Auth::id())
                        <form action="{{ route('users.deactivate', $user->id) }}" method="POST" style="display: contents;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btnDeactivate">
                                <i class="bi bi-person-x-fill"></i> Desactivar
                            </button>
                        </form>
                    @endif

                    <a class="btn btn-secondary" href="{{ route('users.index') }}">Volver</a>
                </div>

            </div>

        </div>
    </section>
@endsection

@push('scripts')

    <script>

        $(document).ready(function() {

            $('.btnDeactivate').click(function(event) {

                event.preventDefault();

                Swal.fire({

                    title: "¿Desea desactivar este usuario?",
                    text: "Dejará de aparecer en el listado de usuarios",
                    icon: "question",
                    showCancelButton: true,

                }).then((result) => {

                    if (result.isConfirmed) {

                        const form = $(this).closest('form');

                        form.submit();
                    };
                });
            });
        });

    </script>
@endpush
