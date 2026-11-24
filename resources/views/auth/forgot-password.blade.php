@extends('layouts.guest')

@section('content')

    <div class="card mb-3">

        <div class="card-body">

            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Recuperar contraseña</h5>
                <p class="text-center small">Escribe tu correo y te enviaremos un enlace para crear una nueva</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="row g-3">
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
                    <button class="btn btn-primary w-100" type="submit">Enviar enlace</button>
                </div>

                <div class="col-12 text-center small">
                    <a href="{{ route('login') }}">Volver a iniciar sesión</a>
                </div>

            </form>

        </div>

    </div>

@endsection
