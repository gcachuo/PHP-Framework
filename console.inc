#!/usr/bin/php
<?php
$argv = $_SERVER['argv'];
$mode = $argv[1] ?: 'api';

init();

switch ($mode) {
    case "api":
        api($argv);
        break;
    case "index":
        break;
}

function api($argv)
{
    try {
        $controller = $argv[2];
        $action = $argv[3];

        include_once getcwd() . "/unit/$controller/$action.php";
    } catch (Exception $exception) {
        $trace = $exception->getTrace();
        $explodeFile = explode('/', $trace[0]['file']);
        $file = implode('/', array_slice($explodeFile, -3, 3, false));
        cli_echo("\nMessage: {$exception->getMessage()}\n[File: {$file} | Line: {$trace[0]['line']}]", 'danger');
    }
}

function cli_echo($message, $color)
{
    if (CLI) {
        switch ($color) {
            case "info":
                $echo = "\e[0;37m$message\e[0m\n";
                break;
            case "danger":
                $echo = "\e[0;31m$message\e[0m\n";
                break;
            default:
                $echo = $message;
                break;
        }
        echo $echo;
    }
}

function init()
{
    define('APP_ROOT', getcwd() . "/");
    global $_IDIOMA;
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
    define("CLI", (php_sapi_name() == 'cli'));

    spl_autoload_register('autoloader');

    require_once HTTP_PATH_ROOT . "globales.php";
    require_once HTTP_PATH_ROOT . "control.php";
    require_once HTTP_PATH_ROOT . "conexion.php";
    require_once HTTP_PATH_ROOT . "stopwatch.php";
    require_once HTTP_PATH_ROOT . 'vendor/autoload.php';
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