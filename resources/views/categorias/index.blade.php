@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Index</h1>
@stop

@section('content')

<table class="table table-bordered table-hover" id="categorias-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categorias as $cat)
        <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->nombre }}</td>
            <td>
                <a href="{{ route('categoria.editar', $cat->id) }}" class="btn btn-primary btn-sm">Editar</a>
                <a href="{{ route('categoria.eliminar', $cat->id)}}" type="submit" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop