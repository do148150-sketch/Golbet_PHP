@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Equipos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Equipos</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">

                <div class="row">
                    <div class="col-md-11">
                        <h3>Equipos</h3>
                    </div>

                    <div class="col-md-1">
                        <a href="{{ route('teams.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Escudo</th>
                            <th>Nombre</th>
                            <th>Ciudad</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td>
                                    @if ($team->crest_url)
                                        <img src="{{ $team->crest_url }}" alt="Escudo de {{ $team->name }}" width="40" height="40">
                                    @endif
                                </td>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->city }}</td>
                                <td>
                                    <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <form action="{{ route('teams.delete', $team->id) }}" method="POST"
                                        style="display: contents;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btnDelete"><i class="bi bi-trash-fill"></i></button>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

                    title: "¿Desea eliminar el equipo?",
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
