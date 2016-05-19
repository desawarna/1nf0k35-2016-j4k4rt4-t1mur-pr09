<?php
class Setting_model extends CI_Model {

    var $tabel    = 'sms_config';

    function __construct() {
        parent::__construct();
    }
    

    function get_data()
    {
        $query = $this->db->get($this->tabel);
		foreach($query->result_array() as $key=>$value){
			$data[$value['key']]=$value['value'];
		}
        return $data;
    }

    function update_entry()
    {

		$path['value']=$this->input->post('path');
		$this->db->update($this->tabel, $path, array('key' => 'path'));

		$cardnumber['value']=$this->input->post('cardnumber');
		$this->db->update($this->tabel, $cardnumber, array('key' => 'cardnumber'));

		$daemon_status['value']=$this->input->post('daemon_status');
		$this->db->update($this->tabel, $daemon_status, array('key' => 'daemon_status'));

		$ussd['value']=$this->input->post('ussd');
		$this->db->update($this->tabel, $ussd, array('key' => 'ussd'));

		$com['value']=$this->input->post('com');
		$this->db->update($this->tabel, $com, array('key' => 'com'));

		$gammurc = $this->parser->parse("sms/setting/gammurc",$com);
		$fp = fopen($path['value'].'gammurc', "w");
		fputs($fp,$gammurc);

		$com['hostname']	= $this->db->hostname;
		$com['username']	= $this->db->username;
		$com['password']	= $this->db->password;
		$com['database']	= $this->db->database;

		$smsdrc = $this->parser->parse("sms/setting/smsdrc",$com);
		$fp = fopen($path['value'].'smsdrc', "w");
		fputs($fp,$smsdrc);

		return true;
    }
	
}