<?php
class Pepito extends db{	 
	use Condicion, Algo, Algomas;
	//Atributos
	private $idpepito;
	private $nombre;
	private $apellido;
	private $objRol;
	private $mensajeOp;
	static $mensajeStatic;

	//Constructor
	public function __construct(){
		$this->idpepito = '';
		$this->nombre = '';
		$this->apellido = '';
		$this->objRol = NULL;
		$this->mensajeOp = '';
	}

	//Metodo cargar
	public function cargar( $idpepito, $nombre, $apellido, $objRol, $mensajeOp){
		$this->idpepito = $idpepito;
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->objRol = $objRol;
		$this->mensajeOp = $mensajeOp;
	}

	//Getters y setters
	public function getIdpepito(){
		return $this->idpepito;
	}
	public function setIdpepito($idpepito){
		$this->idpepito = $idpepito;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function getApellido(){
		return $this->apellido;
	}
	public function setApellido($apellido){
		$this->apellido = $apellido;
	}
	public function getObjRol(){
		return $this->objRol;
	}
	public function setObjRol($objRol){
		$this->objRol = $objRol;
	}
	public function getMensajeOp(){
		return $this->mensajeOp;
	}
	public function setMensajeOp($mensajeOp){
		$this->mensajeOp = $mensajeOp;
	}
	public static function getMensajeStatic(){
		return Pepito::$mensajeStatic;
	}
	public static function setMensajeStatic($mensajeStatic){
		Pepito::$mensajeStatic = $mensajeStatic;
	}

	public function buscar($arrayBusqueda){
		//Seteo del array de busqueda, se deberan pasar como claves los campos de la db y como argumentos los parametros a buscar
		$stringBusqueda = $this->SB($arrayBusqueda);
		//Seteo de respuesta
		$respuesta['respuesta'] = false;
		$respuesta['errorInfo'] = '';
		$respuesta['codigoError'] = null;
		//Sql
		$sql = "SELECT * FROM pepito";
		if($stringBusqueda != ''){
			$sql.= " WHERE $stringBusqueda";
		}
		$base = new db();
		try {
			if($base->Iniciar()){
				if($base->Ejecutar($sql)){
					if($row2 = $base->Registro()){
						
						$this->setIdpepito($row2['idpepito']);
						$this->setNombre($row2['nombre']);
						$this->setApellido($row2['apellido']);
						$id = $row2['idrol'];
						$objRol = new Rol();
						$arrayDeBusqueda['idrol'] = $id;
						$objRol->buscar($arrayDeBusqueda);
						$this->setObjRol($objRol);
						$this->setMensajeOp($row2['mensajeOp']);
						$this->setMensajeStatic($row2['mensajeStatic']);
						$respuesta['respuesta'] = true;
					}
				}else{
					$this->setMensajeOp($base->getError());
					$respuesta['respuesta'] = false;
					$respuesta['errorInfo'] = 'Hubo un error en la consulta';
					$respuesta['codigoError'] = 1;
				}
			}else{
				$this->setMensajeOp($base->getError());
				$respuesta['respuesta'] = false;
				$respuesta['errorInfo'] = 'Hubo un error con la conexion a la db';
				$respuesta['codigoError'] = 0;
			}
		} catch (\Throwable $th){
			$respuesta['respuesta'] = false;
			$respuesta['errorInfo'] = $th;
			$respuesta['codigoError'] = 3;
		}
		$base = null;
		return $respuesta;
	}
}