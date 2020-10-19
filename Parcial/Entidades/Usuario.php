<?php

use \Firebase\JWT\JWT;

require_once 'GestionArchivos.php';

class Usuario extends GestionArchivos
{
    public $email;
    public $clave;
    public $tipo;
    public static $pathJsn = "./Archivos/archivoUsuarios.json";

    public  function __construct($email, $clave, $tipo)
    {
        $this->email = $email;
        $this->clave = $clave;
        if ($tipo == 'user' || $tipo == 'admin') {
            $this->tipo = $tipo;
        } else {
            $this->tipo = 'user';
        }
    }

    public static function ExisteEmail($usuario)
    {
        $listaUsuarios = Usuario::LecturaJson();
        foreach ($listaUsuarios as $unUsuario) {
            if ($usuario->email == $unUsuario->email) {
                return true;
            }
        }


        return false;
    }

    public static function LecturaJson()
    {
        $lista = parent::LeerJson(Usuario::$pathJsn);

        $listaUsuarios = array();


        if (is_array($lista) || is_object($lista)) {
            foreach ($lista as $datos) {

                if (count((array)$datos) == 3) {
                    $nuevoUsuario = new Usuario($datos->email, $datos->clave, $datos->tipo);
                    array_push($listaUsuarios, $nuevoUsuario);
                }
            }
        }
        return $listaUsuarios;
    }

    public static function GuardarJson($usuario)
    {
        $arrayUsuarios = $usuario->LecturaJson();

        array_push($arrayUsuarios, $usuario);

        parent::EscribirJson(Usuario::$pathJsn, $arrayUsuarios);
    }

    public static function TipoUser($email)
    {
        $listaUsuarios = Usuario::LecturaJson();
        foreach ($listaUsuarios as $unUsuario) {
            if ($email == $unUsuario->email) {
                return $unUsuario->tipo;
            }
        }


        return 'user';
    }
}
