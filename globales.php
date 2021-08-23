<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 20/feb/2017
 * Time: 05:03 PM
 */

use Mpdf\Mpdf;

/**
 * @property array monthNames
 * @property string formatoFecha
 * @property string btnNuevo
 * @property string btnEditar
 * @property string btnEliminar
 * @property string btnRegistrar
 * @property string btnGuardar
 * @property string btnProyectar
 * @property string btnSuscripcion
 * @property string lblEgresos
 * @property string lblIngresos
 * @property string lblTraspasos
 * @property string lblTransacciones
 * @property string lblTitulo
 * @property string lblSubtitulo
 * @property string lblIngresosTotales
 * @property string lblEgresosTotales
 * @property string lblEmail
 * @property string lblDistribuidor
 * @property string lblNombre
 * @property string lblApellidoP
 * @property string lblApellidoM
 * @property string lblTelefono
 * @property string lblPassword
 * @property string lblRePassword
 * @property string lblTotal
 * @property string lblReestablecer
 * @property string lblSinCategoria
 * @property string lblReseller
 * @property string lblCuentaOrigen
 * @property string lblCuentaDestino
 * @property object topmenu
 * @property object sidebar
 * @property object acciones
 * @property object modulos
 * @property string lblUsuario
 * @property string lblCorreo
 * @property string lblPerfil
 * @property string btnCancelar
 */
class Globales
{
    static $modulo, $namespace;
    static private $idioma, $permisos, $token;

    static function getVersion()
    {
        if (file_exists(HTTP_PATH_ROOT . "release.txt")) {
            $file = HTTP_PATH_ROOT . "release.txt";
            $version = file_get_contents($file);
        } else {
            $file = "../framework/version.txt";
            if (file_exists("../.git/HEAD")) {
                $head = file_get_contents("../.git/HEAD");
                $explode = explode("/", $head);
                $version = end($explode);
                $type = str_replace("\n", "", $explode[2] == $version ? $version : $explode[2]);
                switch ($type) {
                    case "":
                    case "develop":
                    case "feature":
                    case "master":
                        $version = file_get_contents($file);
                        break;
                    case "release":
                        $version = trim(preg_replace('/\s\s+/', ' ', $version));
                        file_put_contents($file, $version);
                        break;
                    default:
                        unlink($file);
                        file_put_contents($file, $version);
                        break;
                }
            } else {
                $version = file_get_contents($file);
            }
        }
        return $version;
    }

    /**
     * @param string $modulo
     * @return array|object
     */
    static function getIdioma($modulo)
    {
        return self::$idioma->$modulo;
    }

    /**
     * @param array $idioma
     */
    static function setIdioma($idioma)
    {
        self::$idioma = $idioma;
    }

    /**
     * @param object $permisos
     */
    static function setPermisos($permisos)
    {
        self::$permisos = $permisos;
    }

    /**
     * @param string $modulo
     * @return object
     */
    static function getPermisos($modulo)
    {
        if ($_SESSION[perfil] == 0)
            $permisos = array("nuevo" => 1, "editar" => 1, "eliminar" => 1);
        else {
            $permisos = self::search_in_multi(self::$permisos, "modulo", $modulo);
            $permisos = array_column($permisos, "estatus", "accion");
        }
        return (object)$permisos;
    }

