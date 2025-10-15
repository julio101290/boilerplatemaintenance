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
});
