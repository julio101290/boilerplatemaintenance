<div class="card shadow-sm border-0 mb-4">

    <!-- HEADER -->
    <div class="card-header bg-primary text-white py-2">
        <h6 class="mb-0 fw-semibold">Datos Generales</h6>
    </div>

    <div class="card-body">

        <!-- ================= FILA 1 (CUADRADA) ================= -->
        <div class="row g-3 align-items-end">

            <!-- Fecha -->
            <div class="col-xl-2 col-lg-2 col-md-4 col-12">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.date') ?>
                </label>
                <input type="date"
                       id="date"
                       name="date"
                       class="form-control form-control-sm"
                       value="<?= $fecha ?>">
            </div>

            <!-- Empresa (más espacio) -->
            <div class="col-xl-4 col-lg-4 col-md-8 col-12">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.companie') ?>
                </label>

                <select id="idEmpresaSells"
                        name="idEmpresaSells"
                        class="form-select form-select-sm idEmpresaSells w-100">

                    <?php if (isset($idEmpresa)) : ?>
                        <option value="<?= $idEmpresa ?>">
                            <?= $idEmpresa ?> - <?= $nombreEmpresa ?>
                        </option>
                    <?php else : ?>
                        <option value=""><?= lang('newOrderMaintenance.selectCompanie') ?></option>
                        <?php foreach ($empresas as $value) : ?>
                            <option value="<?= $value['id'] ?>">
                                <?= $value['id'] ?> - <?= $value['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </select>
            </div>

            <!-- Sucursal -->
            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.branchoffice') ?>
                </label>

                <select id="idSucursal"
                        name="idSucursal"
                        class="form-select form-select-sm idSucursal w-100">

                    <?php if (isset($idSucursal)) : ?>
                        <option value="<?= $idSucursal ?>">
                            <?= $idSucursal ?> - <?= $nombreSucursal ?>
                        </option>
                    <?php else : ?>
                        <option value=""><?= lang('newOrderMaintenance.selectBranchoffice') ?></option>
                    <?php endif; ?>

                </select>
            </div>

            <!-- Folio (compacto) -->
            <div class="col-xl-3 col-lg-3 col-md-6 col-12 text-lg-end">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.folio') ?>
                </label>

                <input type="text"
                       id="codeOrder"
                       name="codeOrder"
                       class="form-control form-control-sm text-lg-end bg-light"
                       disabled
                       value="<?= $folio ?>">
            </div>

        </div>

        <!-- ================= RD COMPROBANTE ================= -->
        <div class="row g-3 align-items-end mt-2 comprobantesRD" hidden>

            <div class="col-lg-6 col-md-6 col-12">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.typeVoucher') ?>
                </label>

                <select id="tipoComprobanteRD"
                        name="tipoComprobanteRD"
                        class="form-select form-select-sm tipoComprobanteRD w-100">

                    <?php if (isset($tipoComprobanteRDID)) : ?>
                        <option value="<?= $tipoComprobanteRDID ?>">
                            <?= $tipoComprobanteRDPrefijo ?> - <?= $tipoComprobanteRDNombre ?>
                        </option>
                    <?php else : ?>
                        <option value=""><?= lang('newOrderMaintenance.selectTypeVoucher') ?></option>
                    <?php endif; ?>

                </select>
            </div>

            <div class="col-lg-6 col-md-6 col-12 text-lg-end">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.typeVoucher') ?>
                </label>

                <input type="text"
                       id="folioComprobanteRD"
                       name="folioComprobanteRD"
                       class="form-control form-control-sm text-lg-end bg-light"
                       disabled
                       value="<?= $folioComprobanteRD ?>">
            </div>

        </div>

        <!-- ================= FILA 2 ================= -->
        <div class="row g-3 align-items-end mt-3">

            <!-- Cliente -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.custumer') ?>
                </label>

                <select id="custumerSell"
                        name="custumerSell"
                        class="form-select form-select-sm custumerSell w-100">

                    <?php if (isset($idCustumer)) : ?>
                        <option value="<?= $idCustumer ?>">
                            <?= $idCustumer ?> - <?= $nameCustumer ?>
                        </option>
                    <?php else : ?>
                        <option value=""><?= lang('newOrderMaintenance.selectCustumer') ?></option>
                    <?php endif; ?>

                </select>
            </div>

            <!-- Producto -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.product') ?>
                </label>

                <div class="input-group input-group-sm">
                    <select id="productOrder"
                            name="productOrder"
                            class="form-select productOrder">
                        <?php if (isset($idProduct)) : ?>
                            <option value="<?= $idProduct ?>">
                                <?= $idProduct ?> - <?= $nameProduct ?>
                            </option>
                        <?php else : ?>
                            <option value="0"><?= lang('newOrderMaintenance.selectProductOrder') ?></option>
                        <?php endif; ?>
                    </select>

                    <button type="button"
                            class="btn btn-outline-info btnInfoExtraProduct"
                            title="<?= lang('newOrderMaintenance.productHelp') ?>">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Status -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <label class="form-label small fw-bold">Status</label>
                <select id="status"
                        name="status"
                        class="form-select form-select-sm status">
                    <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '' ?>>Pendiente</option>
                    <option value="2" <?= (isset($status) && $status == 2) ? 'selected' : '' ?>>En Proceso</option>
                    <option value="3" <?= (isset($status) && $status == 3) ? 'selected' : '' ?>>Rechazado</option>
                    <option value="4" <?= (isset($status) && $status == 4) ? 'selected' : '' ?>>Terminado</option>
                </select>
            </div>

            <!-- Fecha Vencimiento -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <label class="form-label small fw-bold">
                    <?= lang('newOrderMaintenance.expirationDate') ?>
                </label>

                <input type="date"
                       id="dateVen"
                       name="dateVen"
                       class="form-control form-control-sm"
                       value="<?= $fecha ?>">

                <input type="hidden" id="titulo" name="titulo" value="<?= $title ?>">
            </div>

            <!-- Usuario -->
            <div class="col-xl-2 col-lg-4 col-md-4 col-12 text-lg-end">
                <label class="form-label small fw-bold">
                    Realizada por
                </label>

                <input type="text"
                       id="user"
                       name="user"
                       class="form-control form-control-sm text-lg-end bg-light"
                       disabled
                       value="<?= $userName ?>">

                <!-- Hidden fields (mismo comportamiento) -->
                <input type="hidden" id="idUser" name="idUser" value="<?= isset($idUser) ? $idUser : '' ?>">
                <input type="hidden" id="idRegister" name="idRegister" value="<?= isset($idRegister) ? $idRegister : 0 ?>">
                <input type="hidden" id="idQuote" name="idQuote" value="<?= isset($idQuote) ? $idQuote : '' ?>">
                <input type="hidden" id="uuid" name="uuid" value="<?= isset($uuid) ? $uuid : '' ?>">
            </div>

        </div>

        <!-- ================= BOTONES ================= -->
        <div class="row mt-4">
            <div class="col-12 d-flex flex-wrap gap-2">

                <button type="button"
                        class="btn btn-outline-secondary btn-sm btnAddArticle"
                        data-toggle="modal"
                        data-target="#modalAddbtnAddArticle">
                    Agregar Artículo
                </button>

                <?php if ($permisoAgregarArticulo) : ?>
                    <button class="btn btn-primary btn-sm btnAddProducts"
                            data-toggle="modal"
                            data-target="#modalAddProducts">
                        <i class="fa fa-plus"></i>
                        <?= lang('newOrderMaintenance.addArticle') ?>
                    </button>
                <?php endif; ?>

                <button class="btn btn-success btn-sm btnAddCustumers"
                        data-toggle="modal"
                        data-target="#modalAddCustumers">
                    <i class="fa fa-plus"></i>
                    <?= lang('newOrderMaintenance.newCustumer') ?>
                </button>

            </div>
        </div>

    </div>
</div>
