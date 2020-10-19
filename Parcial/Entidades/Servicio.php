<?php
require_once 'GestionArchivos.php';

class Servicio extends GestionArchivos
{
    public $nombreServicio;
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    public static $pathJsn = "./Archivos/tipoServicio.json";

    public function __construct($nombreServicio, $id, $tipo, $precio, $demora)
    {
        $this->nombreServicio = $nombreServicio;
        $this->id = $id;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->demora = $demora;
    }


    public static function LecturaJson()
    {
        $lista = parent::LeerJson(Servicio::$pathJsn);

        $listaServicio = array();


        if (is_array($lista) || is_object($lista)) {
            foreach ($lista as $datos) {

                if (count((array)$datos) == 5) {
                    $nuevoServicio = new Servicio($datos->nombreServicio, $datos->id, $datos->tipo, $datos->precio, $datos->demora);
                    array_push($listaServicio, $nuevoServicio);
                }
            }
        }
        return $listaServicio;
    }


    public static function GuardarJson($servicio)
    {
        $arraySer = $servicio->LecturaJson();

        array_push($arraySer, $servicio);

        parent::EscribirJson(Servicio::$pathJsn, $arraySer);
    }
}
