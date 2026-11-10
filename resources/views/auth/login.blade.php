@extends('layouts.guest')

@section('content')

    <div class="card mb-3">

        <div class="card-body">

            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Iniciar sesión</h5>
                <p class="text-center small">Ingresa tus credenciales para continuar</p>
            </div>

            <form action="{{ route('loginPost') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-12">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="correo@golbet.com"
                           autofocus />
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Digite su contraseña" />
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" />
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Ingresar</button>
                </div>

            </form>

        </div>

    </div>

@endsection
