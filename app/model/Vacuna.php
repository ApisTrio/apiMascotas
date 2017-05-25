<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Vacuna
{
    private $db;
    private $table = 'vacunas';
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
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idVacuna = ?");
			$stm->execute(array($id));

			$this->response->setResponse(true);
            $this->response->result = $stm->fetch();
            
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
            if(isset($data['idVacuna']))
            {
                $sql = "UPDATE $this->table SET 
                            vacuna = ?
                        WHERE idVacuna = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['vacuna'],
                            $data['idVacuna']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (vacuna)
                            VALUES (?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['vacuna'])); 
              		 
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
			            ->prepare("DELETE FROM $this->table WHERE idVacuna = ?");			          

			$stm->execute(array($id));
            
			$this->response->setResponse(true);
            return $this->response;
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }
}