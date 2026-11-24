@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Mi Perfil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Mi Perfil</a></li>
                <li class="breadcrumb-item active">Cambiar contraseña</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">
                <h3>Cambiar contraseña</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('profile.updatePassword') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="password"
                                       name="current_password"
                                       class="form-control"
                                       placeholder="Contraseña actual..." />
                                <label for="">Contraseña actual</label>
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
                            <small class="text-muted">Mínimo 8 caracteres.</small>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       placeholder="Confirme la nueva contraseña..." />
                                <label for="">Confirmar nueva contraseña</label>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button class="btn btn-primary" type="submit">Cambiar contraseña</button>
                            <a class="btn btn-secondary" href="{{ route('profile.show') }}">Volver</a>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </section>
@endsection
