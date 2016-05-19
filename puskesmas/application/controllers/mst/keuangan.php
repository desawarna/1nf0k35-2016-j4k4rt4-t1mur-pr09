<?php
class Keuangan extends CI_Controller {

    public function __construct(){
		parent::__construct();
	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group'] = "Keuangan";
		$data['title_form'] = "Master Data - Keuangan";

		$data['content'] = "";

		$this->template->show($data,"home");
	}

}
?>
