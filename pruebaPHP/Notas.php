<?php
class Notas {
    //incluir el trait para organizar en busqueda y listar la condicion
    use Condicion;

    //Atributos
    private $id;
    private $legajo;
    private $apellidoNombre;
    private $materia;
    private $nota;
    private $mensajeOperacion;
    static $mensajeError;

    public function __construct(){
        $this->id = null;
        $this->legajo = '';
        $this->apellidoNombre = '';
        $this->materia = '';
        $this->nota = null;
        $this->mensajeOperacion = '';
    }

    //getter y setter
    public function getId() {
        return $this->id;
    }
    public function setId( $id ){
        $this->id = $id;
    }

    public function getLegajo() {
        return $this->legajo;
    }
    public function setLegajo( $legajo ){
        $this->legajo = $legajo;
    }

    public function getApellidoNombre() {
        return $this->apellidoNombre;
    }
    public function setApellidoNombre( $apellidoNombre ){
        $this->apellidoNombre = $apellidoNombre;
    }

    public function getMateria() {
        return $this->materia;
    }
    public function setMateria( $materia ){
        $this->materia = $materia;
    }

    public function getNota() {
        return $this->nota;
    }
    public function setNota( $nota ){
        $this->nota = $nota;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion( $mensajeOperacion ){
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public static function getMensajeError() {
        return Notas::$mensajeError;
    }
    public static function setMensajeError( $mensajeError ){
        Notas::$mensajeError = $mensajeError;
    }

    public function cargar($id, $apellidoNombre, $legajo, $materia, $nota){
        $this->setId($id);
        $this->setApellidoNombre($apellidoNombre);
        $this->setLegajo($legajo);
        $this->setMateria($materia);
        $this->setNota($nota);
    }

    /*La idea del buscar no es que se pueda buscar por persona, sino que eso se haga con el listar, y el buscar solo sirva para cuando uno va a modificar un registro. Hay que ver si se usa el trait de setearBusqueda o solo se deja como que viene el id ya que se deberia acceder solo desde la lista.
    El controlador se encargara de buscar los campos en el post/get y armar el array. Si o si debe haber uno de los 4 campos.
    Se pasara un array asociativo que contenga
    $arrayBusqueda['id'] = id/null,
    $arrayBusqueda['dni'] = dni/null,
    $arrayBusqueda['legajo'] = legajo/null,
    $arrayBusqueda['materia'] = materia/null,
    $arrayBusqueda['carrera'] = carrera/null
    */
    public function buscar($arrayBusqueda){
        $stringBusqueda = $this->setearBusqueda($arrayBusqueda);
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //busqueda en si
        $sql = "SELECT * FROM notas WHERE $stringBusqueda";
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    if($row2 = $base->Registro()){
                        $this->setId($row2['id']);
                        $this->setLegajo($row2['legajo']);
                        $this->setApellidoNombre($row2['apellidoNombre']);
                        $this->setMateria($row2['materia']);
                        $this->setNota($row2['nota']);                        
                        $respuesta['respuesta'] = true;
                    }
                }else{
                    $this->setMensajeOperacion($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                $this->setMensajeOperacion($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

    public function insertar(){
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $nota = floatval($this->getNota());
        $sql = "INSERT INTO notas VALUES(DEFAULT, '{$this->getLegajo()}', '{$this->getApellidoNombre()}', '{$this->getMateria()}', $nota)";
        //var_dump($sql);
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $respuesta['respuesta'] = true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = $this->getMensajeOperacion();
                    $respuesta['codigoError'] = 1; 
                }
            }else{
                $this->setMensajeOperacion($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0; 
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

    //Antes de usar el modificar se debe utilizar el buscar
    public function modificar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "UPDATE notas SET legajo = '{$this->getLegajo()}', apellidoNombre = '{$this->getApellidoNombre()}', materia = '{$this->getMateria()}', nota = {$this->getNota()} WHERE id = {$this->getId()}";
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $respuesta['respuesta'] = true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                $this->setMensajeOperacion($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

    //Antes de utilizar el eliminar se debe utilizar el buscar
    public function eliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "DELETE FROM notas WHERE id = {$this->getId()}";
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $respuesta['respuesta'] = true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                $this->setMensajeOperacion($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

    /*Se pasara un array asociativo que contenga
    $arrayBusqueda['id'] = id/null,
    $arrayBusqueda['dni'] = dni/null,
    $arrayBusqueda['legajo'] = legajo/null,
    $arrayBusqueda['materia'] = materia/null,
    $arrayBusqueda['carrera'] = carrera/null
    */
    public static function listar($arrayBusqueda){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $arregloPersona = null;
        $base = new db();
        //seteo de busqueda
        $stringBusqueda = Notas::setearBusquedaStatic($arrayBusqueda);
        $sql = "SELECT * FROM notas";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloNotas = array();
                    while($row2 = $base->Registro()){
                        $id = $row2['id'];
                        $legajo = $row2['legajo'];
                        $apellidoNombre = $row2['apellidoNombre'];
                        $materia = $row2['materia'];
                        $notaNum = $row2['nota'];
    
                        $nota = new Notas();
                        $nota->cargar($id, $legajo, $apellidoNombre, $materia, $notaNum);
                        array_push($arregloNotas, $nota);
                    }
                    $respuesta['respuesta'] = true;
                }else{
                    Profesor::setMensajeOperacion($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                Profesor::setMensajeOperacion($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        if($respuesta['respuesta']){
            $respuesta['array'] = $arregloNotas;
        }
        return $respuesta;
    }

    public function dameDatos(){
        $data = [];
        $data['id'] = $this->getId();
        $data['legajo'] = $this->getLegajo();
        $data['apellidoNombre'] = $this->getApellidoNombre();
        $data['materia'] = $this->getMateria();
        $data['nota'] = $this->getNota();
        return $data;        
    }

}