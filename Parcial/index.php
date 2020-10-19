<?php
require __DIR__ . '\vendor\autoload.php';

use \Firebase\JWT\JWT;

include_once './Entidades/servicio.php';
include_once './Entidades/Turno.php';
include_once './Entidades/vehiculo.php';
include_once './Entidades/GestionArchivos.php';
include_once './Entidades/Usuario.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;

$key = "primerparcial";
$token = $_SERVER['HTTP_TOKEN'] ?? '';


function token()
{
    try {
        $key = "primerparcial";
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $decoded = JWT::decode($token, $key, array('HS256'));
    } catch (Exception $ex) {

        echo $ex;
    }

    return $decoded;
}


switch ($path) {
    case '/registro':
        switch ($method) {
            case 'POST':
                try {
                    $clave = $_POST['password'];
                    $email = $_POST['email'];
                    $tipoUsuario = $_POST['tipo'];
                    if ($clave != null &&  $email != null && !empty($clave) && !empty($email)) {
                        $nuevoUsuario = new Usuario($email, $clave, $tipoUsuario);

                        if (Usuario::ExisteEmail($nuevoUsuario)) {
                            echo "Registro con email repetido.";
                        } else {
                            Usuario::GuardarJson($nuevoUsuario);
                            echo "Registro con exito.";
                        }
                    }
                } catch (Exception $ex) {
                    echo "No se pudo realizar el registro.";
                }

                break;
        }
        break;

    case '/login':
        switch ($method) {
            case 'POST':

                $clave = $_POST['password'];
                $email = $_POST['email'];

                if ($clave != null &&  $email != null && !empty($clave) && !empty($email)) {

                    $nuevoUsuario = new Usuario($email, $clave, Usuario::TipoUser($email));
                    if (Usuario::ExisteEmail($nuevoUsuario)) {

                        $payload = ['User' => $nuevoUsuario->email, 'type' => Usuario::TipoUser($email)];
                        $token = JWT::encode($payload, $key);



                        echo '-----------PERMISO PARA ACCEDER---------------';

                        echo "\n";
                        echo $email;
                        echo "\n";
                        echo Usuario::TipoUser($email);
                        echo "\n";
                        echo $token;
                    } else {

                        echo 'SIN PERMISO PARA ACCEDER/ LOS DATOS INGRESADOS NO SON CORRECTOS';
                    }
                }
                break;
        }

        break;

    case '/vehiculo':
        switch ($method) {
            case 'POST':

                $decoded = token();
                if ($decoded->type == 'admin') {

                    $nuevoVehiculo = new Vehiculo($_POST['marca'], $_POST['modelo'], $_POST['patente'], $_POST['precio']);
                    if (!Vehiculo::patenteDuplicada($nuevoVehiculo)) {

                        Vehiculo::GuardarJson($nuevoVehiculo);
                        echo 'Auto generado con Exito';
                    } else {

                        echo 'Patente duplicada, ya existe auto en sistema';
                    }
                } else {
                    echo 'sin permisos para ejecutar esta accion';
                }


                break;
        }
        break;

    case '/patente/aaa123':
        switch ($method) {
            case 'GET':

                $decoded = token();
                $arrayBusqueda = array();
                $valor = "";
                if ($decoded->type == 'admin') {
                    if (!empty($_GET['patente'])) {
                        $valor =  $_GET['patente'];
                        $arrayBusqueda = Vehiculo::busquedaPorParametro('patente', $valor);
                    } else {
                        echo "Para buscar, debe ingresar un valor.";
                    }

                    if (empty($arrayBusqueda)) {
                        echo "No se encontraron coincidencias para: ";
                        echo $valor;
                    } else {
                        var_dump($arrayBusqueda);
                    }
                }

                break;
        }
        break;

    case '/servicio':
        switch ($method) {
            case 'POST':
                $decoded = token();
                if ($decoded->type == 'admin') {
                    $nombre = $_POST['nombreServicio'];
                    $id = $_POST['id'];
                    $tipo = $_POST['tipo'];
                    $precio = $_POST['precio'];
                    $demora = $_POST['demora'];

                    if (!empty($nombre) && !empty($id) && !empty($tipo) && !empty($precio) && !empty($demora)) {
                        if ($nombre != NULL && $id != NULL && $tipo != NULL  && $precio != NULL  && $demora != NULL) {
                            if ($tipo != '10000' || $tipo != '20000' || $tipo != '50000') {
                                $nuevoServicio = new Servicio($nombre, $id, $tipo, $precio, $demora);
                                if ($nuevoServicio != null) {
                                    Servicio::GuardarJson($nuevoServicio);
                                    echo "servicio guardado con exito";
                                }
                            } else {
                                echo "valor incorrecto para tipo de servicio";
                            }
                        } else {
                            echo "los campos no pueden ser NULL";
                        }
                    } else {
                        echo "debe completar todos los campos";
                    }
                }


                break;
        }
        break;

    case '/turno':
        switch ($method) {
            case 'GET':

                $patente = $_GET['patente'];
                $dia = $_GET['fecha'];
                $tipoServicio = $_GET['tipoServicio'];


                $vehiculo = Vehiculo::buscarVehiculo($patente);

                if ($vehiculo != NULL) {
                    $nuevoT = new Turno($dia, $vehiculo->marca, $vehiculo->modelo, $vehiculo->patente, $vehiculo->precio, $tipoServicio);
                    Turno::GuardarJson($nuevoT);

                    echo "Turno reservado";
                } else {
                    echo "Patente ingresada no es correcta, reintente";
                }

                break;
        }
        break;

    case '/stats':
        switch ($method) {
            case 'POST':
                $decoded = token();
                if ($decoded->type == 'admin') {

                    $servicio = $_POST['servicio'];
                    $arrayServicios = array();

                    if (empty($servicio) || $servicio == NULL) {
                        $arrayServicios = Turno::buscarServicios('');
                    } else {
                        $arrayServicios = Turno::buscarServicios($servicio);
                    }

                    var_dump($arrayServicios);
                }

                break;
        }
        break;
}
