<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of arbolOrm
 *
 * @author sistemas09
 */
require_once 'ORM.php';

class arbolOrm extends Orm {
    //put your code here
    protected static $ns;
    protected static $pathfunction;
    
    /////////
    protected static $padre;
    public function __construct($data)
    {
        parent::__construct($data);
        if ($data && sizeof($data)) {
            $this->populateFromRow($data);
        }

    }
    public function populateFromRow($data) {
        $this->id=isset($data[static::$keyfield]) ? intval($data[static::$keyfield]) : null;
        $this->cnombre=isset($data['cnombre']) ? $data['cnombre'] : null;
        static::$padre=isset($data[static::$ns]) ? $data[static::$ns] : null;
    }	
    public function getnombre(){
        return $this->cnombre;
    }
    public function hijos($tipo){
       if ($tipo=='cliente') {
            $res=static::$database->execute('Select count(*) hijos from tsat_departamento where bcliente=1 and '.static::$ns.'='.$this->id, null,null);
            return $res[0]['hijos'];
        }
    }
    public function getPadre(){
        return $this->find($this->padre);
    }
    
    public function path(){
        //echo 'path';
        if($this->id!=null){
            $res=static::$database->execute('Select '.static::$pathfunction.'('.$this->id.') path', null,null);
            return $res[0]['path'];
        }
        else
            return '';
    }


    public static function selecttreelist($nombre, $valor, $sel ,$filtro='',$valor2='',$submit,$dp='',$clase=''){
//        echo 'filtro '.$filtro;
//        echo 'valor '.$valor;
        //var_dump($sel);
        //echo $nombre;
        //echo $valor;
        //echo '\''.$valor2.'\'';
        //var_dump($sel);
        
        if ($valor=='' && $valor2==''){
            $items = static::all(' ORDER by 2');
        }else{
            $fields[]=static::$ns;
            $fields[]=static::$activo;
            $fields[]=$filtro;
            $values[]=$valor;
            $values[]='1';
            $values[]=$valor2;
            $items = static::wheren($fields,$values,'2');
        }
        //var_dump($items);
        if ($items!=null){
            $result= "<select name='".$nombre."' onchange='".$submit."' data-rel='chosen'>";
            $result.= "<option value='-1' > Seleccione una opcion</option>";
            foreach($items as $item){
                $selected=(isset($sel) && ($sel->id==$item->id)) ? "selected='selected'" : "";
                $result.= "<option value='".$item->id."' ".$selected.">".$item->getnombre()."</option>";
            }
        }
        else{
            $result= "<select name='".$nombre."' onchange='this.form.submit()' data-rel='chosen'>";
            if( isset($sel))
                $result.= "<option value=".$sel->id." selected='selected'>".$sel->getnombre()."</option>";
        }
        if ($sel!=null){
            if (($dp=='') ){
                if ($items!=null){ 
                    $result=$sel->path().$result;
                }else{
                    $result=(($sel->getPadre()!=null)?$sel->getPadre()->path():'').$result;
                }
            }
        }   
        $result.= '</select>';
        
        return $result;
    }
    
    
 
}
