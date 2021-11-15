<?php

// AEROLINEA(id: int, nombre: string)
// VUELO(id: int, origen: string, destino: string, fecha: string, estado: string, capacidad: int, internacional: bool, id_aerolinea: int)
// PASAJE(id: int, fecha_venta: string, clase: int, equipaje: int, id_vuelo: int, id_usuario: int)


class PasajeController
{

    private $view;
    private $model;
    private $vueloModel;

    public function __construct()
    {
        $this->view = new PasajeView();
        $this->model = new PasajeModel();
        $this->vueloModel = new VueloModel();
    }

    // pasajes/cancelacion/:id

    public function cancelarPasaje($pasajeId)
    {
        if (!Auth::isLogged())
            return $this->view->showError("No estas logeado papu");

        // el usuario está logeado.

        $pasaje = $this->model->get($pasajeId);
        if (!isset($pasaje))
            return $this->view->showError("No existe el pasaje");


        // el pasaje existe.

        $vuelo = $this->vueloModel->get($pasaje->id_vuelo);

        $diasFaltantesParaElVuelo = $this->getDiasFaltantesParaElVuelo($vuelo->fecha);
        if ($diasFaltantesParaElVuelo < 15)
            return $this->view->showError("No puedes cancelar el pasaje a menos que vos, sabes");


        // el pasaje es válido.
        $this->model->delete($pasajeId);
    }
}


class VueloController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new VueloView();
        $this->model = new VueloModel();
    }

    function filtrarVuelosPorFecha()
    {

        // falta hacer el isset del fecha

        $vuelosyAerolineasPorFecha = $this->model->getVuelosYAerolineaPorFecha($_POST['fecha']);

        $capacidades = [];
        if (isset($vuelosyAerolineasPorFecha)) {

            foreach ($vuelosyAerolineasPorFecha as $vuelo) {
                // por cada vuelo obtengo la cantidad de pasajes comprados que tiene
                $pasajesDeVuelo = $this->pasajeModel->getPasajesPorVuelo($vuelo->id);

                // comparo la cantidad de pasajes comprados con la capacidad del vuelo para saber si tiene o no.
                $tieneCapacidad = false;
                if ($pasajesDeVuelo < $vuelo->capacidad)
                    $tieneCapacidad = true;
                array_push($capacidades, $tieneCapacidad);
            }

            $this->view->mostrarVuelosYCapaidades($vuelosyAerolineasPorFecha, $capacidades);
        } else
            $this->view->mostrarError("No existen vuelos para esta fecha");
    }


    function filtrarVuelosPorOrigenYDestino()
    {
        $origen = $_POST['origen'];
        $destino = $_POST['destino'];

        if (isset($origen) && isset($destino)) {

            $vuelosDelMismoOrigenYDestino = $this->model->getVuelosDelMismoOrigenYDestino($origen, $destino);

            $capacidades = [];
            if (isset($vuelosDelMismoOrigenYDestino)) {

                foreach ($vuelosDelMismoOrigenYDestino as $vuelo) {

                    // por cada vuelo obtengo la cantidad de pasajes comprados que tiene
                    $pasajesDelVueloVendidos = $this->pasajeModel->getPasajesPorVuelo($vuelo->id);

                    // comparo la cantidad de pasajes comprados con la capacidad del vuelo para saber si tiene o no capacidad.
                    $tieneCapacidad = false;
                    if ($pasajesDelVueloVendidos < $vuelo->capacidad)
                        $tieneCapacidad = true;

                    array_push($capacidades, $tieneCapacidad);
                }

                $this->view->mostrarVuelosYCapaidades($vuelosDelMismoOrigenYDestino, $capacidades);
            } else
                $this->view->mostrarError("No existen vuelos para este origen y destino.");
        } else
            $this->view->mostrarError("No se ingresaron datos validos.");
    }
}

class PasajeModel
{

    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=mysql-tpeweb2-c;port=3306;dbname=db-tpe-web2', 'root', '');
    }

    public function get($pasajeId)
    {
        $query = $this->db->prepare("SELECT * FROM pasaje WHERE id = ?");
        $query->execute([$pasajeId]);
        $pasaje = $query->fetch(PDO::FETCH_OBJ);
        return $pasaje;
    }

    public function getPasajesPorVuelo($vueloId)
    {
        $query = $this->db->prepare("SELECT * FROM pasaje WHERE id_vuelo = ?");
        $query->execute([$vueloId]);
        $pasajes = $query->fetchAll(PDO::FETCH_OBJ);
        return $pasajes;
    }

    public function delete($pasajeId)
    {
        $query = $this->db->prepare("DELETE FROM pasaje WHERE id = ?");
        $query->execute([$pasajeId]);
    }
}

class VueloModel
{

    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=mysql-tpeweb2-c;port=3306;dbname=db-tpe-web2', 'root', '');
    }

    public function get($vueloId)
    {
        $query = $this->db->prepare("SELECT * FROM vuelo WHERE id = ?");
        $query->execute([$vueloId]);
        $vuelo = $query->fetch(PDO::FETCH_OBJ);
        return $vuelo;
    }

    public function getVuelosDelMismoOrigenYDestino($origen, $destino)
    {
        $query = $this->db->prepare("SELECT * FROM vuelo v join aerolinea a on v.id_aerolinea = a.id WHERE origen = ? AND destino = ?");
        $query->execute([$origen, $destino]);
        $vuelos = $query->fetchAll(PDO::FETCH_OBJ);
        return $vuelos;
    }

    public function getVuelosYAerolineaPorFecha($fecha)
    {
        $query = $this->db->prepare("SELECT * FROM vuelo v join aerolinea a on v.id_aerolinea = a.id WHERE fecha = ?");
        $query->execute([$fecha]);
        $vuelos = $query->fetchAll(PDO::FETCH_OBJ);
        return $vuelos;
    }
}
