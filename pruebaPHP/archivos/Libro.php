<?php
class Libro extends db{	 
	use Condicion;
	//Atributos
	private $idproducto;
	private $isbn;
	private $nombrelibro;
	private $sinopsis;
	private $autor;
	private $precio;
	private $mensajeOp;
	static $mensajeStatic;

	//Constructor
	public function __construct(){
		$this->idproducto = '';
		$this->isbn = '';
		$this->nombrelibro = '';
		$this->sinopsis = '';
		$this->autor = '';
		$this->precio = '';
		$this->mensajeOp = '';
	}

	//Metodo cargar
	public function cargar( $idproducto, $isbn, $nombrelibro, $sinopsis, $autor, $precio, $mensajeOp){
		$this->idproducto = $idproducto;
		$this->isbn = $isbn;
		$this->nombrelibro = $nombrelibro;
		$this->sinopsis = $sinopsis;
		$this->autor = $autor;
		$this->precio = $precio;
		$this->mensajeOp = $mensajeOp;
	}

	//Getters y setters
	public function getIdproducto(){
		return $this->idproducto;
	}
	public function setIdproducto($idproducto){
		$this->idproducto = $idproducto;
	}
	public function getIsbn(){
		return $this->isbn;
	}
	public function setIsbn($isbn){
		$this->isbn = $isbn;
	}
	public function getNombrelibro(){
		return $this->nombrelibro;
	}
	public function setNombrelibro($nombrelibro){
		$this->nombrelibro = $nombrelibro;
	}
	public function getSinopsis(){
		return $this->sinopsis;
	}
	public function setSinopsis($sinopsis){
		$this->sinopsis = $sinopsis;
	}
	public function getAutor(){
		return $this->autor;
	}
	public function setAutor($autor){
		$this->autor = $autor;
	}
	public function getPrecio(){
		return $this->precio;
	}
	public function setPrecio($precio){
		$this->precio = $precio;
	}
	public function getMensajeOp(){
		return $this->mensajeOp;
	}
	public function setMensajeOp($mensajeOp){
		$this->mensajeOp = $mensajeOp;
	}
	public static function getMensajeStatic(){
		return Libro::$mensajeStatic;
	}
	public static function setMensajeStatic($mensajeStatic){
		Libro::$mensajeStatic = $mensajeStatic;
	}

	public function buscar($arrayBusqueda){
		//Seteo del array de busqueda, se deberan pasar como claves los campos de la db y como argumentos los parametros a buscar
		$stringBusqueda = $this->SB($arrayBusqueda);
		//Seteo de respuesta
		$respuesta['respuesta'] = false;
		$respuesta['errorInfo'] = '';
		$respuesta['codigoError'] = null;
		//Sql
		$sql = "SELECT * FROM libro";
		if($stringBusqueda != ''){
			$sql.= " WHERE $stringBusqueda";
		}
		$base = new db();
		try {
			if($base->Iniciar()){
				if($base->Ejecutar($sql)){
					if($row2 = $base->Registro()){
						
						$this->setIdproducto($row2['idproducto']);
						$this->setIsbn($row2['isbn']);
						$this->setNombrelibro($row2['nombrelibro']);
						$this->setSinopsis($row2['sinopsis']);
						$this->setAutor($row2['autor']);
						$this->setPrecio($row2['precio']);
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