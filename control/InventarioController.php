<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace control;

/**
 * Description of InventarioController
 *
 * @author miguel.acosta
 */
class InventarioController extends \Controlador{
    
    public  function login(){
        $this->view('seguridad/login',array());
    }
    public function validar(){
        $u=\usuario::valida($_POST);
        if ($u>0){
          $_SESSION['usuario']=$u;
          echo 'correcto';
        }else {
          echo 'permiso denegado';
        }
    }
    public function listado(){
        //$u=  \usuario::find($_SESSION['usuario']);
        //$modulos=$u->modulos(isset($_GET['st'])?'='.$_GET['st']:' is null ');
        //$this->view('menu', array('modulos'=>$modulos, 'usuario'=>$u));
        $this->view('Inventario', array());
    }
    
}
