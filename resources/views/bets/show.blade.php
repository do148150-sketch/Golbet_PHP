@extends('layouts.app')

@use('App\Models\Bet')

@section('content')
    <div class="pagetitle">

        <h1>Detalle de Apuesta</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bets.index') }}">Mis Apuestas</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h3 class="m-0">{{ $bet->match->homeTeam->name }} vs {{ $bet->match->awayTeam->name }}</h3>

                @switch ($bet->status)
                    @case (Bet::WON)
                        <span class="badge bg-success fs-6">{{ $bet->status }}</span>
                        @break
                    @case (Bet::LOST)
                        <span class="badge bg-danger fs-6">{{ $bet->status }}</span>
                        @break
                    @default
                        <span class="badge bg-warning text-dark fs-6">{{ $bet->status }}</span>
                @endswitch

            </div>

            <div class="card-body mt-3">

                <table class="table">
                    <tr>
                        <th>Partido</th>
                        <td>
                            <a href="{{ route('matches.show', $bet->match->id) }}">
                                {{ $bet->match->homeTeam->name }} vs {{ $bet->match->awayTeam->name }}
                            </a>
                            · {{ $bet->match->match_date->format('d/m/Y h:i a') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Pronóstico</th>
                        <td>{{ $bet->pick }}</td>
                    </tr>
                    <tr>
                        <th>Monto apostado</th>
                        <td>{{ number_format($bet->amount, 0, ',', '.') }} FutCoins</td>
                    </tr>
                    <tr>
                        <th>Cuota congelada</th>
                        <td>
                            {{ number_format($bet->odds_at_placement, 2) }}
                            <small class="text-muted">(la que había al momento de apostar)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ $bet->status === Bet::WON ? 'Pago recibido' : 'Pago potencial' }}</th>
                        <td class="fw-bold">{{ number_format($bet->potentialPayout(), 0, ',', '.') }} FutCoins</td>
                    </tr>
                    <tr>
                        <th>Fecha de la apuesta</th>
                        <td>{{ $bet->created_at->format('d/m/Y h:i a') }}</td>
                    </tr>
                </table>

                <div class="text-center">
                    <a class="btn btn-secondary" href="{{ route('bets.index') }}">Volver</a>
                </div>

            </div>

        </div>
    </section>
@endsection
