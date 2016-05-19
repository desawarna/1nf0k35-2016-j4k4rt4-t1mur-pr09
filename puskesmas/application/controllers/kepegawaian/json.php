<?php
class Json extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('kepegawaian/drh_model');
		$this->load->model('mst/puskesmas_model');
	}

	function json_alamat($id=""){
		$this->authentication->verify('kepegawaian','show');


		$data	  	= array();
		$filter 	= array();
		$filterLike = array();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'date_received' || $field == 'date_accepted') {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where('nip_nit',$id);
		$rows = $this->drh_model->get_data_alamat($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'nip_nit'			=> $act->nip_nit,
				'urut'				=> $act->urut,
				'alamat'			=> $act->alamat,
				'rt'				=> $act->rt,
				'rw'				=> $act->rw,
				'propinsi'			=> $act->value,
				'kota'				=> $act->value,
				'kecamatan'			=> $act->nama,
				'kelurahan'			=> $act->value,
				'code_cl_province'	=> $act->code_cl_province,
				'code_cl_district'	=> $act->code_cl_district,
				'code_cl_kec'		=> $act->code_cl_kec,
				'code_cl_village'	=> $act->code_cl_village,
				// 'view'		=> 1,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$size = sizeof($data);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function json_diklat($id=""){
		$this->authentication->verify('kepegawaian','show');


		$data	  	= array();
		$filter 	= array();
		$filterLike = array();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'date_received' || $field == 'date_accepted') {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where('nip_nit',$id);
		$rows = $this->drh_model->get_data_diklat($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		// var_dump($rows);
		// exit();
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kursus'			=> $act->id_kursus,
				'nama_kursus'		=> $act->nama_kursus,
				'id_mst_peg_kursus'	=> $act->id_mst_peg_kursus,
				'nip_nit'			=> $act->nip_nit,
				'nama_diklat'		=> $act->nama_diklat,
				'lama_diklat'		=> $act->lama_diklat,
				'tgl_diklat'		=> $act->tgl_diklat,
				'tar_penyelenggara'	=> $act->tar_penyelenggara,
				// 'view'		=> 1,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$size = sizeof($data);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}


	function json_dp3($id=""){
		$this->authentication->verify('kepegawaian','show');


		$data	  	= array();
		$filter 	= array();
		$filterLike = array();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'date_received' || $field == 'date_accepted') {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where('nip_nit',$id);
		$rows = $this->drh_model->get_data_dp3($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		// var_dump($rows);
		// exit();
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'nip_nit'			=> $act->nip_nit,
				'tahun'				=> $act->tahun,
				'setia'				=> $act->setia,
				'prestasi'			=> $act->prestasi,
				'tanggungjawab'		=> $act->tanggungjawab,
				'taat'				=> $act->taat,
				'jujur'				=> $act->jujur,
				'kerjasama'			=> $act->kerjasama,
				'pimpin'			=> $act->pimpin,
				'prakarsa'			=> $act->prakarsa,
				'jumlah'			=> $act->jumlah,
				'ratarata'			=> $act->ratarata,
				// 'view'		=> 1,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$size = sizeof($data);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
}