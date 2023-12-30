<?php



class Database {  
    private $provider;
    private $params;
    private static $_con;
    public function __construct($provider, $host, $user, $pass, $db) {
        //require_once $provider.'.php';
        /*if (!class_exists($provider)) {
            throw new \Exception("The provider doesn't exists or it wasn't implemented");
        }*/
		try{
           $this->provider = new PDO("$provider:host=$host;dbname=$db", "$user", "$pass");
		   return $this->provider ;
		}catch(Exception $e){
           throw new \Exception("We couldn't connect to the database >> $db");
        } 
    }
    public static function getConnection($provider, $host, $user, $pass, $db) {
        if (self::$_con) {
            return self::$_con;
        } else {
            $class = __CLASS__;
            self::$_con = new $class($provider, $host, $user, $pass, $db);
            return self::$_con;
        }
    }
    public static function ClearConnection() {
        self::$_con=null;
    }

    private function replaceParams() {
        if (isset($this->params)){
			$b = current($this->params);
			next($this->params);
			return $b;
		}
    }
    private function prepare($sql, $params) {
        //var_dump($sql);
        //var_dump($params);
        $escaped = null;
        if ($params) {
            foreach ($params as $key => $value) {
                //echo $value.'<br>';
                if (is_bool($value)) {
                    $value = $value ? 1 : 0;
                } elseif (is_double($value)) {
                    $value = str_replace(',', '.', $value);
                } elseif (is_numeric($value)) {
                    if (is_string($value)) {
                        $value = "'$value'";
                    } 
                } elseif (is_null($value)) {
                    $value = "NULL";
                } else {
                    $value = "'$value'";
                }
                $escaped[] = $value; // donde se encuentra el error

            }
        }
        $this->params = $escaped;
        $q = preg_replace_callback("/(\?)/i", array($this, "replaceParams"), $sql);
        //echo $q;
        return $q;
    }
    private function sendQuery($q, $params) {
        $query = $this->prepare($q, $params);
        //echo $query;
        $result=$this->provider->prepare($query);
		$result->execute();
        //var_dump($result);
        if ($this->provider->errorInfo()>'') {
            error_log(implode(',',$this->provider->errorInfo()));
        }
        return $result;
    }
    public function executeScalar($q, $params = null) {
        $result = $this->sendQuery($q, $params);
        if (!is_null($result)) {
            if (!is_object($result)) {
                return $result;
            } else {
                $row = $result->fetch_all(PDO::FETCH_OBJ);
                return $row[0];
            }
        }
        return null;
    }
    public function execute($q, $array_index = null, $params = null) {
        //echo $q;
        //var_dump($params);
        $result = $this->sendQuery($q, $params);
        //var_dump($result);
        if ((is_object($result) || $result->rowCount() || $result) && ($result !== true && $result !== false)) {
            $arr = $result->fetchall(PDO::FETCH_OBJ);
			//var_dump($arr);
            /*while ($row = $result->fetchall(PDO::FETCH_OBJ)) {
  //              var_dump($row);
                if ($array_index) {
                    $arr[$row[$array_index]] = $row;
                } else {
                    $arr[] = $row;
                }
            }*/
            return $arr;
        }
        return $this->provider->errorInfo()>'' ? false : true;
    }
    
    public function changeDB($database) {
        $this->provider->changeDB($database);
    }
    public function getInsertedID() {
        return $this->provider->getInsertedID();
    }
    public function getError() {
        return $this->provider->getError();
    }
    public function disconnect(){
 //       $this->provider->disconnect();
    }
    public function __destruct() {
        $this->disconnect();
    }
}