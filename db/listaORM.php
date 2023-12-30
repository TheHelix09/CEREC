<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * Description of listaORM
 *
 * @author sistemas09
 */

//require_once('./system/indicadores.php');
class listaORM extends Orm{
    
    protected static $table='';
    protected static $keyfield='';
    protected static $idbase;
    protected static $clase='';
    protected static $namespace='';
    
    public $lista;
    public $id;
    protected static $masterTable='tinve_solicitud';
    protected static $masterid='idsolicitud';
    
    function __construct($filtro){
        //var_dump($filtro);
        parent::__construct(null);
        $this->cargar($filtro);
        //var_dump($this->lista);
    }

    public function count(){
        return count($this->lista);
    }
    public function cargar($value){
        $this->id=$value;
        $this->getConnection();
        $query = "SELECT ".static::$idbase." FROM " . static::$table." where ".static::$keyfield.' = '.$value .(isset(static::$activo)?' and '.static::$activo.'=1':'');
        //echo $query;
        $results = self::$database->execute($query, null, null);
        if ($results) {
            foreach ($results as $index => $obj) {
                //echo '<br>'.$obj[static::$idbase];
                $file='./'.static::$namespace.'/'.static::$clase;
                $className='\\'.static::$namespace.'\\'.static::$clase;    
                //echo $className;
                //echo $file;
                require_once( $file.'.php');
                $this->lista[]= $className::find($obj[static::$idbase]);
                
            }
        }
    }
    
    public static function find($id) {
        $results = self::where(isset(static::$masterid)?(static::$masterid):static::$keyfield, $id);
             
        return $results[0];
    }
    
    
    //devuelve un arreglo de objetos de la clase
    public static function where($field, $value, $order='') {
        $obj = null;
        self::getConnection();
        $w=($value=='null')?' is null ':' = ?';
        $o=($order=='')?'  ':' order by '.$order;
        $query = "SELECT * FROM " . (isset(static ::$masterTable)?(static ::$masterTable):static ::$table) . " WHERE " . $field .$w .$o;
         //echo get_called_class().'<br>'.$query;
//        echo '<br>'.$value;
        $results = self::$database->execute($query, null, array(($value!=null)?$value:0));
        if ($results) {
            $class = get_called_class();
            for ($i = 0;$i < sizeof($results);$i++) {
//                echo 'xxxxx';
                $obj[] = new $class($results[$i]);
            }
  //          var_dump($results);
        }
        return $obj;

    }

}
