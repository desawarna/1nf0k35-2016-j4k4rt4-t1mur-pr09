<?php
class Lap_kir_model extends CI_Model {

    var $tabel       = 'mst_inv_ruangan';
    var $t_puskesmas = 'cl_phc';
	var $lang	     = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
	function get_pilihan_kondisi(){
		$this->db->select('code as id, value as val');
		$this->db->where('tipe','keadaan_barang');
		$q = $this->db->get('mst_inv_pilihan');
		return $q;
	}

	function get_data_detail($start=0, $limit=9999999, $options=array()){
		$txt = "SELECT * FROM(
			SELECT * FROM
				(
					SELECT * FROM
						(
							SELECT * FROM
								(
									SELECT * FROM inv_inventaris_distribusi
									ORDER BY tgl_distribusi DESC,id_inventaris_distribusi DESC
								) AS a
							WHERE a.tgl_distribusi <= ?
							GROUP BY a.id_inventaris_barang
						) AS b
					WHERE
						id_ruangan = ?
				) z
			INNER JOIN (
				SELECT
					id_inventaris_barang,
					id_mst_inv_barang,
					nama_barang,
					tanggal_pengadaan,
					year(tanggal_diterima) as tahun,
					barang_kembar_proc,
					harga,
					keterangan_inventory as keterangan,
					IFNULL(
						b.pilihan_keadaan_barang,
						a.pilihan_keadaan_barang
					) AS kondisi
				FROM
					inv_inventaris_barang a
				LEFT JOIN (
					SELECT * FROM inv_keadaan_barang
					WHERE tanggal <= ?
					ORDER BY tanggal DESC,id_keadaan_barang DESC
				) b USING (id_inventaris_barang)	
				GROUP BY id_inventaris_barang
				ORDER BY tanggal DESC
			) x USING (id_inventaris_barang)
			WHERE
			tgl_distribusi <= ?
			AND id_cl_phc = ?
			AND id_ruangan = ?) as inv ";

		$tgl 		= $this->session->userdata('filter_tanggal');
		$id_cl_phc 	= $this->session->userdata('filter_code_cl_phc');
		$id_ruang 	= $this->session->userdata('filter_id_ruang');
		$query 		= $this->db->query($txt, array($tgl, $id_ruang, $tgl, $tgl, $id_cl_phc,$id_ruang));
		
		$rows = array();
		$data = $query->result_array();
		$kondisi = $this->get_pilihan_kondisi()->result();
		foreach ($data as $row) {
			$real_kondisi = $this->get_detail_kondisi($row['id_inventaris_barang'], $tgl);
			foreach($kondisi as $k){
				$row[$k->id] = ($real_kondisi==$k->id ? 1:0);
			}
			$detail = $this->get_detail_inventaris($row['id_inventaris_barang']);
			$row = array_merge($row,$detail);
			$row['jml'] = 1;
			$rows[] = $row;
		}

