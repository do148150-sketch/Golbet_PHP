@extends('layouts.app')

@section('content')
    <div class="pagetitle">

        <h1>Usuarios</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">

            <div class="card-header">

                <div class="row">
                    <div class="col-md-11">
                        <h3>Usuarios</h3>
                    </div>

                    <div class="col-md-1">
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role_name ?? 'Sin rol' }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>
@endsection
