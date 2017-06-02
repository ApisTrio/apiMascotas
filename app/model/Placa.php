<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Placa
{
    private $db;
    private $table = 'placas';
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

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idPlaca = ?");
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
    
    public function Insert($codigo)
    {
		try 
		{

                $sql = "INSERT INTO $this->table
                            (activa,codigo)
                            VALUES (?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array(0,$codigo)); 
              		 
              $this->response->idInsertado = $this->db->lastInsertId();
            
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
			            ->prepare("DELETE FROM $this->table WHERE idPlaca = ?");			          

			$stm->execute(array($id['idPlaca']));
            
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
                        WHERE idPlaca = ?");                     

            $stm->execute(array($id['idPlaca']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Codigos()
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT codigo FROM $this->table");
            $stm->execute();
            
            return $stm->fetchAll();
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}