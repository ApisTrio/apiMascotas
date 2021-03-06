<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Raza
{
    private $db;
    private $table = 'razas';
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
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }

    public function RazasEspecie($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE borrado IS NULL AND especies_idEspecie = ?");
            $stm->execute(array($id));
            
            
            $this->response->result = $stm->fetchAll();
            
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
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idRaza = ?");
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
         $data = array_map( "null" , $data);
		try 
		{
            if(isset($data['idRaza']))
            {
                $sql = "UPDATE $this->table SET 
                            raza = ?
                        WHERE idRaza = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['raza'],
                            $data['idRaza']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (raza,especies_idEspecie)
                            VALUES (?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['raza'],$data['especies_idEspecie'])); 
              		 
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
			            ->prepare("DELETE FROM $this->table WHERE idRaza = ?");			          

			$stm->execute(array($id['idRaza']));
            
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
                        WHERE idRaza = ?");                     

            $stm->execute(array($id['idRaza']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}