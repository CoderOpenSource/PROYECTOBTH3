<div class="modal fade" id="editarVentaModal{{ $venta->id }}" tabindex="-1" role="dialog" aria-labelledby="editarVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarVentaLabel">Editar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Campo oculto para enviar el total calculado -->
                    <input type="hidden" id="total-hidden-editar-{{ $venta->id }}" name="total" value="{{ $venta->total }}">

                    <!-- Sección de productos (detalle de venta) -->
                    <h5>Productos</h5>
                    <div class="form-group">
                        <label for="producto-select-editar-{{ $venta->id }}">Seleccionar Producto</label>
                        <select id="producto-select-editar-{{ $venta->id }}" class="form-control">
                            <option value="" selected disabled>Seleccione un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_venta }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contenedor de productos -->
                    <div id="productos-container-editar-{{ $venta->id }}">
                        @foreach($venta->detalles as $detalle)
                            <div class="form-group row product-row" data-precio="{{ $detalle->producto->precio_venta }}" data-producto-id="{{ $detalle->producto_id }}">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $detalle->producto->nombre }}" readonly>
                                    <input type="hidden" name="productos[{{ $loop->index }}][producto_id]" value="{{ $detalle->producto_id }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="productos[{{ $loop->index }}][cantidad]" class="form-control cantidad-input" value="{{ $detalle->cantidad }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" value="{{ $detalle->producto->precio_venta }}" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger remove-product">X</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success" id="add-product-editar-{{ $venta->id }}">Añadir Producto</button>

                    <!-- Mostrar el total dinámico -->
                    <h5>Total: <span id="total-venta-editar-{{ $venta->id }}">{{ $venta->total }}</span></h5>

                    <!-- Sección de pago -->
                    <h5>Pago</h5>
                    <div class="form-group">
                        <label for="metodo_pago-editar-{{ $venta->id }}">Método de Pago</label>
                        <select id="metodo_pago-editar-{{ $venta->id }}" name="metodo_pago" class="form-control" required>
                            <option value="EFECTIVO" {{ $venta->pago->metodo_pago === 'EFECTIVO' ? 'selected' : '' }}>EFECTIVO 💵</option>
                            <option value="QR" {{ $venta->pago->metodo_pago === 'QR' ? 'selected' : '' }}>TRANSFERENCIA QR 📱</option>
                        </select>
                    </div>

                    <!-- Campo de imagen de pago (solo visible si es QR) -->
                    <div class="form-group" id="imagen_pago_container-editar-{{ $venta->id }}" style="{{ $venta->pago->metodo_pago === 'QR' ? '' : 'display:none;' }}">
                        <label for="imagen_pago-editar-{{ $venta->id }}">Imagen de Pago (QR)</label>
                        <input type="file" class="form-control" name="imagen_pago" id="imagen_pago-editar-{{ $venta->id }}">
                        @if($venta->pago->imagen_pago)
                            <img src="{{ $venta->pago->imagen_pago }}" alt="Imagen Pago" style="width: 100px;">
                        @endif
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

<script>
    $(document).ready(function() {
        $('#editarVentaModal{{ $venta->id }}').on('shown.bs.modal', function () {
            console.log('Modal de edición abierto.');

            // Evento para añadir producto
            $('#add-product-editar-{{ $venta->id }}').on('click', function() {
                const productoSelect = $('#producto-select-editar-{{ $venta->id }}').find(':selected');
                if (!productoSelect.val()) {
                    alert("Seleccione un producto válido.");
                    return;
                }

                const productoId = productoSelect.val();
                const productoNombre = productoSelect.text();
                const productoPrecio = productoSelect.data('precio');
                const productosContainer = $('#productos-container-editar-{{ $venta->id }}');
                const index = productosContainer.children().length;

                // Añadir la fila del producto
                productosContainer.append(`
                    <div class="form-group row product-row" data-precio="${productoPrecio}" data-producto-id="${productoId}">
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="${productoNombre}" readonly>
                            <input type="hidden" name="productos[${index}][producto_id]" value="${productoId}">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="productos[${index}][cantidad]" class="form-control cantidad-input" value="1">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" value="${productoPrecio}" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-product">X</button>
                        </div>
                    </div>
                `);
                productoSelect.prop('disabled', true);
                calcularTotalEditar();
            });

            // Recalcular el total
            $('#productos-container-editar-{{ $venta->id }}').on('input', '.cantidad-input', function() {
                calcularTotalEditar();
            });

            $('#productos-container-editar-{{ $venta->id }}').on('click', '.remove-product', function() {
                $(this).closest('.product-row').remove();
                calcularTotalEditar();
            });

            // Mostrar campo imagen QR si se selecciona QR
            $('#metodo_pago-editar-{{ $venta->id }}').on('change', function() {
                if ($(this).val() === 'QR') {
                    $('#imagen_pago_container-editar-{{ $venta->id }}').show();
                } else {
                    $('#imagen_pago_container-editar-{{ $venta->id }}').hide();
                }
            });
        });

        // Función para calcular el total
        function calcularTotalEditar() {
            let total = 0;
            $('#productos-container-editar-{{ $venta->id }} .product-row').each(function() {
                const precio = parseFloat($(this).data('precio'));
                const cantidad = parseInt($(this).find('.cantidad-input').val());
                total += precio * cantidad;
            });
            $('#total-venta-editar-{{ $venta->id }}').text(total.toFixed(2));
            $('#total-hidden-editar-{{ $venta->id }}').val(total.toFixed(2));
        }
    });
</script>
