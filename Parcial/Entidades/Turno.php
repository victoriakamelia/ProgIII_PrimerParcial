<?php
require_once 'GestionArchivos.php';

class Turno extends GestionArchivos
{

    public $fecha;
    public $patente;
    public $marca;
    public $modelo;
    public $precio;
    public $tipoServicio;
    public static $pathJsn = "./Archivos/turno.json";

    public function __construct($fecha, $marca, $modelo, $patente, $precio, $tipoServicio)
    {
        $this->fecha = $fecha;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $patenteAux = strval($patente);
        $this->patente = strtolower($patenteAux);
        $this->precio = $precio;
        $this->tipoServicio = $tipoServicio;
    }

    public static function LecturaJson()
    {
        $lista = parent::LeerJson(Turno::$pathJsn);

        $listaTurno = array();


        if (is_array($lista) || is_object($lista)) {
            foreach ($lista as $datos) {

                if (count((array)$datos) == 6) {
                    $nuevoT = new Turno($datos->fecha, $datos->marca, $datos->modelo, $datos->patente, $datos->precio, $datos->tipoServicio);
                    array_push($listaTurno, $nuevoT);
                }
            }
        }
        return $listaTurno;
    }


    public static function GuardarJson($turno)
    {
        $listaTurno = $turno->LecturaJson();

        array_push($listaTurno, $turno);

        parent::EscribirJson(Turno::$pathJsn, $listaTurno);
    }


    public static function buscarServicios($servicio)
    {
        $arrayServicios = Turno::LecturaJson();
        $arrayServiciosEncontrados = array();

        if (!empty($servicio)) {
            foreach ($arrayServicios as $servicioQueBusco) {

                if ($servicioQueBusco->tipoServicio == $servicio) {
                    array_push($arrayServiciosEncontrados, $servicioQueBusco->tipoServicio);
                }
            }
        } else {
            foreach ($arrayServicios as $servicioQueBusco) {

                array_push($arrayServiciosEncontrados, $servicioQueBusco->tipoServicio);
            }
        }
        return $arrayServiciosEncontrados;
    }
}
