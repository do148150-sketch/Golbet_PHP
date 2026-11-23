@extends('layouts.app')

@use('App\Helpers\RoleHelper')
@use('App\Models\GameMatch')

@section('content')
    <div class="pagetitle">

        <h1>Partidos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Partidos</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">

                <div class="row">
                    <div class="col-md-11">
                        <h3>Cartelera</h3>
                    </div>

                    @if (RoleHelper::can('matches.create'))
                        <div class="col-md-1">
                            <a href="{{ route('matches.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body mt-3">

                {{-- Los dos filtros viajan por GET en el mismo formulario, asi
                     que se combinan entre ellos y la URL queda compartible --}}
                <form action="{{ route('matches.index') }}" method="GET" class="row g-2 mb-4">

                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Todos los estados</option>
                            @foreach ($statuses as $option)
                                <option value="{{ $option }}" @selected($status === $option)>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="team_id" class="form-select">
                            <option value="">Todos los equipos</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" @selected((string) $teamId === (string) $team->id)>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-funnel-fill"></i> Filtrar
                        </button>
                        <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">
                            Limpiar
                        </a>
                    </div>

                </form>

                @if ($matches->isEmpty())

                    <div class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">No hay partidos que coincidan con el filtro.</p>
                    </div>

                @else

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Partido</th>
                                <th class="text-center">1</th>
                                <th class="text-center">X</th>
                                <th class="text-center">2</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($matches as $match)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ $match->match_date->format('d/m/Y') }}
                                        <small class="text-muted d-block">{{ $match->match_date->format('h:i a') }}</small>
                                    </td>

                                    <td>
                                        @if ($match->homeTeam->crest_url)
                                            <img src="{{ $match->homeTeam->crest_url }}" alt="{{ $match->homeTeam->name }}" width="22" height="22">
                                        @endif
                                        {{ $match->homeTeam->name }}

                                        @if ($match->status === GameMatch::FINISHED)
                                            <strong class="mx-2">{{ $match->home_goals }} - {{ $match->away_goals }}</strong>
                                        @else
                                            <span class="mx-2 text-muted">vs</span>
                                        @endif

                                        @if ($match->awayTeam->crest_url)
                                            <img src="{{ $match->awayTeam->crest_url }}" alt="{{ $match->awayTeam->name }}" width="22" height="22">
                                        @endif
                                        {{ $match->awayTeam->name }}
                                    </td>

                                    <td class="text-center">{{ number_format($match->home_odds, 2) }}</td>
                                    <td class="text-center">{{ number_format($match->draw_odds, 2) }}</td>
                                    <td class="text-center">{{ number_format($match->away_odds, 2) }}</td>

                                    <td>
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
                                    </td>

                                    <td class="text-nowrap">

                                        <a href="{{ route('matches.show', $match->id) }}" class="btn btn-info">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        @if (RoleHelper::can('matches.update') && $match->status !== GameMatch::FINISHED)
                                            <a href="{{ route('matches.edit', $match->id) }}" class="btn btn-warning">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                        @endif

                                        @if (RoleHelper::can('matches.delete'))
                                            <form action="{{ route('matches.delete', $match->id) }}" method="POST"
                                                style="display: contents;">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-danger btnDelete"><i class="bi bi-trash-fill"></i></button>

                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @endif

            </div>

        </div>
    </section>
@endsection

@push('scripts')

    <script>

        $(document).ready(function() {

            $('.btnDelete').click(function(event) {

                event.preventDefault();

                Swal.fire({

                    title: "¿Desea eliminar el partido?",
                    text: "No podrá revertirlo",
                    icon: "question",
                    showCancelButton: true,

                }).then((result) => {

                    if (result.isConfirmed) {

                        const form = $(this).closest('form');

                        form.submit();
                    };
                });
            });
        });

    </script>
@endpush
