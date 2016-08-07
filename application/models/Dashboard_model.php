<?php   defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->config->load('general');
        $this->load->helper('fecha');
    }
    
    public function recursos_totales() {
        //$sql = "select count(*) as total from pd_publicacion pb INNER JOIN pd_pub_bd bd ON pb.pub_id=bd.pub_id  INNER JOIN pd_revision rv ON rv.bd_id=bd.bd_id  RIGHT JOIN pd_validacion vl ON rv.rev_id=vl.rev_id where responsable_id=".$this->db->escape($matricula)."";
        $this->db->select('count(*) as total');
        $query = $this->db->get("publicacion");
        //return $query->num_rows();
        $resultados = $query->result();
        if(!empty($resultados)){
            return $resultados[0]->total;
        } else {
            return false;
        }
    }

    public function validado() {
        $publicacion_estado = $this->config->item('publicacion_estado');
        
        $this->db->select('count(*) as total');
        $this->db->where('est_pub_id', $publicacion_estado['revisada']);
        $query = $this->db->get("publicacion");
        //pr($this->db->last_query());
        $resultados = $query->result();
        if(!empty($resultados)){
            return $resultados[0]->total;
        } else {
            return false;
        }
    }

    public function mis_recursos_totales($matricula) {
        $this->db->select('count(*) as total');
        $this->db->where('responsable_id', $matricula);
        $query = $this->db->get("publicacion");
        $resultados = $query->result();
        
        if(!empty($resultados)){
            return $resultados[0]->total;
        } else {
            return false;
        }
    }

    public function mis_validados($matricula) {
        $publicacion_estado = $this->config->item('publicacion_estado');
        
        $this->db->select('count(*) as total');
        $this->db->where('responsable_id', $matricula);
        $this->db->where('est_pub_id', $publicacion_estado['revisada']);
        $query = $this->db->get("publicacion");
        //pr($this->db->last_query());
        $resultados = $query->result();
        if(!empty($resultados)){
            return $resultados[0]->total;
        } else {
            return false;
        }
    }
    /*
    public function estado_recurso($matricula) {
                
        $sql = "
            SELECT
                vl.ev_id, COUNT(*) AS num
               FROM 
                   pd_publicacion pb INNER JOIN pd_pub_bd bd ON pb.pub_id=bd.pub_id  INNER JOIN pd_revision rv ON rv.bd_id=bd.bd_id  INNER JOIN pd_validacion vl ON rv.rev_id=vl.rev_id
               WHERE 
                   pb.responsable_id=".$this->db->escape($matricula)."
                       GROUP BY
                       vl.ev_id";
        
        $query = $this->db->query($sql);

        $resultados = $query->result();
        //return $query->num_rows();
        if(!empty($resultados)){
            foreach ($query->result() as $row) {
                $estado[$row->ev_id] = $row->num;
            }
            return $estado;
        } else {
            return false;
        }
        
    }
    
    public function validados_ei($matricula) {
        
        $sql = "
            SELECT 
                rv.rev_tipo, COUNT(*) as num
            FROM 
                pd_publicacion pb INNER JOIN pd_pub_bd bd ON pb.pub_id=bd.pub_id  INNER JOIN pd_revision rv ON rv.bd_id=bd.bd_id  INNER JOIN pd_validacion vl ON rv.rev_id=vl.rev_id
            WHERE 
                pb.responsable_id=".$this->db->escape($matricula)." 
                       AND
                vl.ev_id =1 
            GROUP BY 
            rv.rev_tipo";
        
        $query = $this->db->query($sql);
        //return $query->num_rows();
        
        $resultados = $query->result();
        if(!empty($resultados)){
            foreach ($query->result() as $row)  {
                $revTipo[$row->rev_tipo ] = $row->num;
            }
            return $revTipo;
        } else {
            return false;
        }
    }*/
    
    
    public function ultimo_recurso($matricula) {        
        $sql = "
            SELECT 
                pb.pub_id, pb.pub_issn, vl.val_fecha, tt.t_titulo, /*rv.rev_folio,*/ rv.rev_tipo, es.est_nombre
            FROM 
                pd_titulo tt INNER JOIN pd_publicacion pb ON tt.pub_id=pb.pub_id INNER JOIN pd_pub_bd bd ON pb.pub_id=bd.pub_id INNER JOIN pd_revision rv ON rv.bd_id=bd.bd_id INNER JOIN pd_validacion vl ON rv.rev_id=vl.rev_id LEFT JOIN pd_estado_val es ON vl.ev_id=es.ev_id
            WHERE
                vl.val_fecha= (SELECT   MAX(v.val_fecha)
                    FROM 
                        pd_publicacion p INNER JOIN pd_pub_bd b ON p.pub_id=b.pub_id INNER JOIN pd_revision r ON r.bd_id=b.bd_id  INNER JOIN pd_validacion v ON r.rev_id=v.rev_id
                    WHERE 
                        p.responsable_id=".$this->db->escape($matricula).")
                AND
                    pb.responsable_id=".$this->db->escape($matricula)." GROUP BY pb.pub_id";
        
        $query = $this->db->query($sql);
        //return $query->num_rows();
        $resultados = $query->result();
        if(!empty($resultados)){
            foreach ($query->result() as $row)  {
                $ultimo['pub_id'] = $row->pub_id;
                $ultimo['pub_issn'] = $row->pub_issn;
                $ultimo['val_fecha'] = $row->val_fecha;
                $ultimo['t_titulo'] = $row->t_titulo;
                //$ultimo['rev_folio'] = $row->rev_folio;
                $ultimo['rev_tipo'] = $row->rev_tipo;
                $ultimo['est_nombre'] = $row->est_nombre;
            }
            return $ultimo;
        }  else {
        
            return false;
            
        }
        
    }
    
    /*
    public function no_validados_ei($matricula) {
        
        $sql = "
            SELECT 
                rv.rev_tipo, COUNT(*) as num
            FROM 
                pd_publicacion pb INNER JOIN pd_pub_bd bd ON pb.pub_id=bd.pub_id  INNER JOIN pd_revision rv ON rv.bd_id=bd.bd_id  INNER JOIN pd_validacion vl ON rv.rev_id=vl.rev_id
            WHERE 
                pb.responsable_id=".$this->db->escape($matricula)." 
                       AND
                vl.ev_id !=1 

            GROUP BY 
            rv.rev_tipo";
        
        $query = $this->db->query($sql);
        //return $query->num_rows();
        $resultados = $query->result();
        if(!empty($resultados)){
            foreach ($query->result() as $row)  {            
                $revTipo[$row->rev_tipo ] = $row->num;
            }
            return $revTipo;
        }else{
            return false;
        }
    }*/
    
    
   

}
   