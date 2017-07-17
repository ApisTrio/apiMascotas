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

			$query = $this->db->prepare("SELECT idUsuario, pass, usuario, emailU, borrado, creado, activo, actualizado, duenos_idDueno FROM $this->table WHERE usuario = ? LIMIT 1");

			$query->execute([$data['usuario']]);

            $usuario = $query->fetch();
            
            if($usuario){

                if ($usuario->activo) {

                    if($usuario->borrado == NULL){

                        if( password_verify($data['pass'], $usuario->pass ) ){

                            $query2 = $this->db->prepare("SELECT * FROM duenos WHERE idDueno = ? LIMIT 1");
                            $query2->execute([$usuario->duenos_idDueno]);

                            $dueno = $query2->fetch();

                            $this->response->setResponse(true);
                            $this->response->result = ['usuario' => $usuario, 'dueno' => $dueno];
                            return $this->response;

                        }

                        $this->response->setResponse(false, 'Clave invalida');
                        return $this->response;
                    
                    }
                    
                    $this->response->setResponse(false, 'Usuario borrado');
                    return $this->response; 

                }
             
                $this->response->setResponse(false, 'Usuario inactivo');
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

            $query = $this->db->prepare("SELECT idUsuario, usuario, token, emailU, borrado, creado, actualizado, duenos_idDueno FROM $this->table WHERE idUsuario = ? AND borrado IS NULL LIMIT 1");
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
            if(isset($data['idUsuario'])){

                $fields = "usuario, pass, emailU, token, duenos_idDueno";
                $sql = "UPDATE $this->table SET usuario = ? , emailU = ? WHERE idUsuario = ? AND borrado IS NULL";
                $query = $this->db->prepare($sql);
                $query->execute([$data['usuario'],$data['emailU']]);

            } else {

                $fields = "usuario, pass, emailU, token, duenos_idDueno";
                $sql = "INSERT INTO $this->table ($fields) VALUES (?, ?, ?, ?, ?)";
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

            $query = $this->db->prepare("SELECT idUsuario, emailU, usuario, borrado, creado, actualizado, duenos_idDueno FROM $this->table WHERE $field = ? AND borrado IS NULL LIMIT 1");
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

    public function activar($data)
    {
        try 
        {
            $query = $this->db->prepare("UPDATE $this->table SET token = NULL, activo = 1 WHERE idUsuario = ? AND token = ?");
            $query->execute([$data['idUsuario'],$data['token']]);
            
            $this->response->setResponse(true);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function recordarUsuario($data)
    {
        try 
        {
            $query = $this->db->prepare("SELECT * FROM $this->table WHERE emailU = ? LIMIT 1");
            $query->execute([$data['emailU']]);
            $usuario = $query->fetch();

            if($usuario){

                $query2 = $this->db->prepare("SELECT * FROM duenos WHERE idDueno = ? LIMIT 1");
                $query2->execute([$usuario->duenos_idDueno]);
                $dueno = $query2->fetch();

                $this->response->setResponse(true);
                $this->response->result = ['nombre' => $dueno->nombre, 'apellido' => $dueno->apellido, 'usuario' => $usuario->usuario];
                return $this->response;

            }
            
            $this->response->setResponse(false);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function cambiarContrasena($data)
    {
        try 
        {
            $query = $this->db->prepare("UPDATE $this->table SET pass = ? WHERE idUsuario = ?");
            $query->execute([password_hash($data['pass'], PASSWORD_DEFAULT), $data['id']]);

            
            $this->response->setResponse(true);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function cambiarEmail($data)
    {
        try 
        {
            $query = $this->db->prepare("UPDATE $this->table SET emailU = ? WHERE idUsuario = ?");
            $query->execute([$data['email'], $data['id']]);

            $this->response->setResponse(true);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function confirmarCuentaDatos($id)
    {
        try 
        {
            $query = $this->db->prepare("SELECT usuario, nombre, apellido, emailU, token FROM usuarios INNER JOIN duenos ON duenos.idDueno = usuarios.duenos_idDueno WHERE idUsuario = ?");
            $query->execute([$id]);
            $usuario = $query->fetch();

            if($usuario){

                $this->response->setResponse(true);
                $this->response->result = $usuario;
                return $this->response;

            }
            
            $this->response->setResponse(false);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}