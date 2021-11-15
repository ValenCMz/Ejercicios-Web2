<?php

// AEROLINEA(id: int, nombre: string)

// VUELO(id: int, origen: string, destino: string, fecha: string, estado: string,
//  capacidad: int, internacional: bool, id_aerolinea: int)

// Además, nos brinda una tabla donde se almacena la información de pasajes vendidos.
// PASAJE(id: int, fecha_venta: string, clase: int, equipaje: int, 
// id_vuelo: int, id_usuario: int)

// Donde clase es un número entre el 1 y el 3, y equipaje es el peso en kilos.


// Eliminar vuelos que no tengan pasajeros de primera clase.
// Se deberá verificar que el agente está logueado.


//MODEL VUELO

function deleteVuelo($id){
    $query = $this->db->prepare("DELETE FROM VUELO WHERE id=?");
    $query->execute(array($id));
}

//MODEL PASAJE

function getVuelosSinPasajerosDePrimeraClase(){
    $query=$this->db->prepare("SELECT id_vuelo FROM PASAJE WHERE clase <> 1 ")
    $query->execute();
    $vuelos = $sentencia->fetchAll(PDO::FETCH_OBJ);
    return $vuelos; 
}

// LOGIN CONTROLLER
class ControlLogin{
    public function __construct(){
    }
    function checkRolLoggedIn(){
        if(!isset($_SESSION)){ 
            session_start(); 
        }
	//Si no esta logueado lo redirige al Login.
        if(!isset($_SESSION["usuario"])){
            header("Location: ".BASE_URL."login");
            die();
        }
    }
   
}

// CONTROLLER VUELO
require_once "Model/VueloModel.php";
require_once "Model/PasajeModel.php";
require_once "View/VueloView.php";
require_once 'Helpers/ControlLogin.php';

class VueloController {

    private $vueloModel;
    private $pasajeModel;
    private $view;
    private $controlLogin;

    public function __construct(){
        $this->vueloModel = new VueloModel();
        $this->pasajeModel = new PasajeModel();
        $this->view = new VueloView();
        $this->controlLogin = new ControlLogin(); 
    }

    function deleteVuelosSinPasajerosDePrimeraClase(){
        $this->controlLogin->checkLoggedIn();
        $vuelos = $this->pasajeModel->getVuelosSinPasajerosDePrimeraClase();
        foreach($vuelos as $vuelo){
            $this->vueloModel->deleteVuelo($vuelo->id_vuelo);
        }
    }
}