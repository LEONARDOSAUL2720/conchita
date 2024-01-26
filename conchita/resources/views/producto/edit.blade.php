@extends('layouts.app')


@section('content')


    <div class="container">
        <div class="row">
            <h2>Crear un nuevo Producto</h2>
            <hr>
            <form action="/productos/{{$producto->id}}" method="post" enctype="multipart/form-data" class="col-lg-7">
                <!-- Protección contra ataques ya implementado en laravel  https://www.welivesecurity.com/la-es/2015/04/21/vulnerabilidad-cross-site-request-forgery-csrf/-->
                @csrf
                @method('PUT')


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $producto->title }}" />
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" id="description" name="description">{{ $producto->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="image">Imagen</label>
                    <input type="file" class="form-control" id="image" name="image" value="{{$producto->image}}" />
                </div>
               <a href="/productos" type="submit" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-success">Actualizar Producto</button>
            </form>
        </div>
    </div>


@endsection
