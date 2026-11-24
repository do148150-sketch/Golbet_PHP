@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Partidos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.index') }}">Partidos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.show', $match->id) }}">Detalle</a></li>
                <li class="breadcrumb-item active">Cerrar Partido</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">
                <h3>Registrar marcador final</h3>
                <span class="text-muted">
                    {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}
                    · {{ $match->match_date->format('d/m/Y · h:i a') }}
                </span>
            </div>

            <div class="card-body mt-3">

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Al guardar, el partido queda <b>Finalizado</b> y se resuelven sus
                    <b>{{ $pendingBets }}</b> {{ $pendingBets === 1 ? 'apuesta pendiente' : 'apuestas pendientes' }}.
                    Esta acción no se puede deshacer.
                </div>

                <form action="{{ route('matches.close') }}" method="POST" id="closeForm">
                    @csrf

                    <input type="hidden" name="match_id" value="{{ $match->id }}" />

                    <div class="row justify-content-center align-items-center text-center">

                        <div class="col-md-4">
                            <label for="home_goals" class="form-label fw-bold">{{ $match->homeTeam->name }}</label>
                            <input type="number"
                                   name="home_goals"
                                   id="home_goals"
                                   class="form-control form-control-lg text-center"
                                   min="0" max="99"
                                   value="{{ old('home_goals') }}"
                                   placeholder="0" />
                        </div>

                        <div class="col-md-1">
                            <h2 class="text-muted mt-4">-</h2>
                        </div>

                        <div class="col-md-4">
                            <label for="away_goals" class="form-label fw-bold">{{ $match->awayTeam->name }}</label>
                            <input type="number"
                                   name="away_goals"
                                   id="away_goals"
                                   class="form-control form-control-lg text-center"
                                   min="0" max="99"
                                   value="{{ old('away_goals') }}"
                                   placeholder="0" />
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-flag-fill"></i> Cerrar partido y resolver apuestas
                        </button>
                        <a class="btn btn-secondary" href="{{ route('matches.show', $match->id) }}">Volver</a>
                    </div>

                </form>

            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Confirmacion antes de enviar: cerrar el partido no se puede deshacer
        document.getElementById('closeForm').addEventListener('submit', function (event) {

            event.preventDefault();

            const form = this;
            const home = document.getElementById('home_goals').value || '?';
            const away = document.getElementById('away_goals').value || '?';

            Swal.fire({
                title: '¿Cerrar el partido ' + home + ' - ' + away + '?',
                text: 'Se resolverán todas las apuestas pendientes. No se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar',
            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
