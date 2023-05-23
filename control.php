<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 20/feb/2017
 * Time: 12:43 PM
 */

/**
 * Class Control
 * @property ModeloControl control
 * @property string app_name
 * @property string help_link
 * @property object color
 */
abstract class Control
{

    /** @var Control $metadata */
    public $metadata;
    /** @var Globales $idioma */
    public $idioma;
    public $permisos, $nombreUsuario, $vista, $error, $modulos, $tabla, $listas, $registro, $diasRestantes, $listNotifications, $numNot, $total;
    public $floating_button;
    public $configSistema;
    protected $acciones;
    private $customStylesheets, $customScripts, $stylesheets, $scripts, $page;

    /**
     * Constructor.
     * Carga los recursos, agrega el script del modulo y genera el codigo HTML para la vista.
     * @param bool $api
     * @throws Exception
     * @internal param $vista
     */
    public function __construct($api = false)
    {
        if ($api) return;
        $this->obtenerDiasRestantes();
        $this->buildListNotificacions();
        $this->obtenerIdioma();
        $this->permisos = $this->permisosModulo();
        $this->nombreUsuario = $this->obtenerNombreUsuario();
        define('MODULO', $this->control->modulos->selectIdFromNombre(explode('/', $_SESSION['modulo'])[0]) ?: 0);
        if (isset($_SESSION['post']) and empty($_POST['post'])) {
            $_POST['post'] = $_SESSION['post'];
            unset($_SESSION['post']);
        }
        if (isset($_POST['post'])) {
            if (is_string($_POST['post']))
                $_POST['post'] = json_decode($_POST['post'], true);
            $_POST = array_merge($_POST, isset($_POST['post']) ? $_POST['post'] : []);
        }
        if (isset($_POST['form']) or isset($_POST['aside'])) {
            parse_str($_POST['form'], $_POST['form']);
            parse_str(isset($_POST['aside']) ? $_POST['aside'] : '', $_POST['aside']);
            $_POST = array_merge($_POST, $_POST['form']);
            $_POST = array_merge($_POST, $_POST['aside']);
            unset($_POST['form']);
            unset($_POST['aside']);
        }
        $_SESSION['id'] = isset($_POST['id']) ? $_POST['id'] : (isset($_SESSION['id']) ? $_SESSION['id'] : null);
        if (isset($_POST['fn']) and !isset($_GET['aside'])) {
            $data = $this->{$_POST['fn']}();
            if (!is_array($data)) {
                $response = [
                    'code' => 200,
                    'message' => 'Completed.',
                    'data' => $data
                ];
            } else {
                $response = $data;
            }
            define('JSON_RESPONSE', Globales::json_encode($response));
        } else {

            $this->obtenerDatos();
            if (isset($_GET['aside']) or strpos($_SESSION['modulo'], '/')) $this->cargarAside();
            else $this->cargarPrincipal();

            $vista = $this->setVista();
            $this->getAssets();

            if ($vista != 'login' and $vista != 'registro')
                $this->modulos = $this->buildModulos(0);

            if (isset($_GET['aside'])) {
                $vista = $_REQUEST['asideModulo'] . '/' . $_REQUEST['asideAccion'];
                $file = $_REQUEST['asideModulo'] . '_' . $_REQUEST['asideAccion'];
                $this->addCustom($file, true);
                $page = $this->buildAside($vista) . $this->customStylesheets . $this->customScripts;
            } else {
                $page = $this->buildPage($vista);
            }
            define('PAGE', $page);
            $this->showMessage();
        }
    }

    private function obtenerDiasRestantes()
    {
        $this->diasRestantes = -1;
        return;
    }

    public function buildListNotificacions()
    {
        $this->numNot = 0;
        if ($this->diasRestantes != -1) {
            $this->numNot++;
            $this->listNotifications .= <<<HTML
    <div class="scrollable" style="max-height: 220px">
        <ul class="list-group list-group-gap m-a-0">
            <li class="list-group-item black lt box-shadow-z0 b">
                <a onclick="navegar('pago')">Te quedan $this->diasRestantes días de la version
                    de
                    prueba</a>
            </li>
        </ul>
    </div>
HTML;
        }
    }

    /**
     * @return array
     * @throws Exception
     * @property $formatoFecha
     */
    private function obtenerIdioma()
    {
        $config = Globales::getConfig();
        if (!empty($_GET['lang'])) $selectIdioma = $_GET['lang'];
        else $selectIdioma = $config->metadata->default_lang;

        if (!defined('SYSTEM_LANG')) define('SYSTEM_LANG', $selectIdioma);

        if (!file_exists(APP_ROOT . "recursos/lang/$selectIdioma.json")) {
            Globales::mensaje_error("No existe el JSON de idioma: $selectIdioma", 500);
        }
        $json = file_get_contents(APP_ROOT . "recursos/lang/$selectIdioma.json");
        $idioma = Globales::json_decode($json, false);
        if (is_string($idioma)) {
            Globales::mensaje_error("Error en el JSON de idioma: $idioma", 500);
        }

        $modulo = Globales::$modulo;
        if (isset($_GET['aside']) ? $_GET['aside'] : null) {
            $modulo = "$modulo/$_POST[asideAccion]";
        }
        Globales::setIdioma($idioma);
        $this->idioma = (object)array_merge((array)(isset($idioma->$modulo) ? $idioma->$modulo : []), (array)$idioma->sistema);
        return (array)$idioma;
    }

