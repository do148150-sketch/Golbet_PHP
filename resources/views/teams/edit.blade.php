@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Equipos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teams.index') }}">Equipos</a></li>
                <li class="breadcrumb-item active">Editar Equipo</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">
                <h3>Editar Equipo</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('teams.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="team_id" value="{{ $team->id }}" />

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Digite el nombre del equipo..."
                                       value="{{ $team->name }}" />
                                <label for="">Nombre</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="text"
                                       name="city"
                                       class="form-control"
                                       placeholder="Digite la ciudad..."
                                       value="{{ $team->city }}" />
                                <label for="">Ciudad</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-floating">
                                <input type="url"
                                       name="crest_url"
                                       class="form-control"
                                       placeholder="Digite la URL del escudo..."
                                       value="{{ $team->crest_url }}" />
                                <label for="">URL del escudo</label>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" href="{{ route('teams.index') }}">Volver</a>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </section>
@endsection
