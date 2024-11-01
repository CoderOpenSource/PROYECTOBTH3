@extends('layouts.app')

@section('title', 'Dashboard Control de Ventas')
@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg rounded">
                    <div class="card-header text-center">
                        <h1 class="display-4 text-dark">Proyecto de Control de Ventas</h1>
                        <p class="lead text-dark">Producto del Bachiller Técnico Humanístico representado al Colegio Juancito Pinto</p>
                    </div>
                    <div class="card-body p-0 d-flex justify-content-center">
                        <img src="{{ asset('assets/img/imagen4.jpeg') }}" class="img-fluid rounded-bottom" alt="Imagen de fondo" style="width: 100%; height: 400px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
