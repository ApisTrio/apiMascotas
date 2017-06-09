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

                $fields = "nombre, apellido, telefono, email, nacimiento, direccion, pais, provincia, ciudad, codigo_postal, sexo";
                $sql = "INSERT INTO $this->table ($fields) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $query = $this->db->prepare($sql);

                $values = [$data['nombre'], $data['apellido'], $data['telefono'], $data['email'], date('d/m/Y',strtotime($data['nacimiento'])), $data['direccion'], $data['pais'], $data['provincia'], $data['ciudad'], $data['codigo_postal'], $data['sexo']];
                $query->execute($values); 
              		 
                $this->response->idInsertado = $this->db->lastInsertId();

            }
            
			$this->response->setResponse(true);
            return $this->response;
		}
        catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }
    
    public function softDelete($id)
    {
		try 
		{
			$query = $this->db->prepare("UPDATE $this->table SET borrado = NOW() WHERE idUsuario = ?");
            $query->execute([$id]);
            
			$this->response->setResponse(true);
            return $this->response;
		} 
        catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }

    public function delete($id)
    {
        try 
        {
            $query = $this->db->prepare("DELETE FROM $this->table WHERE idUsuario = ?");
            $query->execute([$id]);
            
            $this->response->setResponse(true);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function check($field,$value)
    {
        try
        {
            $result = array();

            $query = $this->db->prepare("SELECT * FROM $this->table WHERE $field = ? AND borrado IS NULL LIMIT 1");
            $query->execute([$value]);

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


    public function hasMascota($idd, $idm)
    {
        try
        {
            $result = array();

            $query = $this->db->prepare("INSERT INTO duenos_has_mascotas (duenos_idDueno, mascotas_idMascota) VALUES (?,?)");
            $query->execute([$idd, $idm]);

            $this->response->setResponse(true);
            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
   
    }
}