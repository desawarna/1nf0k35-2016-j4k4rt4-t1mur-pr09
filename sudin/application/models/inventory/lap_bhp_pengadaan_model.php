<?php
class Lap_bhp_pengadaan_model extends CI_Model {

    var $tabel       = 'mst_inv_ruangan';
    var $t_puskesmas = 'cl_phc';
	var $lang	     = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    function get_data_jenis()
    {
        $this->db->select('*');
        $query = $this->db->get('mst_inv_barang_habispakai_jenis');
        return $query->result();
    }
	function get_pilihan_kondisi(){
		$this->db->select('code as id, value as val');
		$this->db->where('tipe','keadaan_barang');
		$q = $this->db->get('mst_inv_pilihan');
		return $q;
	}
	/*SELECT inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai,mst_inv_barang_habispakai.uraian,mst_inv_pilihan.value, 
				mst_inv_barang_habispakai.harga AS harga_asli, 
				inv_inventaris_habispakai_pembelian_item.harga AS harga_beli,
				DATE_FORMAT(tgl_update, \" %m-%Y \") AS MONTH, SUM(jml) 
				FROM inv_inventaris_habispakai_pembelian_item
				JOIN mst_inv_barang_habispakai ON (mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = 
				inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai AND code_cl_phc=".'"'.$pusksmas.'"'." )
				LEFT JOIN mst_inv_pilihan ON (mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code AND mst_inv_pilihan.tipe='satuan_bhp' )
				WHERE inv_inventaris_habispakai_pembelian_item.tgl_update >=".'"'.$tanggal1.'"'." AND
							inv_inventaris_habispakai_pembelian_item.tgl_update <= ".'"'.$tanggal1.'"'."
				GROUP BY DATE_FORMAT(tgl_update, \" %m-%Y \") ,inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai*/
			/*$this->db->where('MONTH(tglbeli)',$tanggalbaru);
			$query =$this->db->get("lap_bhp_pengadaan");*/
	function get_data_permohonan($bulan=0,$start=0,$limit=999999,$options=array())
    {	
    	$tanggals = explode("-", $this->input->post('filter_tanggal'));
		$tanggal = $this->input->post('filter_tanggal');
		$tanggals1 = explode("-", $this->input->post('filter_tanggal1'));
		$tanggal1 = $this->input->post('filter_tanggal1');
    	$pusksmas = $this->input->post('puskes');
    	$data = array();
    	for($i=$tanggals[1]; $i<=$tanggals1[1];$i++){
    		$pusksmas = $this->input->post('puskes');
			$newdate = strtotime("+$i MONTH",strtotime($tanggal));
			$newdate = date('m', $newdate);
			$tanggalbaru = date("m",mktime(0, 0, 0, $i, $tanggals[2], $tanggals[0]));
    		$this->db->where('inv_inventaris_habispakai_pembelian.code_cl_phc',$pusksmas);
			
			$this->db->group_by('inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai,inv_inventaris_habispakai_pembelian_item.batch');
			$this->db->where("MONTH(inv_inventaris_habispakai_pembelian.tgl_pembelian)" ,"$i");
			$this->db->where("YEAR(inv_inventaris_habispakai_pembelian.tgl_pembelian)",$tanggals[0]);
			$this->db->select("mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.merek_tipe,SUM(jml) AS total,(SELECT b.harga FROM inv_inventaris_habispakai_pembelian_item b JOIN inv_inventaris_habispakai_pembelian a WHERE b.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai AND b.batch = inv_inventaris_habispakai_pembelian_item.batch ORDER BY a.tgl_pembelian DESC LIMIT 1 ) AS hargabeli,inv_inventaris_habispakai_pembelian.tgl_pembelian,mst_inv_barang_habispakai.pilihan_satuan
				",false);
			$this->db->join('inv_inventaris_habispakai_pembelian','inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian','left');
			$this->db->join('mst_inv_barang_habispakai','inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai','left');
			$query=$this->db->get('inv_inventaris_habispakai_pembelian_item');
			$datas = $query->result_array(); 
			foreach ($datas as $brg) {
				$tglbarang = explode("-", $brg['tgl_pembelian']);
	        	$data[$brg['uraian']][$tglbarang[1]] = $brg;
	        }
		}
        //die(print_r($data)) ;
        return $data;
    }
	
}
/*
SELECT b.id_mst_inv_barang_habispakai,
				       mst_inv_barang_habispakai.uraian,
				       mst_inv_pilihan.value,
				       mst_inv_barang_habispakai.harga AS harga_asli,
				       b.harga AS harga_beli,
				       DATE_FORMAT(b.tgl_update, \" %m-%Y \") AS MONTH,
				       SUM(jml),

					  (SELECT jml AS jml
					   FROM inv_inventaris_habispakai_opname
					   WHERE id_mst_inv_barang_habispakai=b.id_mst_inv_barang_habispakai
					     AND code_cl_phc=b.code_cl_phc
					   ORDER BY tgl_update DESC LIMIT 1) AS jmlbaik,

					  (SELECT SUM(jml) AS jmltotal
					   FROM inv_inventaris_habispakai_pembelian_item a
					   JOIN inv_inventaris_habispakai_pembelian ON (inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = a.id_inv_hasbispakai_pembelian
					                                                AND inv_inventaris_habispakai_pembelian.code_cl_phc = a.code_cl_phc
					                                                AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)
					   WHERE a.code_cl_phc=b.code_cl_phc
					     AND a.id_mst_inv_barang_habispakai=b.id_mst_inv_barang_habispakai
					     AND a.tgl_update > IF(
					                             (SELECT MAX(tgl_update)
					                              FROM inv_inventaris_habispakai_opname
					                              WHERE a.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai
					                                AND a.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,
					                             (SELECT MAX(tgl_update)
					                              FROM inv_inventaris_habispakai_opname
					                              WHERE a.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai
					                                AND a.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc),
					                             (SELECT MIN(tgl_update- INTERVAL 1 DAY)
					                              FROM inv_inventaris_habispakai_pembelian_item))
					     AND a.tgl_update <= CURDATE()) AS totaljumlah,

					  (SELECT SUM(jml) AS jmlpeng
					   FROM inv_inventaris_habispakai_pengeluaran a
					   WHERE a.id_mst_inv_barang_habispakai=b.id_mst_inv_barang_habispakai
					     AND a.code_cl_phc=b.code_cl_phc
					     AND a.tgl_update > IF(
					                             (SELECT MAX(tgl_update)
					                              FROM inv_inventaris_habispakai_opname
					                              WHERE a.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai
					                                AND a.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,
					                             (SELECT MAX(tgl_update)
					                              FROM inv_inventaris_habispakai_opname
					                              WHERE a.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai
					                                AND a.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc),
					                             (SELECT MIN(tgl_update - INTERVAL 1 DAY)
					                              FROM inv_inventaris_habispakai_pengeluaran))
					     AND a.tgl_update <= CURDATE()
					   ORDER BY a.tgl_update DESC LIMIT 1) AS jmlpengeluaran
					FROM inv_inventaris_habispakai_pembelian_item b
					JOIN mst_inv_barang_habispakai ON (mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = b.id_mst_inv_barang_habispakai
					                                   AND b.code_cl_phc=".'"'.$pusksmas.'"'." )
					LEFT JOIN mst_inv_pilihan ON (mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code
					                              AND mst_inv_pilihan.tipe='satuan_bhp')
					WHERE b.tgl_update >=".'"'.$tanggal.'"'."
					  AND b.tgl_update <= ".'"'.$tanggal1.'"'."
					GROUP BY DATE_FORMAT(b.tgl_update, \" %m-%Y \"),
				         b.id_mst_inv_barang_habispakai
*/