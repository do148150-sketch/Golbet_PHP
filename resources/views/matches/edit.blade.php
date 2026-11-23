@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Partidos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.index') }}">Partidos</a></li>
                <li class="breadcrumb-item active">Editar Partido</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">
                <h3>Editar Partido</h3>
                <small class="text-muted">Aquí también se actualizan las cuotas.</small>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('matches.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="match_id" value="{{ $match->id }}" />

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="home_team_id" class="form-select">
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            @selected(old('home_team_id', $match->home_team_id) == $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="">Equipo local</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="away_team_id" class="form-select">
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            @selected(old('away_team_id', $match->away_team_id) == $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="">Equipo visitante</label>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-floating">
                                <input type="date"
                                       name="match_date"
                                       class="form-control"
                                       value="{{ old('match_date', $match->match_date->format('Y-m-d')) }}" />
                                <label for="">Fecha</label>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-floating">
                                <input type="time"
                                       name="match_time"
                                       class="form-control"
                                       value="{{ old('match_time', $match->match_date->format('H:i')) }}" />
                                <label for="">Hora</label>
                            </div>
                        </div>

                        {{-- step="0.01" para que el navegador acepte decimales en las cuotas --}}
                        <div class="col-md-4 mt-2">
                            <div class="form-floating">
                                <input type="number"
                                       name="home_odds"
                                       class="form-control"
                                       step="0.01"
                                       min="1.01"
                                       value="{{ old('home_odds', number_format($match->home_odds, 2, '.', '')) }}" />
                                <label for="">Cuota local (1)</label>
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <div class="form-floating">
                                <input type="number"
                                       name="draw_odds"
                                       class="form-control"
                                       step="0.01"
                                       min="1.01"
                                       value="{{ old('draw_odds', number_format($match->draw_odds, 2, '.', '')) }}" />
                                <label for="">Cuota empate (X)</label>
                            </div>
                        </div>

                        <div class="col-md-4 mt-2">
                            <div class="form-floating">
                                <input type="number"
                                       name="away_odds"
                                       class="form-control"
                                       step="0.01"
                                       min="1.01"
                                       value="{{ old('away_odds', number_format($match->away_odds, 2, '.', '')) }}" />
                                <label for="">Cuota visitante (2)</label>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" href="{{ route('matches.index') }}">Volver</a>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </section>
@endsection
