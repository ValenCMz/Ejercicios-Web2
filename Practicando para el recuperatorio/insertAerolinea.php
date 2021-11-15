<?php
// 
// El agente de viajes podrá cargar una nueva aerolínea.
//  Se deberá verificar que el agente está logueado. 
//  Se deberá verificar que no exista una aerolínea con el mismo nombre,
//   salvo que ésta no haya realizado vuelos en el último año. Utilice el patrón MVC.
//    En SQL, 
// el año se extrae de una fecha con la función YEAR(date).
MODEL

function aerolineaExiste($nombre){
    $query = $this->db->prepare("SELECT * FROM aerolinea WHERE nombre=?");
    $query->execute(array($nombre));
    $aerolineaExiste = $query->fetch(PDO::FETCH_OBJ);
}

function getUltimoVuelo($aerolinea){
    $query = $this->db->prepare("SELECT YEAR(fecha) FROM vuelo WHERE id_erolinea=?");
    $query->execute(array($aerolinea));
    $ultimoVuelo = $query->fetch(PDO::FETCH_OBJ);
}

function insertarAerolinea($nombre){    
    $query = $this->db->prepare("INSERT INTO aerolinea(nombre) VALUES(?)");
    $query->execute(array($nombre));
}

CONTROLLER

function insertarAerolinea($aerolinea){
    if(checkloggedIn()){
        $aerolineaExiste = $this->model->aerolineaExiste($aerolinea);   
        if(empty($aerolineaExiste)){
            insertarAerolinea($aerolinea);
            $ultimoVuelo = $this->model->getUltimoVuelo($aerolineaExiste)
        }else
        { //Asumo lo siguiente : "Camila, podes suponer la existencia de la funcion de comparacion de fecha"
            (comparadorDeFechas($ultimoVuelo,$hoy)<1)
            insertarAerolinea($aerolinea);
        }
        $this->view->mostrarMensaje("La aerolínea no se pudo insertar");
    }

}

function checkloggedIn()
    {
        session_start();
        if (!isset($_SESSION["nombre"])) {  
        echo "Se necesitan permisos para realizar la operación solicitada"; 
        //$this->view->response("Se necesitan permisos para realizar la operación solicitada", 400)
        return false;
        }
        return true;
    }