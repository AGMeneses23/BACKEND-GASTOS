@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

<form action="{{route('categoria.guardar')}}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{$categoria->id}}">
    <div class="form-group">
        <label for="nombre">Nombre de la Categoría:</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{$categoria->nombre}}" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>

</form>

@stop