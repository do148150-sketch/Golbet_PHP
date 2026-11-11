@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Usuarios</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                <li class="breadcrumb-item active">Editar Usuario</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">
                <h3>Editar Usuario</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('users.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="user_id" value="{{ $user->id }}" />

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Digite el nombre..."
                                       value="{{ old('name', $user->name) }}" />
                                <label for="">Nombre</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="Digite el correo..."
                                       value="{{ old('email', $user->email) }}" />
                                <label for="">Correo</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Nueva contraseña..." />
                                <label for="">Nueva contraseña</label>
                            </div>
                            <small class="text-muted">Dejar en blanco para no cambiarla.</small>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-floating">
                                <select name="role_id" class="form-select">
                                    <option value="">Seleccione un rol...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="">Rol</label>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" href="{{ route('users.index') }}">Volver</a>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </section>
@endsection
