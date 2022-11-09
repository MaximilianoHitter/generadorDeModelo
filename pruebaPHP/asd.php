<?php
//Documentacion
/*
Importante: Se debe trabajar con la version de PHP 8.1 o superior para que se soporte la función str_contains

Configuracion: El nombre del archivo debe ser el contenido de la variable nombre, dentro cada value va a ser un atributo diferente, si uno debe ser un objeto se debe poner en el nombre objRol por ejemplo, en el arrayTraits se debe poner en cada value el nombre de un trait

*/
$Pepito = [];
array_push($Pepito, 'idproducto', 'isbn', 'nombrelibro', 'sinopsis', 'autor', 'precio');
array_push($Pepito, 'mensajeOp', 'mensajeStatic');
var_dump($Pepito);
$arrayTraits = [];
array_push($arrayTraits, 'Condicion');
$nombre = 'Libro';
$db = 'db';
$ruta = "archivos/$nombre.php";
$miArchivo = fopen($ruta, 'w');
//Abro bloque php
fwrite($miArchivo, '<?php');
//Creo la clase 
fwrite($miArchivo, "\nclass $nombre extends $db{");
//Incluyo traits
if(count($arrayTraits) > 0){
    $string = "\n\tuse";
    $ultima = end($arrayTraits);
    foreach ($arrayTraits as $key => $value) {
        $string.= " $value";
        if($value != $ultima){
            $string.= ',';
        }else{
            $string.= ';';
        }
    }
    var_dump($ultima);
    fwrite($miArchivo, "\t $string");
}
//creo atributos
fwrite($miArchivo, "\n\t//Atributos");
foreach ($Pepito as $key => $value) {
    if(!str_contains($value, 'Static')){
        fwrite($miArchivo, "\n\tprivate \$$value;");
    }else{
        fwrite($miArchivo, "\n\tstatic \$$value;");
    }
    
}
//creo el constructor vacio
fwrite($miArchivo, "\n\n\t//Constructor\n\tpublic function __construct(){");
foreach ($Pepito as $key => $value) {
    if(!str_contains($value, 'Static')){
        if(str_contains($value, 'obj')){
            fwrite($miArchivo, "\n\t\t\$this->$value = NULL;");
        }else{
            fwrite($miArchivo, "\n\t\t\$this->$value = '';");
        }
    }
    
}
fwrite($miArchivo, "\n\t}");
//crear metodo cargar
$string = ''; 
$ultima = end($Pepito);
foreach ($Pepito as $key => $value) {
    if(!str_contains($value, 'Static')){
        $string.= " \$$value,";
    }
}
$string = substr($string, 0, -1);
fwrite($miArchivo, "\n\n\t//Metodo cargar\n\tpublic function cargar($string){");
foreach ($Pepito as $key => $value) {
    if(!str_contains($value, 'Static')){
        fwrite($miArchivo, "\n\t\t\$this->$value = \$$value;");
    }    
}
fwrite($miArchivo, "\n\t}");
//getters y setters 
fwrite($miArchivo, "\n\n\t//Getters y setters");
foreach ($Pepito as $key => $value) {
    $capitalize = ucfirst($value);
    if(!str_contains($value, 'Static')){
        //Getter
        fwrite($miArchivo, "\n\tpublic function get$capitalize(){\n\t\treturn \$this->$value;\n\t}");
        //Setter 
        fwrite($miArchivo, "\n\tpublic function set$capitalize(\$$value){\n\t\t\$this->$value = \$$value;\n\t}");
    }else{
        //Getter
        fwrite($miArchivo, "\n\tpublic static function get$capitalize(){\n\t\treturn $nombre::\$$value;\n\t}");
        //Setter 
        fwrite($miArchivo, "\n\tpublic static function set$capitalize(\$$value){\n\t\t$nombre::\$$value = \$$value;\n\t}");
    }
    
}
//Metodo buscar con retorno de errores y array de busqueda
fwrite($miArchivo, "\n\n\tpublic function buscar(\$arrayBusqueda){");
fwrite($miArchivo, "\n\t\t//Seteo del array de busqueda, se deberan pasar como claves los campos de la db y como argumentos los parametros a buscar\n\t\t\$stringBusqueda = \$this->SB(\$arrayBusqueda);");
fwrite($miArchivo, "\n\t\t//Seteo de respuesta\n\t\t\$respuesta['respuesta'] = false;\n\t\t\$respuesta['errorInfo'] = '';\n\t\t\$respuesta['codigoError'] = null;");
$nombreMinuscula = strtolower($nombre);
fwrite($miArchivo, "\n\t\t//Sql\n\t\t\$sql = \"SELECT * FROM $nombreMinuscula\";\n\t\tif(\$stringBusqueda != ''){\n\t\t\t\$sql.= \" WHERE \$stringBusqueda\";\n\t\t}");
fwrite($miArchivo, "\n\t\t\$base = new $db();");
fwrite($miArchivo, "\n\t\ttry {\n\t\t\tif(\$base->Iniciar()){\n\t\t\t\tif(\$base->Ejecutar(\$sql)){\n\t\t\t\t\tif(\$row2 = \$base->Registro()){\n\t\t\t\t\t\t");
foreach ($Pepito as $key => $value) {
    if(!str_contains($value, 'Static') && !str_contains($value, 'mensajeOp')){
        if(!str_contains($value, 'obj')){
            //no es objeto
            $capitalize = ucfirst($value);
            fwrite($miArchivo, "\n\t\t\t\t\t\t\$this->set$capitalize(\$row2['$value']);");
        }else{
            //es un objeto
            $stringSinObj = strtolower(str_replace('obj', 'id', $value));
            //var_dump($stringSinObj);
            $stringSolo = ucfirst(str_replace('obj', '', $value));
            $capitalize = ucfirst($stringSinObj);
            $capital = ucfirst($value);
            //$capitalizeSs = ucfirst($stringSolo);
            fwrite($miArchivo, "\n\t\t\t\t\t\t\$id = \$row2['$stringSinObj'];\n\t\t\t\t\t\t\$obj$stringSolo = new $stringSolo();\n\t\t\t\t\t\t\$arrayDeBusqueda['$stringSinObj'] = \$id;\n\t\t\t\t\t\t\$obj$stringSolo"."->buscar(\$arrayDeBusqueda);\n\t\t\t\t\t\t\$this->set$capital(\$obj$stringSolo);");
        }
        
    }
}
fwrite($miArchivo, "\n\t\t\t\t\t\t\$respuesta['respuesta'] = true;");
fwrite($miArchivo, "\n\t\t\t\t\t}\n\t\t\t\t}else{\n\t\t\t\t\t\$this->setMensajeOp(\$base->getError());\n\t\t\t\t\t\$respuesta['respuesta'] = false;\n\t\t\t\t\t\$respuesta['errorInfo'] = 'Hubo un error en la consulta';\n\t\t\t\t\t\$respuesta['codigoError'] = 1;\n\t\t\t\t}");
fwrite($miArchivo, "\n\t\t\t}else{\n\t\t\t\t\$this->setMensajeOp(\$base->getError());\n\t\t\t\t\$respuesta['respuesta'] = false;\n\t\t\t\t\$respuesta['errorInfo'] = 'Hubo un error con la conexion a la db';\n\t\t\t\t\$respuesta['codigoError'] = 0;\n\t\t\t}");
fwrite($miArchivo, "\n\t\t} catch (\\Throwable \$th){\n\t\t\t\$respuesta['respuesta'] = false;\n\t\t\t\$respuesta['errorInfo'] = \$th;\n\t\t\t\$respuesta['codigoError'] = 3;\n\t\t}\n\t\t\$base = null;\n\t\treturn \$respuesta;\n\t}");
//Cierre de clase 
fwrite($miArchivo, "\n}");


