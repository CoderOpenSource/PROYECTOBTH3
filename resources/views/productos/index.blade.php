@extends('layouts.app')

@section('title', 'Productos Control de Ventas')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <!-- Alerta de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>¡Éxito!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Alerta de errores -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card data-tables">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Productos</h3>
                                    <p class="text-sm mb-0">Gestión de productos en el sistema.</p>
                                </div>
                                @if(session('rol') === 'administrador')
                                <div class="col-4 text-right">
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#crearProductoModal">Añadir producto</button>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-full-width table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Categoría</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($productos as $producto)
                                    <tr>
                                        <!-- Mostrar la imagen o un mensaje si no hay imagen -->
                                        <td>
                                            @if($producto->foto_url)
                                                <img src="{{ $producto->foto_url }}" alt="Imagen del producto" style="width: 100px; height: auto;">
                                            @else
                                                <span>No hay imagen disponible</span>
                                            @endif
                                        </td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->precio_compra }}</td>
                                        <td>{{ $producto->precio_venta }}</td>
                                        <td>{{ $producto->stock }}</td>
                                        <td>{{ $producto->categoria->nombre }}</td>
                                        <td class="d-flex justify-content-end">
                                            <!-- Ícono de editar -->
                                            @if(session('rol') === 'administrador')
                                            <a href="#" data-toggle="modal" data-target="#editarProductoModal{{ $producto->id }}" class="mr-3" style="font-size: 2rem;">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- Formulario de eliminar -->
                                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link" style="font-size: 1.5rem;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal para Editar Producto -->
                                    <div class="modal fade" id="editarProductoModal{{ $producto->id }}" tabindex="-1" role="dialog" aria-labelledby="editarProductoLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarProductoLabel">Editar Producto</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre" value="{{ $producto->nombre }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="descripcion">Descripción</label>
                                                            <textarea class="form-control" name="descripcion">{{ $producto->descripcion }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="precio_compra">Precio Compra</label>
                                                            <input type="number" step="0.01" class="form-control" name="precio_compra" value="{{ $producto->precio_compra }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="precio_venta">Precio Venta</label>
                                                            <input type="number" step="0.01" class="form-control" name="precio_venta" value="{{ $producto->precio_venta }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="stock">Stock</label>
                                                            <input type="number" class="form-control" name="stock" value="{{ $producto->stock }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="categoria_id">Categoría</label>
                                                            <select name="categoria_id" class="form-control" required>
                                                                @foreach($categorias as $categoria)
                                                                    <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="foto_url">Imagen Actual</label><br>
                                                            @if($producto->foto_url)
                                                                <img src="{{ $producto->foto_url }}" alt="Imagen del producto" style="width: 150px;"><br><br>
                                                            @endif
                                                            <label for="foto_url">Subir Nueva Imagen</label>
                                                            <input type="file" class="form-control" name="foto_url">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Crear Producto -->
        <div class="modal fade" id="crearProductoModal" tabindex="-1" role="dialog" aria-labelledby="crearProductoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearProductoLabel">Añadir Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" name="descripcion"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="precio_compra">Precio Compra</label>
                                <input type="number" step="0.01" class="form-control" name="precio_compra" required>
                            </div>
                            <div class="form-group">
                                <label for="precio_venta">Precio Venta</label>
                                <input type="number" step="0.01" class="form-control" name="precio_venta" required>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" name="stock" required>
                            </div>
                            <div class="form-group">
                                <label for="categoria_id">Categoría</label>
                                <select name="categoria_id" class="form-control" required>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="foto_url">Subir Imagen</label>
                                <input type="file" class="form-control" name="foto_url">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Añadir Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
