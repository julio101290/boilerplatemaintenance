<!-- Modal Departaments -->
<div class="modal fade" id="modalAddDepartaments" tabindex="-1" role="dialog" aria-labelledby="modalAddDepartaments" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('departaments.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-departaments" class="form-horizontal">
                    <input type="hidden" id="idDepartaments" name="idDepartaments" value="0">

                    <div class="form-group row">
                        <label for="idempresa" class="col-sm-2 col-form-label"><?= lang('departaments.fields.idempresa') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idempresa form-idempresa" name="idempresa" id="idempresa" style="width:80%;">
                                    <option value="0">Seleccione empresa</option>
                                    <?php
                                    foreach ($empresas as $key => $value) {

                                        echo "<option value='$value[id]'>$value[id] - $value[nombre] </option>  ";
                                    }
                                    ?>

                                </select>

                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="idsucursal" class="col-sm-2 col-form-label"><?= lang('departaments.fields.idsucursal') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select id='idsucursal' name='idsucursal' class="idsucursal" style='width: 80%;'>

                                    <?php
                                    if (isset($idSucursal)) {

                                        echo "   <option value='$idSucursal'>$idSucursal - $nombreSucursal</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label"><?= lang('departaments.fields.description') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="description" id="description" class="form-control <?= session('error.description') ? 'is-invalid' : '' ?>" value="<?= old('description') ?>" placeholder="<?= lang('departaments.fields.description') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="areamanager" class="col-sm-2 col-form-label"><?= lang('departaments.fields.areamanager') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select id='areamanager' name='areamanager' class="areamanager" style='width: 80%;'>

                                    <?php
                                    if (isset($idUser)) {

                                        echo "   <option value='$user'>$idUser - $user->username</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveDepartaments"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $("#idempresa").select2();

    $(document).on('click', '.btnAddDepartaments', function (e) {


        $(".form-control").val("");

        $("#idDepartaments").val("0");

        $("#btnSaveDepartaments").removeAttr("disabled");

        $("#idempresa").val("0").trigger("change");

        var newOptionBranchOffice = new Option("Seleccione una sucursal", "0", true, true);
        $('#idsucursal').append(newOptionBranchOffice).trigger('change');
        $("#idsucursal").val("0");


        var newOptionAreaManager = new Option("Seleccione una sucursal", "0", true, true);
        $('#areamanager').append(newOptionAreaManager).trigger('change');
        $("#areamanager").val("0");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditDepartaments', function (e) {


        var idDepartaments = $(this).attr("idDepartaments");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idDepartaments").val(idDepartaments);
        $("#btnGuardarDepartaments").removeAttr("disabled");


    });


    $("#idEmpresa").select2();

    $("#idsucursal").select2({
        ajax: {
            url: "<?= site_url('admin/sucursales/getSucursalesAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idempresa').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
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

    $("#areamanager").select2({
        ajax: {
            url: "<?= site_url('admin/sapservicelayer/getUsersAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 2000, // ⏱ espera 2 segundos después de la última tecla
            data: function (params) {
                var idEmpresa = $('.idempresa').val(); // CSRF Token name
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF value

                return {
                    searchTerm: params.term || '', // search term
                    idEmpresa: idEmpresa || '', // search term
                    [csrfName]: csrfHash
                };
            },
            processResults: function (response) {
                // Si el servidor devuelve un nuevo token, lo actualizamos
                if (response.token) {
                    $('.txt_csrfname').val(response.token);
                }

                return {
                    results: response.results || []
                };
            },
            cache: true
        }
    });

</script>


<?= $this->endSection() ?>
        