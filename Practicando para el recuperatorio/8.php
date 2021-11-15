<?php

// Cancelar/Devolver un pasaje para un vuelo que se había comprado
// Un usuario logueado podrá realizar la cancelación de una compra de un pasaje.

// Se debe verificar que el vuelo sea cancelado con al menos 15 días de anticipación.

AEROLINEA(id: int, nombre: string)

VUELO(id: int, origen: string, destino: string, fecha: string, estado: string,
 capacidad: int, internacional: bool, id_aerolinea: int)

Además, nos brinda una tabla donde se almacena la información de pasajes vendidos.
PASAJE(id: int, fecha_venta: string,
 clase: int, equipaje: int, id_vuelo: int, id_usuario: int)

Donde clase es un número entre el 1 y el 3, y equipaje es el peso en kilos.

//modelPasaje

function deletePasaje($idPasaje)
{
    $query = $this->db->prepare("DELETE FROM PASAJE WHERE id=?");
    $query->execute(array($id));
}

//vueloModel

function getFechaVuelo($idPasaje){
    $query= $this->db->prepare("SELECT fecha FROM VUELO WHERE id=?");
    $query->execute($idPasaje);
    $fechaDeVuelo = $query->fetch(PDO::FETCH_OBJ);
    return $fechaDeVuelo;
}

//Controller pasaje
// Se debe verificar que el vuelo sea cancelado con al menos 15 días de anticipación.

function deletePasaje($fechaActual, $idPasaje){
    $this->authHelper->checkLogin();
    $fechaDeVuelo = $this->modelVuelo->getFechaVuelo($idPasaje);
    if(){
        
    }
}


public function cancelarPasaje($pasajeId){
    if(!Auth::isLogged())
        return $this->view  
}

//Controller login

function checkLogin(){
    if(!isset($_SESSION)){ 
        session_start(); 
    }
    if(!isset($_SESSION["usuario"])){
        header("Location: ".BASE_URL."login");
        die();
    }
}