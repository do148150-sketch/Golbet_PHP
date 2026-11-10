@extends('layouts.app')

@section('content')

    <div class="pagetitle">

        <h1>Acceso denegado</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Acceso denegado</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">

            <div class="card-body">

                <div class="text-center py-5">

                    <h1 class="display-1">🚫</h1>

                    <h3>403 · Acceso denegado</h3>

                    <p class="text-muted">
                        {{ $exception?->getMessage() ?: 'No tienes permiso para acceder a esta página.' }}
                    </p>

                    <a href="{{ route('home.index') }}" class="btn btn-primary">
                        Volver al inicio
                    </a>

                </div>

            </div>

        </div>
    </section>

@endsection
