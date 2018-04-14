<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class Usuarios extends REST_Controller {

    public function __construct () {
        parent::__construct();
    
        $this->load->database();
        $this->load->helper('url');
    
    }

    public function obtener_get($id=0) {

        if($id) $this->db->where('id',$id);

        $usuarios=[];
        $usuarios=$this->db->get('usuarios')->result_array();

        if(!empty($usuarios))
        {
            $this->set_response([
                'status' => TRUE,
                'message' => '',
                'result' => $usuarios],
                REST_Controller::HTTP_OK ); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Usuarios no encontrados',
                'result' => $usuarios],
                REST_Controller::HTTP_NOT_FOUND ); // NOT_FOUND (400) being the HTTP response code

        }


    }

    public function eliminar_delete ($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('usuarios');

        $this->set_response([
            'id' => $id,
            'message' => 'Registro eliminado',
            'result' => [] ],
            REST_Controller::HTTP_NO_CONTENT ); 
    }

    public function insertar_post(){
        $data=file_get_contents("php://input");
        $usuario=json_decode($data);
        $this->db->insert('usuarios',$usuario);
        $usuario->id=$this->db->insert_id();
        $this->set_response($usuario, REST_Controller::HTTP_CREATED);
    }

    public function actualizar_put(){
        $data=file_get_contents("php://input");
        $usuario=json_decode($data);

        $this->db->where('id',$usuario->id);
        $this->db->update('usuarios',$usuario);

        $this->set_response([
            'id' => $usuario->id,
            'message' => 'Registro actualizado',
            'result' => [] ],
            REST_Controller::HTTP_NO_CONTENT ); 

    }

}