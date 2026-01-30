<?= $this->include('julio101290\boilerplate\Views\load/daterangapicker') ?>
<?= $this->include('julio101290\boilerplate\Views\load/toggle') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load/extrasDatatable') ?>
<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>


<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>


<!-- Section content -->
<?= $this->section('content') ?>

<?= $this->include('julio101290\boilerplatemaintenance\Views\modulesOrderMaintenance/modaSendMail') ?>


<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <div class="card-header">

        <div class="float-left">
            <div class="btn-group">

                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>


            </div>

            <div class="btn-group">



                <div class="form-group">
                    <label for="idEmpresa"><?= lang('ordersMaintenance.companie') ?> </label>
                    <select id='idEmpresa' name='idEmpresa' class="idEmpresa" style='width: 80%;'>

                        <?php
                        if (isset($idEmpresa)) {

                            echo "   <option value='$idEmpresa'>$idEmpresa - $nombreEmpresa</option>";
                        } else {

                            echo "  <option value='0'>" . lang('ordersMaintenance.allCompanies') . "</option>";

                            foreach ($empresas as $key => $value) {

                                echo "<option value='$value[id]'>$value[id] - $value[nombre] </option>  ";
                            }
                        }
                        ?>

                    </select>
                </div>

            </div>


            <div class="btn-group">



                <div class="form-group">
                    <label for="idSucursal"><?= lang('ordersMaintenance.branchoffice') ?> </label>
                    <select id='idSucursal' name='idSucursal' class="idSucursal" style='width: 100%;'>

                        <?php
                        echo "  <option value='0'>" . lang('ordersMaintenance.AllBranchoffice') . "</option>";
                        if (isset($idSucursal)) {

                            echo "   <option value='$idSucursal'>$idSucursal - $nombreSucursal</option>";
                        }
                        ?>

                    </select>
                </div>

            </div>




            <div class="btn-group">



                <div class="form-group">
                    <label for="productos"><?= lang('ordersMaintenance.custumer') ?> </label>
                    <select id='clientes' name='clientes' class="clientes" style='width: 100%;'>

                        <?php
                        echo "  <option value='0'>" . lang('ordersMaintenance.allCustumer') . "</option>";
                        ?>

                    </select>
                </div>

            </div>

            <div class="btn-group">



                <input type="checkbox" id="chkTodasLasOrdenes" name="chkTodasLasOrdenes" class="chkTodasLasOrdenes" data-width="250" data-height="40" checked data-toggle="toggle" data-on="<?= lang('ordersMaintenance.allOrdersMaintenence') ?>" data-off="<?= lang('ordersMaintenance.pending') ?>" data-onstyle="success" data-offstyle="danger">

            </div>


        </div>

        <div class="float-right">
            <div class="btn-group">

                <a href="<?= base_url("admin/newOrderMaintenance") ?>" class="btn btn-primary btnAddCustumers" data-target="#modalAddCustumers"><i class="fa fa-plus"></i>

                    <?= lang('ordersMaintenance.add') ?>

                </a>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-md-12">

                <div class="table-responsive">

                    <table id="tableOrdersMaintenence" class="table table-striped table-hover va-middle tableOrdersMaintenence">

                        <thead>

                            <tr>

                                <th><?= lang('ordersMaintenance.fields.row') ?></th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.folio') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.custumer') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.date') ?>
                                </th>

                                <th>
                                    <?= lang('ordersMaintenance.fields.expirationDate') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.subTotal') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.tax') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.total') ?>
                                </th>

                                <th>
                                    <?= lang('ordersMaintenance.fields.pending') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.timeDelevery') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.created_at') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.updated_at') ?>
                                </th>
                                <th>
                                    <?= lang('ordersMaintenance.fields.deleted_at') ?>
                                </th>

                                <th>
                                    <?= lang('ordersMaintenance.fields.actions') ?>
                                </th>

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

    var tableOrderMaintenence = $('#tableOrdersMaintenence').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
        lengthMenu: [
            [150, 200, 500, -1],
            ['150  <?= lang('ordersMaintenance.fields.rows') ?>', '200  <?= lang('ordersMaintenance.fields.rows') ?>', '500  <?= lang('ordersMaintenance.fields.rows') ?>', ' <?= lang('ordersMaintenance.all') ?>']
        ],
        responsive: true,
        autoWidth: false,
        order: [
            [1, 'desc']
        ],

        ajax: {
            url: '<?= base_url('admin/orderMaintenance') ?>',
            method: 'get',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [13],
                searchable: false,
                targets: [13]

            }],
        columns: [{
                'data': 'id'
            },

            {
                'data': 'folio'
            },
            {
                'data': 'razonSocial'
            },

            {
                'data': 'date'
            },

            {
                'data': 'dateVen'
            },

            {
                'data': 'subTotal'
            },

            {
                'data': 'taxes'
            },

            {
                'data': 'total'
            },

            {
                'data': 'balance'
            },

            {
                'data': 'delivaryTime'
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
                             <a href="<?= base_url('admin/editOrderMaintenance') ?>/${data.UUID}" class="btn btn-primary btn-edit"><i class="fas fa-pencil-alt"></i></a>
                             <button class="btn btn-success btnSendMail" data-toggle="modal" correoCliente ="${data.correoCliente}" uuid="${data.UUID}" folio="${data.folio}" data-toggle="modal" data-target="#modalSendMail"  >  <i class=" fas fa-envelope"></i></button>
                             <button class="btn bg-warning btnPrintOrder" uuid="${data.UUID}" ><i class="far fa-file-pdf"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button> 
                         </div>
                         </td>`
                }
            }
        ]
    });




    $("#idEmpresa").select2();

    $("#idSucursal").select2({
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

    $("#productos").select2({
        ajax: {
            url: "<?= site_url('admin/products/getProductsAjax') ?>",
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


    // Initialize select2 storages
    $("#clientes").select2({
        ajax: {
            url: "<?= site_url('admin/custumers/getCustumersTodosAjax') ?>",
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


    /**@abstract
     * 
     * Al cambiar la el rango de fecha
     */

    $("#chkTodasLasOrdenes").on("change", function () {

        var datePicker = $('#reportrange').data('daterangepicker');
        var desdeFecha = datePicker.startDate.format('YYYY-MM-DD');
        var hastaFecha = datePicker.endDate.format('YYYY-MM-DD');
        var idEmpresa = $("#idEmpresa").val();
        var idSucursal = $("#idSucursal").val();
        var idCliente = $("#clientes").val();


        if ($(this).is(':checked')) {

            todas = true;

        } else {

            todas = false;

        }

        tableOrderMaintenence.ajax.url(`<?= base_url('admin/orderMaintenance') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idSucursal + '/' + idCliente).load();

    });





    /*=============================================
     Load Payment List 
     =============================================*/

    $(".tableOrdersMaintenence").on("click", '.btnPaymentsList', function () {


        var uuid = $(this).attr("uuid");

        console.log(uuid);

        tableProducts.ajax.url(`<?= base_url('admin/payments/getPayments') ?>/` + uuid).load();

    });


    /*=============================================
     ENVIAR CORREO  
     =============================================*/

    $(".tableOrdersMaintenence").on("click", '.btnSendMail', function () {

        var uuid = $(this).attr("uuid");
        var folio = $(this).attr("folio");
        var correo = $(this).attr("correocliente");


        $('#correos').empty();

        var arr = correo.split(',');

        $.each(arr, function (index, value) {

            var newOption = new Option(value, value);


            $('#correos').append(newOption).trigger('change');

        });

        $('#correos option').prop('selected', true);

        $("#uuidMail").val(uuid);
        $("#folioVentaMail").val(folio);


    });



    /*=============================================
     ENVIAR CORREO  
     =============================================*/

    $(".tableOrdersMaintenence").on("click", '.btnSetPayment', function () {

        var uuid = $(this).attr("uuid");
        var balance = $(this).attr("balance");

        console.log("asd");

        $("#uuidSellPayment").val(uuid);
        $("#pago").val("0.00");
        $("#granTotal").val(balance);


    });




    /*=============================================
     IMPRIMIR VEnta
     =============================================*/

    $(".tableOrdersMaintenence").on("click", '.btnPrintOrder', function () {

        var uuid = $(this).attr("uuid");


        window.open("<?= base_url('admin/orderMaintenance/report') ?>" + "/" + uuid, "_blank");

    });


    /*=============================================
     ELIMINAR custumers
     =============================================*/
    $(".tableOrdersMaintenence").on("click", ".btn-delete", function () {

        var idOrder = $(this).attr("data-id");

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
                            url: `<?= base_url('admin/orderMaintenance') ?>/` + idOrder,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableOrderMaintenence.ajax.reload();
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

        var start = moment().subtract(29, 'days');
        var end = moment();
        var todas = true;

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            if ($('#chkTodasLasVentas').is(':checked')) {

                todas = true;

            } else {

                todas = false;

            }



            var desdeFecha = start.format('YYYY-MM-DD');
            var hastaFecha = end.format('YYYY-MM-DD');
            var idEmpresa = $("#idEmpresa").val();
            var idSucursal = $("#idSucursal").val();
            var idCliente = $("#clientes").val();

            tableQuotes.ajax.url(`<?= base_url('admin/sells') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idSucursal + '/' + idCliente).load();


        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Todo': [moment().subtract(100, 'year').startOf('month'), moment().add(100, 'year').endOf('year')]
            }
        }, cb);

        cb(start, end);





    });

    $(document).ready(function () {
<?php
if (isset($cliente)) {

    echo "tableQuotes.ajax.url('" . base_url('admin/sells') . "/$desdeFecha/$hastaFecha/$todas/$empresa/$sucursal/$cliente').load()";
}
?>
    })
</script>
<?= $this->endSection() ?>