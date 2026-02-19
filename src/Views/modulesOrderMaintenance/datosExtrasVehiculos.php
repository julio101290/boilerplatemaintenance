<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-primary text-white">
        <h6 class="mb-0 fw-semibold">
            <?= lang('newSell.othersDataVehicle') ?>
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3 align-items-end">

            <!-- Vehículo -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <label class="form-label fw-semibold">
                    <?= lang('newSell.vehiclePlate') ?>
                </label>

                <select id="idVehiculoSell"
                        name="idVehiculoSell"
                        class="form-select idVehiculoSell w-100">

                    <?php
                    if (isset($idVehiculo)) {
                        echo "<option value='$idVehiculo'>$idVehiculo - $vehiculoNombre</option>";
                    } else {
                        echo "<option value=''>Seleccione Vehiculo</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Chofer -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <label class="form-label fw-semibold">
                    <?= lang('newSell.driver') ?>
                </label>

                <select id="idChoferSell"
                        name="idChoferSell"
                        class="form-select idChoferSell w-100">

                    <?php
                    if (isset($idChofer)) {
                        echo "<option value='$idChofer'>$idChofer - $choferNombre</option>";
                    } else {
                        echo "<option value=''>" . lang('newSell.selectDriver') . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Tipo Vehículo -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <label class="form-label fw-semibold">
                    <?= lang('newSell.VehicleType') ?>
                </label>

                <?php
                if (isset($idChofer)) {
                    echo "<input class='form-control' type='text' id='tipoVehiculo' value=\"$tipoVehiculo\" name='tipoVehiculo'>";
                } else {
                    echo "<input class='form-control' type='text' id='tipoVehiculo' name='tipoVehiculo'>";
                }
                ?>
            </div>

        </div>

        <!-- Botones -->
        <div class="row mt-4">
            <div class="col-12 d-flex gap-2">

                <button type="button"
                        class="btn btn-primary btnAddVehiculos"
                        data-toggle="modal"
                        data-target="#modalAddVehiculos">
                    <i class="fa fa-plus me-1"></i>
                    <?= lang('newSell.newVehicle') ?>
                </button>

                <button type="button"
                        class="btn btn-primary btnAddChoferes"
                        data-toggle="modal"
                        data-target="#modalAddChoferes">
                    <i class="fa fa-plus me-1"></i>
                    <?= lang('newSell.newDriver') ?>
                </button>

            </div>
        </div>

    </div>
</div>
