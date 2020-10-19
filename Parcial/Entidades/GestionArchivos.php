<?php

class GestionArchivos
{

    public static function LeerJson($nombreArhivo)
    {
        
        $arrayObjetos = array();
        $fread = "{}";

        if (file_exists($nombreArhivo) && filesize($nombreArhivo) > 0) 
        {
            $arch = fopen($nombreArhivo, 'r');
          
            $fread = fread($arch, filesize($nombreArhivo));

            fclose($arch);
            
        }
     
        $arrayObjetos = json_decode($fread);


        return $arrayObjetos;

    }

    public static function EscribirJson($nombreArhivo, $arrayObjetos)
    {
        $arch = fopen($nombreArhivo, 'w');

        fwrite($arch, json_encode($arrayObjetos));

        fclose($arch);
    }

 
}