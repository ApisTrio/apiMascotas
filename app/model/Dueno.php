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

			$query = $this->db->prepare("SELECT * FROM $this->table WHERE borrado IS NULL");
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

    public function mascotaDuenos($id)
    {
        try
        {
            $result = [];

            $stm = $this->db->prepare("SELECT duenos.idDueno, duenos.nombre, duenos.apellido, duenos.telefono, duenos.email, DATE_FORMAT(duenos.nacimiento, '%d/%m/%Y') AS nacimiento, duenos.direccion, duenos.pais, duenos.provincia, duenos.ciudad, duenos.codigo_postal, duenos.sexo
                FROM duenos
                INNER JOIN duenos_has_mascotas on duenos_idDueno = idDueno
                INNER JOIN mascotas on duenos_has_mascotas.mascotas_idMascota = idMascota
                WHERE  idMascota = ?
                AND duenos.borrado IS NULL ORDER BY duenos.creado");
            $stm->execute([$id]);
            
            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            
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

            $query = $this->db->prepare("SELECT nombre, apellido, telefono, email, DATE_FORMAT(nacimiento, '%d/%m/%Y') as nacimiento, direccion, pais, provincia, ciudad, codigo_postal, sexo FROM $this->table WHERE idDueno = ? AND borrado IS NULL LIMIT 1");
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

        

        $data = array_map( "null" , $data);
        
		try 
		{
            if(isset($data['idDueno'])){

                $sql = "UPDATE $this->table SET nombre = ?, apellido = ?, telefono = ?, email = ?, nacimiento = STR_TO_DATE( ?, '%d/%m/%Y'), direccion = ?, pais = ?, provincia = ?, ciudad = ?, codigo_postal = ?, sexo = ? WHERE idDueno = ?";
                $query = $this->db->prepare($sql);
                $values = [$data['nombre'], $data['apellido'], $data['telefono'], $data['email'], $data['nacimiento'], $data['direccion'], $data['pais'], $data['provincia'], $data['ciudad'], $data['codigo_postal'], $data['sexo'], $data['idDueno']];
                $query->execute($values);

            } else {

                $fields = "nombre, apellido, telefono, email, nacimiento, direccion, pais, provincia, ciudad, codigo_postal, sexo";
                $sql = "INSERT INTO $this->table ($fields) VALUES (?, ?, ?, ?, STR_TO_DATE( ?, '%d/%m/%Y'), ?, ?, ?, ?, ?, ?)";
                $query = $this->db->prepare($sql);

                $values = [$data['nombre'], $data['apellido'], $data['telefono'], $data['email'], $data['nacimiento'], $data['direccion'], $data['pais'], $data['provincia'], $data['ciudad'], $data['codigo_postal'], $data['sexo']];
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
			$query = $this->db->prepare("UPDATE $this->table SET borrado = NOW() WHERE idDueno = ?");
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
            $query = $this->db->prepare("DELETE FROM $this->table WHERE idDueno = ?");
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