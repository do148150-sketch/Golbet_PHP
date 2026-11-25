@extends('layouts.app')

@use('App\Helpers\RoleHelper')
@use('App\Models\GameMatch')
@use('App\Models\Bet')

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

                                {{-- Formulario para apostar: solo si el partido admite apuestas
                     y el usuario tiene permiso. El controlador lo vuelve a validar. --}}
                @if ($match->acceptsBets() && RoleHelper::can('bets.create'))

                    <hr />

                    <h5 class="text-center mb-3">Realizar apuesta</h5>

                    <form action="{{ route('bets.store') }}" method="POST" class="row g-3 justify-content-center">
                        @csrf

                        <input type="hidden" name="match_id" value="{{ $match->id }}" />

                        <div class="col-md-4">
                            <div class="form-floating">
                                <select name="pick" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <option value="{{ Bet::HOME }}" @selected(old('pick') == Bet::HOME)>
                                        1 · Gana {{ $match->homeTeam->name }} ({{ number_format($match->home_odds, 2) }})
                                    </option>
                                    <option value="{{ Bet::DRAW }}" @selected(old('pick') == Bet::DRAW)>
                                        X · Empate ({{ number_format($match->draw_odds, 2) }})
                                    </option>
                                    <option value="{{ Bet::AWAY }}" @selected(old('pick') == Bet::AWAY)>
                                        2 · Gana {{ $match->awayTeam->name }} ({{ number_format($match->away_odds, 2) }})
                                    </option>
                                </select>
                                <label for="">Pronóstico</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="amount" class="form-control" min="1" step="1"
                                       placeholder="Monto" value="{{ old('amount') }}" />
                                <label for="">Monto (FutCoins)</label>
                            </div>
                            <small class="text-muted">
                                Tu saldo: {{ number_format(Auth::user()->balance, 0, ',', '.') }} FutCoins
                            </small>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-cash-coin"></i> Apostar
                            </button>
                        </div>
                    </form>

                @endif

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
