<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Informacion
{
    private $db;
    private $table = 'informacion_medica';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function Insert($data)
    {
		try 
		{
                $sql = "INSERT INTO $this->table
                            (desparasitacion_i,
                            desparasitacion_e,
                            centro,
                            veterinario,
                            direccion_veterinario,
                            telefono_veterinario,
                            mascotas_idMascota)
                            VALUES (STR_TO_DATE( ?, '%d/%m/%Y'),STR_TO_DATE( ?, '%d/%m/%Y'),?,?,?,?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array(
                            $data['desparasitacion_i'],
                            $data['desparasitacion_e'],
                            $data['centro'],
                            $data['veterinario'],
                            $data['direccion_veterinario'],
                            $data['telefono_veterinario'],
                            $data['idMascota'])); 
              		 
              $this->response->idInsertado = $this->db->lastInsertId();
            
			$this->response->setResponse(true);
            return $this->response;
		}catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }

    public function Update($data)
    {
        try 
        {
            if(isset($data['idMascota']))
            {
                $sql = "UPDATE $this->table SET 
                            desparasitacion_i = STR_TO_DATE( ?, '%d/%m/%Y'),
                            desparasitacion_e = STR_TO_DATE( ?, '%d/%m/%Y'),
                            centro = ?,
                            veterinario = ?,
                            direccion_veterinario = ?,
                            telefono_veterinario = ?                     
                        WHERE mascotas_idMascota = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['desparasitacion_i'],
                            $data['desparasitacion_e'],
                            $data['centro'],
                            $data['veterinario'],
                            $data['direccion_veterinario'],
                            $data['telefono_veterinario'],
                            $data['idMascota']
                        )
                    );
            }
            
            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function GetAll($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE borrado IS NULL AND mascotas_idMascota = ?");

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

    ////////////////////////

    public function InsertVacuna($data)
    {
        try 
        {
            if(isset($data["idVamas"]))
            {
                $sql = "UPDATE vacunas_mascotas SET 
                            fecha = STR_TO_DATE( ?, '%d/%m/%Y'),
                            recordatorio = STR_TO_DATE( ?, '%d/%m/%Y'),
                            activo = ?,
                            vacunas_idVacuna = ?                     
                        WHERE idVamas = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                             $data['fecha'],
                            $data['recordatorio'],
                            $data['activo'],
                            $data['vacunas_idVacuna'],
                            $data["idVamas"]
                        )
                    );
               
            }
            else{
                $sql = "INSERT INTO vacunas_mascotas
                            ( fecha,
                            recordatorio,
                            activo,
                            vacunas_idVacuna,
                            mascotas_idMascota
                            )
                            VALUES (STR_TO_DATE( ?, '%d/%m/%Y'),STR_TO_DATE( ?, '%d/%m/%Y'),?,?,?)";
            
                
            $this->db->prepare($sql)
                     ->execute(array(
                            $data['fecha'],
                            $data['recordatorio'],
                            $data['activo'],
                            $data['vacunas_idVacuna'],
                            $data["idMascota"])); 

            $this->response->idInsertado = $this->db->lastInsertId();
            }         
            
            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }

    }


    public function VacunasMascota($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT idVamas, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, DATE_FORMAT(recordatorio,'%d/%m/%Y') as fecha_recordatorio,
            activo as recordatorio_activo, vacuna FROM vacunas_mascotas
            INNER JOIN vacunas
            ON vacunas_idVacuna = idVacuna 
            WHERE vacunas_mascotas.borrado IS NULL AND mascotas_idMascota = ?");

            $stm->execute(array($id));
            
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

    public function Recordatorio($datos)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE vacunas_mascotas SET 
                            activo = ?
                        WHERE idVamas = ?");                     

            $stm->execute(array($datos['activo'],$datos['idVamas']));
            
            $this->response->setResponse(true);
             $this->response->result = false;
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}