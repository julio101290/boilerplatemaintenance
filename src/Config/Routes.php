<?php

$routes->group('admin', function ($routes) {


    /**
     * Ruta para las ubicaciones
     */
    $routes->resource('ubicaciones', [
        'filter' => 'permission:ubicaciones-permission',
        'controller' => 'ubicacionesController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplatelocations\Controllers',
    ]);

    $routes->post('ubicaciones/save'
            , 'UbicacionesController::save'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );
    
    $routes->post('ubicaciones/getUbicacionesAjax'
            , 'UbicacionesController::getUbicacionesAjax'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
            );


    $routes->post('ubicaciones/getUbicaciones'
            , 'UbicacionesController::getUbicaciones'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );

    $routes->post('ubicaciones/getColoniaSATAjax'
            , 'UbicacionesController::getColoniasSAT'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );

    $routes->post('ubicaciones/getLocalidadSATAjax'
            , 'UbicacionesController::getLocalidadSAT'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );

    $routes->post('ubicaciones/getPaisesSATAjax'
            , 'UbicacionesController::getPaisesSAT'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );

    $routes->post('ubicaciones/getEstadosSATAjax'
            , 'UbicacionesController::getEstadosSAT'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );

    $routes->post('ubicaciones/getMunicipiosSATAjax'
            , 'UbicacionesController::getMunicipiosSAT'
            , ['namespace' => 'julio101290\boilerplatelocations\Controllers']
    );
});
