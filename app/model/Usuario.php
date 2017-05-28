<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Usuario
{
    private $db;
    private $table = 'usuarios';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function login($data)
    {
		try
		{
			$result = array();

			$query = $this->db->prepare("SELECT * FROM $this->table WHERE usuario = ? LIMIT 1");
			$query->execute([$data['usuario']]);

            $usuario = $query->fetch();

            if($usuario->borrado == NULL){

                if( password_verify($data['pass'], $usuario->pass ) ){

                    $this->response->setResponse(true);
                    $this->response->result = $usuario;
                    return $this->response;

                }

                $this->response->setResponse(false, 'Datos invalidos');
                return $this->response;

            }

            $this->response->setResponse(false, 'Su usuario no existe');
            return $this->response;
		}
		catch(Exception $e)
		{
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
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

            $query = $this->db->prepare("SELECT * FROM $this->table WHERE idUsuario = ? LIMIT 1");
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
            if(isset($data['idUsuario'])){

                $sql = "UPDATE $this->table SET 
                            usuario = ?
                            WHERE idUsuario = ?";
                $query = $this->db->prepare($sql);
                $query->execute([$data['usuario'],$data['idUsuario']]);

            } else {

                $fields = "idUsuario, usuario, pass, borrado, creado, actualizado, duenos_idDueno";
                $sql = "INSERT INTO $this->table ($fields) VALUES (NULL, ?, ?, NULL, ?, NULL, ?)";
                $query = $this->db->prepare($sql);

                $values = [$data['usuario'], password_hash($data['pass'], PASSWORD_DEFAULT), date('Y-m-d H:i:s'), $data['idDueno']];
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


    public function check($usuario)
    {
        try
        {
            $result = array();

            $query = $this->db->prepare("SELECT * FROM $this->table WHERE usuario = ? LIMIT 1");
            $query->execute([$usuario]);

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
}