    /**
     * @return object
     * @internal param string $modulo
     */
    public function permisosModulo()
    {
        $nombreModulo = $_SESSION['modulo'];
        if (!is_null($nombreModulo)) {
            $permisos = $this->control->obtenerPermisosModulo();
        }
        return (object)$permisos;
    }

    public function obtenerNombreUsuario()
    {
        $usuario = (object)['nombre' => ''];
        $namespace = Globales::$namespace;
        if (isset($_SESSION['usuario']))
            if ($namespace == "\\")
                $usuario = $this->control->usuarios->selectUsuarioFromId($_SESSION['usuario']);
            else
                $usuario = $_SESSION['usuario'];
        return $usuario->nombre;
    }

    public function obtenerDatos()
    {
        $metadata = Globales::getConfig()->metadata;
        $botones = Globales::getConfig()->floating_button ?: [];
        foreach ($botones as $nombre => $boton) {
            $this->floating_button .= <<<HTML
<div class="row">
    <span style="cursor: pointer" onclick="{$boton->onclick}" 
          class="label label-lg {$boton->color}">$nombre</span>
</div>
HTML;
        }
        $this->metadata = $metadata;
        $this->cargarDatosSistema();
    }

    public function cargarDatosSistema()
    {
        $token = Globales::getToken();
        $file = 'empresa.json';
        $path = APP_ROOT . "usuario/$token/config/";
        if (!file_exists($path . $file)) {
            $path = HTTP_PATH_ROOT . "usuario/$token/config/";
            if (!file_exists($path . $file)) {
                $path = APP_ROOT . "usuario/$token/config/";
                $empresa = array('nombre' => 'Cbiz Admin', 'color' => '#2e3e4e', 'imagen' => 'logo.png', 'direccion' => '', 'correo' => '', 'telefono' => '', 'nota1' => '', 'nota2' => '', 'etiqueta' => '', 'recibos' => '0', 'ordenes' => '0', 'ticket' => '0', 'llegada' => '0', 'clientes' => '0');
                $json_string = json_encode($empresa);
                mkdir($path, 0777, true);
                file_put_contents($path . $file, $json_string);
            }
        }
        if (!file_exists($path . 'logo.png')) {
            mkdir($path, 0777, true);
            copy(HTTP_PATH_ROOT . 'recursos/img/logo.png', $path . 'logo.png');
        }
        $datosSistema = file_get_contents($path . $file);
        $this->configSistema = json_decode($datosSistema);
    }

    abstract protected function cargarAside();

    abstract protected function cargarPrincipal();

    private function setVista()
    {
        if (!empty($_POST['accion'])) $vista = $_POST['accion'];
        elseif (empty($_POST['vista'])) $vista = Globales::$modulo;
        else $vista = $_POST['vista'];

        Globales::$modulo = $vista;

        return $vista;
    }

