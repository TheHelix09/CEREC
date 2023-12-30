<?php 
date_default_timezone_set('America/Chihuahua');
//error_reporting(-1);
require_once 'config/global.php';
require_once 'control/Controlador.php';
require_once 'control/controladorFrontal.php';
cargaControladores();
session_start();
if(isset($_GET['controller'])&&isset($_GET['action'])){
    $controladorObj=cargarControlador($_GET['controller']);
    lanzarAccion($controladorObj);
}else{ 
    $controladorObj=cargarControlador(CONTROLADOR_DEFECTO);
    lanzarAccion($controladorObj);
}
?>