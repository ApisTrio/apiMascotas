<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class Admin
{
    private $db;
    private $table = 'admin';
    private $response;
    
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function login($args)
    {
		try
		{
            $result = array();

            $query = $this->db->prepare("SELECT * FROM admin WHERE usuario = ? LIMIT 1");
            $query->execute([$args['usuario']]);

            $usuario = $query->fetch();

            if ($usuario) {


                if($usuario->borrado == NULL){

                    if( password_verify($args['pass'], $usuario->pass ) ){

                        $this->response->setResponse(true);
                        $this->response->result = $usuario;
                        return $this->response;

                    }

                    $this->response->setResponse(false, 'Datos invalidos');
                    return $this->response;

                }

                $this->response->setResponse(false, 'Su usuario no existe');
                return $this->response;

            }

      
            $this->response->setResponse(false, 'Su usuario no existe');
            return $this->response;  

            
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
    
    public function get($id)
    {
        try
        {
            $result = array();

            $query = $this->db->prepare("SELECT * FROM $this->table WHERE idAdmin = ? LIMIT 1");
            $query->execute([$id]);

            $this->response->setResponse(true);
            $this->response->result = $query->fetchAll();
            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
    }
    
    public function insertOrUpdate($data)
    {
		try 
		{
            if(isset($data['idPrueba'])){

                $sql = "UPDATE $this->table SET 
                            campo = ?
                        WHERE idAdmin = ?";
                
                $query = $this->db->prepare($sql);
                $query->execute( [$data['campo'], $data['idAdmin']] );
            
            } else {

                $sql = "INSERT INTO $this->table
                            (campo)
                            VALUES (?)";
                
                $query = $this->db->prepare($sql);
                $query->execute([$data['campo']]); 
              		 
                $this->response->idInsertado = $this->db->lastInsertId();
            
            }
            
			$this->response->setResponse(true);
            return $this->response;
        }
        catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }

    public function duenosMascotasPlacas()
    {
        try
        {
            $result = array();

            $query = $this->db->prepare("SELECT usuarios.idusuario, usuarios.usuario, duenos.nombre, duenos.apellido, duenos.telefono, duenos.pais, duenos.provincia, duenos.ciudad, duenos.codigo_postal, usuarios.emailU FROM usuarios INNER JOIN duenos ON usuarios.duenos_idDueno = duenos.idDueno");
            $query->execute();

            $usuarios = $query->fetchAll();

            foreach ($usuarios as $usuario) {
                
                $query = $this->db->prepare("SELECT mascotas.nombre, mascotas.fecha_nacimiento FROM usuarios INNER JOIN duenos ON usuarios.duenos_idDueno = duenos.idDueno");
                $query->execute();                
            }


            $this->response->setResponse(true);
            $this->response->result = $usuarios;
            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }  
    }
    

}