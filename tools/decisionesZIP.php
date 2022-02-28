<?php


class decisionesZIP extends View {
  
  function crearZIP() {
    $zip = new ZipArchive();
 
    /*directorio a comprimir
     * la barra inclinada al final es importante
     * la ruta debe ser relativa no absoluta
     */
    $dir = URL_PRIVATE . 'equipodecision/';
     
    //ruta donde guardar los archivos zip, ya debe existir
    $rutaFinal = URL_PRIVATE . 'procesos';
     
    if(!file_exists($rutaFinal)){
      mkdir($rutaFinal);
    }
     
    $archivoZip = "private/procesos/decisiones.zip";
    if(file_exists($archivoZip)){
      chmod($archivoZip, 0777);
      unlink($archivoZip);
    }
    if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
      $this->agregar_zip($dir, $zip);
      
      $zip->close();
      //Muevo el archivo a una ruta
      //donde no se mezcle los zip con los demas archivos
      //rename(URL_PRIVATE . 'equipodecision/decisiones.zip', "$rutaFinal/decisiones.zip");
     
      //Hasta aqui el archivo zip ya esta creado
      //Verifico si el archivo ha sido creado
      if (file_exists($rutaFinal. "/decisiones.zip")) {
        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment; filename=decisiones.zip");
        // leemos el archivo creado
        readfile($archivoZip);
        exit;
      }
    }
  }

  function agregar_zip($dir, $zip) {
    //verificamos si $dir es un directorio
    if (is_dir($dir)) {
      //abrimos el directorio y lo asignamos a $da
      if ($da = opendir($dir)) {
        //leemos del directorio hasta que termine
        while (($archivo = readdir($da)) !== false) {
          /*Si es un directorio imprimimos la ruta
           * y llamamos recursivamente esta función
           * para que verifique dentro del nuevo directorio
           * por mas directorios o archivos
           */
          if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
            //echo "<strong>Creando directorio: $dir$archivo</strong><br/>";
            $this->agregar_zip($dir . $archivo . "/", $zip);

            /*si encuentra un archivo imprimimos la ruta donde se encuentra
             * y agregamos el archivo al zip junto con su ruta 
             */
          } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != ".." && $archivo != "var" && $archivo != "www" && $archivo != "agromese"
                    && $archivo != "private" && $archivo != "equipodecision") {
            //echo "Agregando archivo: $dir$archivo <br/>";
            $temp_dir = str_replace("/var/www/agromese/private/equipodecision", "", $dir);
            $zip->addFile($dir . $archivo, $temp_dir . $archivo);
          }
        }
        //cerramos el directorio abierto en el momento
        closedir($da);
      }
    }
  }
}
function decisionesZIP() {return new decisionesZIP();} 
?>
