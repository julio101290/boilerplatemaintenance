<!-- Modal Registrovisitas -->
<div class="modal fade" id="modalUploadFiles" tabindex="-1" role="dialog" aria-labelledby="modalUploadFiles" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Archivos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-registrovisitas" class="form-horizontal">
                    <input type="hidden" id="idOrderMaintenance" name="idOrderMaintenance" value="0">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#tabUploadFile" type="button" role="tab" aria-controls="home" aria-selected="true">Captura Archivo</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="filesList-tab" data-toggle="tab" data-target="#tablistFiles" type="button" role="tab" aria-controls="profile" aria-selected="false">Lista Archivos</button>
                        </li>

                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tabUploadFile" role="tabpanel" aria-labelledby="tabUploadFile">

                            <p>
                            <h3>Subir Archivos </h3>

                            <div class="form-group">



                                <input type="file" class="file" name="file" id="file" capture>

                                <p class="help-block">Peso máximo de la foto 10MB</p>


                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">
                                    Descripción
                                </label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                        </div>
                                        <input type="text" name="descriptionFile" id="descriptionFile" class="form-control form-controlCustumers " placeholder="Descripcion archivo" autocomplete="off">
                                    </div>
                                </div>
                            </div>




                            </p>

                        </div>


                        <div class="tab-pane fade show" id="tablistFiles" role="tabpanel" aria-labelledby="tablistFiles">

                            <p>
                            <h3>Lista Archivos </h3>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tableFiles" class="table table-striped table-hover va-middle tableFiles">
                                            <thead>
                                                <tr>

                                                    <th>#</th>
                                                    <th>Descripcion </th>

                                                    <th>Fecha</th>

                                                    <th>Acciones </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            </p>

                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveLoadFile"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>



    $(document).on('click', '#btnSaveLoadFile', function (e) {


        var idOrderMaintenance = $("#idOrderMaintenance").val();
        var description = $("#descriptionFile").val();
        var file = $("#file").prop("files")[0];

        $("#btnSaveLoadFile").attr("disabled", true);

        var datos = new FormData();
        datos.append("idOrderMaintenance", idOrderMaintenance);
        datos.append("description", description);
        datos.append("file", file);

        $.ajax({

            url: "<?= base_url('admin/filesOrderMaintenance/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            //dataType:"json",
            success: function (respuesta) {


                if (respuesta.match(/Correctamente.*/)) {

                    tableFiles.ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: "Imagen Capturada Correctamente"
                    });




                    $('#modalAddImagen').modal('toggle');


                    $("#btnSaveLoadFile").removeAttr("disabled");


                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveLoadFile").removeAttr("disabled");




                }

            }

        }).fail(function (jqXHR, textStatus, errorThrown) {

            if (jqXHR.status === 0) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No hay conexión.!" + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });
                $("#btnSaveRegistrovisitas").removeAttr("disabled");
            }
        });



    });


    /**
     * Cargamos la tabla
     */

    var tableFiles = $('#tableFiles').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],

        ajax: {
            url: '<?= base_url('admin/orderMaintenance/getFilesPerOrder') ?>/0',
            method: 'get',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [3],
                searchable: false,
                targets: [3]

            }],
        columns: [{
                'data': 'id'
            },

            {
                'data': 'description'
            },

            {
                'data': 'updated_at'
            },

            {
                "data": function (data) {
                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             <button type="button" class="btn btn-danger btn-deleteFile" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                             <button type="button" class="btn btn-success btn-downloadFile" data-id="${data.id}"><i class="fas fa-download"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });



    /*=============================================
     ELIMINAR imagenesregistros
     =============================================*/
    $(".tableFiles").on("click", ".btn-deleteFile", function () {

        var idDeleteFile = $(this).attr("data-id");

        Swal.fire({
            title: '<?= lang('boilerplate.global.sweet.title') ?>',
            text: "<?= lang('boilerplate.global.sweet.text') ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= lang('boilerplate.global.sweet.confirm_delete') ?>'
        })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: `<?= base_url('admin/ordenesMantenimientoArchivos') ?>/` + idDeleteFile,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableFiles.ajax.reload();
                        }).fail((error) => {
                            Toast.fire({
                                icon: 'error',
                                title: error.responseJSON.messages.error,
                            });
                        })
                    }
                })
    })



    /*=============================================
     DESCARGAR ARCHIVO
     =============================================*/
    $(".tableFiles").on("click", ".btn-downloadFile", function () {

        var idFile = $(this).attr("data-id");

        // Redirige al endpoint que ya creamos
        window.location.href = `<?= base_url('admin/ordenesMantenimientoArchivos/download') ?>/` + idFile;

    });


</script>


<?= $this->endSection() ?>
        