<!-- Modal Asignación de Producto a Empleado -->
<div class="modal fade" id="modalProductoEmploye" tabindex="-1" role="dialog" aria-labelledby="modalProductoEmploye" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignación de Producto a Empleados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idProductSelect">

                <div class="table-responsive">
                    <table id="table-productEmploye" class="table table-striped table-hover va-middle tabla-productEmploye" width="100%">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <?= lang('boilerplate.global.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>

<script>
    /**
     * Inicializa DataTable con método POST
     */
    var tablaProductoEmploye = $('#table-productEmploye').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        order: [[0, 'asc']],
        ajax: {
            url: "<?= base_url('admin/productos/empleadosProducto') ?>",
            method: "POST",
            dataType: "json",
            data: function (d) {
                d.idProduct = $("#idProductSelect").val();
            }
        },
        columnDefs: [
            { orderable: false, targets: [1], searchable: false }
        ],
        columns: [
            { data: 'fullname' },
            {
                data: function (data) {
                    let clase = (data.status === "on") ? "btn-success" : "btn-danger";
                    let texto = (data.status === "on") ? "ON" : "OFF";

                    return `
                        <div class="text-center">
                            <button class="btn ${clase} btn-sm btnToggleProduct" 
                                data-status="${data.status}" 
                                data-id-employe="${data.id}"
                                data-id-product="${data.idProduct}">
                                ${texto}
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    /**
     * Abre el modal y carga empleados del producto seleccionado
     */
    $(".tableSaldos").on("click", ".btnAddEmploye", function () {
        
        
        var idProduct = $(this).attr("idProducts");
        console.log("idProduct",idProduct);
        
        $("#idProductSelect").val(idProduct);

        tablaProductoEmploye.ajax.reload();
        $("#modalProductoEmploye").modal("show");
    });

    /**
     * Cambia estado ON/OFF del producto por empleado
     */
    $(".tabla-productEmploye").on("click", ".btnToggleProduct", function () {
        var boton = $(this);
        var status = boton.data("status");
        var idEmploye = boton.data("id-employe");
        var idProduct = $("#idProductSelect").val();
        var nuevoStatus = (status === "on") ? "off" : "on";

        $.ajax({
            url: "<?= base_url('admin/productos/toggleEmployeProduct') ?>",
            method: "POST",
            data: {
                idProduct: idProduct,
                idEmploye: idEmploye,
                status: nuevoStatus
            },
            dataType: "json",
            success: function (resp) {
                if (resp.status === "ok") {
                    tablaProductoEmploye.ajax.reload(null, false);
                } else {
                    Toast.fire({ icon: 'error', title: resp.message || 'Error al actualizar' });
                }
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'Error de conexión con el servidor' });
            }
        });
    });
</script>

<?= $this->endSection() ?>
