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

    ////////////////////////

    public function InsertVacuna($data,$idMascota)
    {
        try 
        {
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
                            $idMascota)); 
                     
              $this->response->idInsertado = $this->db->lastInsertId();
            
            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }

    }

    public function UpdateVacuna($data,$idMascota)
    {
        try 
        {
            if(isset($idMascota))
            {
                $sql = "UPDATE vacunas_mascotas SET 
                            fecha = STR_TO_DATE( ?, '%d/%m/%Y'),
                            recordatorio = STR_TO_DATE( ?, '%d/%m/%Y'),
                            activo = ?,
                            vacunas_idVacuna = ?                     
                        WHERE mascotas_idMascota = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                             $data['fecha'],
                            $data['recordatorio'],
                            $data['activo'],
                            $data['vacunas_idVacuna'],
                            $idMascota
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
}