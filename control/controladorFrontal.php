<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controladorFrontal
 *
 * @author sistemas09
 */
function cargacontroladores() {
    foreach (glob("db/*.php") as $file) {
        require_once $file;
    }
    foreach (glob("Model/*.php") as $file) {
        require_once $file;
    }

    foreach (glob("control/*.php") as $file) {
        require_once $file;
    }
}

function cargarControlador($controller) {
    cargacontroladores();
    $controlador = ucwords($controller) . 'Controller';
    $controlador = str_replace('/', '\\', $controlador);
    $controladorf = str_replace('\\', '/', $controlador);
    $strFileController = 'control/' . $controladorf . '.php';
    $controlador = '\\control\\' . $controlador;

         //echo $strFileController; 

    if (!is_file($strFileController)) {
        $strFileController = 'control/' . ucwords(CONTROLADOR_DEFECTO) . 'Controller.php';
    }

    //require_once $strFileController;
    $controllerObj = new $controlador();
    return $controllerObj;
}

function cargarAccion($controllerObj, $action) {
    //session_start();
    $accion = $action;
    $controllerObj->$accion();
}

function lanzarAccion($controllerObj) {
    if (isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])) {
        cargarAccion($controllerObj, $_GET["action"]);
    } else {
        cargarAccion($controllerObj, ACCION_DEFECTO);
    }
}
