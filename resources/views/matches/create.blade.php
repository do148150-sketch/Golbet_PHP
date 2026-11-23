@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Partidos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('matches.index') }}">Partidos</a></li>
                <li class="breadcrumb-item active">Crear Partido</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">
                <h3>Crear Partido</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('matches.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="home_team_id" class="form-select">
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" @selected(old('home_team_id') == $team->id)>
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
                                        <option value="{{ $team->id }}" @selected(old('away_team_id') == $team->id)>
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
                                       value="{{ old('match_date', now()->addDay()->format('Y-m-d')) }}" />
                                <label for="">Fecha</label>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-floating">
                                <input type="time"
                                       name="match_time"
                                       class="form-control"
                                       value="{{ old('match_time', '19:00') }}" />
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
                                       placeholder="2.10"
                                       value="{{ old('home_odds', '2.00') }}" />
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
                                       placeholder="3.20"
                                       value="{{ old('draw_odds', '3.00') }}" />
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
                                       placeholder="2.80"
                                       value="{{ old('away_odds', '2.50') }}" />
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
