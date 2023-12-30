<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controlador
 *
 * @author sistemas09
 */
class Controlador{
 
     
    //Plugins y funcionalidades
     
/*
* Este método lo que hace es recibir los datos del controlador en forma de array
* los recorre y crea una variable dinámica con el indice asociativo y le da el 
* valor que contiene dicha posición del array, luego carga los helpers para las
* vistas y carga la vista que le llega como parámetro. En resumen un método para
* renderizar vistas.
*/
    public function view($vista,$datos){
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
         
        require_once 'control/AyudaVistas.php';
        $helper=new AyudaVistas();
     
        require_once 'view/'.$vista.'View.php';
    }
    
    function get_include_contents($filename) {
    }

    public function viewvar($vista,$datos){
        if (is_file('view/'.$vista.'View.php')) {
            ob_start();
            foreach ($datos as $id_assoc => $valor) {
                ${$id_assoc}=$valor; 
            }
         
            require_once 'control/AyudaVistas.php';
            $helper=new AyudaVistas();
            include 'view/'.$vista.'View.php';
     
            $var = ob_get_contents();
            ob_end_clean();
            return $var;
        }
    }


    
    public function redirect($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
        /*var_dump($controlador);
        var_dump($accion);*/
        
        header("Location:index.php?controller=".$controlador."&action=".$accion);
        exit();
    }
}
