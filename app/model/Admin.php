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

            $query = $this->db->prepare("SELECT usuarios.idusuario, usuarios.usuario, duenos.nombre, duenos.apellido, duenos.telefono, duenos.pais, duenos.provincia, duenos.ciudad, duenos.codigo_postal, usuarios.emailU, usuarios.duenos_idDueno FROM usuarios INNER JOIN duenos ON usuarios.duenos_idDueno = duenos.idDueno");
            $query->execute();

            $usuarios = $query->fetchAll();

            foreach ($usuarios as $keyu => $usuario) {
                
                $query2 = $this->db->prepare("SELECT mascotas.idMascota, mascotas.nombre, DATE_FORMAT(mascotas.fecha_nacimiento,'%d/%m/%Y') as fecha_nacimiento, razas.raza, especies.especie 
                    FROM mascotas 
                    INNER JOIN razas ON mascotas.razas_idRaza = razas.idRaza 
                    INNER JOIN especies ON razas.especies_idEspecie = especies.idEspecie 
                    INNER JOIN duenos_has_mascotas ON duenos_has_mascotas.mascotas_idMascota  = mascotas.idMascota 
                    INNER JOIN duenos ON duenos.idDueno = duenos_has_mascotas.duenos_idDueno
                    WHERE duenos.idDueno = ?
                    AND mascotas.borrado IS NULL
                    GROUP BY idMascota");
                $query2->execute([$usuario->duenos_idDueno]);

                $mascotas = $query2->fetchAll();

                foreach ($mascotas as $keym => $mascota) {

                    $query3 = $this->db->prepare("SELECT placas.idPlaca, placas.codigo, modelos.modelo FROM placas 
                        INNER JOIN mascotas_has_placas on placas.idPlaca = mascotas_has_placas.placas_idPlaca 
                        INNER JOIN modelos on mascotas_has_placas.modelos_idModelo = modelos.idModelo
                        WHERE mascotas_has_placas.mascotas_idMascota = ? AND 
                        mascotas_has_placas.borrado IS NULL");

                    $query3->execute([$mascota->idMascota]);

                    $placas = $query3->fetchAll();

                    $mascotas[$keym]->placas = $placas;                    
               
                }

                $usuarios[$keyu]->mascotas = $mascotas;


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

    public function superBusqueda($args)
    {
        try
        {
            $result = array();

            $nexo = "";
            $sql_usuarios = "SELECT usuarios.idusuario, usuarios.usuario, duenos.nombre, duenos.apellido, duenos.telefono, duenos.pais, duenos.provincia, duenos.ciudad, duenos.codigo_postal, usuarios.emailU, usuarios.duenos_idDueno FROM usuarios INNER JOIN duenos ON usuarios.duenos_idDueno = duenos.idDueno";

            if ( !empty($args['usuario']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; $nexo = " WHERE"; }
                $sql_usuarios .= " usuarios.usuario LIKE '%".$args['usuario']."%'";
            }
            if ( !empty($args['telefono']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.telefono LIKE '%".$args['telefono']."%'";
            }
            if ( !empty($args['email']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " usuarios.emailU LIKE '%".$args['email']."%'";
            }
            if ( !empty($args['email']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " usuarios.emailU LIKE '%".$args['email']."%'";
            }
            if ( !empty($args['pais']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.pais LIKE '%".$args['pais']."%'";
            }
            if ( !empty($args['ciudad_provincia']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.provincia LIKE '%".$args['ciudad_provincia']."%' OR duenos.ciudad LIKE '%".$args['ciudad_provincia']."%'";
            }
            if ( !empty($args['codigo_postal']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.codigo_postal LIKE '%".$args['codigo_postal']."%'";
            }

            $query = $this->db->prepare($sql_usuarios);
            $query->execute();

            $usuarios = $query->fetchAll();

            foreach ($usuarios as $keyu => $usuario) {

                $sql_mascotas = "SELECT mascotas.idMascota, mascotas.nombre, DATE_FORMAT(mascotas.fecha_nacimiento,'%d/%m/%Y') as fecha_nacimiento, razas.raza, especies.especie 
                    FROM mascotas 
                    INNER JOIN razas ON mascotas.razas_idRaza = razas.idRaza 
                    INNER JOIN especies ON razas.especies_idEspecie = especies.idEspecie 
                    INNER JOIN duenos_has_mascotas ON duenos_has_mascotas.mascotas_idMascota  = mascotas.idMascota 
                    INNER JOIN duenos ON duenos.idDueno = duenos_has_mascotas.duenos_idDueno
                    INNER JOIN perdidas ON perdidas.mascotas_idMascota = idMascota
                    WHERE duenos.idDueno = ?
                    AND mascotas.borrado IS NULL";
                
                if ( !empty($args['mascota']) ) {
                    $sql_mascotas .= " AND mascotas.nombre LIKE '%".$args['mascota']."%'";
                }   
                if ( !empty($args['especie']) ) {
                    $sql_mascotas .= " AND especies.especie LIKE '%".$args['especie']."%'";
                }                
                if ( !empty($args['raza']) ) {
                    $sql_mascotas .= " AND razas.raza LIKE '%".$args['raza']."%'";
                }                  
                if ( $args['perdida'] = "encontradas" ) {
                    $sql_mascotas .= " AND perdidas.encontrado IS NOT NULL";
                }else if ($args['perdida'] = "perdidas") {
                    $sql_mascotas .= " AND perdidas.encontrado IS NULL";
                }              
                $sql_mascotas .= " GROUP BY idMascota";

                $query2 = $this->db->prepare($sql_mascotas);
                $query2->execute([$usuario->duenos_idDueno]);

                $mascotas = $query2->fetchAll();

                foreach ($mascotas as $keym => $mascota) {

                    $sql_placas = "SELECT placas.idPlaca, placas.codigo, modelos.modelo FROM placas 
                        INNER JOIN mascotas_has_placas on placas.idPlaca = mascotas_has_placas.placas_idPlaca 
                        INNER JOIN modelos on mascotas_has_placas.modelos_idModelo = modelos.idModelo
                        WHERE mascotas_has_placas.mascotas_idMascota = ? AND 
                        mascotas_has_placas.borrado IS NULL";

                    if ( !empty($args['id']) ) {
                        $sql_placas .= " AND placas.codigo LIKE '%".$args['id']."%'";
                    }                    
                    if ( !empty($args['forma']) ) {
                        $sql_placas .= " AND modelos.forma = '".$args['forma']."'";
                    }  

                    $query3 = $this->db->prepare($sql_placas);

                    $query3->execute([$mascota->idMascota]);

                    $placas = $query3->fetchAll();

                    if ( count($placas) > 0 ) {
                        $mascotas[$keym]->placas = $placas; 
                    }else{
                        unset($mascotas[$keym]);    
                    }                   
               
                }

                if ( count($mascotas) > 0 ) {
                    $usuarios[$keyu]->mascotas = $mascotas;
                }else{
                    unset($usuarios[$keyu]);
                }



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