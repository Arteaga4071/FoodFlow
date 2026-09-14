$(function () {
    // Tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) { new bootstrap.Tooltip(el); });

    // Eliminar producto del menú (SweetAlert) 
    $(document).on('click', '.btn-eliminar', function () {
        var url = $(this).data('url');
        Swal.fire({
            title: '¿Eliminar producto?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#E85D04'
        }).then(function (result) {
            if (result.isConfirmed) window.location = url;
        });
    });

    // Confirmar cierre / pago de pedido
    $(document).on('click', '.btn-confirmar-cierre', function () {
        var url = $(this).data('url');
        Swal.fire({
            title: 'Cerrar pedido',
            text: '¿Confirmas que el pedido fue pagado? La mesa quedará libre.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#E85D04'
        }).then(function (result) {
            if (result.isConfirmed) window.location = url;
        });
    });

    // Agregar producto al pedido (AJAX) 
    $(document).on('click', '.btn-agregar-item', function () {
        var $btn = $(this);
        var $card = $btn.closest('.menu-item-card');
        var cantidad = parseInt($card.find('.cantidad-input').val()) || 1;
        var menuItemId = $btn.data('id');
        var pedidoId = $('#pedido_id').val();

        $.post($('#url_agregar_item').val(), {
            pedido_id: pedidoId,
            menu_item_id: menuItemId,
            cantidad: cantidad,
            [$('#csrf_name').val()]: $('#csrf_hash').val()
        }, function (resp) {
            if (resp.ok) {
                actualizarResumen(resp);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto agregado',
                    showConfirmButton: false,
                    timer: 1200
                });
            } else {
                Swal.fire('Error', resp.msg || 'No se pudo agregar el producto.', 'error');
            }
        }, 'json');
    });

    // Mantiene el token CSRF sincronizado tras cada petición AJAX
    function actualizarCsrf(resp) {
        if (resp.csrf_hash) $('#csrf_hash').val(resp.csrf_hash);
    }

    // Quitar producto del pedido (AJAX) 
    $(document).on('click', '.btn-quitar-item', function () {
        var detalleId = $(this).data('detalle-id');
        var pedidoId = $('#pedido_id').val();

        $.post($('#url_quitar_item').val(), {
            detalle_id: detalleId,
            pedido_id: pedidoId,
            [$('#csrf_name').val()]: $('#csrf_hash').val()
        }, function (resp) {
            if (resp.ok) actualizarResumen(resp);
        }, 'json');
    });

    function actualizarResumen(resp) {
        actualizarCsrf(resp);
        $('#total-pedido').text('$' + resp.total);
        $('#btn-confirmar-pedido').prop('disabled', !resp.detalles || resp.detalles.length === 0);
        var $lista = $('#lista-detalles');
        $lista.empty();
        resp.detalles.forEach(function (d) {
            var subtotal = new Intl.NumberFormat('es-CO').format(d.subtotal);
            $lista.append(
                '<li class="list-group-item d-flex justify-content-between align-items-center" data-detalle-id="' + d.id + '">' +
                    '<span>' + d.cantidad + ' x ' + d.producto_nombre + '</span>' +
                    '<span>$' + subtotal +
                        ' <button class="btn btn-sm btn-link text-danger btn-quitar-item" data-detalle-id="' + d.id + '">' +
                        '<i class="bi bi-x-circle"></i></button></span>' +
                '</li>'
            );
        });
    }

    // Actualiza automáticamente el panel de cocina cada 20s (simula notificación de nuevos pedidos)
    if (window.location.pathname.indexOf('/cocina') !== -1) {
        setInterval(function () { window.location.reload(); }, 20000);
    }
});
