# generadorDeModelo
Es un pequeño proyecto para ingresar las columnas de una tabla (perteneciente a una db), el nombre que va a tener la clase y la db de la cual extiende, en base a esto solo se debe correr en un server con PHP 8.1 el archivo asd.php y ya generará la clase.
La clase se generará con un trait de Condición, este necesita recibir como parámetro un array asociativo donde las claves deben ser el nombre de la columna y el contenido por el cual se desea filtrar para devolver un string que se sumará a la consulta con un WHERE.
La clase contiene atributos, un constructor, getters y setters, un método cargar y un método buscar(este método posee un pequeño manejo de errores).
El método buscar soporta delegacion, si uno de los campos se le pasa como objTanto, va a generar un obj de la clase Tanto y va a llamar a su metodo buscar con el parámetro que se ha obtenido desde la db.