    /**
     * Condensa en HTML los recursos requeridos, como plugins
     * @return string
     */
    private function getAssets()
    {
        $plugins = '../framework/libs';
        if (!file_exists($plugins))
            $plugins = '../../../framework/libs';
        $CSSassets = '../framework/recursos/css/lib';
        if (!file_exists($CSSassets))
            $CSSassets = '../../../framework/recursos/css/lib';
        $JSassets = '../framework/recursos/js/lib';
        if (!file_exists($JSassets))
            $JSassets = '../../../framework/recursos/js/lib';

        $this->stylesheet("$CSSassets/animate.css");
        $this->stylesheet("$plugins/glyphicons/glyphicons.css");
        $this->stylesheet('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
        $this->stylesheet('https://fonts.googleapis.com/css2?family=Material+Icons');
        $this->stylesheet("$CSSassets/font.css");


        $this->script('https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.js');
        $this->script('https://cdn.jsdelivr.net/npm/jquery@3.6.0');
        $this->script('https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js');
        $this->script('https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js');

        $this->script("$plugins/tether/dist/js/tether.min.js");

        $this->stylesheet('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css');
        $this->script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js');
        //$this->stylesheet("https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css");
        //$this->script("https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js");

        $this->stylesheet('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
        $this->script('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js');

        $google_maps_key = isset($this->configSistema->api_keys->google_maps) ? $this->configSistema->api_keys->google_maps : '';
        $this->script("https://maps.googleapis.com/maps/api/js?key=$google_maps_key");
        $this->script('https://unpkg.com/location-picker@1.1.1/dist/location-picker.umd.js');

        $this->stylesheet('https://unpkg.com/filepond/dist/filepond.css');
        $this->stylesheet('https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css');
        $this->script('https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js');
        $this->script('https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js');
        $this->script('https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js');
        $this->script('https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js');
        $this->script('https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js');
        $this->script('https://unpkg.com/filepond/dist/filepond.js');

        $this->script('https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js');

        $this->minStylesheet("$CSSassets/app.css", "$CSSassets/app.min.css");

        $this->script("$plugins/underscore/underscore-min.js");
        $this->script("$plugins/jQuery-Storage-API/jquery.storageapi.min.js");
        #$this->script("$libs/jquery/PACE/pace.min.js");

        $this->stylesheet('https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.css');
        $this->stylesheet('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        $this->stylesheet('https://fonts.googleapis.com/css?family=Montserrat|Oleo+Script&display=swap');
        $this->script('https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.js');
        $this->script('https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/locale/es.js');
        $this->script('https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.js');
        $this->stylesheet('https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.css');
        $this->script('https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.26/sweetalert2.all.js');

        //JQuery-UI
        $this->stylesheet("$plugins/jquery-ui/jquery-ui.css");
        $this->script("$plugins/jquery-ui/jquery-ui.js");
        $this->script('https://cdn.jsdelivr.net/npm/jquery-ui@1.12.1/ui/widget.js');

        $this->stylesheet("$plugins/daterangepicker/daterangepicker.css");
        $this->script("$plugins/daterangepicker/daterangepicker.js");

        $this->script("$JSassets/config.lazyload.js");

        $this->script("$JSassets/palette.js");
        $this->script("$JSassets/ui-load.js");
        $this->script("$JSassets/ui-jp.js");
        $this->script("$JSassets/ui-include.js");
        $this->script("$JSassets/ui-device.js");
        $this->script("$JSassets/ui-form.js");
        #$this->script("$scripts/ui-nav.js");
        $this->script("$JSassets/ui-screenfull.js");
        $this->script("$JSassets/ui-scroll-to.js");
        $this->script("$JSassets/ui-toggle-class.js");

        #$this->script("$libs/jquery/jquery-pjax/jquery.pjax.js");
        $this->script("$plugins/jquery/FileSaver.js");
        $this->script("$plugins/jquery/jquery.wordexport.js");
        $this->script("$JSassets/ajax.js");

        $this->stylesheet('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/cr-1.5.3/fc-3.3.2/fh-3.1.8/r-2.2.7/rr-1.2.7/sc-2.0.3/datatables.min.css');
        $this->script('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js');
        $this->script('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js');
        $this->script('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/cr-1.5.3/fc-3.3.2/fh-3.1.8/r-2.2.7/rr-1.2.7/sc-2.0.3/datatables.min.js');
        $this->script('https://cdn.datatables.net/plug-ins/1.10.25/filtering/type-based/accent-neutralise.js');


        $this->stylesheet("$plugins/select2/css/select2.css");
        $this->script("$plugins/select2/js/select2.full.js");

        $this->stylesheet("$plugins/dropzone/dropzone.css");
        //$this->stylesheet("$plugins/dropzone/style.css");
        $this->script("$plugins/dropzone/dropzone.js");

        $this->stylesheet("$plugins/nestable/jquery.nestable.css");
        $this->script("$plugins/nestable/jquery.nestable.js");

        $this->stylesheet("$plugins/multiselect/jquery.multiselect.css");
        $this->script("$plugins/multiselect/jquery.multiselect.js");

        $this->stylesheet("$plugins/switchery/dist/switchery.css");
        $this->script("$plugins/switchery/dist/switchery.js");

        $this->script("$plugins/echarts/build/dist/theme.js");
        $this->script("$plugins/echarts/build/dist/echarts-all.js");
        $this->script("$plugins/echarts/build/dist/jquery.echarts.js");
        $this->script("$plugins/echarts/build/dist/echarts.js");


        $this->script("$plugins/maskedinput/masked-input-1.4-min.js");
        $this->script("$plugins/jic/js/JIC.js");

        $this->script("$JSassets/jquery.numeric.js");
        $this->script("$JSassets/facturama.api.multiemisor.js");
        /*$this->stylesheet("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css");
        $this->script("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js");*/
        $this->script("$JSassets/certificates.js");

        $this->script("$JSassets/bootstrap-datepicker.js");

        #Override
        $this->stylesheet("$CSSassets/wrap.css");
        if (file_exists('recursos/css/lib/styles.css'))
            $this->stylesheet('recursos/css/lib/styles.css');
        else
            $this->stylesheet("$CSSassets/styles.css");
        $this->script("$JSassets/globales.js?" . uniqid());
        $this->script("$JSassets/app.js");

        $modulo = str_replace('/', '_', Globales::$modulo);
        $this->addCustom($modulo);

        $this->stylesheets .= $this->customStylesheets;
        $this->scripts .= $this->customScripts;
    }

    /**
     * Convierte el enlace del recurso en etiquetas de referencia para CSS
     * @param $href
     */
    private function stylesheet($href)
    {
        $this->stylesheets .= <<<HTML
<link rel="stylesheet" type="text/css" href="$href">
HTML;
    }

    /**
     * Convierte el enlace del recurso en etiquetas de referencia para Javascript
     * @param $src
     */
    private function script($src)
    {
        $script = <<<HTML
<script src="$src"></script>
HTML;

        $this->scripts .= $script;
        return $script;
    }

    private function minStylesheet($href, $hrefmin)
    {
        $this->stylesheets .= <<<HTML
  <!-- build:css $hrefmin -->
<link rel="stylesheet" type="text/css" href="$href">
  <!-- endbuild -->
HTML;
    }

    private function addCustom($modulo, $custom = false)
    {
        $stylesheet = !$custom ? 'stylesheet' : 'customStylesheet';
        $script = !$custom ? 'script' : 'customScript';
        if (file_exists(APP_ROOT . "recursos/css/{$modulo}.css"))
            $this->$stylesheet(APP_ROOT . "recursos/css/{$modulo}.css");
        elseif (file_exists(HTTP_PATH_ROOT . "recursos/css/{$modulo}.css"))
            $this->$stylesheet(HTTP_PATH_ROOT . "recursos/css/{$modulo}.css");
        elseif (file_exists("../framework/recursos/css/{$modulo}.css"))
            $this->$stylesheet("../framework/recursos/css/{$modulo}.css");

        $modulo = str_replace('/', '_', $modulo);
        if (file_exists(APP_ROOT . "recursos/js/{$modulo}.js"))
            $this->script("recursos/js/{$modulo}.js");
        elseif (file_exists(HTTP_PATH_ROOT . "recursos/js/{$modulo}.js"))
            $this->$script(HTTP_PATH_ROOT . "recursos/js/{$modulo}.js");
        elseif (file_exists("../framework/recursos/js/{$modulo}.js"))
            $this->$script("../framework/recursos/js/{$modulo}.js");
    }

    /**
     * @param int $padre
     * @return string
     * @throws Exception
     */
    private function buildModulos($padre = 0)
    {
        $this->cargarDatosSistema();
        if ($this->diasRestantes == 0) return null;
        $idioma = $this->idioma->modulos;
        $htmlModulos = '';
        $modulos = $this->control->obtenerModulos($padre);
        foreach ($modulos as $modulo) {
            if ($_SESSION['sistema'] == 'admin') {
                if (!$this->configSistema->llegada) {
                    if ($modulo['idModulo'] == 2003)
                        continue;
                }
                if (!$this->configSistema->clientes) {
                    if ($modulo['idModulo'] == 6007)
                        continue;
                }
                if (!$this->configSistema->clientes) {
                    if ($modulo['idModulo'] == 2006)
                        continue;
                }
            }
            $nombre = isset($idioma->{$modulo['idModulo']}[0]) ? $idioma->{$modulo['idModulo']}[0] : null;
            $navegar = mb_strtolower($modulo['navegarModulo']);
            $icono = !empty($modulo['iconoModulo']) ? <<<HTML
<span class="nav-icon"><i class="material-icons">$modulo[iconoModulo]</i></span>
HTML
                : '';

            $submodulos = $this->buildModulos($modulo['idModulo']);

            $onclick = (empty($submodulos) and !empty($navegar)) ? <<<HTML
onclick="navegar('$navegar');"
HTML
                : '';
            $flecha = !empty($submodulos) ? <<<HTML
<span class="nav-caret">
<i class="fa fa-caret-down"></i>
</span>
HTML
                : '';
            $disabled = '';
            if (empty($submodulos) and empty($navegar)) {
                $disabled = 'color: black;';
                if ($modulo['padreModulo'] == 0) {
                    $disabled .= 'display:none;';
                }
            }
            $htmlModulos .= <<<HTML
<li style="$disabled">
    <a $onclick>
        $flecha
        $icono
        <span class="nav-text">$nombre</span>
    </a>
    <ul class="nav-sub">
    $submodulos
    </ul>
</li>
HTML;
        }
        return $htmlModulos;
    }

    private function buildAside($vista)
    {
        ob_start();
        if (!file_exists(APP_ROOT . "vista/{$vista}.phtml")) {
            if (!file_exists(HTTP_PATH_ROOT . "vista/{$vista}.phtml")) $vista = '404';
            else
                $ruta = HTTP_PATH_ROOT;
        } else {
            $ruta = APP_ROOT;
        }
        require $ruta . "vista/{$vista}.phtml";
        $pagina = ob_get_contents();

        $modulo = str_replace('/', '_', $vista);
        $pagina .= $this->script("recursos/js/{$modulo}.js");

        ob_end_clean();
        return $pagina;
    }

    /**
     * Genera la vista en HTML a partir del controlador
     * @param $vista
     * @return string
     * @throws Exception
     */
    private function buildPage($vista)
    {
        ob_start();
        if (!file_exists(APP_ROOT . "vista/{$vista}.phtml")) {
            $ruta = HTTP_PATH_ROOT;
            if (!file_exists(HTTP_PATH_ROOT . "vista/{$vista}.phtml")) {

                $ruta = dirname(__FILE__) . '/';
                if (!file_exists($ruta . "/vista/{$vista}.phtml")) {
                    $vista = '404';
                }
            }
        } else {
            $ruta = APP_ROOT;
        }
        require $ruta . "vista/{$vista}.phtml";
        $this->page = ob_get_contents();
        ob_end_clean();
        if (!file_exists(APP_ROOT . 'vista/wrap.phtml')) {
            if (!file_exists(HTTP_PATH_ROOT . 'vista/wrap.phtml')) {
                $ruta = dirname(__FILE__) . '/vista/wrap.phtml';
                if (!file_exists($ruta)) {
                    $vista = '404';
                } else $ruta = dirname(__FILE__) . '/';
            } else
                $ruta = HTTP_PATH_ROOT;
        } else {
            $ruta = APP_ROOT;
        }
        require $ruta . 'vista/wrap.phtml';
        $pagina = ob_get_contents();
        ob_end_clean();
        return $pagina;
    }

    public function showMessage()
    {
        if (isset($_SESSION['messages'])) {
            $message = '';
            $color = '';
            switch (true) {
                case $_SESSION['messages']['transaccion']:
                    $message = 'Registrado correctamente';
                    $color = 'light-green-500';
                    break;
            }
            echo "<script>showMessage('$message', '$color');</script>";
            unset($_SESSION['messages']);
        }
    }

    public function buildAcciones($acciones, $ancho)
    {
        $this->acciones = isset($this->acciones) ? $this->acciones : (object)[];
        $this->acciones->ancho = $ancho;
        $this->acciones->html = '';
        foreach ($acciones as $accion) {
            $this->acciones->html .= <<<HTML
<div class="$accion[class] b-r b-b">
    <a title="$accion[title]" class="p-a block text-center" onclick="$accion[onclick]">
        <i class="material-icons md-24 text-muted m-v-sm">$accion[icon]</i>
    </a>
</div>
HTML;
        }
    }

    public function getStylesheets()
    {
        $this->getAssets();
        return $this->stylesheets;
    }

    public function getScripts()
    {
        $this->getAssets();
        return $this->scripts . $this->customScripts;
    }

    public function __get($key)
    {
        try {
            if ($key == 'control') $modulo = $key;
            elseif ($key == 'modelo') {
                $modulo = explode('/', Globales::$modulo)[0];
                //if (isset($_POST["modulo"])) $modulo = $_POST["modulo"];
                if ($modulo != get_class($this)) $modulo = get_class($this);
                elseif ($_GET['aside']) $modulo = $_REQUEST['asideModulo'];
            }
            $modelo = new ArchivoModelo();
            return $modelo->$modulo;
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * @param $registros
     * @param array|bool $acciones
     * @param array $columns
     * @return string
     * @throws Exception
     */
    protected function buildTabla($registros, array $acciones = [], $columns = [], $hide = [])
    {
        $tabla = '';
        if (get_class($registros) == 'mysqli_result') {
            $registros = $this->obtenerRegistros($registros);
        }
        foreach ($registros as $id => $cells) {
            $rows = '';

            $index = 0;
            foreach ($cells as $key => $cell) {
                if (in_array($key, $hide)) continue;
                $explode = explode('-', $columns[$index]);
                $type = $explode[0] ?: $columns[$index]['type'];
                switch ($type) {
                    case 'button':
                        $accion = $explode[1];
                        $onclick = 'btn' . ucfirst($accion) . "($id)";
                        $button = $this->permisos->{$accion} ? <<<HTML
<a onclick="$onclick" class="btn btn-default">$cell</a>
HTML
                            : $cell;
                        $rows .= <<<HTML
<td>$button</td>
HTML;
                        break;
                    case 'input':
                        $inputType = isset($explode[1]) ? $explode[1] : '';
                        $input = <<<HTML
<input type="$inputType" class="form-control" id="{$key}_$id" name="{$key}[$id]" value="$cell">
HTML;
                        $rows .= <<<HTML
<td>$input</td>
HTML;

                        break;
                    case 'select':
                        $table = $explode[1];
                        $funcion = 'selectLista' . ucfirst($table);
                        $registros = $this->modelo->$table->$funcion();
                        $lista = $this->buildLista($registros, $cell);
                        $select = <<<HTML
<select name="$key" id="select$key" data-id="$id">
<option selected disabled value="0">$key</option>
$lista
</select>
HTML;

                        $rows .= <<<HTML
<td>$select</td>
HTML;
                        break;
                    case 'date':
                        $cell = $cell != '0000-00-00'
                            ? Globales::formato_fecha($this->idioma->formatoFecha, $cell)
                            : '';
                        $rows .= <<<HTML
<td>$cell</td>
HTML;
                        break;
                    case 'expired':
                        $now = date('Y-m-d');
                        $label = $now > $cell
                            ? 'danger'
                            : ($now == $cell
                                ? 'info'
                                : '');
                        $cell = $cell != '0000-00-00'
                            ? Globales::formato_fecha($this->idioma->formatoFecha, $cell)
                            : '';
                        $rows .= <<<HTML
<td><span class="label label-lg block $label">$cell</span></td>
HTML;
                        break;
                    case 'datetime':
                        $cell = ($cell != '0000-00-00' and $cell != '')
                            ? Globales::formato_fecha($this->idioma->formatoFecha . ' H:ia', $cell)
                            : 'N/A';
                        $rows .= <<<HTML
<td>$cell</td>
HTML;
                        break;
                    case 'time':
                        $cell = Globales::formato_fecha('h:ia', $cell);
                        $rows .= <<<HTML
<td>$cell</td>
HTML;
                        break;
                    case 'age':
                        $tz = new DateTimeZone(TIMEZONE);
                        $age = ($cell != '0000-00-00' and !empty($cell)) ? DateTime::createFromFormat('Y-m-d', $cell, $tz)
                            ->diff(new DateTime('now', $tz))
                            ->y : '';
                        $rows .= <<<HTML
<td>$age</td>
HTML;
                        break;
                    case 'estatus':
                        $color = $columns[$index][$cell][0];
                        $cell = $columns[$index][$cell][1];
                        $rows .= <<<HTML
<td><a onclick="btnCambiarEstatus($id)" class="label label-lg $color btn">$cell</a></td>
HTML;
                        break;
                    case 'label':
                        $color = $columns[$index][$cell][0];
                        $print = $columns[$index][$cell][1];
                        $rows .= <<<HTML
<td><a class="label label-lg block $color">$print</a></td>
HTML;
                        break;
                    case 'nada':
                        break;
                    default:
                        $rows .= <<<HTML
<td>$cell</td>
HTML;
                        break;
                }
                $index++;
            }
            $btnAcciones = '';
            foreach ($acciones as $icono => $accion) {
                if (is_array($accion)) continue;
                $code = true;
                foreach ($acciones['conditions'][$accion] as $column => $condition) {
                    $code = (($cells[$column] == $condition[0]) == $condition[1]);
                }
                $accion = htmlentities($accion);
                $permiso = $this->permisos->{strtolower($accion)};
                $title = $this->idioma->acciones->{strtolower($accion)};
                $onclick = 'btn' . ucfirst($accion) . "($id)";
                $btnAcciones .= ($permiso and $code) ? <<<HTML
<a onclick="$onclick" title="$title" class="dropdown-item"><i class="material-icons">$icono</i></a>
HTML
                    : '';
            }
            $rowAcciones = !empty($acciones) ? <<<html
<td class="tdAcciones dropdown"><a class="nav-link dropdown-acciones" data-toggle="dropdown"><i class="material-icons md-18">more_vert</i></a><div class="dropdown-menu dropdown-menu-scale pull-right">$btnAcciones</div></td>
html
                : ($acciones === true ? "<td class='tdAcciones'></td>" : '');
            $tabla .= <<<HTML
<tr>
$rows
$rowAcciones
</tr>
HTML;
        }
        return $tabla;
    }

    /**
     * @param mysqli_result $registros
     * @return array
     */
    protected function obtenerRegistros($registros)
    {
        $datos = [];
        foreach ($registros as $dato) {
            $datos[$dato['id']] = $dato;
            unset($datos[$dato['id']]['id']);
        }
        return $datos;
    }

    protected function buildLista($lista, $default = null, $disallowed = [])
    {
        $html = '';
        foreach ($lista as $key => $item) {
            $disabled = (in_array($key, $disallowed)) ? 'disabled' : '';
            $selected = $key == $default ? 'selected' : '';
            $item = ucwords(mb_strtolower($item));
            $html .= <<<HTML
<option $selected $disabled value="$key">$item</option>
HTML;
        }
        return $html;
    }

    protected function buildDataList($lista, $default = null, $disallowed = [])
    {
        $html = '';
        foreach ($lista as $key => $item) {
            $disabled = (in_array($key, $disallowed)) ? 'disabled' : '';
            $selected = $key == $default ? 'selected' : '';
            $html .= <<<HTML
<option $selected $disabled value="$item"></option>
HTML;
        }
        return $html;
    }

    protected function buildListaEstados($idCiudad = null)
    {
        $idEstado = '';
        $listaEstados = '';
        $estados = $this->control->obtenerEstados();
        if (!is_null($idCiudad)) {
            $idEstado = $this->control->ciudades->selectEstadoFromCiudad($idCiudad);
        }
        foreach ($estados as $id => $estado) {
            $selected = $idEstado == $estado['idEstado'] ? 'selected' : '';
            $listaEstados .= <<<HTML
<option $selected value="$estado[idEstado]">$estado[nombreEstado]</option>
HTML;
        }

        return $listaEstados;
    }

    /**
     * @param int|array $idCiudad
     * @return array
     */
    protected function buildListaCiudades($idCiudad = null)
    {
        $listaCiudades = !empty($_POST['placeholder']) ? "<option selected disabled value='0'>$_POST[placeholder]</option>" : '';
        $idEstado = null;
        $idCiudad = $idCiudad ?: $_POST['ciudad'];
        if (isset($_POST['estado'])) {
            $idEstado = $_POST['estado'];
            if (strpos($idEstado, '(+)') !== false) return compact('listaCiudades');
        } elseif (!is_null($idCiudad)) {
            $idEstado = $this->control->ciudades->selectEstadoFromCiudad($idCiudad);
        }

        $ciudades = is_null($idEstado)
            ? $this->control->ciudades->selectCiudades()
            : $this->control->ciudades->selectCiudadesFromEstado($idEstado);

        foreach ($ciudades as $idciu => $ciudad) {
            $selected = '';
            $id = is_array($idCiudad) ? $idCiudad[0] : $idCiudad;
            if ($idciu == $id) {
                $selected = 'selected';
                if (is_array($idCiudad)) {
                    unset($idCiudad[0]);
                    $idCiudad = array_values($idCiudad);
                }
            }
            $listaCiudades .= <<<HTML
<option $selected value="$idciu">$ciudad</option>
HTML;
        }

        return compact('listaCiudades');
    }

    private function script_module($src)
    {
        $script = <<<HTML
<script type="module" src="$src"></script>
HTML;
        $this->scripts .= $script;
        return $script;
    }

    /**
     * Hojas de estilo especificas del modulo
     * @param $href
     */
    private function customStylesheet($href)
    {
        $this->customStylesheets .= <<<HTML
<link rel="stylesheet" type="text/css" href="$href">
HTML;
    }

    /**
     * Scripts especificos del modulo
     * @param $src
     */
    private function customScript($src)
    {
        $this->customScripts .= <<<HTML
<script type="text/javascript" src="$src"></script>
HTML;

    }

    private function getAssetsPDF()
    {
        $modulo = str_replace('/', '_', Globales::$modulo);

        $plugins = HTTP_PATH_ROOT . 'libs';
        $js = HTTP_PATH_ROOT . 'recursos/js';
        $css = HTTP_PATH_ROOT . 'recursos/css';

        $assets = "$plugins/flatkit/assets";
        $libs = "$plugins/flatkit/libs";
        $scripts = "$plugins/flatkit/scripts";

        $this->stylesheetPDF("$assets/animate.css");
        $this->stylesheetPDF("$assets/glyphicons/glyphicons.css");
        /*$this->stylesheetPDF("//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");*/
        $this->stylesheetPDF("$assets/material-design-icons/material-design-icons.css");


        $this->stylesheetPDF("$assets/bootstrap/dist/css/bootstrap.css");
        $this->stylesheetPDF("$assets/styles/app.css", "$assets/styles/app.min.css");
        $this->stylesheetPDF("$assets/styles/font.css");

        $this->scriptPDF("$libs/jquery/tether/dist/js/tether.min.js");
        $this->scriptPDF("$libs/jquery/bootstrap/dist/js/bootstrap.js");
        $this->scriptPDF("$libs/jquery/underscore/underscore-min.js");
        $this->scriptPDF("$libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js");
        #$this->script("$libs/jquery/PACE/pace.min.js");

        $this->stylesheetPDF("$plugins/jquery-ui/jquery-ui.css");
        $this->scriptPDF("$plugins/jquery-ui/jquery-ui.js");

        $this->stylesheetPDF("$plugins/daterangepicker/daterangepicker.css");
        $this->scriptPDF("$plugins/daterangepicker/daterangepicker.js");

        $this->scriptPDF("$scripts/config.lazyload.js");

        $this->scriptPDF("$scripts/palette.js");
        $this->scriptPDF("$scripts/ui-load.js");
        $this->scriptPDF("$scripts/ui-jp.js");
        $this->scriptPDF("$scripts/ui-include.js");
        $this->scriptPDF("$scripts/ui-device.js");
        $this->scriptPDF("$scripts/ui-form.js");
        #$this->script("$scripts/ui-nav.js");
        $this->scriptPDF("$scripts/ui-screenfull.js");
        $this->scriptPDF("$scripts/ui-scroll-to.js");
        $this->scriptPDF("$scripts/ui-toggle-class.js");
        $this->scriptPDF("$scripts/app.js");

        #$this->script("$libs/jquery/jquery-pjax/jquery.pjax.js");
        $this->scriptPDF("$scripts/ajax.js");

        $this->stylesheetPDF("$libs/jquery/plugins/integration/bootstrap/3/dataTables.bootstrap.css");
        $this->scriptPDF("$plugins/datatables/datatables.js");

        $this->stylesheetPDF("$plugins/select2/css/select2.css");
        $this->scriptPDF("$plugins/select2/js/select2.full.js");

        $this->stylesheetPDF("$plugins/dropzone/dropzone.css");
        //$this->stylesheet("$plugins/dropzone/style.css");
        $this->scriptPDF("$plugins/dropzone/dropzone.js");

        $this->stylesheetPDF("$plugins/nestable/jquery.nestable.css");
        $this->scriptPDF("$plugins/nestable/jquery.nestable.js");

        $this->stylesheetPDF("$plugins/switchery/dist/switchery.css");
        $this->scriptPDF("$plugins/switchery/dist/switchery.js");

        $this->scriptPDF("$plugins/echarts/build/dist/theme.js");
        $this->scriptPDF("$plugins/echarts/build/dist/echarts-all.js");
        $this->scriptPDF("$plugins/echarts/build/dist/jquery.echarts.js");
        $this->scriptPDF("$plugins/echarts/build/dist/echarts.js");

        $this->scriptPDF("$plugins/maskedinput/masked-input-1.4-min.js");
        $this->scriptPDF("$plugins/jic/js/JIC.js");


        #Override
        $this->stylesheetPDF(HTTP_PATH_ROOT . 'recursos/css/wrap.css');
        $this->stylesheetPDF("$css/styles.css");
        $this->scriptPDF("$js/globales.js?" . uniqid());

        if (file_exists("recursos/css/{$modulo}.css"))
            $this->stylesheetPDF("recursos/css/{$modulo}.css");
        if (file_exists("recursos/js/{$modulo}.js"))
            $this->scriptPDF("recursos/js/{$modulo}.js");

        $this->stylesheets .= $this->customStylesheets;
        $this->scripts .= $this->customScripts;
    }

    private function stylesheetPDF($href)
    {
        $this->stylesheets .= file_get_contents($href);
    }

    private function scriptPDF($src)
    {
        $this->scripts .= file_get_contents($src);
        HTML;

    }
}

class ArchivoModelo
{
    public function __get($key)
    {
        try {
            $namespace = str_replace(' ', '_', APP_NAMESPACE);
            $key = strtolower(str_replace($namespace, '', $key));
            $ruta = __DIR__ . '/' . APP_ROOT . "/modelo/{$key}Modelo.php";
            if (!file_exists($ruta)) {
                $ruta = HTTP_PATH_ROOT . "modelo/{$key}Modelo.php";
                $namespace = '';
                if (!file_exists($ruta)) {
                    $ruta = dirname(__FILE__) . "/modelo/{$key}Modelo.php";
                }
            }
            if (file_exists($ruta)) {
                require_once $ruta;
                $modelo = "{$namespace}Modelo{$key}";
                $class = new $modelo();
            } else {
                throw new Exception(APP_ROOT . "modelo/{$key}Modelo.php", 500);
            }
            return $class;
        } catch (Exception $ex) {
            return new stdClass();
        }
    }
}

/**
 * @property TablaModulos modulos
 * @property TablaCampos campos
 */
class Modelo
{
    private static $token;

    public static function clearToken()
    {
        unset($_SESSION['token']);
        self::$token = null;
    }

    public function __get($key)
    {
        self::getToken();
        $key = ltrim($key, '_');
        $namespace = Globales::$namespace == "\\" ? '' : Globales::$namespace;
        $namespaceDir = str_replace("\\", '/', $namespace);
        $ruta = __DIR__ . '/' . APP_ROOT . "/modelo/tablas/{$namespaceDir}{$key}.php";
        if (!file_exists($ruta)) {
            $ruta = HTTP_PATH_ROOT . "modelo/tablas/{$namespaceDir}{$key}.php";
            if (!file_exists($ruta)) {
                $ruta = dirname(__FILE__) . "/modelo/tablas/{$namespaceDir}{$key}.php";
                if (!file_exists($ruta))
                    Globales::mensaje_error("No existe el archivo. ($ruta)", 500);
            }
        } else {
            $namespace = APP_NAMESPACE;
        }

        require_once $ruta;
        $modelo = "{$namespace}Tabla{$key}";
        $tabla = new $modelo(self::$token);

        return $tabla;
    }

    public static function getToken()
    {
        self::$token = isset($_SESSION['token']) ? $_SESSION['token'] : self::$token;
        return self::$token;
    }

    public static function setToken($token)
    {
        $token = $token ?: self::getToken();
        $_SESSION['token'] = $token;
        self::$token = $token;
    }

    public function obtenerCampos($nombre = null)
    {
        $nombre = is_null($nombre) ? $_SESSION['modulo'] : $nombre;
        $idModulo = $this->modulos->selectIdFromNombre($nombre);
        return $this->campos->selectCampos($idModulo);
    }

    protected function obtenerNombrePadre($idPadre)
    {
        switch ($idPadre) {
            case 'I':
                $nombrePadre = 'Ingresos';
                break;
            case 'E':
                $nombrePadre = 'Gastos';
                break;
            case 'P':
                $nombrePadre = 'Productos';
                break;
            case 'S':
                $nombrePadre = 'Servicios';
                break;
            default:
                $nombrePadre = '';
                break;
        }
        return $nombrePadre;
    }
}
