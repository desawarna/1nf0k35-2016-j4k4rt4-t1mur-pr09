<?php
class Inventory_model extends CI_Model {

    var $tabel    = '';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
}