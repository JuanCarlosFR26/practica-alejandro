<?php

// Clase del modelo para trabajar con objetos Item que se almacenan en BD en la tabla items_auto
class ItemAutoModel
{
    // Conexión a la BD
    protected $db;

    // Atributos del objeto item que coinciden con los campos de la tabla items_auto
    private $codigo;
    private $nombre;

    // Constructor que utiliza el patrón Singleton para tener una única instancia de la conexión a BD
    public function __construct()
    {
        // Traemos la única instancia de PDO
        $this->db = SPDO::singleton();
    }

    // Getters y Setters
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function setCodigo($codigo)
    {
        return $this->codigo = $codigo;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        return $this->nombre = $nombre;
    }

    // Método para obtener todos los registros de la tabla items_auto
    // Devuelve un array de objetos de la clase ItemModel
    public function getAll()
    {
        // Realizamos la consulta de todos los items
        $consulta = $this->db->prepare('SELECT * FROM items_auto');
        $consulta->execute();
        
        // OJO!! El fetchAll() funcionará correctamente siempre que el nombre
        // de los atributos de la clase coincida con los campos de la tabla
        $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "ItemAutoModel");

        // Devolvemos la colección para que la vista la presente.
        return $resultado;
    }

    // Método que devuelve (si existe en BD) un objeto ItemModel con un código determinado
    public function getById($codigo)
    {
        $gsent = $this->db->prepare('SELECT * FROM items_auto WHERE codigo = ?');
        $gsent->bindParam(1, $codigo);
        $gsent->execute();

        $gsent->setFetchMode(PDO::FETCH_CLASS, "ItemAutoModel");
        $resultado = $gsent->fetch();

        return $resultado;
    }

    // Método que almacena en BD un objeto ItemModel
    // Si tiene ya código actualiza el registro y si no tiene lo inserta
    public function save()
    {
        if (!isset($this->codigo)) {
            $consulta = $this->db->prepare('INSERT INTO items_auto(nombre) VALUES (?)');
            $consulta->bindParam(1, $this->nombre);
            $consulta->execute();
        } else {
            $consulta = $this->db->prepare('UPDATE items_auto SET nombre=? WHERE codigo=?');
            $consulta->bindParam(1, $this->nombre);
            $consulta->bindParam(2, $this->codigo);
            $consulta->execute();
        }
    }

    // Método que elimina el ItemModel de la BD
    public function delete()
    {
        $consulta = $this->db->prepare('DELETE FROM items_auto WHERE codigo=?');
        $consulta->bindParam(1, $this->codigo);
        $consulta->execute();
    }
}
?>
