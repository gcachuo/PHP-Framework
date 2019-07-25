<?php
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 11/may/2017
 * Time: 10:52 AM
 */
class Mail
{
    private $to, $name, $subject, $htmlBody, $extra, $cotizacion, $sistema, $venta;

    /**
     * mail constructor.
     * @param $template
     * @param $email
     * @param $nombre
     * @param $asunto
     * @param array $extra
     */
    function __construct($template, $email, $nombre, $asunto, $extra = array())
    {
        $this->to = $email;
        $this->name = $nombre;
        $this->subject = $asunto;
        $this->extra = $extra;

        $this->cargarBodyCorreo($template);
    }

    /**
     * @param string $template
     * @return string
     * @internal param array $data
     */
    private function cargarBodyCorreo($template)
    {
        /** @var object $data */
        if ($template == "cotizacion") {
            $cotizaciones = new Cotizaciones(true);
            $this->cotizacion = $cotizaciones->obtenerVenta($_POST['txtId']);
            $this->cotizacion->link = $_POST['link'];
        }
        if ($template == "venta") {
            $ventas = new Ventas(true);
            $this->venta = $ventas->obtenerVenta($_POST['txtId']);
            $this->venta->link = $_POST['link'];
        }
        $data = $this->$template();
        ob_start();
        include "vista/correos/$template.phtml";
        $html = ob_get_contents();
        ob_end_clean();
        $this->htmlBody = $html;
    }

    function send_mail(&$errorInfo, $attachment = null, $correoSistema = "info@grupocamrey.com", $nombreEmpresa = "Cbiz Admin")
    {
        $from = $correoSistema;
        $nameMailer = $nombreEmpresa;

        $host = "smtp.1and1.mx";
        $username = "carloscamarena@cbiz.mx";
        $password = "113l3v3n11";

        $mail = new PHPMailer();

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';                                      // Set mailer to use SMTP
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $username;                          // SMTP username
        $mail->Password = $password;                          // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($from, $nameMailer);
        $mail->addAddress($this->to, $this->name);     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $this->subject;
        $mail->Body = $this->htmlBody;

        if (!is_null($attachment))
            $mail->addAttachment($attachment);

        $send = $mail->send();

        $errorInfo = $mail->ErrorInfo;

        return $send;
    }


    /**
     * @return object
     */
    private function bienvenida()
    {
        $title = "Hola " . $this->name;
        $to = $this->to;
        $password = $this->extra[password];

        $message = <<<HTML
Bienvenido a Cbiz Admin, la herramienta donde podr&aacute;s registrar tus ingresos, gastos y adem&aacute;s poder proyectar los mismos. <br><br>

Cbiz Admin esta siempre disponible para tu uso desde cualquier tel&eacute;fono, Tablet o computadora con internet solo
    entra a:<br><br>

    <a href="http://www.cbiz.mx/admin/">http://www.cbiz.mx/admin/</a><br><br>


Tus datos de acceso son: <br>
Usuario: $to <br>
Password: $password<br><br>
HTML;

        $part_one = <<<HTML
<td align="left" valign="top"
    style="padding-right:20px;padding-bottom:60px">
    <img alt="Learn"
         src="http://www.cbiz.mx/money/recursos/img/bulby.png"
         width="48"
         style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:600;height:auto;letter-spacing:-1px;padding:0;margin:0;text-align:center">
</td>
<td align="left" valign="top"
    style="padding-bottom:40px">
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:24px;padding-top:0;margin-top:0;text-align:left">
        Olv&iacute;date de los recibos</p>
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:24px;padding:0;margin:0;text-align:left">
        T&oacute;male una foto al recibo de gasto y reg&iacute;stralo en Cbiz Admin, ya con eso tus gastos quedan respaldados para
        cualquier aclaraci&oacute;n.</p>
</td>
HTML;

        $part_two = <<<HTML
<td align="left" valign="top"
    style="padding-right:20px;padding-bottom:60px">
    <img alt="Lists"
         src="http://www.cbiz.mx/money/recursos/img/list_icon.png"
         width="48"
         style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:600;height:auto;letter-spacing:-1px;padding:0;margin:0;text-align:center">
</td>
<td align="left" valign="top"
    style="padding-bottom:40px">
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:24px;padding-top:0;margin-top:0;text-align:left">Reduce tiempo de TAX</p>
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:24px;padding:0;margin:0;text-align:left">Al registrar los gastos y movimientos, est&aacute;s listo para que tu agente prepare tu declaraci&oacute;n.</p>
</td>
HTML;

        $part_three = <<<HTML
<td align="left" valign="top"
    style="padding-right:20px;padding-bottom:60px">
    <img alt="Reports"
         src="http://www.cbiz.mx/money/recursos/img/report_icon.png"
         width="48"
         style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:600;height:auto;letter-spacing:-1px;padding:0;margin:0;text-align:center">
</td>
<td align="left" valign="top"
    style="padding-bottom:40px">
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:24px;padding-top:0;margin-top:0;text-align:left">Conoce en que gastas</p>
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:24px;padding:0;margin:0;text-align:left">Te sorprender&aacute;s al darte cuenta de los gastos que haces y podr&aacute;s tomar mejores decisiones para el futuro.</p>
</td>
HTML;

        $part_four = <<<HTML
<td align="left" valign="top"
    style="padding-right:20px;padding-bottom:60px">
    <img alt="Test"
         src="http://www.cbiz.mx/money/recursos/img/test_icon.png"
         width="48"
         style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:600;height:auto;letter-spacing:-1px;padding:0;margin:0;text-align:center">
</td>
<td align="left" valign="top"
    style="padding-bottom:40px">
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:24px;padding-top:0;margin-top:0;text-align:left">Tu informaci&oacute;n est&aacute; segura</p>
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:24px;padding:0;margin:0;text-align:left">Solo t&uacute; decides con quien compartirla, Cbiz Admin te ayuda a tener un registro para cuando lo necesites.</p>
</td>
HTML;

        $help = <<<HTML
<td align="center" valign="middle"
    style="padding:20px">
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:24px;padding-top:0;margin-top:0;text-align:left">
        &iquest;Necesitas ayuda?</p>
    <p style="color:#737373;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:24px;padding-top:0;margin-top:0;text-align:left">
        Contacta a tu agente de TAX o env&iacute;a un correo a <a style="color: #000000">help@cbiz.mx</a>.
Recuerda que tienes 5 d&iacute;as en versi&oacute;n gratuita para que hagas pruebas, en cuanto est&eacute;s listo puedes borrar los registros de prueba y empezar a usarlo.</p>
</td>
HTML;

        $data = array(
            "title" => $title,
            "message" => $message,
            "part_one" => $part_one,
            "part_three" => $part_three,
            "part_four" => $part_four
        );

        return (object)$data;
    }

    private function password()
    {
        $title = "Hola, esta es tu nueva contrase&ntilde;a";
        $to = $this->to;
        $password = $this->extra[password];
        $message = <<<HTML
Tus datos de acceso son: <br>
Usuario: <a style="color: #000000">$to</a> <br>
Password: $password<br><br>
HTML;
        $data = array(
            "title" => $title,
            "message" => $message
        );
        return (object)$data;
    }

    private function reporte()
    {

    }

    private function cotizacion()
    {

    }

    private function venta(){

    }
}