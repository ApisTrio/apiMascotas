<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Mascota
{
    private $db;
    private $table = 'mascotas';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function UsuarioMascotas($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT mascotas.nombre, fecha_nacimiento, foto, genero, peso, comentarios, chip, raza, especie
                FROM mascotas
                INNER JOIN razas on razas_idRaza = idRaza 
                INNER JOIN especies on especies_idEspecie = idEspecie
                INNER JOIN duenos_has_mascotas on mascotas_idMascota = idMascota
                INNER JOIN duenos on duenos_has_mascotas.duenos_idDueno = idDueno
                WHERE  idDueno = ?
                AND mascotas.borrado IS NULL");
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
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE idMascota = ? ");
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
    
    public function Insert($data)
    {
		try 
		{
                $sql = "INSERT INTO $this->table
                            ( nombre,
                            foto,
                            genero,
                            peso,
                            comentarios,
                            fecha_nacimiento,
                            chip,
                            razas_idRaza)
                            VALUES (?,?,?,?,?,STR_TO_DATE( ?, '%d/%m/%Y'),?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['nombre'],
                            $data['foto'],
                            $data['genero'],
                            $data['peso'],
                            $data['comentarios'],
                            $data['fecha_nacimiento'],
                            $data['chip'],
                            $data['razas_idRaza'])); 
              		 
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
                            nombre = ?,
                            foto = ?,
                            genero = ?,
                            peso = ?,
                            comentarios = ?,
                            fecha_nacimiento = ?,
                            chip = ?,
                            razas_idRaza = ?
                        WHERE idMascota = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['nombre'],
                            $data['foto'],
                            $data['genero'],
                            $data['peso'],
                            $data['comentarios'],
                            $data['fecha_nacimiento'],
                            $data['chip'],
                            $data['razas_idRaza'],
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
    
    public function Delete($id)
    {
		try 
		{
			$stm = $this->db
			            ->prepare("DELETE FROM $this->table WHERE idMascota = ?");			          

			$stm->execute(array($id['idMascota']));
            
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
                        WHERE idMascota = ?");                     

            $stm->execute(array($id['idMascota']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }


    ////////////////////////

    public function InsertVacuna($data)
    {
        try 
        {
                $sql = "INSERT INTO $this->table
                            ( fecha,
                            recordatorio,
                            activo,
                            vacunas_idVacuna,
                            mascotas_idMascota
                            )
                            VALUES (STR_TO_DATE( ?, '%d/%m/%Y'),?,?,?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['nombre'],
                            $data['fecha'],
                            $data['recordatorio'],
                            $data['activo'],
                            $data['vacunas_idVacuna'],
                            $data['mascotas_idMascota'])); 
                     
              $this->response->idInsertado = $this->db->lastInsertId();
            
            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}