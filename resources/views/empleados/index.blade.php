@extends('layouts.app')

@section('title', 'Empleados Control de Ventas')
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
                                    <h3 class="mb-0">Empleados</h3>
                                    <p class="text-sm mb-0">Gestión de empleados en el sistema.</p>
                                </div>
                                <div class="col-4 text-right">
                                    <button class="btn btn-sm btn-danger btn-default" data-toggle="modal" data-target="#crearEmpleadoModal">Añadir empleado</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-full-width table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr><th>Nombre</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr></thead>
                                <tbody>
                                @foreach($empleados as $empleado)
                                    <tr>
                                        <td>{{ $empleado->nombre }}</td>
                                        <td>{{ $empleado->email }}</td>
                                        <td class="d-flex justify-content-end">
                                            <!-- Ícono de editar -->
                                            <a href="#" data-toggle="modal" data-target="#editarEmpleadoModal{{ $empleado->id }}" class="mr-3" style="font-size: 2rem;">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- Formulario de eliminar -->
                                            <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link" style="font-size: 1.5rem;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal para Editar Empleado -->
                                    <div class="modal fade" id="editarEmpleadoModal{{ $empleado->id }}" tabindex="-1" role="dialog" aria-labelledby="editarEmpleadoLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarEmpleadoLabel">Editar Empleado</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre" value="{{ $empleado->nombre }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" class="form-control" name="email" value="{{ $empleado->email }}" required>
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

        <!-- Modal para Crear Empleado -->
        <div class="modal fade" id="crearEmpleadoModal" tabindex="-1" role="dialog" aria-labelledby="crearEmpleadoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearEmpleadoLabel">Añadir Empleado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="crearEmpleadoForm" action="{{ route('empleados.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="passwordField" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Añadir Empleado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script para mostrar el valor de la contraseña en la consola -->
        <script>
            document.getElementById('crearEmpleadoForm').addEventListener('submit', function(event) {
                // Evitar el envío del formulario para poder ver el console.log
                event.preventDefault();

                // Obtener el valor del campo contraseña
                var password = document.getElementById('passwordField').value;

                // Mostrar el valor de la contraseña en la consola
                console.log('Contraseña ingresada: ' + password);

                // Luego de mostrar el console.log, puedes proceder con el envío
                this.submit();
            });
        </script>

    </div>
@endsection
