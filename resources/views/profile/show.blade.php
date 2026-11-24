@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Mi Perfil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Mi Perfil</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body pt-4 d-flex flex-column align-items-center text-center">

                        <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                        <h2 class="mt-2">{{ $user->name }}</h2>
                        <h3 class="text-muted">{{ $role }}</h3>

                        <div class="mt-3">
                            <span class="badge bg-success fs-6">
                                {{ number_format($user->balance, 0, ',', '.') }} FutCoins
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">

                    <div class="card-header">
                        <h3>Mis datos</h3>
                    </div>

                    <div class="card-body mt-3">

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               placeholder="Digite su nombre..."
                                               value="{{ old('name', $user->name) }}" />
                                        <label for="">Nombre</label>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <div class="form-floating">
                                        <input type="email"
                                               name="email"
                                               class="form-control"
                                               placeholder="Digite su correo..."
                                               value="{{ old('email', $user->email) }}" />
                                        <label for="">Correo</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" value="{{ $role }}" disabled />
                                        <label for="">Rol</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="form-floating">
                                        <input type="text"
                                               class="form-control"
                                               value="{{ number_format($user->balance, 0, ',', '.') }} FutCoins"
                                               disabled />
                                        <label for="">Saldo</label>
                                    </div>
                                </div>

                                <small class="text-muted mt-1">El rol y el saldo no se pueden cambiar desde aquí.</small>

                                <div class="text-center mt-3">
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                    <a class="btn btn-outline-secondary" href="{{ route('profile.password') }}">
                                        <i class="bi bi-key-fill"></i> Cambiar contraseña
                                    </a>
                                </div>

                            </div>
                        </form>

                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
