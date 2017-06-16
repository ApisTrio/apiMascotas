<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Modelo
{
    private $db;
    private $table = 'modelos';
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

    public function GetForma($forma)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE forma = ? AND borrado IS NULL");
            $stm->execute(array($forma));
            
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

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idModelo = ?");
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
            if(isset($data['idModelo']))
            {
                $sql = "UPDATE $this->table SET 
                            modelo = ?,
                            forma = ?
                        WHERE idModelo = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['modelo'],
                            $data['forma'],
                            $data['idModelo']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (modelo,forma)
                            VALUES (?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['modelo'],$data['forma'])); 
              		 
              $this->response->idInsertado = $this->db->lastInsertId();
            }
            
			$this->response->setResponse(true);
            return $this->response;
		}catch (Exception $e) 
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
                        WHERE idModelo = ?");                     

            $stm->execute(array($id['idModelo']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}