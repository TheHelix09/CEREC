<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AyudaVistas
 *
 * @author sistemas09
 */

class AyudaVistas{
     
    public function url($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
        $urlString='index.php?controller='.$controlador.'&action='.$accion;
        return $urlString;
    }
     
    //Helpers para las vistas
}
