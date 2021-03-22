<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 21/feb/2017
 * Time: 06:14 PM
 */

abstract class Tabla extends Conexion
{
    /**
     * cbiz constructor.
     */
    function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ALL ^ (MYSQLI_REPORT_INDEX));
        $config = Globales::getConfig()->conexion;
        $token = $_SESSION['token'] ?? $config->default_database;
        Conexion::$host = $config->host;
        Conexion::$db = "{$config->prefix}$token";
        Conexion::$user = $config->user;
        Conexion::$pass = $config->password;
        Globales::setToken($token);
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)) {
            Globales::mensaje_error("La función $name en la clase " . get_class($this) . " no existe");
        }
    }

    /**
     * Agrega las columnas faltantes a partir de la plantilla de creacion de tabla
     * @param $tabla
     * @return int|mysqli_result|null
     * @throws Exception
     */
    function modify_table($tabla)
    {
        $tabla = strtolower($tabla);
        $consulta = null;
        $metadata = Globales::getConfig()->conexion;
        $create_table =
            str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS",
                str_replace($tabla, "temp_" . $tabla,
                    $this->create_table()));
        $consulta_create = $this->multiconsulta($create_table);
        $verificar = !is_null($consulta_create);
        if (!$verificar)
            Globales::mensaje_error("No se creo tabla temporal [modify_table]");
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  c1.column_name columna,
  COLUMN_TYPE    tipo,
  COLUMN_DEFAULT defaultColumna,
  COLUMN_COMMENT comment,
  IS_NULLABLE    nullable
FROM information_schema.COLUMNS c1
WHERE c1.table_name = 'temp_$tabla'
      AND c1.table_schema = '{$metadata->prefix}$_SESSION[token]'
      AND COLUMN_NAME NOT IN (
  SELECT column_name
  FROM information_schema.COLUMNS
  WHERE table_name = '$tabla'
        AND table_schema = '{$metadata->prefix}$_SESSION[token]');
MySQL;
        $columnas = $this->query2array($this->consulta($sql));

        foreach ($columnas as $columna) {
            $columna['defaultColumna'] = strpos($columna['tipo'], "varchar") === false ? $columna['defaultColumna'] : "'$columna[defaultColumna]'";
            $default = !is_null($columna['defaultColumna']) ? "DEFAULT $columna[defaultColumna]" : "";
            $notnull = $columna['nullable'] == "YES" ? "" : "NOT NULL";
            $sql = /** @lang MySQL */
                <<<MySQL
ALTER TABLE $tabla
  ADD $columna[columna] $columna[tipo] $default
COMMENT '$columna[comment]' $notnull;
MySQL;
            $consulta = $this->consulta($sql);
        }
        $drop = "drop table temp_$tabla;";
        $this->consulta($drop);
        return $consulta;
    }

    /**
     * @return string regresar el texto de la consulta de la creacion de la tabla
     */
    abstract function create_table();
}

abstract class cbizcontrol extends Conexion
{
    /**
     * @return string regresar el texto de la consulta de la creacion de la tabla
     */
    abstract function create_table(): string;

    /**
     * cbizcontrol constructor.
     */
    function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ALL ^ (MYSQLI_REPORT_INDEX));
        $config = Globales::getConfig()->conexion;
        Conexion::$db = "e11_cbizcontrol";
        Conexion::$host = $config->host;
        Conexion::$user = $config->user;
        Conexion::$pass = $config->password;
    }
}

/**
 * Class Conexion
 */
abstract class Conexion
{
    static $host, $db, $user, $pass;
    /** @var mysqli $conexion */
    static private $conexion;
    /** @var \EMysqli\EMysqli $mysqli */
    private static $mysqli;
    private $retry;
    private $error = false;

    /**
     * @param string $sql
     * @param array $params
     * @return int|mysqli_result
     * @throws Exception
     */
    protected function consulta($sql, $params = [])
    {
        $this->retry = true;
        $resultado = null;
        while ($this->retry) {
            try {
                $this->conectar();
                if (!empty($params)) {
                    //Necesario para ejecutar $stmt->fullQuery
                    $stmt = $this->error ? mysqli_prepare(self::$conexion, $sql) : self::$mysqli->prepare($sql);
                    /* use call_user_func_array, as $stmt->bind_param('s', $param); does not accept params array */
                    foreach ($params as $k => &$param) {
                        $array[] =& $param;
                    }
                    call_user_func_array(array($stmt, 'bind_param'), $array);
                    $execute = $stmt->execute();
                    $fullQuery = $stmt->fullQuery;
                    /** @var string $fullQuery Debug */
                    $resultado = $stmt->get_result();
                    if ($execute)
                        if (is_bool($resultado) and !$resultado) {
                            $resultado = $stmt->insert_id;
                        }
                } else {
                    $resultado = mysqli_query(self::$conexion, $sql);

                    if (is_bool($resultado) and $resultado != false) {
                        $resultado = mysqli_insert_id(self::$conexion);
                    }
                }
                $this->retry = false;
            } catch (mysqli_sql_exception $ex) {
                switch ($ex->getCode()) {
                    case 2002:
                        Globales::mensaje_error("[2002] Error de conexion. Verifique si está conectado a internet.");
                        break;
                    default:
                        $this->handleErrors($ex, $sql);
                        break;
                }
            }
        }
        $this->desconectar();
        return $resultado;
    }

