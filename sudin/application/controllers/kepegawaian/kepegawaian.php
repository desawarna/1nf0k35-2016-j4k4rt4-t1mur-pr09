<?php
class Kepegawaian extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
	}
	
	function index(){
		$this->authentication->verify('kepegawaian','show');

		$data['content'] = $this->parser->parse("kepegawaian/show",$data,true);
		$this->template->show($data,'home');
	}
}
