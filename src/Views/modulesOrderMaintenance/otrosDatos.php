<div class="card mb-4 shadow-sm">

    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><?= lang('newSell.others') ?></h5>
    </div>

    <div class="card-body">

        <!-- FILA 1 -->
        <div class="form-row">

            <div class="form-group col-lg-6 col-md-8 col-12">
                <label><?= lang('newSell.quoteTo') ?>:</label>
                <input type="text"
                       class="form-control"
                       id="quoteTo"
                       name="quoteTo"
                       value="<?= $quoteTo ?? '' ?>">
            </div>

            <div class="form-group col-lg-3 col-md-4 col-12">
                <label><?= lang('newSell.deleveryTime') ?>:</label>
                <input type="text"
                       class="form-control"
                       id="delivaryTime"
                       name="delivaryTime"
                       value="<?= $delivaryTime ?? '' ?>">
            </div>

        </div>

        <!-- FILA 2 -->
        <div class="form-row">

            <div class="form-group col-12">
                <label><?= lang('newSell.sellsObservations') ?>:</label>
                <textarea class="form-control"
                          rows="4"
                          id="obsevations"
                          name="obsevations"><?= $observations ?? '' ?></textarea>
            </div>

        </div>

    </div>
</div>