    protected function conectar()
    {
        try {
            self::$conexion = new mysqli(self::$host, self::$user, self::$pass, self::$db);
            self::$mysqli = new EMysqli\EMysqli(self::$host, self::$user, self::$pass, self::$db);

            if (!self::$conexion) Globales::mensaje_error('Error de conexion. [' . self::$db . ']');
        } catch (mysqli_sql_exception $ex) {
            Globales::mensaje_error($ex->getMessage(), 500);
        }
    }

    /**
     * @param Exception $ex
     * @param $sql
     * @throws Exception
     */
    private function handleErrors($ex, $sql)
    {
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $trace = $ex->getTrace();
        /** @var Tabla $this */
        switch ($code) {
            case 1005:
                $this->retry = false;
                $token = strtolower($_SESSION['token']);
                $message = str_replace("'", "", str_replace("Can't create table 'e11_$token.", "", $message));
                $explode = explode(" (errno: ", $message);
                $table = $explode[0];
                $errno = str_replace(")", "", $explode[1]);
                switch ($errno) {
                    case 150: #Foreign Key
                        $foreignTable = explode("REFERENCES ", $sql);
                        unset($foreignTable[0]);
                        foreach ($foreignTable as $tabla) {
                            $explode = explode(" ", $tabla);
                            $tabla = str_replace("`", "", $explode[0]);
                            $tabla = trim($tabla, "_");
                            $namet = "Tabla$tabla";
                            $t = new $namet();
                            $sql = $t->create_table();
                            $consulta = $this->multiconsulta($sql);
                            $verificar = is_null($consulta);
                            if ($verificar) {
                                $this->retry = false;
                                Globales::mensaje_error("No se creo la tabla $tabla");
                            }
                        }
                        break;
                }
                Globales::mensaje_error("No se creo la tabla $table [ForeignKey]", 200, $sql);
                break;
            case 1146:
                /** @var Tabla $table */
                $token = strtolower($_SESSION['token']);
                $table = trim(strstr(preg_replace("/Table \'(.+)\' doesn\'t exist/", '$1', $message), '.'), '.');

                //Linea para evitar recursividad infinita
                $recursive = strpos($sql, "CREATE TABLE") !== false ? true : false;
                if ($recursive) {
                    $this->retry = false;
                    return;
                }
                $modelo = new Modelo();
                $consulta = $this->multiconsulta($modelo->$table->create_table());
                $verificar = is_null($consulta);
                if ($verificar) {
                    $this->retry = false;
                    Globales::mensaje_error("No se creo la tabla $table");
                } else $this->retry = true;
                break;
            case 1054:
                $table = strtolower(str_replace("distribuidor\\", "", str_replace("Tabla", "", get_class($this))));
                /** @var Tabla $this */
                $consulta = $this->modify_table($table);
                if (is_null($consulta)) {
                    Globales::mensaje_error("Error $code. $message");
                }
                $this->retry = false;
                break;
            case 1060:
                $this->retry = false;
                break;
            case 1064:
                /** Error de sintaxis */
                $this->retry = false;
                Globales::mensaje_error("Error 1064. Contacte al desarrollador. [{$trace[2]['class']}][{$trace[2]['function']}]");
                break;
            case 2002:
                /** Error de conexion */
                $this->retry = true;
                Globales::mensaje_error("[2002] Verifique su conexion.");
                break;
            case 2006:
                $this->retry = true;
                mysqli_ping(self::$conexion);
                Globales::mensaje_error('Error 2006. Intente de nuevo. [MySQL Server Has Gone Away]');
                break;
            case 2014:
                $this->error = true;
                break;
            default:
                $this->retry = false;
                Globales::mensaje_error("Error $code. $message", 500);
                break;
        }
    }

    /**
     * @param $sql
     * @return bool|mysqli_result
     * @throws Exception
     */
    protected function multiconsulta($sql)
    {
        $this->retry = true;
        $resultado = null;
        while ($this->retry) {
            try {
                $this->conectar();
                $multi = mysqli_multi_query(self::$conexion, $sql);
                do {
                    null;
                } while (mysqli_more_results(self::$conexion) && mysqli_next_result(self::$conexion));
                $resultado = mysqli_store_result(self::$conexion);
                $this->retry = false;
            } catch (mysqli_sql_exception $ex) {
                $this->handleErrors($ex, $sql);
            }
        }
        $this->desconectar();
        return $resultado;
    }

    protected function desconectar()
    {
        mysqli_close(self::$conexion);
    }

    /**
     * @param mysqli_result $consulta
     * @return null|object
     */
    protected function siguiente_registro($consulta)
    {
        return mysqli_fetch_object($consulta);
    }

    protected function query2array($result, $name = false, $index = "id")
    {
        $array = array();
        foreach ($result as $item) {
            if (!$name)
                array_push($array, $item);
            else
                $array[$item[$index]] = $item[$name];
        }
        return $array;
    }

    /**
     * @param mysqli_result $consulta
     * @return array
     */
    protected function query2multiarray($consulta)
    {
        $results = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
        return $results;
    }
}
