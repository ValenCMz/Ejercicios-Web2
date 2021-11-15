<?php

// Cancelar/Devolver un pasaje para un vuelo que se había comprado
// Un usuario logueado podrá realizar la cancelación de una compra de un pasaje.

// Se debe verificar que el vuelo sea nacional dado que no se 
// admiten devoluciones de vuelos internacionales.

// Informar los errores que pueden aparecer.

AEROLINEA(id: int, nombre: string)

VUELO(id: int, origen: string, destino: string, fecha: string, estado: string,
capacidad: int, internacional: bool, id_aerolinea: int)

Además, nos brinda una tabla donde se almacena la información de pasajes vendidos.
PASAJE(id: int, fecha_venta: string, clase: int, equipaje: int, 
id_vuelo: int, id_usuario: int)

//Model pasaje
public function get($idPasaje){
    $query->$this->db->prepare("SELECT * FROM pasaje WHERE id=?");
    $query->execute([$idPasaje]);
    $pasaje = $query->fetch(PDO::FETCH_OBJ);
    return $pasaje;
}

public function delete($idPasaje){
    $query = $this->db->prepare("DELETE FROM pasaje WHERE id = ?");
    $query->execute([$pasajeId]);
}

//Model vuelos

public function get($idPasaje){
    $query->$this->db->prepare("SELECT * FROM vuelo WHERE id=?");
    $query->execute([$idPasaje]);
    $vuelo = $query->fetch(PDO::FETCH_OBJ);
    return $vuelo;
}

//Controller pasaje


public function devolverPasaje($idPasaje){
    if(!$this->authHelper->checkLogin()){
        return $this->view->Error("No estas logueado");
    }
    else{//esta logueado 
        $pasaje = $this->modelPasaje->get($idPasaje);
        if(!isset($pasaje)){
            return  $this->view->Error("No existe el pasaje");
        }
        else{//el pasaje existe
            $idVuelo = $pasaje->id_vuelo;
            $vuelo = $this->modelVuelo->get($idVuelo)
            //supongo que nacional en booleano es 0 y internacional es 1
            if($vuelo->internacional == 0){
                $this->modelPasaje->deletePasaje($idPasaje);
                $this->view->showExito("Se borro el pasaje");
            }else{
                $this->view->error("EL pasaje no se puede eliminar porque es internacional")
            }
        }
    }
}

//controller login

function checkLogin(){
    if(!isset($_SESSION)){ 
        session_start(); 
    }
    if(!isset($_SESSION["usuario"])){
        header("Location: ".BASE_URL."login");
        die();
    }

