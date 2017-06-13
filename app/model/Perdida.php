<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Perdida
{
    private $db;
    private $table = 'perdidas';
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

			$stm = $this->db->prepare("SELECT idMascota, codigo, nombre, foto, genero, 
            TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())  AS anios,
            (TIMESTAMPDIFF(MONTH,fecha_nacimiento,CURDATE()) - (TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) * 12)) AS meses
            FROM mascotas INNER JOIN perdidas
            ON perdidas.mascotas_idMascota = idMascota
            INNER JOIN mascotas_has_placas
            ON mascotas_has_placas.mascotas_idMascota = idMascota
            INNER JOIN placas
            ON placas_idPlaca = idPlaca
            AND mascotas.borrado IS NULL
            AND mascotas_has_placas.borrado IS NULL
            AND placas.bloqueado IS NULL
            AND encontrado IS NULL");
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

    
    public function GetDueno($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idModelo = ?");
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
