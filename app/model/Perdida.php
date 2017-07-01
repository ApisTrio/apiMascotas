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
    
    public function GetAll($limit,$offset)
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
            AND encontrado IS NULL 
            GROUP BY idMascota
            ORDER BY idMascota DESC 
            LIMIT $limit OFFSET $offset");

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

    
    public function GetDueno($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT idMascota, codigo, mascotas.nombre, foto, genero, DATE_FORMAT(fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento,
            TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())  AS anios,
            (TIMESTAMPDIFF(MONTH,fecha_nacimiento,CURDATE()) - (TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) * 12)) AS meses
            FROM mascotas INNER JOIN perdidas
            ON perdidas.mascotas_idMascota = idMascota
            INNER JOIN mascotas_has_placas
            ON mascotas_has_placas.mascotas_idMascota = idMascota
            INNER JOIN duenos_has_mascotas 
            on duenos_has_mascotas.mascotas_idMascota = idMascota
            INNER JOIN duenos on duenos_has_mascotas.duenos_idDueno = idDueno
            INNER JOIN placas
            ON placas_idPlaca = idPlaca
            AND mascotas.borrado IS NULL
            AND mascotas_has_placas.borrado IS NULL
            AND placas.bloqueado IS NULL
            AND encontrado IS NULL 
            WHERE idDueno = ?
            GROUP BY idMascota");

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

    public function Encontradas($limit,$offset)
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
            AND encontrado IS NOT NULL 
            GROUP BY idMascota
            ORDER BY idMascota DESC 
            LIMIT $limit OFFSET $offset");

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

    public function EncontradasDueno($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT idMascota, codigo, mascotas.nombre, foto, genero, DATE_FORMAT(fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento,
            TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())  AS anios,
            (TIMESTAMPDIFF(MONTH,fecha_nacimiento,CURDATE()) - (TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) * 12)) AS meses
            FROM mascotas INNER JOIN perdidas
            ON perdidas.mascotas_idMascota = idMascota
            INNER JOIN mascotas_has_placas
            ON mascotas_has_placas.mascotas_idMascota = idMascota
            INNER JOIN duenos_has_mascotas 
            on duenos_has_mascotas.mascotas_idMascota = idMascota
            INNER JOIN duenos on duenos_has_mascotas.duenos_idDueno = idDueno
            INNER JOIN placas
            ON placas_idPlaca = idPlaca
            AND mascotas.borrado IS NULL
            AND mascotas_has_placas.borrado IS NULL
            AND placas.bloqueado IS NULL
            AND encontrado IS NOT NULL 
            WHERE idDueno = ?
            GROUP BY idMascota");

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
    
    public function Insert($data)
    {
         $data = array_map( "null" , $data);
		try 
		{
                $sql = "INSERT INTO $this->table
                            (ubicacion,mensaje,mascotas_idMascota)
                            VALUES (?,?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data['ubicacion'],$data['mensaje'],
                         $data['idMascota'])); 
              		 
              $this->response->idInsertado = $this->db->lastInsertId();
            
			$this->response->setResponse(true);
            return $this->response;
		}catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }
    public function NuevaPerdida($datos)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE $this->table SET 
                            creado = STR_TO_DATE(?,'%d/%m/%Y'), encontrado = ?, ubicacion = ?, mensaje = ?
                        WHERE mascotas_idMascota = ?");                     

        $stm->execute(array($datos['fecha'], NULL, $datos['ubicacion'], $datos['mensaje'], $datos['idMascota']));
            
            $this->response->setResponse(true);
            $this->response->result = [];
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Encontrado($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE $this->table SET 
                            encontrado = NOW(), aviso = NULL
                        WHERE mascotas_idMascota = ?");                     

            $stm->execute(array($id['idMascota']));
            
            $this->response->setResponse(true);
             $this->response->result = false;
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

  

    public function Aviso($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE $this->table SET 
                            aviso = NOW()
                        WHERE mascotas_idMascota = ?");                     

            $stm->execute(array($id['idMascota']));
            $this->response->result = false;
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }



    public function Avisadas($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT idMascota, codigo, mascotas.nombre, foto, genero, DATE_FORMAT(fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento,
            TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())  AS anios,
            (TIMESTAMPDIFF(MONTH,fecha_nacimiento,CURDATE()) - (TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) * 12)) AS meses
            FROM mascotas INNER JOIN perdidas
            ON perdidas.mascotas_idMascota = idMascota
            INNER JOIN mascotas_has_placas
            ON mascotas_has_placas.mascotas_idMascota = idMascota
            INNER JOIN duenos_has_mascotas 
            on duenos_has_mascotas.mascotas_idMascota = idMascota
            INNER JOIN duenos on duenos_has_mascotas.duenos_idDueno = idDueno
            INNER JOIN placas
            ON placas_idPlaca = idPlaca
            AND mascotas.borrado IS NULL
            AND mascotas_has_placas.borrado IS NULL
            AND placas.bloqueado IS NULL
            AND encontrado IS NULL 
            AND aviso IS NOT NULL
            WHERE idDueno = ?
            GROUP BY idMascota");

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

    public function Verificar($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT encontrado, aviso
            FROM mascotas INNER JOIN perdidas
            ON perdidas.mascotas_idMascota = idMascota
            WHERE mascotas.borrado IS NULL
            AND idMascota = ?");

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

}
