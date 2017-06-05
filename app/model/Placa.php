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

    public function Bloquear($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE $this->table SET 
                            bloqueado = NOW()
                        WHERE idPlaca = ?");                     

            $stm->execute(array($id['idPlaca']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Desbloquear($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE $this->table SET 
                            bloqueado = NULL
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

    public function Datos($codigo)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE codigo = ? ");
            $stm->execute(array($codigo));

            $this->response->result = $stm->fetch();

            if ($this->response->result){
                $this->response->setResponse(true);
            } 

            else{
                $this->response->setResponse(false,"Placa no existe");
            }
            
            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
    }

    public function Inactivas()
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE  activa = 0");
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
    
///////////////////////////////////////////////////////////////////////////////////////////////

    public function Asignar($data)
    {
        try 
        {
                $sql = "INSERT INTO mascotas_has_placas
                            (mascotas_idMascota,placas_idPlaca,forma,modelos_idModelo)
                            VALUES (?,?,?,?)";
                
            $this->db->prepare($sql)
                     ->execute(array($data["mascotas_idMascota"],$data["placas_idPlaca"],$data["forma"],$data["modelos_idModelo"])); 
            
            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function VerificarAsignada($codigo)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT mascotas_has_placas.borrado, mascotas_idMascota, placas_idPlaca
             FROM mascotas_has_placas 
                INNER JOIN placas on placas_idPlaca = idPlaca 
                WHERE codigo = ? AND mascotas_has_placas.borrado IS NULL");
            $stm->execute(array($codigo));

            $this->response->result = $stm->fetch();

            if ($this->response->result){
                $this->response->setResponse(true,"Placa asignada");
            } 

            else{
                $this->response->setResponse(false,"Placa no asignada");
            }
            
            
            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
    }

    public function Desactivar($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE mascotas_has_placas SET 
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

    public function Activar($id)
    {
        try 
        {
            $stm = $this->db
                        ->prepare("UPDATE mascotas_has_placas SET 
                            borrado = NULL
                        WHERE idPlaca = ?");                     

            $stm->execute(array($id['idPlaca']));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) 
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function PlacasMascota($datos)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT idPlaca, codigo, forma, mascotas_has_placas.creado,
            mascotas_has_placas.borrado, modelo FROM $this->table 
            INNER JOIN mascotas_has_placas on idPlaca = placas_idPlaca 
            INNER JOIN modelos on modelos_idModelo = idModelo
            WHERE idMascota = ? AND 
            borrado IS NULL");

            $stm->execute(array($id['idMascota']));
            
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

    public function PlacasMascotaBloqueadas($datos)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT idPlaca, codigo, forma, mascotas_has_placas.creado,
            mascotas_has_placas.borrado, modelo FROM $this->table 
            INNER JOIN mascotas_has_placas on idPlaca = placas_idPlaca 
            INNER JOIN modelos on modelos_idModelo = idModelo
            WHERE idMascota = ? AND 
            borrado IS NOT NULL");

            $stm->execute(array($id['idMascota']));
            
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
}