		return $rows;
	}

	function get_data_detail_group($start=0,$limit=9999999, $options=array()){
		$txt = "SELECT  * FROM (
			SELECT  * FROM (
					SELECT * FROM (
							SELECT * FROM (
									SELECT * FROM
										inv_inventaris_distribusi
									ORDER BY
										tgl_distribusi DESC,id_inventaris_distribusi DESC
								) AS a
							WHERE a.tgl_distribusi <= ?
							GROUP BY a.id_inventaris_barang
						) AS b
					WHERE id_ruangan = ?
				) z
			INNER JOIN (
				SELECT
					id_inventaris_barang,
					id_mst_inv_barang,
					nama_barang,
					tanggal_pengadaan,
					year(tanggal_diterima) as tahun,
					barang_kembar_proc,
					harga,
					IFNULL(
						b.pilihan_keadaan_barang,
						a.pilihan_keadaan_barang
					) AS kondisi
				FROM
					inv_inventaris_barang a
				LEFT JOIN (
					SELECT * FROM inv_keadaan_barang
					WHERE tanggal <= ?
					ORDER BY tanggal DESC,id_keadaan_barang DESC
				) b USING (id_inventaris_barang)	
				GROUP BY id_inventaris_barang
				ORDER BY tanggal DESC
			) x USING (id_inventaris_barang)
			WHERE
			tgl_distribusi <= ?
			AND id_cl_phc = ?
			AND id_ruangan = ?
			group by barang_kembar_proc
		) as inv";
		$tgl = $this->session->userdata('filter_tanggal');
		$id_cl_phc = $this->session->userdata('filter_code_cl_phc');
		$id_ruang = $this->session->userdata('filter_id_ruang');

		$query = $this->db->query($txt, array($tgl, $id_ruang, $tgl, $tgl, $id_cl_phc,$id_ruang));
		
		$jml = 0;
		$rows = array();
		$data = $query->result_array();
		$kondisi = $this->get_pilihan_kondisi()->result();
		foreach ($data as $row) {
			foreach($kondisi as $k){
				$n = $this->get_jumlah_kondisi($k->id, $row['barang_kembar_proc']);
				$row[$k->id] = $n;
				$jml += $n;
			}

			$detail = $this->get_detail_inventaris($row['id_inventaris_barang']);
			$row = array_merge($row,$detail);
			$row['jml'] = $jml;
			$rows[] = $row;
		}

		return $rows;
	}


	function get_detail_inventaris($id){
		$this->db->select('inv_inventaris_barang_b.*,bahan.value as bahan');
		$this->db->join('mst_inv_pilihan as bahan','bahan.code=inv_inventaris_barang_b.pilihan_bahan AND bahan.tipe="bahan"','left');
		$this->db->where('id_inventaris_barang',$id);
		$q = $this->db->get('inv_inventaris_barang_b');
		$datab = $q->row_array();

		$this->db->where('id_inventaris_barang',$id);
		$q = $this->db->get('inv_inventaris_barang_e');
		$datae = $q->row_array();

		$data = array_merge($datab,$datae);

		if(!empty($data)){
			return $data;
		}else{
			$data = array(
				'merek_type'		=>'',
				'identitas_barang'	=>'',
				'ukuran_barang'		=>'',
				'merek_type'		=>'',
				'merek_type'		=>'',
				);
			return $data;
		}
	}

	function get_jumlah_kondisi($kondisi, $kembar){
		$tgl = $this->session->userdata('filter_tanggal');
			
		$txt = "select * from (SELECT * FROM 
				(
				SELECT * FROM
				(SELECT * FROM inv_inventaris_distribusi ORDER BY tgl_distribusi DESC,id_inventaris_distribusi DESC) AS a WHERE a.tgl_distribusi <= ? 
				GROUP BY a.id_inventaris_barang
				) 
				AS b WHERE id_ruangan=?) z 
				inner JOIN (SELECT
					id_inventaris_barang, IFNULL(b.pilihan_keadaan_barang,a.pilihan_keadaan_barang) as kondisi
				FROM
					inv_inventaris_barang a
				LEFT JOIN (
					SELECT
						*
					FROM
						inv_keadaan_barang
					WHERE
						tanggal <= ?
					ORDER BY
						tanggal DESC,id_keadaan_barang DESC
				) b USING (id_inventaris_barang)
				where a.barang_kembar_proc = ?
				GROUP BY
					id_inventaris_barang
				ORDER BY tanggal desc) x using(id_inventaris_barang)
				where barang_kembar_inv = ? and tgl_distribusi <= ?
				and id_cl_phc = ? and id_ruangan = ? and kondisi = ?";
		$code_cl_phc = $this->session->userdata('filter_code_cl_phc');
		$id_ruangan = $this->session->userdata('filter_id_ruang');
		$query = $this->db->query($txt,array($tgl, $id_ruangan, $tgl, $kembar, $kembar, $tgl, $code_cl_phc, $id_ruangan, $kondisi))->result_array();
		return count($query);		
	}

	function get_data_detail_groupxxx($start=0,$limit=999999,$options=array()){
		//filter puskes
		$filter_code_cl_phc = $this->session->userdata('filter_code_cl_phc');
		$filter_id_ruang = $this->session->userdata('filter_id_ruang');
		$filter_tanggal = $this->session->userdata('filter_tanggal');

		if(!empty($filter_code_cl_phc) and $filter_code_cl_phc != 'none'){		
			$this->db->where('inv_inventaris_distribusi.id_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			
			//filter ruang
			if(!empty($filter_id_ruang) and $filter_id_ruang != '0'){
				if($this->session->userdata('filter_id_ruang') == 'none'){
					$this->db->where('inv_inventaris_distribusi.id_ruangan','0');
				}else if($this->session->userdata('filter_id_ruang') == 'all'){
					
				}else{
					$this->db->where('inv_inventaris_distribusi.id_ruangan',$this->session->userdata('filter_id_ruang'));
				}
			}					
			
		}else{
			$this->db->where('inv_inventaris_distribusi.id_ruangan');
		}
		//filter date
		if(!empty($filter_tanggal) and $filter_tanggal != '0'){
			$this->db->where('inv_inventaris_distribusi.tgl_distribusi',$this->session->userdata('filter_tanggal'));
		}else{
			$this->db->where('inv_inventaris_distribusi.status','1');
		}
		
		$this->db->select('inv_inventaris_barang.id_inventaris_barang, id_mst_inv_barang, nama_barang, register, year(tanggal_pembelian) as tahun,  harga, barang_kembar_inv ');
		$this->db->join('inv_inventaris_distribusi','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang ');
		$this->db->order_by('barang_kembar_inv');
		$this->db->group_by('barang_kembar_proc');
		$q = $this->db->get('inv_inventaris_barang', $limit, $start);
		return $q->result_array();
	}
	
    function get_data($start=0,$limit=999999,$options=array())
    {
    	$this->db->select('mst_inv_ruangan.*,`cl_phc`.`value` AS puskesmas, count(inv_inventaris_distribusi.id_inventaris_distribusi) as jml, sum(inv_inventaris_barang.harga) as nilai');
	    $this->db->join('cl_phc', 'mst_inv_ruangan.code_cl_phc = cl_phc.code', 'inner'); 
	    $this->db->join('inv_inventaris_distribusi', 'inv_inventaris_distribusi.id_cl_phc = mst_inv_ruangan.code_cl_phc AND inv_inventaris_distribusi.id_ruangan = mst_inv_ruangan.id_mst_inv_ruangan AND inv_inventaris_distribusi.status=1', 'left'); 
	    $this->db->join('inv_inventaris_barang', 'inv_inventaris_barang.id_inventaris_barang = inv_inventaris_distribusi.id_inventaris_barang', 'left'); 
	    $query = $this->db->group_by('`mst_inv_ruangan`.code_cl_phc,`mst_inv_ruangan`.id_mst_inv_ruangan');
	    $query = $this->db->get('mst_inv_ruangan',$limit,$start);
    	return $query->result();
    }

    function get_data_puskesmas($start=0,$limit=999999,$options=array())
    {
    	$this->db->order_by('value','asc');
    	// $this->db->where(code)
        $query = $this->db->get($this->t_puskesmas,$limit,$start);
        return $query->result();
    }

    function get_ruangan_id($puskesmas="")
    {
    	$this->db->select('max(id_mst_inv_ruangan) as id');
    	$this->db->where('code_cl_phc',$puskesmas);
    	$jum = $this->db->get('mst_inv_ruangan')->row();
    	
    	if (empty($jum)){
    		return 1;
    	}else {
			return $jum->id+1;
    	}

	}
	
	function get_data_deskripsi($kode, $id){
		$this->db->select("IFNULL(value,'-') as value, IFNULL(nama_ruangan,'-') as nama_ruangan, IFNULL(keterangan,'-') as keterangan",false);
		$this->db->where("code_cl_phc",$kode);
		$this->db->where("id_mst_inv_ruangan",$id);
		$this->db->join("mst_inv_ruangan", 'mst_inv_ruangan.code_cl_phc=cl_phc.code','left');
		$query = $this->db->get("cl_phc");
		return $query->result();
	}
	
 	function get_data_row($kode,$id){
		$data = array();
		$this->db->where("code_cl_phc",$kode);
		$this->db->where("id_mst_inv_ruangan",$id);
		$query = $this->db->get_where($this->tabel);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('code_cl_phc'=>$data));
    }

	function get_detail_kondisi($id_barang, $tgl){
		$this->db->select('id_inventaris_barang, pilihan_keadaan_barang');
		$this->db->where('tanggal <=', $tgl);
		$this->db->where('id_inventaris_barang', $id_barang);
		$this->db->order_by('tanggal','desc');
		$this->db->order_by('id_keadaan_barang','desc');
		$q = $this->db->get('inv_keadaan_barang')->row();
		if(!empty($q)){
			return $q->pilihan_keadaan_barang;
		}else{
			return "B";
		}
	}
}