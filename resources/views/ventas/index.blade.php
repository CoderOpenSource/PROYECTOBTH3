@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card data-tables">
                        <div class="card-header">
                            <h3 class="mb-0">Ventas</h3>
                                <div class="text-right">
                                    <!-- Botón para abrir el modal de creación de ventas -->
                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#crearVentaModal">
                                        Añadir Venta
                                    </button>
                                </div>
                        </div>
                        <div class="card-body table-full-width table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Productos</th>
                                    <th>Pago</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ventas as $venta)
                                    <tr>
                                        <td>{{ $venta->fecha }}</td>
                                        <td>{{ $venta->total }}</td>
                                        <td>
                                            <ul>
                                                @foreach($venta->detalles as $detalle)
                                                    <li>{{ $detalle->producto->nombre }} ({{ $detalle->cantidad }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <p>Método: {{ $venta->pago->metodo_pago }}</p>
                                            <p>Monto: {{ $venta->pago->monto }}</p>
                                            @if($venta->pago->imagen_pago)
                                                <img src="{{ $venta->pago->imagen_pago }}" alt="Imagen Pago" style="width: 100px;">
                                            @endif
                                        </td>
                                        <td>
                                            @if(session('rol') === 'administrador')
                                                <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editarVentaModal{{ $venta->id }}">Editar</a>
                                                <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal para editar venta -->
                                    @include('ventas.partials.editar_venta_modal', ['venta' => $venta, 'productos' => $productos, 'action' => route('ventas.update', $venta->id)])
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear venta -->
        @include('ventas.partials.crear_venta_modal', ['productos' => $productos, 'action' => route('ventas.store')])
    </div>
@endsection
