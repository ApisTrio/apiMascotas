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
    
    public function DuenoMascotas($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT idMascota, codigo, mascotas.nombre, DATE_FORMAT(fecha_nacimiento,'%d/%m/%Y') as fecha_nacimiento,
            TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())  AS anios,
            (TIMESTAMPDIFF(MONTH,fecha_nacimiento,CURDATE()) - (TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) * 12)) AS meses, foto, genero, peso, comentarios, chip, raza, especie, 
                perdidas.creado as perdida, perdidas.encontrado
                FROM mascotas
                INNER JOIN razas on razas_idRaza = idRaza 
                INNER JOIN especies on especies_idEspecie = idEspecie
                INNER JOIN duenos_has_mascotas on mascotas_idMascota = idMascota
                INNER JOIN duenos on duenos_has_mascotas.duenos_idDueno = idDueno
                INNER JOIN mascotas_has_placas
                ON mascotas_has_placas.mascotas_idMascota = idMascota
                INNER JOIN placas
                ON placas_idPlaca = idPlaca
                LEFT JOIN perdidas
                ON perdidas.mascotas_idMascota = idMascota
                WHERE  idDueno = ?
                AND mascotas.borrado IS NULL
                AND mascotas_has_placas.borrado IS NULL
                AND placas.bloqueado IS NULL
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
    
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT idMascota, codigo, mascotas.nombre, DATE_FORMAT(fecha_nacimiento,'%d/%m/%Y') as fecha_nacimiento,
            TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())  AS anios,
            (TIMESTAMPDIFF(MONTH,fecha_nacimiento,CURDATE()) - (TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) * 12)) AS meses, foto, genero, peso, comentarios, chip, idRaza, raza, idEspecie, especie, 
                perdidas.creado as perdida, perdidas.encontrado
                FROM mascotas
                INNER JOIN razas on razas_idRaza = idRaza 
                INNER JOIN especies on especies_idEspecie = idEspecie
                INNER JOIN duenos_has_mascotas on mascotas_idMascota = idMascota
                INNER JOIN duenos on duenos_has_mascotas.duenos_idDueno = idDueno
                INNER JOIN mascotas_has_placas
                ON mascotas_has_placas.mascotas_idMascota = idMascota
                INNER JOIN placas
                ON placas_idPlaca = idPlaca
                LEFT JOIN perdidas
                ON perdidas.mascotas_idMascota = idMascota
                WHERE  idMascota = ?
                AND mascotas.borrado IS NULL
                AND mascotas_has_placas.borrado IS NULL
                AND placas.bloqueado IS NULL
                GROUP BY idMascota");
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
    
    public function Insert($data)
    {
        $data = array_map( "null" , $data);
        
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

    public function Edad($a,$m)
    {
        
          if ($a == 0 ){
            $anios = "";
          }
          else if ($a == 1 ){
             $anios = "1 Año";
          }
          else if ($a > 1 ){
            $anios = $a." Años";
          }

           if ($m == 0 ){
            $meses = "";
          }
          else if ($m == 1 ){
             $meses = " y 1 mes";
          }
          else if ($m > 1 ){
            $meses = " y ".$m." meses";
          }

          return $anios.$meses;
    }

    public function nuevaMascotaDatos($id)
    {
        try 
        {
            $query = $this->db->prepare("SELECT mascotas.nombre AS nombremascota, usuario, duenos.nombre, apellido, emailU 
                FROM mascotas
                INNER JOIN duenos_has_mascotas on mascotas_idMascota = mascotas.idMascota
                INNER JOIN duenos ON duenos_has_mascotas.duenos_idDueno = duenos.idDueno 
                INNER JOIN usuarios ON duenos.idDueno = usuarios.duenos_idDueno 
                WHERE idMascota = ?");
            $query->execute([$id]);
            $datos = $query->fetch();

            if($datos){

                $this->response->setResponse(true);
                $this->response->result = $datos;
                return $this->response;

            }
            
            $this->response->setResponse(false);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function asignarFoto($id, $nombreimg)
    {
        try 
        {
            $query = $this->db->prepare("SELECT * FROM $this->table WHERE idMascota = ?");
            $query->execute([$id]);
            $datos = $query->fetch();

            if($datos){

                unlink('./public/images/mascotas/'.$datos->foto);

                $query2 = $this->db->prepare("UPDATE $this->table SET foto = ? WHERE idMascota = ?");
                $query2->execute([$nombreimg, $id]);

                $this->response->setResponse(true);
                $this->response->result = $datos;
                return $this->response;

            }
            
            $this->response->setResponse(false);
            return $this->response;
        } 
        catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

}