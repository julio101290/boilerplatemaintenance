<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>
<?= $this->section('content') ?>
<?= $this->include('julio101290\boilerplatemaintenance\Views/modulesEmployes/modalCaptureEmployes') ?>

<div class="card card-default">
    <div class="card-header">
        <div class="float-right">
            <div class="btn-group">
                <button class="btn btn-primary btnAddEmployes" data-toggle="modal" data-target="#modalAddEmployes">
                    <i class="fa fa-plus"></i> <?= lang('employes.add') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableEmployes" class="table table-striped table-hover va-middle tableEmployes">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('employes.fields.idEmpresa') ?></th>
                                <th><?= lang('employes.fields.idBranchOffice') ?></th>
                                <th><?= lang('employes.fields.idDepartament') ?></th>
                                <th><?= lang('employes.fields.status') ?></th>
                                <th><?= lang('employes.fields.fullname') ?></th>
                                <th><?= lang('employes.fields.email') ?></th>
                                <th><?= lang('employes.fields.workstation') ?></th>
                                <th><?= lang('employes.fields.phone') ?></th>
                                <th><?= lang('employes.fields.ext') ?></th>

                                <th><?= lang('employes.fields.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
    var tableEmployes = $('#tableEmployes').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= base_url('admin/employes') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [10],
                searchable: false,
                targets: [10]
            }],
        columns: [{'data': 'id'},
            {'data': 'nombreEmpresa'},
            {'data': 'nameBranchoffice'},
            {'data': 'descripcionDepartament'},
            {'data': 'status'},
            {'data': 'fullname'},
            {'data': 'email'},
            {'data': 'workstation'},
            {'data': 'phone'},
            {'data': 'ext'},

            {
                "data": function (data) {
                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             <button class="btn btn-warning btnEditEmployes" data-toggle="modal" idEmployes="${data.id}" data-target="#modalAddEmployes">  <i class=" fa fa-edit"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });

    $(document).on('click', '#btnSaveEmployes', function (e) {
        var idEmployes = $("#idEmployes").val();
        var idEmpresa = $("#idEmpresa").val();
        var idBranchOffice = $("#idBranchOffice").val();
        var idDepartament = $("#idDepartament").val();
        var status = $("#status").val();
        var fullname = $("#fullname").val();
        var email = $("#email").val();
        var workstation = $("#workstation").val();
        var phone = $("#phone").val();
        var ext = $("#ext").val();

        $("#btnSaveEmployes").attr("disabled", true);
        var datos = new FormData();
        datos.append("idEmployes", idEmployes);
        datos.append("idEmpresa", idEmpresa);
        datos.append("idBranchOffice", idBranchOffice);
        datos.append("idDepartament", idDepartament);
        datos.append("status", status);
        datos.append("fullname", fullname);
        datos.append("email", email);
        datos.append("workstation", workstation);
        datos.append("phone", phone);
        datos.append("ext", ext);

        $.ajax({
            url: "<?= base_url('admin/employes/save') ?>",
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
                    tableEmployes.ajax.reload();
                    $("#btnSaveEmployes").removeAttr("disabled");
                    $('#modalAddEmployes').modal('hide');
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: respuesta.message || "Error desconocido"
                    });
                    $("#btnSaveEmployes").removeAttr("disabled");
                }
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: jqXHR.responseText
            });
            $("#btnSaveEmployes").removeAttr("disabled");
        });
    });

    $(".tableEmployes").on("click", ".btnEditEmployes", function () {
        var idEmployes = $(this).attr("idEmployes");
        var datos = new FormData();
        datos.append("idEmployes", idEmployes);
        $.ajax({
            url: "<?= base_url('admin/employes/getEmployes') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idEmployes").val(respuesta["id"]);
                $("#idEmpresa").val(respuesta["idEmpresa"]).trigger("change");

                var newOptionBranchOffice = new Option(respuesta["branchOfficeName"], respuesta["idBranchOffice"], true, true);
                $('#idBranchOffice').append(newOptionBranchOffice).trigger('change');
                $("#idBranchOffice").val(respuesta["idBranchOffice"]);


                var newOptionBranchOffice = new Option(respuesta["departamentName"], respuesta["idDepartament"], true, true);
                $('#idDepartament').append(newOptionBranchOffice).trigger('change');
                $("#idDepartament").val(respuesta["idDepartament"]);

                $("#status").val(respuesta["status"]).trigger("change");
                $("#fullname").val(respuesta["fullname"]);
                $("#email").val(respuesta["email"]);
                $("#workstation").val(respuesta["workstation"]);
                $("#phone").val(respuesta["phone"]);
                $("#ext").val(respuesta["ext"]);

            }
        });
    });

    $(".tableEmployes").on("click", ".btn-delete", function () {
        var idEmployes = $(this).attr("data-id");
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
                    url: `<?= base_url('admin/employes') ?>/` + idEmployes,
                    method: 'DELETE',
                }).done((data, textStatus, jqXHR) => {
                    Toast.fire({
                        icon: 'success',
                        title: jqXHR.statusText,
                    });
                    tableEmployes.ajax.reload();
                }).fail((error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.messages.error,
                    });
                });
            }
        });
    });


    $("#status").select2();

    $(function () {
        $("#modalAddEmployes").draggable();
    });
</script>
<?= $this->endSection() ?>