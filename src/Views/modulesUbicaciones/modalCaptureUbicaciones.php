<!-- Modal Ubicaciones -->
<div class="modal fade" id="modalAddUbicaciones" tabindex="-1" role="dialog" aria-labelledby="modalAddUbicaciones" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('ubicaciones.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ubicaciones" class="form-horizontal">
                    <input type="hidden" id="idUbicaciones" name="idUbicaciones" value="0">

                    <div class="form-group row">
                        <label for="idEmpresa" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.idEmpresa') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select class="form-control idEmpresa form-controlCustumers" name="idEmpresa" id="idEmpresa" style="width:80%;">
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
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.descripcion') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="descripcion" id="descripcion" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('descripcion') ?>" placeholder="<?= lang('ubicaciones.fields.descripcion') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.nombreRazonSocial') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="nombreRazonSocial" id="nombreRazonSocial" class="form-control <?= session('error.nombreRazonSocial') ? 'is-invalid' : '' ?>" value="<?= old('nombreRazonSocial') ?>" placeholder="<?= lang('ubicaciones.fields.nombreRazonSocial') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.RFCRemitenteDestinatario') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="RFCRemitenteDestinatario" id="RFCRemitenteDestinatario" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('RFCRemitenteDestinatario') ?>" placeholder="<?= lang('ubicaciones.fields.RFCRemitenteDestinatario') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="codigoPostal" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.codigoPostal') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="codigoPostal" id="codigoPostal" class="form-control codigoPostal <?= session('error.codigoPostal') ? 'is-invalid' : '' ?>" value="<?= old('codigoPostal') ?>" placeholder="<?= lang('ubicaciones.fields.codigoPostal') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pais" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.pais') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control colonia form-control pais" name="pais" id="pais" style="width:80%;">
                                    <option value="0">Seleccione el pais</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="estado" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.estado') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select class="form-control estado form-control estado" name="estado" id="estado" style="width:80%;">
                                    <option value="0">Seleccione el estado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="municipio" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.municipio') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control estado form-control municipio" name="municipio" id="municipio" style="width:80%;">
                                    <option value="0">Seleccione el municipio</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="localidad" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.localidad') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select class="form-control localidad form-control" name="localidad" id="localidad" style="width:80%;">
                                    <option value="0">Seleccione la localidad</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="colonia" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.colonia') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <select class="form-control colonia form-control" name="colonia" id="colonia" style="width:80%;">
                                    <option value="0">Seleccione la colonia</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="calle" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.calle') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="calle" id="calle" class="form-control <?= session('error.calle') ? 'is-invalid' : '' ?>" value="<?= old('calle') ?>" placeholder="<?= lang('ubicaciones.fields.calle') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="numInterior" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.numInterior') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="numInterior" id="numInterior" class="form-control <?= session('error.numInterior') ? 'is-invalid' : '' ?>" value="<?= old('numInterior') ?>" placeholder="<?= lang('ubicaciones.fields.numInterior') ?>" autocomplete="off">
                                <input type="hidden" name="txt_csrfname" id="txt_csrfname" class="txt_csrfname" value="" >


                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="numExterior" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.numExterior') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="numExterior" id="numExterior" class="form-control <?= session('error.numExterior') ? 'is-invalid' : '' ?>" value="<?= old('numExterior') ?>" placeholder="<?= lang('ubicaciones.fields.numExterior') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="referencia" class="col-sm-2 col-form-label"><?= lang('ubicaciones.fields.referencia') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="referencia" id="referencia" class="form-control <?= session('error.referencia') ? 'is-invalid' : '' ?>" value="<?= old('referencia') ?>" placeholder="<?= lang('ubicaciones.fields.referencia') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    
                    


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveUbicaciones"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddUbicaciones', function (e) {


        $(".form-control").val("");

        $("#idUbicaciones").val("0");

        $("#btnSaveUbicaciones").removeAttr("disabled");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditUbicaciones', function (e) {


        var idUbicaciones = $(this).attr("idUbicaciones");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idUbicaciones").val(idUbicaciones);
        $("#btnGuardarUbicaciones").removeAttr("disabled");

    });

    $("#idEmpresa").select2();

</script>


<?= $this->endSection() ?>
        