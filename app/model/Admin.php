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

            $query = $this->db->prepare("SELECT usuarios.idusuario, usuarios.usuario, usuarios.activo, duenos.nombre, duenos.apellido, duenos.telefono, duenos.pais, duenos.provincia, duenos.ciudad, duenos.codigo_postal, usuarios.emailU, usuarios.duenos_idDueno FROM usuarios INNER JOIN duenos ON usuarios.duenos_idDueno = duenos.idDueno WHERE usuarios.borrado IS NULL");
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

                    $query3 = $this->db->prepare("SELECT placas.idPlaca, DATE_FORMAT(mascotas_has_placas.creado,'%d/%m/%Y') as creado, placas.codigo, modelos.modelo, modelos.forma, modelos.nombre FROM placas 
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

            $nexo = " WHERE";
            $sql_usuarios = "SELECT usuarios.idusuario, usuarios.usuario, usuarios.activo, duenos.nombre, duenos.apellido, duenos.telefono, duenos.pais, duenos.provincia, duenos.ciudad, duenos.codigo_postal, usuarios.emailU, usuarios.duenos_idDueno FROM usuarios INNER JOIN duenos ON usuarios.duenos_idDueno = duenos.idDueno WHERE usuarios.borrado IS NULL";

            if ( isset($args['usuario']) and !empty($args['usuario']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " usuarios.usuario LIKE '%".$args['usuario']."%'";
            }
            if ( isset($args['telefono']) and !empty($args['telefono']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.telefono LIKE '%".$args['telefono']."%'";
            }
            if ( isset($args['email']) and !empty($args['email']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " usuarios.emailU LIKE '%".$args['email']."%'";
            }
            if ( isset($args['pais']) and !empty($args['pais']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.pais LIKE '%".$args['pais']."%'";
            }
            if ( isset($args['ciudad_provincia']) and !empty($args['ciudad_provincia']) ) {
                if ($nexo == "") { $sql_usuarios .= " WHERE"; } else if ($nexo == " WHERE") { $sql_usuarios .= " AND"; $nexo = " AND"; }
                $sql_usuarios .= " duenos.provincia LIKE '%".$args['ciudad_provincia']."%' OR duenos.ciudad LIKE '%".$args['ciudad_provincia']."%'";
            }
            if ( isset($args['codigo_postal']) and !empty($args['codigo_postal']) ) {
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
                    INNER JOIN duenos ON duenos.idDueno = duenos_has_mascotas.duenos_idDueno";
                

                    
                if ( isset($args['perdida']) and $args['perdida'] == "perdidas" ) {
                    $sql_mascotas .= " INNER JOIN perdidas ON perdidas.mascotas_idMascota = idMascota WHERE duenos.idDueno = ? AND mascotas.borrado IS NULL AND perdidas.encontrado IS NULL";
                }
                if ( !isset($args['perdida']) or empty($args['perdida']) or $args['perdida'] == "encontradas" ){
                    $sql_mascotas .= " WHERE duenos.idDueno = ? AND mascotas.borrado IS NULL";
                } 
                if ( isset($args['mascota']) and !empty($args['mascota']) ) {
                    $sql_mascotas .= " AND mascotas.nombre LIKE '%".$args['mascota']."%'";
                }  
                if ( isset($args['fecha']) and !empty($args['fecha']) ) {
                    $sql_mascotas .= " AND mascotas.fecha_nacimiento = STR_TO_DATE( '".$args['fecha']."', '%d/%m/%Y') ";
                } 
                if ( isset($args['especie']) and !empty($args['especie']) ) {
                    $sql_mascotas .= " AND especies.especie LIKE '%".$args['especie']."%'";
                }                
                if ( isset($args['raza']) and !empty($args['raza']) ) {
                    $sql_mascotas .= " AND razas.raza LIKE '%".$args['raza']."%'";
                }                  
            
                $sql_mascotas .= " GROUP BY idMascota";

                $query2 = $this->db->prepare($sql_mascotas);
                $query2->execute([$usuario->duenos_idDueno]);

                $mascotas = $query2->fetchAll();

                foreach ($mascotas as $keym => $mascota) {

                    if ( isset($args['perdida']) and $args['perdida'] == "encontradas" ) {
                        
                        $sql_perdida = "SELECT * FROM perdidas 
                            WHERE mascotas_idMascota = ?";

                        $query_perdida = $this->db->prepare($sql_perdida);

                        $query_perdida->execute([$mascota->idMascota]);

                        $perdida = $query_perdida->fetchAll();                       

                        if ( count($perdida) > 0 ) {
                        
                            if ( $perdida[0]->encontrado == NULL ) {

                                unset($mascotas[$keym]);
                        
                            }else {

                                $sql_placas = "SELECT placas.idPlaca, DATE_FORMAT(mascotas_has_placas.creado,'%d/%m/%Y') as creado, placas.codigo, modelos.modelo, modelos.forma, modelos.nombre FROM placas 
                                    INNER JOIN mascotas_has_placas on placas.idPlaca = mascotas_has_placas.placas_idPlaca 
                                    INNER JOIN modelos on mascotas_has_placas.modelos_idModelo = modelos.idModelo
                                    WHERE mascotas_has_placas.mascotas_idMascota = ? AND 
                                    mascotas_has_placas.borrado IS NULL";

                                if ( isset($args['id']) and !empty($args['id']) ) {
                                    $sql_placas .= " AND placas.codigo LIKE '%".$args['id']."%'";
                                }                    
                                if ( isset($args['forma']) and !empty($args['forma']) ) {
                                    $sql_placas .= " AND modelos.forma = '".$args['forma']."'";
                                }  

                                $query3 = $this->db->prepare($sql_placas);

                                $query3->execute([$mascota->idMascota]);

                                $placas = $query3->fetchAll();

                                if ( count($placas) > 0 ) {

                                    $mascotas[$keym]->placas = $placas; 
                                
                                }else{

                                    if(isset($args['perdida']) and $args['perdida'] == "perdidas" or isset($args['mascota']) and !empty($args['mascota']) or isset($args['fecha']) and !empty($args['fecha']) or isset($args['especie']) and !empty($args['especie']) or isset($args['raza']) and !empty($args['raza']) or isset($args['perdida']) and $args['perdida'] == "encontradas"  or isset($args['perdida']) and $args['perdida'] == "perdidas" or isset($args['id']) or !empty($args['id']) or isset($args['forma']) and !empty($args['forma']) ){

                                        unset($mascotas[$keym]);

                                    }
                                    
                                }   

                            }

                        }else {

                            unset($mascotas[$keym]);

                        }

                    } else {

                        $sql_placas = "SELECT placas.idPlaca, DATE_FORMAT(mascotas_has_placas.creado,'%d/%m/%Y') as creado, placas.codigo, modelos.modelo, modelos.forma FROM placas 
                            INNER JOIN mascotas_has_placas on placas.idPlaca = mascotas_has_placas.placas_idPlaca 
                            INNER JOIN modelos on mascotas_has_placas.modelos_idModelo = modelos.idModelo
                            WHERE mascotas_has_placas.mascotas_idMascota = ? AND 
                            mascotas_has_placas.borrado IS NULL";

                        if ( isset($args['id']) or !empty($args['id']) ) {
                            $sql_placas .= " AND placas.codigo LIKE '%".$args['id']."%'";
                        }                    
                        if ( isset($args['forma']) and $args['forma'] != "" ) {
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

                }

                $mascotas = array_values($mascotas); 

                if ( count($mascotas) > 0 ) {

                    $usuarios[$keyu]->mascotas = $mascotas;
                
                }else{

                    if(isset($args['usuario']) and !empty($args['usuario']) or isset($args['telefono']) and !empty($args['telefono']) or isset($args['email']) and !empty($args['email']) or isset($args['pais']) and !empty($args['pais']) or isset($args['ciudad_provincia']) and !empty($args['ciudad_provincia']) or isset($args['codigo_postal']) and !empty($args['codigo_postal']) or isset($args['perdida']) and $args['perdida'] == "perdidas" or isset($args['mascota']) and !empty($args['mascota']) or isset($args['fecha']) and !empty($args['fecha']) or isset($args['especie']) and !empty($args['especie']) or isset($args['raza']) and !empty($args['raza']) or isset($args['perdida']) and $args['perdida'] == "encontradas"  or isset($args['perdida']) and $args['perdida'] == "perdidas" or isset($args['id']) or !empty($args['id']) or isset($args['forma']) and !empty($args['forma']) ){

                        unset($usuarios[$keyu]);
                            
                    }
                    
                }

            }

            $usuarios = array_values($usuarios);

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