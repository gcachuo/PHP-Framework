<?php

try {
    define('APP_ROOT', getcwd() . "/");
    global $_IDIOMA;
    setcookie('XDEBUG_SESSION', 'PHPSTORM');
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    register_shutdown_function('finish');

    $framework = "framework/";
    $app = 'caretrack';
    $namespace = 'CareTrack';
    define("TIMEZONE", "America/Los_Angeles");

    error_reporting(E_ALL ^ E_NOTICE);
    define("HTTP_PATH_ROOT", $framework);
    define("APP_ROOT", "./");
    define("APP_NAMESPACE", "$namespace\\");
    define("TYPE_SYSTEM", "$app");

    spl_autoload_register('autoloader');

    require_once HTTP_PATH_ROOT . "globales.php";
    require_once HTTP_PATH_ROOT . "control.php";
    require_once HTTP_PATH_ROOT . "conexion.php";
    require_once HTTP_PATH_ROOT . "stopwatch.php";
    require_once HTTP_PATH_ROOT . 'vendor/autoload.php';

    $GLOBALS['stopwatch'] = new stopwatch();

    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    if (count($request) > 2) {
        $request = array_slice($request, -2, 2, false);
    }

    $controller = strtolower($request[0]);
    $action = $request[1];

    unset($_GET['controller']);
    unset($_GET['action']);

    header('Content-type: application/json');
    if (function_exists('xdebug_disable')) {
        //Disables stack traces
        //Disable showing stack traces on error conditions.
        xdebug_disable();
    }

    merge_post();
    obtenerIdioma($controller);

    $class = new $controller(true);
    $response = $class->$action();
    $estatus = "success";
    $code = (int)http_response_code();
    $json = Globales::json_encode(compact('estatus', 'response', 'code'));
    ob_end_clean();
    die($json);

} catch (Exception $ex) {
    Globales::mostrar_exception($ex, true);
}

function merge_post()
{
    if (isset($_SESSION["post"]) and empty($_POST["post"])) {
        $_POST["post"] = $_SESSION["post"];
        unset($_SESSION['post']);
    }
    if (isset($_POST["post"])) {
        if (is_string($_POST["post"]))
            $_POST["post"] = Globales::json_decode($_POST["post"], true);
        $_POST = array_merge($_POST, $_POST["post"]);
        /*if (isset($_POST["modo"]))
            $data = $this->$_POST["modo"]();*/
    }
    if (isset($_POST['form']) or isset($_POST['aside']) or isset($_POST['details'])) {
        parse_str($_POST["form"], $_POST["form"]);
        parse_str($_POST["aside"], $_POST["aside"]);
        parse_str($_POST["details"], $_POST["details"]);
        $_POST = array_merge($_POST, $_POST["form"]);
        $_POST = array_merge($_POST, $_POST["aside"]);
        $_POST = array_merge($_POST, $_POST["details"]);
        unset($_POST["form"]);
        unset($_POST["aside"]);
    }
}

/**
 * @param null $_modulo
 * @return void
 * @throws Exception
 * @property $formatoFecha
 */
function obtenerIdioma($_modulo = null)
{
    global $_IDIOMA;
    $config = Globales::getConfig();
    if (!empty($_GET["lang"])) $selectIdioma = $_GET["lang"];
    else $selectIdioma = $config->metadata->default_lang;

    define("SYSTEM_LANG", $selectIdioma);

    $json = file_get_contents(APP_ROOT . "recursos/lang/$selectIdioma.json");
    $idioma = Globales::json_decode($json, false);
    if (is_string($idioma))
        Globales::mensaje_error("Error en el JSON de idioma: $idioma");

    $modulo = $_modulo ?: $_POST['modulo'];
    if ($_GET['aside'])
        $modulo = "$modulo/$_POST[asideAccion]";
    if ($modulo) {
        Globales::setIdioma($idioma);
        $_IDIOMA = (object)array_merge((array)$idioma->$modulo, (array)$idioma->sistema);
    } else {
        $_IDIOMA = $idioma;
    }
}

function autoloader($class)
{
    $class = str_replace(APP_NAMESPACE, "", $class);
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
        $class = strtolower($class);
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

function finish()
{
    $error = error_get_last();
    if ($error && $error['type'] != E_NOTICE && $error['type'] != 2) {
        require_once "framework/modelo/tablas/errores.php";

        Globales::error_log(['message' => $error['message'], 'file' => $error['file'], 'line' => $error['line'], 'code' => $error['type']]);

        http_response_code(500);
        $code = http_response_code();
        $estatus = "error";
        ob_clean();
        die(json_encode(compact('error', 'code', 'estatus')));
    }
}