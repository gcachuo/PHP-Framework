<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 20/feb/2017
 * Time: 11:21 AM
 */

setcookie('XDEBUG_SESSION', 'PHPSTORM');
ini_set('memory_limit', '300M');
ini_set('max_execution_time', 300);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
mysqli_report(MYSQLI_REPORT_ALL ^ (MYSQLI_REPORT_INDEX));//MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

date_default_timezone_set('America/Mexico_City');
setlocale(LC_ALL, 'es_ES');
set_exception_handler('exception');
spl_autoload_register('autoloader');
//register_shutdown_function('cierre');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_REQUEST['lang']))
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));

if ($_SESSION['modulo'] != 'login' and ((isset($_REQUEST['s']) ? $_REQUEST['s'] : null) == '1' or TYPE_SYSTEM != $_SESSION['sistema'])) {
    session_unset();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
}
header('Content-type: text/html; charset=utf-8');

$_SESSION['sistema'] = TYPE_SYSTEM;

require_once 'globales.php';
require_once dirname(__FILE__) . '/control.php';
require_once 'conexion.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';


ob_start();
Globales::$modulo = $_POST["modulo"] ?? $_SESSION["modulo"] ?? null;
Globales::$namespace = __NAMESPACE__ . "\\";
Globales::setVista();
Globales::setControl(Globales::$modulo);
cierre();
ob_clean();

function autoloader($class)
{
    $class = str_replace(APP_NAMESPACE, '', $class);
    if (strpos($class, 'Modelo') !== false) {
        $class = strtolower(str_replace('Modelo', '', $class));
        $path = APP_ROOT . "modelo/{$class}Modelo.php";
        if (!file_exists($path)) {
            $path = HTTP_PATH_ROOT . "modelo/{$class}Modelo.php";
            if (!file_exists($path))
                Globales::mensaje_error("No existe el archivo $path");
        }
    } elseif (strpos($class, 'Tabla') !== false) {
        $class = strtolower(str_replace('Tabla', '', $class));
        $path = APP_ROOT . "modelo/tablas/{$class}.php";
        if (!file_exists($path)) {
            $path = HTTP_PATH_ROOT . "modelo/tablas/{$class}.php";
            if (!file_exists($path))
                Globales::mensaje_error("No existe el archivo $path");
        }
    } else {
        $path = APP_ROOT . "controlador/{$class}Control.php";
        if (!file_exists($path)) {
            $path = HTTP_PATH_ROOT . "controlador/{$class}Control.php";
            if (!file_exists($path)) {
                $path = __DIR__ . "/controlador/{$class}Control.php";
                if (!file_exists($path))
                    Globales::mensaje_error("No existe el archivo $path");
            }
        }
    }
    require_once $path;
}

function cierre()
{
    $error = error_get_last();
    if (!isset($_POST['fn'])) {
        if ($error['type'] === E_ERROR) {
            http_response_code(500);
            // fatal error has occured
            die(<<<HTML
Ha ocurrido un error. Contacte al desarrollador.

file: $error[file]
line: $error[line]
message: $error[message]
HTML
            );

        } elseif ($error && !defined('EXCEPTION')) {
            $file = addslashes($error['file']);
            $message = addslashes(preg_replace("/\r|\n/", '', $error['message']));
            $script = (<<<HTML
<script>
console.error('$message','$file',$error[line])
</script>
HTML
            );
        }
    }
    if (
        $error &&
        $error['type'] !== E_NOTICE &&
        $error['type'] !== E_WARNING
    ) {
        ob_end_clean();
        http_response_code(500);
        header('Content-Type: application/json');
        die(json_encode([
            'type' => $error['type'],
            'code' => 500,
            'message' => $error['message'],
            'data' => [
                'file' => $error['file'],
                'line' => $error['line']
            ]
        ]));
    } elseif (defined('JSON_RESPONSE')) {
        Globales::json_decode(JSON_RESPONSE);
        $json_error = json_last_error();
        if (!$json_error) {
            header('Content-Type: application/json');
        }
        http_response_code(200);
        die(JSON_RESPONSE);
    } else if (defined('PAGE')) {
        die(PAGE);
    }
}

function exception($ex)
{
    define('EXCEPTION', true);
    Globales::mostrar_exception($ex);
}
