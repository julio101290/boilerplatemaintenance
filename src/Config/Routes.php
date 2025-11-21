<?php

$routes->group('admin', function ($routes) {


    /**
     * Ruta para las ubicaciones
     */
    $routes->resource('departaments', [
        'filter' => 'permission:departaments-permission',
        'namespace' => 'julio101290\boilerplatemaintenance\Controllers',
        'controller' => 'departamentsController',
        'except' => 'show'
    ]);

    $routes->post('departaments/save'
            , 'DepartamentsController::save'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->post('departaments/getDepartaments'
            , 'DepartamentsController::getDepartaments'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->post('departaments/getDepartamentsAjax'
            , 'DepartamentsController::getDepartamentsAjax'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->resource('employes', [
        'filter' => 'permission:employes-permission',
        'namespace' => 'julio101290\boilerplatemaintenance\Controllers',
        'controller' => 'employesController',
        'except' => 'show'
    ]);

    $routes->post('employes/save'
            , 'EmployesController::save'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );
    $routes->post('employes/getEmployes'
            , 'EmployesController::getEmployes'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->resource('ProductsEmploye', [
        'filter' => 'permission:productsemploye-permission',
        'namespace' => 'julio101290\boilerplatemaintenance\Controllers',
        'controller' => 'ProductsEmployeController',
        'except' => 'show'
    ]);

    $routes->post('productos/empleadosProducto'
            , 'ProductsEmployeController::ctrDatatableProductsEmployes'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->post('productos/toggleEmployeProduct'
            , 'ProductsEmployeController::toggleEmployeProduct'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->resource('orderMaintenance', [
        'filter' => 'permission:orderMaintenance-permission',
        'controller' => 'OrderMaintenenceController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplatemaintenance\Controllers',
    ]);

    $routes->get('newOrderMaintenance'
            , 'OrderMaintenenceController::newOrderMaintenance'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->get('editOrderMaintenance/(:any)'
            , 'SellsController::editSell/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->post('orderMaintenance/save'
            , 'SellsController::save'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->post('orderMaintenance/getLastCode'
            , 'SellsController::getLastCode'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('orderMaintenance/report/(:any)'
            , 'SellsController::report/$1'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );
    $routes->get('orderMaintenance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'OrderMaintenenceController::ordersMaintenanceFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->get('listOrderMaintenance/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'OrderMaintenenceController::orderMaintenanceListFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatemaintenance\Controllers']
    );

    $routes->get('reporteVentas'
            , 'SellsController::reportSellsProducts'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );

    $routes->get('sellsReport/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'SellsController::sellsReport/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplatesells\Controllers']
    );
});
