<?php
require_once 'GestionArchivos.php';

class Vehiculo extends GestionArchivos
{
    public $marca;
    public $modelo;
    public $patente;
    public $precio;
    public static $pathJsn = "./Archivos/vehiculo.json";

    public function __construct($marca, $modelo, $patente, $precio)
    {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $patenteAux = strval($patente);
        $this->patente = strtolower($patenteAux);
        $this->precio = $precio;
    }

    public static function patenteDuplicada($vehiculo)
    {

        $listaVehiculos = Vehiculo::LecturaJson();
        foreach ($listaVehiculos as $unVehiculo) {
            if ($unVehiculo->patente == $vehiculo->patente) {
                return true;
            }
        }


        return false;
    }

    public static function LecturaJson()
    {
        $lista = parent::LeerJson(Vehiculo::$pathJsn);

        $listaVehiculos = array();


        if (is_array($lista) || is_object($lista)) {
            foreach ($lista as $datos) {

                if (count((array)$datos) == 4) {
                    $nuevoVehiculo = new Vehiculo($datos->marca, $datos->modelo, $datos->patente, $datos->precio);
                    array_push($listaVehiculos, $nuevoVehiculo);
                }
            }
        }
        return $listaVehiculos;
    }


    public static function GuardarJson($vehiculo)
    {
        $arrayVehiculos = $vehiculo->LecturaJson();

        array_push($arrayVehiculos, $vehiculo);

        parent::EscribirJson(Vehiculo::$pathJsn, $arrayVehiculos);
    }

    public static function busquedaPorParametro($parametro, $valor)
    {
        $arrayCoincidencias = array();
        $arrayVehiculos = Vehiculo::LecturaJson();


        if ($parametro == 'patente') {

            foreach ($arrayVehiculos as $unVehiculo) {
                if (strtolower($unVehiculo->$parametro) == strtolower($valor)) {
                    array_push($arrayCoincidencias, $unVehiculo);
                }
            }

            return $arrayCoincidencias;
        } else {
            return $arrayCoincidencias;
        }
    }

    public static function buscarVehiculo($patente)
    {
        $arrayVehiculo = Vehiculo::busquedaPorParametro('patente', $patente);

        if (!empty($arrayVehiculo)) {
            foreach ($arrayVehiculo as $datos) {

                if (count((array)$datos) == 4) {
                    $nuevoVehiculo = new Vehiculo($datos->marca, $datos->modelo, $datos->patente, $datos->precio);
                    return $nuevoVehiculo;
                }
            }
        }

        return null;
    }
}
