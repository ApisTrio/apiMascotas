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

			$query = $this->db->prepare("SELECT idUsuario, usuario, borrado, creado, actualizado, duenos_idDueno FROM $this->table WHERE usuario = ? LIMIT 1");
			$query->execute([$data['usuario']]);

            $usuario = $query->fetch();

            if($usuario->borrado == NULL){

                if( password_verify($data['pass'], $usuario->pass ) ){

                    $query2 = $this->db->prepare("SELECT * FROM duenos WHERE ususarios_idUsuario = ? LIMIT 1");
                    $query2->execute([$usuario->idUsuario]);

                    $dueno = $query2->fetch();

                    $this->response->setResponse(true);
                    $this->response->result = ['usuario' => $usuario, 'dueno' => $dueno];
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

			$query = $this->db->prepare("SELECT idUsuario, usuario, borrado, creado, actualizado, duenos_idDueno FROM $this->table WHERE borrado IS NULL");
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

            $query = $this->db->prepare("SELECT idUsuario, usuario, borrado, creado, actualizado, duenos_idDueno FROM $this->table WHERE idUsuario = ? AND borrado IS NULL LIMIT 1");
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

                $sql = "UPDATE $this->table SET usuario = ?, WHERE idUsuario = ? AND borrado IS NULL";
                $query = $this->db->prepare($sql);
                $query->execute([$data['usuario'],$data['idUsuario']]);

            } else {

                $fields = "usuario, pass, emailU, token, duenos_idDueno";
                $sql = "INSERT INTO $this->table ($fields) VALUES (?, ?, ?, ?)";
                $query = $this->db->prepare($sql);

                $values = [
                    $data['usuario'], 
                    password_hash($data['pass'], PASSWORD_DEFAULT), 
                    $data['emailU'],
                    substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16),
                    $data['duenos_idDueno']
                ];
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

            $query = $this->db->prepare("SELECT idUsuario, usuario, borrado, creado, actualizado, duenos_idDueno FROM $this->table WHERE $field = ? AND borrado IS NULL LIMIT 1");
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
}