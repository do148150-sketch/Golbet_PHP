@extends('layouts.app')

@use('App\Models\Bet')

@section('content')
    <div class="pagetitle">

        <h1>Mis Apuestas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Mis Apuestas</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="m-0">Mis Apuestas</h3>
                <span class="badge bg-success fs-6">
                    Saldo: {{ number_format(Auth::user()->balance, 0, ',', '.') }} FutCoins
                </span>
            </div>

            <div class="card-body mt-3">

                @if ($bets->isEmpty())
                    <p class="text-muted text-center">
                        Todavía no has hecho apuestas.
                        <a href="{{ route('matches.index') }}">Ir a la cartelera</a>
                    </p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Partido</th>
                                <th>Pronóstico</th>
                                <th>Monto</th>
                                <th>Cuota</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bets as $bet)
                                <tr>
                                    <td>{{ $bet->match->homeTeam->name }} vs {{ $bet->match->awayTeam->name }}</td>
                                    <td>{{ $bet->pick }}</td>
                                    <td>{{ number_format($bet->amount, 0, ',', '.') }}</td>
                                    <td>{{ number_format($bet->odds_at_placement, 2) }}</td>
                                    <td>
                                        @switch ($bet->status)
                                            @case (Bet::WON)
                                                <span class="badge bg-success">{{ $bet->status }}</span>
                                                @break
                                            @case (Bet::LOST)
                                                <span class="badge bg-danger">{{ $bet->status }}</span>
                                                @break
                                            @default
                                                <span class="badge bg-warning text-dark">{{ $bet->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $bet->created_at->format('d/m/Y h:i a') }}</td>
                                    <td>
                                        <a href="{{ route('bets.show', $bet->id) }}" class="btn btn-primary">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
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
