<?php



class Orm
{

    protected static $database;
    protected static $table;
    protected static $keyfield;
    protected static $activo;
    protected static $descr;


    function __construct($data = null)
    {
        self::getConnection();

        if ($data) {
            $this->populateFromObject($data);
        }
    }

    public function populateFromObject($obj)
    {
        $this->data = $obj;
    }

    static function valida($post)
    {
        return true;
    }


    public static function getConnection()
    {
        require_once('Database.php');
        self::$database = \Database::getConnection("mysql", "localhost", "root", "", "papelerama"); //MySqlProvider
    }


    public static function find($id)
    {
        //echo $id;
        $results = self::where(static::$keyfield, $id);
        //     var_export($results);
        return $results[0];
    }

    public static function where($field, $value, $order = '')
    {
        $obj = array();
        self::getConnection();
        $w = ($value == 'null') ? ' is null ' : ' = ?';
        $o = ($order == '') ? '  ' : ' order by ' . $order;
        $query = "SELECT * FROM " . static::$table . " WHERE " . $field . $w . $o;
        //  if( $field=='ccurp');
        //var_dump($query);
        $results = self::$database->execute($query, null, array(($value != null) ? $value : 0));
        if ($results) {
            $class = get_called_class();
            for ($i = 0; $i < sizeof($results); $i++) {
                $obj[] = new $class($results[$i]);
            }
        }
        return $obj;
    }

    public static function wheredistintc($field, $value, $campos, $order = '')
    {
        $obj = null;
        self::getConnection();
        $w = ($value == 'null') ? ' is null ' : ' = ?';
        $o = ($order == '') ? '  ' : ' order by ' . $order;
        $query = "SELECT distinct $campos FROM " . static::$table . " WHERE " . ((static::$bactivo == '') ? '' : static::$bactivo . '=1 and ') . $field . $w . $o;
        //var_dump($query);
        $results = self::$database->execute($query, null, array(($value != null) ? $value : 0));

        if ($results) {
            $class = get_called_class();
            for ($i = 0; $i < sizeof($results); $i++) {
                $obj[] = new $class($results[$i]);
            }
        }
        return $obj;
    }

    public static function execute($sentencia)
    {
        if (!isset(self::$database))
            static::getConnection();
        return self::$database->execute($sentencia);
    }

    public static function all($order = null)
    {
        $objs = null;
        if (!isset(self::$database)) {
            //echo 'is null';
            self::getConnection();
        }
        $query = "SELECT * FROM " . static::$table;
        if ($order) {
            $query .= $order;
        }
        //  var_dump($query);
        $results = self::$database->execute($query, null, null);
        //var_dump($results);
        //echo self::$database->getError();
        if ($results) {
            $class = get_called_class();
            foreach ($results as $index => $obj) {
                $objs[] = new $class($obj);
            }
        }
        //self::$database->ClearConnection();
        return $objs;
    }

    public function save()
    {
        $values = get_object_vars($this);
        $filtered = null;
        foreach ($values as $key => $value) {
            if ($value !== null && $value !== '' && strpos($key, 'obj_') === false && $key !== 'id' && strpos($key, 'm_') === false) {
                if ($value === false) {
                    $value = 0;
                }
                $filtered[$key] = $value;
            }
        }
        $columns = array_keys($filtered);
        if ($this->id) {
            $columns = join(" = ?, ", $columns);
            $columns .= ' = ?';
            $query = "UPDATE " . static::$table . " SET $columns WHERE " . static::$keyfield . " =" . $this->id;
        } else {
            $params = join(", ", array_fill(0, count($columns), "?"));
            $columns = join(", ", $columns);
            $query = "INSERT INTO " . static::$table . " ($columns) VALUES ($params)";
        }
        $result = self::$database->execute($query, null, $filtered);
        if ($result) {
            $result = array('error' => false, 'message' => self::$database->getInsertedID());
        } else {
            $result = array('error' => true, 'message' => self::$database->getError());
        }
        return $result;
    }

    public static function selectlist($nombre, $filtro, $valor, $sel, $submit, $size = 12, $id = '', $campos = '*', $enabled = '')
    {
        //echo 'xx'.$filtro.'xx';
        if ($filtro == '') {
            if (static::$descr == '') {
                $items = static::all(' ORDER by 2');
            } else
                $items = static::all(' ORDER by ' . static::$descr);
        } else {
            //  echo '***'.static::$descr.'**';
            if ($campos == '*')
                if (static::$activo > '') {
                    //echo 1;                    
                    $items = static::wheren(array($filtro, static::$activo), array($valor, '1'), static::$descr);
                } else
                    $items = static::where($filtro, $valor, static::$descr);
            else
                $items = static::wheredistintc($filtro, $valor, $campos, static::$descr);
        }

        if ($id == '')
            $id = $nombre;
        //var_dump($items);

        $result = "<select name='" . $nombre . "' id='$id' onchange='$submit' style='font-size:" . $size . "px' data-rel='chosen' " . $enabled . " required='required'  >";
        $result .= "<option value='-1' > Seleccione una opcion</option>";
        if (count($items) > 0) {
            foreach ($items as $item) {
                $selected = (isset($sel) && ($sel->getid() == $item->getid())) ? "selected='selected'" : "";
                $result .= "<option value='" . $item->getid() . "' " . $selected . ">" . $item->getnombre() . "</option>";
            }
        }
        $result .= '</select>';
        //var_dump($result);
        return $result;
    }

    public static function wheren($fields, $values, $order = '')
    {
        $obj = null;
        self::getConnection();
        $w = '1=1 ';
        for ($i = 0; $i < count($fields); $i++) {
            if ($values[$i] == 'null') {
                $w .= ' and ' . $fields[$i] . ' is null ';
            } else if ($values[$i] != '' && $fields[$i] != '') {
                $w .= ' and ' . $fields[$i] . ' = ?';
                $values2[] = $values[$i];
            }

        }
        $o = ($order == '') ? '  ' : ' order by ' . $order;
        $query = "SELECT * FROM " . static::$table . " WHERE " . $w . $o;
        $results = self::$database->execute($query, null, isset($values2) ? $values2 : null);
        //echo $results;
        if ($results) {
            $class = get_called_class();
            for ($i = 0; $i < sizeof($results); $i++) {
                $obj[] = new $class($results[$i]);
            }
        }
        return $obj;
    }

    public static function wherelike($field, $value, $order = '')
    {
        $obj = null;
        self::getConnection();
        $w = ($value == 'null') ? ' is null ' : ' like ? ';
        $w = $w . ((static::$activo == '') ? '' : (' and ' . static::$activo . '=1'));
        $o = ($order == '') ? '  ' : ' order by ' . $order;
        $query = "SELECT * FROM " . static::$table . " WHERE " . $field . $w . $o;
        $results = self::$database->execute($query, null, array(($value != null) ? $value : 0));
        if ($results) {
            $class = get_called_class();
            for ($i = 0; $i < sizeof($results); $i++) {
                $obj[] = new $class($results[$i]);
            }
        }
        return $obj;
    }

}
