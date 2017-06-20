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
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idVacuna = ?");
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

			$stm->execute(array($id['idVacuna']));
            
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
                        WHERE idVacuna = ?");                     

            $stm->execute(array($id['idVacuna']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function notificables()
    {
        try 
        {
            $stm = $this->db->prepare("SELECT vacunas.vacuna, vacunas_mascotas.fecha, DATE_FORMAT(vacunas_mascotas.recordatorio,'%d/%m/%Y') as recordatorio, vacunas_mascotas.activo, mascotas.nombre AS nombremascota, duenos.nombre, duenos.apellido, usuarios.emailU
                    FROM vacunas_mascotas 
                    INNER JOIN vacunas ON vacunas.idVacuna = vacunas_mascotas.vacunas_idVacuna
                    INNER JOIN mascotas ON mascotas.idMascota = vacunas_mascotas.mascotas_idMascota
                    INNER JOIN duenos_has_mascotas ON duenos_has_mascotas.mascotas_idMascota  = mascotas.idMascota 
                    INNER JOIN duenos ON duenos.idDueno = duenos_has_mascotas.duenos_idDueno  
                    INNER JOIN usuarios ON usuarios.duenos_idDueno = duenos.idDueno
                    WHERE CURDATE() 
                    = DATE_SUB(vacunas_mascotas.recordatorio, INTERVAL 7 DAY) 
                    OR CURDATE() = DATE(vacunas_mascotas.recordatorio) AND vacunas_mascotas.activo = 1 AND vacunas_mascotas.borrado IS NULL");            

            $stm->execute();
            
            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}