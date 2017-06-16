<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Especie
{
    private $db;
    private $table = 'especies';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function GetAll()
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE borrado IS NULL");
			$stm->execute();
            
			
            $this->response->result = $stm->fetchAll();
            
            if($this->response->result)
                $this->response->setResponse(true);

            else
                $this->response->setResponse(false);
            
            return $this->response;
            return $this->response;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idEspecie = ?");
			$stm->execute(array($id));

			
            $this->response->result = $stm->fetch();

            if($this->response->result)
                $this->response->setResponse(true);

            else
                $this->response->setResponse(false);
            
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
            if(isset($data['idEspecie']))
            {
                $sql = "UPDATE $this->table SET 
                            especie = ?
                        WHERE idEspecie = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['especie'],
                            $data['idEspecie']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (especie)
                            VALUES (?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['especie'])); 
              		 
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
			            ->prepare("DELETE FROM $this->table WHERE idEspecie = ?");			          

			$stm->execute(array($id['idEspecie']));
            
			$this->response->setResponse(true);
            return $this->response;
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }

    public function Borrar($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE $this->table SET 
                            borrado = NOW()
                        WHERE idEspecie = ?");                     

            $stm->execute(array($id['idEspecie']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}