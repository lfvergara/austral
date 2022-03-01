<?php
require_once "common/libs/PHPMailer/class.phpmailer.php";


class EmailHelper extends View {
        public function informa_acceso($destino, $array_datos) { 
                $gui = file_get_contents("static/mail.html");
                $fecha_desglosada = $this->descomponer_fecha();
                $origen = "desafioagromese@gmail.com";
                $nombre = "Desafio Agromese";

                $render = $this->render($fecha_desglosada, $gui);
                $render = $this->render($array_datos, $render);
                
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465;
                $mail->Username = 'desafioagromese@gmail.com';
                $mail->Password = 'JUNIOR1234';
                $mail->From = 'desafioagromese@gmail.com';
                $mail->FromName = $nombre;
                $mail->AddAddress($destino);
                $mail->Subject = utf8_decode("Desafio Agromese: Accesos");
                $mail->IsHTML(true);
                $mail->Body = utf8_decode($render);
                $mail->Send();
                # desafioagromese@gmail.com
                # JUNIOR1234
        }
}
?>