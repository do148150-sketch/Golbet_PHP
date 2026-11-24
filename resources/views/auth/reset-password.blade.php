@extends('layouts.guest')

@section('content')

    <div class="card mb-3">

        <div class="card-body">

            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Nueva contraseña</h5>
                <p class="text-center small">Escribe la contraseña nueva para tu cuenta</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="row g-3">
                @csrf

                {{-- El token llega en la URL del enlace y viaja oculto en el formulario --}}
                <input type="hidden" name="token" value="{{ $token }}" />

                <div class="col-12">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control"
                           value="{{ old('email', $email) }}"
                           placeholder="correo@golbet.com" />
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Nueva contraseña</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Mínimo 8 caracteres"
                           autofocus />
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
                    <button class="btn btn-primary w-100" type="submit">Guardar contraseña</button>
                </div>

            </form>

        </div>

    </div>

@endsection
