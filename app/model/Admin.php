<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Admin
{
    private $db;
    private $table = 'admin';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function login($args)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM admin WHERE usuario = ? LIMIT 1");
			$stm->execute([$args['usuario']]);

            $usuario = $stm->fetch();

            if( password_verify($args['pass'], $usuario->pass ) ){

                $this->response->setResponse(true);
                $this->response->result = $usuario;
                return $this->response;

            }

            $this->response->setResponse(false, 'Datos invalidos');
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

			$stm = $this->db->prepare("SELECT * FROM $this->table");
			$stm->execute();

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

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE idAdmin = ? LIMIT 1");
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
    
    public function InsertOrUpdate($data)
    {
		try 
		{
            if(isset($data['idPrueba']))
            {
                $sql = "UPDATE $this->table SET 
                            campo = ?
                        WHERE idPrueba = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['campo'],
                            $data['idPrueba']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (campo)
                            VALUES (?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['campo'])); 
              		 
              $this->response->idInsertado = $this->db->lastInsertId();
            }
            
			$this->response->setResponse(true);
            return $this->response;
		}catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }
    
    public function Delete($id)
    {
		try 
		{
			$stm = $this->db
			            ->prepare("DELETE FROM $this->table WHERE idPrueba = ?");			          

			$stm->execute(array($id));
            
			$this->response->setResponse(true);
            return $this->response;
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }
}