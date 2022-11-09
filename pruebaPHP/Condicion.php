<?php
trait Condicion
{

    //Metodo publico general
    public function SB($arrayBusqueda)
    {
        $stringBusqueda = '';
        if (count($arrayBusqueda) > 0) {
            foreach ($arrayBusqueda as $key => $value) {
                if ($value != null || $key == 'usdeshabilitado') {
                    $string = " $key = '$value' ";
                    if ($stringBusqueda == '') {
                        $stringBusqueda .= $string;
                    } else {
                        $stringBusqueda .= ' and ';
                        $stringBusqueda .= $string;
                    }
                }
            }
        }
        return $stringBusqueda;
    }

    //Metodo static general
    public static function SBS($arrayBusqueda)
    {
        $stringBusqueda = '';
        if (count($arrayBusqueda) > 0) {
            foreach ($arrayBusqueda as $key => $value) {
                if ($value != null || $key == 'usdeshabilitado') {
                    $string = " $key = '$value' ";
                    if ($stringBusqueda == '') {
                        $stringBusqueda .= $string;
                    } else {
                        $stringBusqueda .= ' and ';
                        $stringBusqueda .= $string;
                    }
                }
            }
        }
        return $stringBusqueda;
    }
}