    /**
     * @param array $array
     * @param string $key
     * @param string $value
     * @return array
     */
    static function search_in_multi($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, self::search_in_multi($subarray, $key, $value));
            }
        }

        return $results;
    }

    /**
     * @param array $array
     * @return false|string
     */
    static function json_encode(array $array)
    {
        $json = json_encode($array);
        $error = json_last_error();
        switch ($error) {
            case 0:
                //No Error
                break;
            case 5:
                //Malformed UTF-8 characters, possibly incorrectly encoded
                array_walk_recursive($array, function (&$item) {
                    $item = utf8_encode($item);
                });
                $json = json_encode($array);
                break;
            default:
                $json = json_last_error_msg();
                break;
        }
        return $json;
    }

    /**
     * @param string $json
     * @param bool $assoc
     * @return object|array
     */
    static function json_decode(string $json, bool $assoc = true)
    {
        $json = json_decode($json, $assoc);
        $error = json_last_error();
        switch ($error) {
            case 0:
                //No Error
                break;
            case 5:
                //Malformed UTF-8 characters, possibly incorrectly encoded
                array_walk_recursive($json, function (&$item) {
                    $item = utf8_encode($item);
                });
                $json = json_decode($json, $assoc);
                break;
            default:
                $json = json_last_error_msg();
                break;
        }
        return $json;
    }

    /** @var Exception $ex */
    static function mostrar_exception($ex)
    {
        $code = $ex->getCode() ?: 500;
        ini_set('log_errors', 1);
        $token = $_SESSION['token'];
        ini_set('error_log', "script_errors_$token.log");
        $trace = $ex->getTrace();
        $error = addslashes($token . " " . $_SESSION['modulo'] . " " . $trace[2]['file'] . " " . $trace[2]['line'] . " " . $ex->getMessage());
        $error2 = addslashes(preg_replace("/\r|\n/", "", print_r($ex, true)));
        error_log($error);
        http_response_code($code);
        if (isset($_POST['fn']) or ($_GET['aside'] ?? null)) {
            ob_end_clean();
            header('Content-Type: application/json');
            die(json_encode([
                'code' => $code,
                'message' => $ex->getMessage(),
                'data' => [
                    'trace' => $ex->getTrace(),
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine()
                ]
            ]));
        } else {
            include "vista/error.phtml";
        }
    }

    /**
     * @param string $password
     * @return string
     */
    static function crypt_blowfish_bydinvaders($password)
    {
        return password_hash($password, CRYPT_BLOWFISH);
    }

    static function array2json($array)
    {
        $json = json_encode($array);
        return $json;
    }

    /**
     * @param string $datetime 'Y-m-d'
     * @param int $time_add
     * @param string $interval
     * @param string $format
     * @return string
     */
    static function datetime_add($datetime, $time_add, $interval, $format)
    {

        switch ($interval) {
            case 'years':
                $spec = "P{$time_add}Y";
                break;
            case 'months':
                $spec = "P{$time_add}M";
                break;
            case 'days':
                $spec = "P{$time_add}D";
                break;
            case 'weeks':
                $spec = "P{$time_add}W";
                break;
            case 'hours':
                $spec = "PT{$time_add}H";
                break;
            case 'minutes':
                $spec = "PT{$time_add}M";
                break;
            case 'seconds':
                $spec = "PT{$time_add}S";
                break;
            default:
                $spec = "PT";
                break;
        }

        $time = new DateTime($datetime);
        $time->add(new DateInterval($spec));
        return $time->format($format);
    }

    /**
     * @param string $path
     * @return object
     */
    static function get_json_to_array($path)
    {
        if (!file_exists($path))
            self::mensaje_error("No existe el archivo $path");
        $json = file_get_contents($path);
        return json_decode($json, true);
    }

    /**
     * @param string $mensaje
     * @param int $code
     * @param null $sql
     * @throws Exception
     */
    static function mensaje_error($mensaje, $code = 400, $sql = null)
    {
        if (!is_null($sql)) {
            $sql = addslashes(preg_replace("/\r|\n/", "", $sql));
            echo "<script>console.log('$sql')</script>";
        }
        throw new Exception($mensaje, $code);
    }

    /**
     * @param mysqli_result $consulta
     * @return object
     * @deprecated
     */
    static function query2object($consulta)
    {
        $array = array();
        foreach ($consulta as $item) {
            foreach ($item as $key => $value) {
                $val = next($item);
                if ($val === false) continue;
                $array[$value] = $val;
            }
        }
        return (object)$array;
    }

    /**
     * @param mysqli_result $consulta
     * @return object
     * @deprecated
     */
    static function query2twoLevelObject($consulta)
    {
        $object = (object)array();
        foreach ($consulta as $item) {
            foreach ($item as $key => $arrayKey) {
                $valueKey = next($item);
                $value = next($item);
                if ($value === false) continue;
                $object->$arrayKey->$valueKey = $value;
            }
        }
        return $object;
    }

    /**
     * @param string $formato
     * @param string $fecha
     * @return string
     * @throws Exception
     * @deprecated usar formato_fecha2
     */
    static function formato_fecha($formato, $fecha)
    {
        try {
            if ($fecha == null) return null;
            $date = date_create_from_format('Y-m-d', $fecha);
            if ($date != false)
                return $date->format($formato);
            else {
                $date = strtotime($fecha);
                return date($formato, $date);
            }
        } catch (Exception $ex) {
            Globales::mensaje_error($ex->getMessage());
        }
    }

    /**
     * @param string $formato %#d/%#m/%Y %I:%M%P http://php.net/manual/es/function.strftime.php
     * @param string $fecha
     * @param bool $locale
     * @param string $formatoOrig
     * @param bool $time
     * @return string
     * @throws Exception
     */
    static function formato_fecha2($formato, $fecha, $locale = false, $formatoOrig = 'Y-m-d', $time = false)
    {
        if ($time) $formatoOrig .= ' H:i:s';
        try {
            if (is_null($fecha)) return null;
            $date = date_create_from_format($formatoOrig, $fecha);
            if ($date != false and !$locale)
                return $date->format($formato);
            else {
                setlocale(LC_TIME, "es_ES");
                $date = strtotime($fecha);
                $formated = strftime($formato, $date);
                return $formated;
            }
        } catch (Exception $ex) {
            Globales::mensaje_error($ex->getMessage());
        }
    }

    static function idioma_fecha($fecha, $hora = false)
    {
        $fecha = substr($fecha, 0, 10);
        $time = $hora ? date('h:ia', strtotime($fecha)) : "";
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return "$nombreMes $numeroDia, $anio $time";
    }

    static function idioma_fecha_comprimida($fecha)
    {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        $nombredia = substr($nombredia, 0, 3);
        $nombreMes = substr($nombreMes, 0, 3);
        return $nombredia . " " . $numeroDia . " " . $nombreMes;
    }

    static function idioma_fecha_completa($fecha)
    {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        $nombreMes = substr($nombreMes, 0, 3);
        return $nombredia . " " . $numeroDia . " de " . $nombreMes . ".";
    }


    /**
     * @param string $simbolo
     * @param double $cantidad
     * @param float|int $suma
     * @return string
     */
    static function formato_moneda($simbolo, &$cantidad, $suma = 0)
    {
        $cantidad += $suma;

        $sign = '';
        if ($cantidad < 0) {
            $cantidad = abs($cantidad);
            $sign = '- ';
        }

        $cantidad = $sign . $simbolo . number_format($cantidad, 2);
        return $cantidad;
    }

    static function setVista()
    {
        if ($_POST['fn'] === 'loadFilePond') {
            $path = $_GET['file'];

            if (file_exists($path)) {
                $mime_content_type = mime_content_type($path);
                header('Content-Disposition: inline; filename="' . basename($path) . '"');
                header('Content-Type: ' . $mime_content_type);
                header('Content-Length: ' . filesize($path));
                readfile($path);
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            }
            exit;
        } else if ($_GET['file'] ?? null) {
            $folder = $_GET['folder'] ?: 'imagenes';
            $token = $_SESSION['token'];
            $path = "usuario/$token/$folder/$_GET[modulo]/";
            $nombreImagen = self::subirImagenSimple($path, $_FILES["file"]);
            if ($nombreImagen != false) echo $nombreImagen;
            exit;
        } else {
            if (isset($_GET['tryit'])) {
                if (isset($_SESSION['usuario']))
                    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
                $json = json_encode($_REQUEST, JSON_FORCE_OBJECT);
                print "<script>getVars = $json;</script>";
            }
            if (self::$modulo != "registro")
                if (isset($_SESSION["usuario"]))
                    self::$modulo = empty($_POST["vista"]) ? self::$modulo : $_POST["vista"];
                else
                    self::$modulo = "login";

            if (isset($_POST["vista"])) {
                if (!empty($_POST["accion"])) {
                    $vista = $_POST["accion"];
                } elseif (empty($_POST["vista"])) {
                    $vista = $_SESSION["modulo"];
                } else {
                    $vista = $_POST["vista"];
                }

                if (!empty($vista)) {
                    self::$modulo = $vista;
                }
                if (!empty($_POST["post"])) {
                    $_SESSION["post"] = $_POST["post"];
                }
                $_SESSION["modulo"] = self::$modulo;
                die(true);
            }
        }
    }

    static function base64_to_jpeg($base64, $archivo_salida)
    {
        $ifp = fopen($archivo_salida, 'wb');

        $data = explode(',', $base64);
        fwrite($ifp, base64_decode($data[1]));

        fclose($ifp);

        return $archivo_salida;

    }


    /**
     * @param string $carpeta
     * @param array $archivo
     * @return string
     */
    static function subirImagenSimple(string $carpeta, array $archivo)
    {
        try {
            $debug = print_r($archivo, true);
            if (is_null($archivo)) Globales::mensaje_error("No se subio el archivo " . $debug);

            if (!empty($_GET['nombre'])) $name = str_replace(basename($archivo["name"]), $_GET['nombre'], $archivo["name"]);
            else
                $name = $_SESSION['token'] . "_" . date('YmdHis') . "_" . basename($archivo["name"]);
            $carpeta = __DIR__ . '/' . APP_ROOT . trim($carpeta, '/') . '/';
            if (!file_exists($carpeta))
                mkdir($carpeta, 0777, true);
            if (is_dir($carpeta) && is_writable($carpeta)) {
                if (!move_uploaded_file($archivo['tmp_name'], $carpeta . $name)) {
                    switch ($archivo['error']) {
                        case 1:
                            $max = ini_get('upload_max_filesize');
                            Globales::mensaje_error("El archivo excede el tamaño establecido ($max): " . $debug);
                            break;
                        default:
                            Globales::mensaje_error("Error al subir el archivo ($archivo[tmp_name]) en la ruta: $carpeta$name " . $debug);
                            break;
                    }
                }
            } else {
                Globales::mensaje_error('Upload directory is not writable, or does not exist.');
            }
            return $name;
        } catch (Exception $ex) {
            ini_set("display_errors", 'On');
            ini_set('error_log', 'script_errors.log');
            ini_set('log_errors', 'On');
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            http_response_code(500);
            $msg = $ex->getMessage();
            error_log($msg);
            exit($msg);
        }
    }

    static function getToken()
    {
        if (empty($_SESSION['token'])) {
            $config = self::getConfig();
            self::setToken($config->conexion->default_database);
        }
        return self::$token;
    }

    public static function getConfig(bool $object = true)
    {
        $env = file_exists(__DIR__ . '/' . APP_ROOT . "config.dev.json") ? "dev" : "prod";

        $ruta = __DIR__ . '/' . APP_ROOT . "config.$env.json";
        if (!file_exists($ruta)) {
            $ruta = HTTP_PATH_ROOT . "config.$env.json";
            if (!file_exists($ruta)) {
                $ruta = __DIR__ . '/' . APP_ROOT . "config.json";
                if (!file_exists($ruta)) {
                    Globales::mensaje_error("No existe el archivo de configuración $ruta", 500);
                }
            }
        }
        if ($object) {
            $config = self::get_json_to_object($ruta);
        } else {
            $config = self::get_json_to_array($ruta);
        }
        return $config;
    }

    /**
     * @param string $path
     * @return object
     * @throws Exception
     */
    static function get_json_to_object($path)
    {
        if (!file_exists($path))
            self::mensaje_error("No existe el archivo $path");
        $json = file_get_contents($path);
        return json_decode($json, false);
    }

    static function setToken($token)
    {
        self::$token = $token;
        $_SESSION['token'] = self::$token;
    }

    /**
     * @param string $modulo
     */
    static function setControl($modulo = null)
    {
        if (isset($_GET["aside"])) $modulo = $_REQUEST["asideModulo"] . "/" . $_REQUEST["asideAccion"];
        $control = explode("/", $modulo)[0];

        if ($_SESSION["namespace"] != self::$namespace) {
            session_unset();
            $_SESSION['modulo'] = "login";
            $_SESSION["namespace"] = self::$namespace;
            header("Refresh:0");
            exit;
        }

        $namespace = Globales::$namespace;
        $_SESSION["namespace"] = $namespace;
        $clase = APP_NAMESPACE . $control;

        if (class_exists($clase)) new $clase();
        else new $control();
    }

    static function generar_pdf($ruta, $stylesheets, $html, $watermark = false)
    {
        if (file_exists($ruta))
            unlink($ruta);
        $html = <<<HTML
$html
<div class="row" style="text-align: center">
    <div class="form-group">
        <div class="col-xs-12 navbar-brand" style="font-size: 12px;">
            <label>

            </label>
        </div>
    </div>
</div>
HTML;
        try {
            $carpeta = dirname($ruta);
            if (!file_exists($carpeta))
                mkdir($carpeta, 0777, true);

            date_default_timezone_set('America/Mexico_City');
            $mpdf = new mPDF();
            $mpdf->debug = false;
            if ($watermark != false) {
                $mpdf->showWatermarkText = 1;
                $mpdf->SetWatermarkText($watermark, 0.5);
            }
            $mpdf->WriteHTML($stylesheets, 1);
            $mpdf->WriteHTML($html, 2);
            $mpdf->Output($ruta, 'F');
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    /**
     * @param string $str
     * @return string
     */
    public static function utf8_encode($str)
    {
        $encoding = mb_detect_encoding($str);
        if ($encoding != "UTF-8") {
            $str = utf8_encode($str);
        }
        return $str;
    }

    function __destruct()
    {
        self::setNamespace("");
    }

    /**
     * @param string $namespace
     */
    static function setNamespace($namespace)
    {
        self::$namespace = $namespace . "\\";
    }

    public static function check_value_empty($array, $required, $message = 'Missing Data.', $code = 400)
    {
        $required = array_flip($required);
        $intersect = array_intersect_key($array ?: $required, $required);
        $empty_values = '';

        foreach ($required as $key => $value) {
            if (!isset($array[$key]) || empty($array[$key]) && $array[$key] !== 0) {
                $empty_values .= $key . ', ';
            }
        }
        $empty_values = trim($empty_values, ', ');
        if (!empty($empty_values)) {
            throw new Exception($message . ' ' . "[$empty_values]", $code);
        }

        foreach ($intersect as $key => $value) {
            $value = is_string($value) ? trim($value) : $value;
            if (empty($value) and $value != 0) {
                $empty_values .= $key . ', ';
            }
        }
        $empty_values = trim($empty_values, ', ');
        if (!empty($empty_values)) {
            throw new Exception($message . ' ' . "[$empty_values]", $code);
        }
    }

    static function uploadFile(string $FILE, array $path_array): string
    {
        $FILE = Globales::json_decode($FILE);
        $data = base64_decode($FILE['data']);

        $path = $path_array[1] . urlencode(str_replace(['(', ')'], '', $FILE['name']));
        $path_full = $path_array[0] . $path;
        is_dir(dirname($path_full)) || @mkdir(dirname($path_full));
        file_put_contents($path_full, $data);

        return $path;
    }

    /**
     * @param $options
     * @param string|null $select
     * @param string|null $code_string
     * @return mixed
     * @throws Exception
     */
    static function curl($options, ?string $select = '', ?string $code_string = 'code')
    {
        $curl = curl_init();

        $headers = [
            'Cookie: XDEBUG_SESSION=PHPSTORM'
        ];
        $options['method'] = mb_strtoupper($options['method'] ?? 'get');

        $options['url'] = str_replace(' ', '%20', $options['url']);
        curl_setopt_array($curl, [
            CURLOPT_URL => ($options['url'] ?? ''),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $options['method'] ?? 'GET',
        ]);
        if (is_array($options['data'])) {
            $data = json_encode($options['data']);

            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($data);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } elseif (($options['data'] ?? null)) {
            $headers[] = 'Content-Length: ' . strlen($options['data']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options['data']);
        }
        $headers = array_merge($headers, $options['headers'] ?? []);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $json = curl_exec($curl);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        $result = ['data' => []];

        if ($error) {
            throw new Exception($error, 500);
        } elseif ($json) {
            if (!self::isJson($json)) {
                if ($info['http_code'] >= 500) {
                    throw new Exception('', $info['http_code'], $result['data']);
                } elseif ($info['http_code'] >= 400) {
                    throw new Exception('', $info['http_code'], $result['data']);
                } else {
                    throw new Exception('', $info['http_code'], ['data' => $json]);
                }
            } else {
                $result = self::json_decode($json);
                $code = $result[$code_string] ?? $info['http_code'];
                if (!$code) {
                    throw new Exception('Response Code not defined', 500);
                } else if ($code >= 400) {
                    if (is_array($result['message'])) {
                        $result['message'] = implode(' ', $result['message']);
                    }
                    throw new Exception($result['message'], $code, $result['data']);
                }
            }
        } else {
            throw new Exception('Empty response: ' . $options['url'], 503);
        }

        if ($select && key_exists($select, $result['data'])) {
            return $result['data'][$select];
        }

        return $result['data'] ?? $result;
    }

    /**
     * @param $string
     * @return bool
     */
    private static function isJson($string): bool
    {
        $decoded = json_decode($string, true);
        $isJson = (json_last_error() == JSON_ERROR_NONE && is_array($decoded));
        if (!$isJson) {
            $error = json_last_error_msg();
        }
        return $isJson;
    }
}
