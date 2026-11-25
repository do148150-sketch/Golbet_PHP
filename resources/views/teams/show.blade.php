@extends('layouts.app')

@use('App\Models\GameMatch')

@section('content')
    <div class="pagetitle">

        <h1>{{ $team->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teams.index') }}">Equipos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center pt-4">
                        @if ($team->crest_url)
                            <img src="{{ $team->crest_url }}" alt="Escudo de {{ $team->name }}" width="120" height="120">
                        @endif
                        <h3 class="mt-3">{{ $team->name }}</h3>
                        <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $team->city }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">

                    <div class="card-header">
                        <h3 class="m-0">Próximos partidos</h3>
                    </div>

                    <div class="card-body mt-3">

                        @if ($matches->isEmpty())
                            <p class="text-muted text-center">Este equipo no tiene partidos programados.</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Partido</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($matches as $match)
                                        <tr>
                                            <td>{{ $match->match_date->format('d/m/Y h:i a') }}</td>
                                            <td>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</td>
                                            <td>
                                                @if ($match->status === GameMatch::IN_PROGRESS)
                                                    <span class="badge bg-warning text-dark">{{ $match->status }}</span>
                                                @else
                                                    <span class="badge bg-primary">{{ $match->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('matches.show', $match->id) }}" class="btn btn-primary">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <div class="text-center">
                            <a class="btn btn-secondary" href="{{ route('teams.index') }}">Volver</a>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
