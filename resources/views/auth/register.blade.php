@extends('layouts.guest')

@section('content')

    <div class="card mb-3">

        <div class="card-body">

            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Crear cuenta</h5>
                <p class="text-center small">Empiezas con 100.000 FutCoins para apostar</p>
            </div>

            <form action="{{ route('registerPost') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-12">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           placeholder="Tu nombre"
                           autofocus />
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="correo@golbet.com" />
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Mínimo 8 caracteres" />
                </div>

                <div class="col-12">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-control"
                           placeholder="Repite la contraseña" />
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Crear cuenta</button>
                </div>

                <div class="col-12 text-center small">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                </div>

            </form>

        </div>

    </div>

@endsection
