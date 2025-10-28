<!-- Modal Employes -->
<div class="modal fade" id="modalAddEmployes" tabindex="-1" role="dialog" aria-labelledby="modalAddEmployes" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('employes.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-employes" class="form-horizontal">
                    <input type="hidden" id="idEmployes" name="idEmployes" value="0">

                    <div class="form-group row">
                        <label for="emitidoRecibido" class="col-sm-2 col-form-label">Empresa</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idEmpresa" name="idEmpresa" id="idEmpresa" style = "width:80%;">
                                    <option value="0">Seleccione empresa</option>
                                    <?php
                                    foreach ($empresas as $key => $value) {

                                        echo "<option value='$value[id]' selected>$value[id] - $value[nombre] </option>  ";
                                    }
                                    ?>

                                </select>

                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="idsucursal" class="col-sm-2 col-form-label"><?= lang('employes.fields.idBranchOffice') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select id='idBranchOffice' name='idBranchOffice' class="idBranchOffice" style='width: 80%;'>

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
                        <label for="idsucursal" class="col-sm-2 col-form-label"><?= lang('employes.fields.idDepartament') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select id='idDepartament' name='idDepartament' class="idDepartament" style='width: 80%;'>

                                    <?php
                                    if (isset($idDepartament)) {

                                        echo "   <option value='$idDepartament'>$idDepartament - $nameDepartartament</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="idsucursal" class="col-sm-2 col-form-label"><?= lang('employes.fields.status') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select id='status' name='status' class="status" style='width: 80%;'>
                                    <option value="1" ><?= lang('employes.fields.statusEnabled') ?></option> 
                                    <option value="0" ><?= lang('employes.fields.statusDisabled') ?></option> 
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label"><?= lang('employes.fields.fullname') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="fullname" id="fullname" class="form-control <?= session('error.fullname') ? 'is-invalid' : '' ?>" value="<?= old('fullname') ?>" placeholder="<?= lang('employes.fields.fullname') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label"><?= lang('employes.fields.email') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="email" id="email" class="form-control <?= session('error.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" placeholder="<?= lang('employes.fields.email') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="workstation" class="col-sm-2 col-form-label"><?= lang('employes.fields.workstation') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="workstation" id="workstation" class="form-control <?= session('error.workstation') ? 'is-invalid' : '' ?>" value="<?= old('workstation') ?>" placeholder="<?= lang('employes.fields.workstation') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label"><?= lang('employes.fields.phone') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="phone" id="phone" class="form-control <?= session('error.phone') ? 'is-invalid' : '' ?>" value="<?= old('phone') ?>" placeholder="<?= lang('employes.fields.phone') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ext" class="col-sm-2 col-form-label"><?= lang('employes.fields.ext') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="ext" id="ext" class="form-control <?= session('error.ext') ? 'is-invalid' : '' ?>" value="<?= old('ext') ?>" placeholder="<?= lang('employes.fields.ext') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveEmployes"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddEmployes', function (e) {


        $(".form-control").val("");

        $("#idEmployes").val("0");

        $("#btnSaveEmployes").removeAttr("disabled");

        $("#idEmpresa").val("0").trigger("change");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditEmployes', function (e) {


        var idEmployes = $(this).attr("idEmployes");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idEmployes").val(idEmployes);
        $("#btnGuardarEmployes").removeAttr("disabled");



    });


    $("#idEmpresa").select2();



    $("#idBranchOffice").select2({
        ajax: {
            url: "<?= site_url('admin/sucursales/getSucursalesAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresa').val(); // CSRF hash

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




    $("#idDepartament").select2({
        ajax: {
            url: "<?= site_url('admin/departaments/getDepartamentsAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresa').val(); // CSRF hash
                var idbrachOffice = $('.idBranchOffice').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa, // Company variable
                    idBranchOffice: idbrachOffice // search term
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
        