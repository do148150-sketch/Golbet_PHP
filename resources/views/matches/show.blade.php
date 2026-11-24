@extends('layouts.app')

@use('App\Helpers\RoleHelper')
@use('App\Models\GameMatch')

@section('content')
    <div class="pagetitle">

        <h1>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.index') }}">Partidos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <span>
                    <i class="bi bi-calendar-event"></i>
                    {{ $match->match_date->format('d/m/Y · h:i a') }}
                </span>

                @switch ($match->status)
                    @case (GameMatch::SCHEDULED)
                        <span class="badge bg-primary">{{ $match->status }}</span>
                        @break
                    @case (GameMatch::IN_PROGRESS)
                        <span class="badge bg-warning text-dark">{{ $match->status }}</span>
                        @break
                    @default
                        <span class="badge bg-secondary">{{ $match->status }}</span>
                @endswitch

            </div>

            <div class="card-body mt-3">

                <div class="row text-center align-items-center">

                    <div class="col-md-4">
                        @if ($match->homeTeam->crest_url)
                            <img src="{{ $match->homeTeam->crest_url }}"
                                 alt="Escudo de {{ $match->homeTeam->name }}" width="90" height="90">
                        @endif
                        <h4 class="mt-2">{{ $match->homeTeam->name }}</h4>
                        <small class="text-muted">{{ $match->homeTeam->city }}</small>
                    </div>

                    <div class="col-md-4">
                        @if ($match->status === GameMatch::FINISHED)
                            <h1 class="display-4">{{ $match->home_goals }} - {{ $match->away_goals }}</h1>
                        @else
                            <h2 class="text-muted">vs</h2>
                        @endif
                    </div>

                    <div class="col-md-4">
                        @if ($match->awayTeam->crest_url)
                            <img src="{{ $match->awayTeam->crest_url }}"
                                 alt="Escudo de {{ $match->awayTeam->name }}" width="90" height="90">
                        @endif
                        <h4 class="mt-2">{{ $match->awayTeam->name }}</h4>
                        <small class="text-muted">{{ $match->awayTeam->city }}</small>
                    </div>

                </div>

                <hr />

                <h5 class="text-center mb-3">Cuotas</h5>

                <div class="row g-3 justify-content-center text-center">

                    <div class="col-md-3">
                        <div class="border rounded py-3">
                            <div class="text-muted">1 · {{ $match->homeTeam->name }}</div>
                            <div class="fs-3 fw-bold">{{ number_format($match->home_odds, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded py-3">
                            <div class="text-muted">X · Empate</div>
                            <div class="fs-3 fw-bold">{{ number_format($match->draw_odds, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded py-3">
                            <div class="text-muted">2 · {{ $match->awayTeam->name }}</div>
                            <div class="fs-3 fw-bold">{{ number_format($match->away_odds, 2) }}</div>
                        </div>
                    </div>

                </div>

                {{-- ===================================================
                     SANTI: aquí va el formulario para realizar la apuesta.
                     Condición: $match->acceptsBets() && RoleHelper::can('bets.create')
                     El método acceptsBets() del modelo ya comprueba que el
                     partido esté Programado y que la fecha no haya pasado.
                     =================================================== --}}

            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">

                <small class="text-muted">
                    <i class="bi bi-ticket-perforated"></i>
                    {{ $totalBets }} {{ $totalBets === 1 ? 'apuesta realizada' : 'apuestas realizadas' }}
                </small>

                <div>
                    @if (RoleHelper::can('matches.update') && $match->status !== GameMatch::FINISHED)
                        <a href="{{ route('matches.edit', $match->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                    @endif

                    {{-- Registrar el marcador y cerrar el partido son una sola
                         operación: al guardar el marcador se resuelven las apuestas. --}}
                    @if (RoleHelper::can('matches.close') && $match->status !== GameMatch::FINISHED)
                        <a href="{{ route('matches.closeForm', $match->id) }}" class="btn btn-danger">
                            <i class="bi bi-flag-fill"></i> Registrar marcador y cerrar
                        </a>
                    @endif

                    <a href="{{ route('matches.index') }}" class="btn btn-secondary">Volver</a>
                </div>

            </div>

        </div>
    </section>
@endsection
