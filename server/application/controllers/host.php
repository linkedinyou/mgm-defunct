<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Host extends CI_Controller {
    
    public function index(){
        if(!$this->client->validate()){
            die(json_encode(array('Success' => false, 'Message' => "Access Denied")));
        }
        if($_SESSION['userLevel'] < 250){
            die(json_encode(array('Success' => false, 'Message' => "Permission Denied")));
        }
        session_write_close();

        $hosts = array();
        $query = $this->db->get('hosts');
        foreach($query->result() as $h){
            $host = array();
            $host['address'] = $h->address;
            $host['name'] = $h->name;
            $host['status'] = json_decode($h->status);
            $host['capacity'] = $h->slots;
            array_push($hosts, $host);
        }
        
        die(json_encode(array('Success' => true, 'Hosts' => $hosts)));
    }
    
    public function add(){
        if(!$this->client->validate()){
            die(json_encode(array('Success' => false, 'Message' => "Access Denied")));
        }
        if($_SESSION['userLevel'] < 250){
            die(json_encode(array('Success' => false, 'Message' => "Permission Denied")));
        }
        session_write_close();
        $input_data = json_decode(trim(file_get_contents('php://input')), true);
        $address = $input_data['host'];
        if(filter_var($address, FILTER_VALIDATE_IP) === false){
            die(json_encode(array('Success' => false, 'Message' => "Invalid Address")));
        }
        
        $this->db->insert("hosts", array("address" => $address));
        $id = $this->db->insert_id();
        if($id){
            die(json_encode(array('Success' => true, 'id' => $id)));
        } else {
            die(json_encode(array('Success' => false, 'Message' => "Error adding host")));
        }
    }
    
    public function remove(){
        if(!$this->client->validate()){
            die(json_encode(array('Success' => false, 'Message' => "Access Denied")));
        }
        if($_SESSION['userLevel'] < 250){
            die(json_encode(array('Success' => false, 'Message' => "Permission Denied")));
        }
        session_write_close();
        if(!$this->client->validate()){
            die(json_encode(array('Success' => false, 'Message' => "Access Denied")));
        }
        if($_SESSION['userLevel'] < 250){
            die(json_encode(array('Success' => false, 'Message' => "Permission Denied")));
        }
        session_write_close();
        $input_data = json_decode(trim(file_get_contents('php://input')), true);
        $address = $input_data['host'];
        if(filter_var($address, FILTER_VALIDATE_IP) === false){
            die(json_encode(array('Success' => false, 'Message' => "Invalid Address")));
        }
        
        $this->db->where(array("address" => $address));
        $this->db->delete("hosts");
        if($this->db->affected_rows() == 0){
            die(json_encode(array('Success' => false, 'Message' => "could not remove host from database")));
        }
        die(json_encode(array('Success' => true)));
    }
}

?>
