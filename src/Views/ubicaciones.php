<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>

<?= $this->include('julio101290\boilerplatelocations\Views\modulesUbicaciones/modalCaptureUbicaciones') ?>

<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <div class="card-header">
        <div class="float-right">
            <div class="btn-group">

                <button class="btn btn-primary btnAddUbicaciones" data-toggle="modal" data-target="#modalAddUbicaciones"><i class="fa fa-plus"></i>

                    <?= lang('ubicaciones.add') ?>

                </button>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableUbicaciones" class="table table-striped table-hover va-middle tableUbicaciones">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th><?= lang('ubicaciones.fields.idEmpresa') ?></th>
                                <th><?= lang('ubicaciones.fields.descripcion') ?></th>
                                <th><?= lang('ubicaciones.fields.calle') ?></th>
                                <th><?= lang('ubicaciones.fields.numInterior') ?></th>
                                <th><?= lang('ubicaciones.fields.numExterior') ?></th>
                                <th><?= lang('ubicaciones.fields.colonia') ?></th>
                                <th><?= lang('ubicaciones.fields.localidad') ?></th>
                                <th><?= lang('ubicaciones.fields.referencia') ?></th>
                                <th><?= lang('ubicaciones.fields.municipio') ?></th>
                                <th><?= lang('ubicaciones.fields.estado') ?></th>
                                <th><?= lang('ubicaciones.fields.pais') ?></th>
                                <th><?= lang('ubicaciones.fields.codigoPostal') ?></th>
                                <th><?= lang('ubicaciones.fields.created_at') ?></th>
                                <th><?= lang('ubicaciones.fields.updated_at') ?></th>
                                <th><?= lang('ubicaciones.fields.deleted_at') ?></th>

                                <th><?= lang('ubicaciones.fields.actions') ?> </th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.card -->

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>

    /**
     * Cargamos la tabla
     */

    var tableUbicaciones = $('#tableUbicaciones').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],

        ajax: {
            url: '<?= base_url('admin/ubicaciones') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [15],
                searchable: false,
                targets: [15]

            }],
        columns: [{
                'data': 'id'
            },

            {
                'data': 'idEmpresa'
            },

            {
                'data': 'descripcion'
            },

            {
                'data': 'calle'
            },

            {
                'data': 'numInterior'
            },

            {
                'data': 'numExterior'
            },

            {
                'data': 'colonia'
            },

            {
                'data': 'localidad'
            },

            {
                'data': 'referencia'
            },

            {
                'data': 'municipio'
            },

            {
                'data': 'estado'
            },

            {
                'data': 'pais'
            },

            {
                'data': 'codigoPostal'
            },

            {
                'data': 'created_at'
            },

            {
                'data': 'updated_at'
            },

            {
                'data': 'deleted_at'
            },

            {
                "data": function (data) {
                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             <button class="btn btn-warning btnEditUbicaciones" data-toggle="modal" idUbicaciones="${data.id}" data-target="#modalAddUbicaciones">  <i class=" fa fa-edit"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });



    $(document).on('click', '#btnSaveUbicaciones', function (e) {


        var idUbicaciones = $("#idUbicaciones").val();
        var idEmpresa = $("#idEmpresa").val();
        var descripcion = $("#descripcion").val();
        var calle = $("#calle").val();
        var numInterior = $("#numInterior").val();
        var numExterior = $("#numExterior").val();
        var colonia = $("#colonia").val();
        var localidad = $("#localidad").val();
        var referencia = $("#referencia").val();
        var municipio = $("#municipio").val();
        var estado = $("#estado").val();
        var pais = $("#pais").val();
        var codigoPostal = $("#codigoPostal").val();
        var RFCRemitenteDestinatario = $("#RFCRemitenteDestinatario").val();
        var nombreRazonSocial = $("#nombreRazonSocial").val();

        if (idEmpresa == 0 || idEmpresa == null) {

            Toast.fire({
                icon: 'error',
                title: "Tiene que seleccionar la empresa"
            });
            return;
        }

        $("#btnSaveUbicaciones").attr("disabled", true);

        var datos = new FormData();
        datos.append("idUbicaciones", idUbicaciones);
        datos.append("idEmpresa", idEmpresa);
        datos.append("descripcion", descripcion);
        datos.append("calle", calle);
        datos.append("numInterior", numInterior);
        datos.append("numExterior", numExterior);
        datos.append("colonia", colonia);
        datos.append("localidad", localidad);
        datos.append("referencia", referencia);
        datos.append("municipio", municipio);
        datos.append("estado", estado);
        datos.append("pais", pais);
        datos.append("codigoPostal", codigoPostal);
        datos.append("RFCRemitenteDestinatario", RFCRemitenteDestinatario);
        datos.append("nombreRazonSocial", nombreRazonSocial);



        $.ajax({

            url: "<?= base_url('admin/ubicaciones/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function (respuesta) {
                if (respuesta.match(/Correctamente.*/)) {

                    Toast.fire({
                        icon: 'success',
                        title: "Guardado Correctamente"
                    });

                    tableUbicaciones.ajax.reload();
                    $("#btnSaveUbicaciones").removeAttr("disabled");


                    $('#modalAddUbicaciones').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveUbicaciones").removeAttr("disabled");


                }

            }

        }

        )

    });



    /**
     * Carga datos actualizar
     */


    /*=============================================
     EDITAR Ubicaciones
     =============================================*/
    $(".tableUbicaciones").on("click", ".btnEditUbicaciones", function () {

        var idUbicaciones = $(this).attr("idUbicaciones");

        var datos = new FormData();
        datos.append("idUbicaciones", idUbicaciones);

        $.ajax({

            url: "<?= base_url('admin/ubicaciones/getUbicaciones') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idUbicaciones").val(respuesta["id"]);

                $("#idEmpresa").val(respuesta["idEmpresa"]);
                $("#idEmpresa").trigger("change");

                $("#nombreRazonSocial").val(respuesta["nombreRazonSocial"]);
                $("#RFCRemitenteDestinatario").val(respuesta["RFCRemitenteDestinatario"]);

                $("#calle").val(respuesta["calle"]);
                $("#descripcion").val(respuesta["descripcion"]);
                $("#numInterior").val(respuesta["numInterior"]);
                $("#numExterior").val(respuesta["numExterior"]);
                $("#colonia").val(respuesta["colonia"]);
                $("#localidad").val(respuesta["localidad"]);
                $("#referencia").val(respuesta["referencia"]);
                $("#municipio").val(respuesta["municipio"]);
                $("#estado").val(respuesta["estado"]);

                var newOption = new Option(respuesta["pais"] + ' ' + respuesta["nombrePais"], respuesta["pais"], true, true);
                $('#pais').append(newOption).trigger('change');
                $("#pais").val(respuesta["pais"]);

                var newOption = new Option(respuesta["estado"] + ' ' + respuesta["nombreEstado"], respuesta["estado"], true, true);
                $('#estado').append(newOption).trigger('change');
                $("#estado").val(respuesta["estado"]);

                var newOption = new Option(respuesta["municipio"] + ' ' + respuesta["nombreMunicipio"], respuesta["municipio"], true, true);
                $('#municipio').append(newOption).trigger('change');
                $("#municipio").val(respuesta["municipio"]);

                var newOption = new Option(respuesta["localidad"] + ' ' + respuesta["nombreLocalidad"], respuesta["localidad"], true, true);
                $('#localidad').append(newOption).trigger('change');
                $("#localidad").val(respuesta["localidad"]);

                var newOption = new Option(respuesta["colonia"] + ' ' + respuesta["nombreColonia"], respuesta["colonia"], true, true);
                $('#colonia').append(newOption).trigger('change');
                $("#colonia").val(respuesta["colonia"]);

                $("#pais").val(respuesta["pais"]);
                $("#codigoPostal").val(respuesta["codigoPostal"]);

            }

        })

    })


    /*=============================================
     ELIMINAR ubicaciones
     =============================================*/
    $(".tableUbicaciones").on("click", ".btn-delete", function () {

        var idUbicaciones = $(this).attr("data-id");

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
                            url: `<?= base_url('admin/ubicaciones') ?>/` + idUbicaciones,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableUbicaciones.ajax.reload();
                        }).fail((error) => {
                            Toast.fire({
                                icon: 'error',
                                title: error.responseJSON.messages.error,
                            });
                        })
                    }
                })
    })

    $(function () {
        $("#modalAddUbicaciones").draggable();

    });



    /**
     * Categorias por empresa
     */

    $(".colonia").select2({
        ajax: {
            url: "<?= base_url('admin/ubicaciones/getColoniaSATAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var codigoPostal = $('.codigoPostal').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    codigoPostal: codigoPostal // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },

            cache: true
        }
    });



    /**
     * Categorias por empresa
     */

    $(".estado").select2({
        ajax: {
            url: "<?= base_url('admin/ubicaciones/getEstadosSATAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var pais = $('.pais').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    pais: pais // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },

            cache: true
        }
    });

    /**
     * Municipios
     */

    $(".municipio").select2({
        ajax: {
            url: "<?= base_url('admin/ubicaciones/getMunicipiosSATAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var estado = $('.estado').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    estado: estado // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },

            cache: true
        }
    });



    /**
     * Municipios
     */

    $(".localidad").select2({
        ajax: {
            url: "<?= base_url('admin/ubicaciones/getLocalidadSATAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var estado = $('.estado').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    estado: estado // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },

            cache: true
        }
    });

    /**
     * Categorias por empresa
     */

    $(".pais").select2({
        ajax: {
            url: "<?= base_url('admin/ubicaciones/getPaisesSATAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,

            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },

            cache: true
        }
    });




</script>
<?= $this->endSection() ?>
        