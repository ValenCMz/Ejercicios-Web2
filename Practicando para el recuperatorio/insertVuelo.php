<?php

Cargar un vuelo
// El agente de viajes podrá cargar un nuevo vuelo. 
// Se deberá verificar que el agente está logueado. 
Se deberá verificar que no exista un vuelo con mismos origen, destino y 
fecha a menos que la cantidad de pasajes vendidos sea mayor al 80%.
 Utilice el patrón MVC.

// Model
class vuelosModel
{
    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=vol_ar;charset=utf8', 'root', '');
    }

    function insertVuelo($origen, $destino, $fecha, $estado, $capacidad, $internacional, $id_aerolinea){
        $sentencia = $this->db->prepare("INSERT INTO VUELO(origen, destino, fecha, estado, capacidad, internacional, id_aerolinea) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $sentencia->execute(
            array($origen, $destino, $fecha, $estado, $capacidad, $internacional, $id_aerolinea)
        );
    }

    function verifyCoincidencias($vuelo){
        $sentencia = $this->db->prepare("SELECT * FROM VUELO WHERE origen=?, destino=?, fecha=?");
        $query->execute(array($vuelo->origen, $vuelo->destino, $vuelo->fecha));
        $vuelos = $query->fetch(PDO::FETCH_OBJ);
        return $vuelos;
    }
}

//Controller Vuelo

class vuelosController
{
    private $model;
    private $view;
    private $authHelper;

    function __construct()
    {
        $this->model = new vuelosModel();
        $this->view = new vuelosView();
        $this->authHelper = new AuthHelper();
    }


    function insertVuelo($origen, $destino, $fecha, $estado, $capacidady, $internacional, $id_aerolinea)
    {
    
    $this->controlLogin->checkLoggedIn();

    $this->model->insertVuelo($origen, $destino, $fecha, $estado, $capacidad, $internacional, $id_aerolinea);
    
    }
}

// Login Controller
class ControlLogin{
    public function __construct(){
    }
    function checkLoggedIn(){
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