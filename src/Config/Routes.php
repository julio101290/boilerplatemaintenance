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
});
