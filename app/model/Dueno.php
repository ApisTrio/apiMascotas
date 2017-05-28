<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Dueno
{
    private $db;
    private $table = 'duenos';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function getAll()
    {
		try
		{
			$result = array();

			$query = $this->db->prepare("SELECT * FROM $this->table");
			$query->execute();

			$this->response->setResponse(true);
            $this->response->result = $query->fetchAll();
            return $this->response;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}  
    }

    public function get($id)
    {
        try
        {
            $result = array();

            $query = $this->db->prepare("SELECT * FROM $this->table WHERE idDueno = ? LIMIT 1");
            $query->execute([$id]);

            $this->response->setResponse(true);
            $this->response->result = $query->fetch();
            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
    }
    
    public function insertOrUpdate($data)
    {
		try 
		{
            if(isset($data['idDueno'])){

                $sql = "UPDATE $this->table SET campo = ? WHERE idDueno = ?";
                $query = $this->db->prepare($sql);
                $query->execute([$data['campo'],$data['idDueno']]);

            } else {

                $fields = "idDueno, nombre, apellido, telefono, email, nacimiento, direccion, borrado, creado, actualizado";
                $sql = "INSERT INTO $this->table ($fields) VALUES (NULL, ?, ?, ?, ?, ?, ?, NULL, ?, NULL)";
                $query = $this->db->prepare($sql);

                $values = [$data['nombre'], $data['apellido'], $data['telefono'], $data['email'], date('Y-m-d',strtotime($data['nacimiento'])), $data['direccion'], date('Y-m-d H:i:s')];
                $query->execute($values); 
              		 
                $this->response->idInsertado = $this->db->lastInsertId();

            }
            
			$this->response->setResponse(true);
            return $this->response;
		}
        catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }
    
    public function delete($id)
    {
		try 
		{
			$query = $this->db->prepare("UPDATE $this->table SET borrado = ? WHERE idUsuario = ?");
            $query->execute([date('Y-m-d H:i:s'),$id]);
            
			$this->response->setResponse(true);
            return $this->response;
		} 
        catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }
}