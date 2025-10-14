<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>
<?= $this->section('content') ?>
<?= $this->include('modulesDepartaments/modalCaptureDepartaments') ?>
<div class="card card-default">
    <div class="card-header">
        <div class="float-right">
            <div class="btn-group">
                <button class="btn btn-primary btnAddDepartaments" data-toggle="modal" data-target="#modalAddDepartaments">
                    <i class="fa fa-plus"></i> <?= lang('departaments.add') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableDepartaments" class="table table-striped table-hover va-middle tableDepartaments">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('departaments.fields.idempresa') ?></th>
                                <th><?= lang('departaments.fields.idsucursal') ?></th>
                                <th><?= lang('departaments.fields.description') ?></th>
                                <th><?= lang('departaments.fields.areamanager') ?></th>
                                <th><?= lang('departaments.fields.created_at') ?></th>
                                <th><?= lang('departaments.fields.updated_at') ?></th>
                                <th><?= lang('departaments.fields.deleted_at') ?></th>

                                <th><?= lang('departaments.fields.actions') ?></th>
                            </tr>
                        </thead>
                        <!--<tbody></tbody>-->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
    var tableDepartaments = $('#tableDepartaments').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= base_url('admin/departaments') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [8],
                searchable: false,
                targets: [8]
            }],
        columns: [{'data': 'id'},
            {'data': 'nombreEmpresa'},
            {'data': 'nameSucursal'},
            {'data': 'description'},
            {'data': 'username'},
            {'data': 'created_at'},
            {'data': 'updated_at'},
            {'data': 'deleted_at'},

            {
                "data": function (data) {
                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             <button class="btn btn-warning btnEditDepartaments" data-toggle="modal" idDepartaments="${data.id}" data-target="#modalAddDepartaments">  <i class=" fa fa-edit"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });

    $(document).on('click', '#btnSaveDepartaments', function (e) {
        var idDepartaments = $("#idDepartaments").val();
        var idempresa = $("#idempresa").val();
        var idsucursal = $("#idsucursal").val();
        var description = $("#description").val();
        var areamanager = $("#areamanager").val();

        $("#btnSaveDepartaments").attr("disabled", true);
        var datos = new FormData();
        datos.append("idDepartaments", idDepartaments);
        datos.append("idempresa", idempresa);
        datos.append("idsucursal", idsucursal);
        datos.append("description", description);
        datos.append("areamanager", areamanager);

        $.ajax({
            url: "<?= base_url('admin/departaments/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                if (respuesta?.message?.includes("Guardado") || respuesta?.message?.includes("Actualizado")) {
                    Toast.fire({
                        icon: 'success',
                        title: respuesta.message
                    });
                    tableDepartaments.ajax.reload();
                    $("#btnSaveDepartaments").removeAttr("disabled");
                    $('#modalAddDepartaments').modal('hide');
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: respuesta.message || "Error desconocido"
                    });
                    $("#btnSaveDepartaments").removeAttr("disabled");
                }
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: jqXHR.responseText
            });
            $("#btnSaveDepartaments").removeAttr("disabled");
        });
    });

    $(".tableDepartaments").on("click", ".btnEditDepartaments", function () {
        var idDepartaments = $(this).attr("idDepartaments");
        var datos = new FormData();
        datos.append("idDepartaments", idDepartaments);
        $.ajax({
            url: "<?= base_url('admin/departaments/getDepartaments') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idDepartaments").val(respuesta["id"]);
                $("#idempresa").val(respuesta["idempresa"]).trigger("change");

                $("#idsucursal").val(respuesta["idsucursal"]);

                var newOptionBranchOffice = new Option(respuesta["nombreSucursal"], respuesta["idsucursal"], true, true);
                $('#idsucursal').append(newOptionBranchOffice).trigger('change');
                $("#idsucursal").val(respuesta["idsucursal"]);


                $("#areamanager").val(respuesta["idsucursal"]);

                var newOptionAreaManager = new Option(respuesta["nombreUsuario"], respuesta["areamanager"], true, true);
                $('#areamanager').append(newOptionAreaManager).trigger('change');
                $("#areamanager").val(respuesta["areamanager"]);


                $("#description").val(respuesta["description"]);

                $("#areamanager").val(respuesta["areamanager"]);

            }
        });
    });

    $(".tableDepartaments").on("click", ".btn-delete", function () {
        var idDepartaments = $(this).attr("data-id");
        Swal.fire({
            title: '<?= lang('boilerplate.global.sweet.title') ?>',
            text: "<?= lang('boilerplate.global.sweet.text') ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= lang('boilerplate.global.sweet.confirm_delete') ?>'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: `<?= base_url('admin/departaments') ?>/` + idDepartaments,
                    method: 'DELETE',
                }).done((data, textStatus, jqXHR) => {
                    Toast.fire({
                        icon: 'success',
                        title: jqXHR.statusText,
                    });
                    tableDepartaments.ajax.reload();
                }).fail((error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.messages.error,
                    });
                });
            }
        });
    });

    $(function () {
        $("#modalAddDepartaments").draggable();
    });
</script>
<?= $this->endSection() ?>