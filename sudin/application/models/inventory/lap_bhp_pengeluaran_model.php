<?php
class Lap_bhp_pengeluaran_model extends CI_Model {

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
	
	function get_data_permohonan($bulan,$tahun,$jenisbhp,$filtername,$ord)
    {	
    	
    	$a_date = "$tahun-$bulan-01";
        $last= date("Y-m-t", strtotime($a_date));
          $data = array();
        for($i=1; $i<=31;$i++){
            $tanggal = date("Y-m-d",mktime(0, 0, 0, $bulan, $i, $tahun));
            $pusksmas = $this->input->post('puskes');
            $query =  $this->db->query("
                    SELECT ((Ifnull( 
                   ( 
                            SELECT   a.jml_akhir
                            FROM     inv_inventaris_habispakai_opname_item a 
                            JOIN     inv_inventaris_habispakai_opname b 
                            ON       a.id_inv_inventaris_habispakai_opname = b.id_inv_inventaris_habispakai_opname
                            WHERE    Month(b.tgl_opname) < ".'"'.$bulan.'"'."
                            AND      Year(b.tgl_opname) <= ".'"'.$tahun.'"'."
                            AND      a.id_mst_inv_barang_habispakai= inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
                            AND      a.batch = inv_inventaris_habispakai_opname_item.batch 
                            AND      a.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname
                            ORDER BY b.tgl_opname DESC limit 1),0))+ (Ifnull( 
                   ( 
                          SELECT Sum(jml) 
                          FROM   inv_inventaris_habispakai_distribusi_item 
                          JOIN   inv_inventaris_habispakai_distribusi 
                          ON     inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi
                          WHERE  inv_inventaris_habispakai_distribusi_item.batch = inv_inventaris_habispakai_opname_item.batch
                          AND    inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
                          AND    ( 
                                        inv_inventaris_habispakai_distribusi.tgl_distribusi) > Ifnull( 
                                 ( 
                                          SELECT   f.tgl_opname 
                                          FROM     inv_inventaris_habispakai_opname f 
                                          JOIN     inv_inventaris_habispakai_opname_item g 
                                          ON       f.id_inv_inventaris_habispakai_opname = g.id_inv_inventaris_habispakai_opname
                                          WHERE    g.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
                                          AND      g.batch = inv_inventaris_habispakai_opname_item.batch 
                                          AND      Month(f.tgl_opname) < ".'"'.$bulan.'"'." 
                                          AND      Year(f.tgl_opname) <= ".'"'.$tahun.'"'." 
                                          ORDER BY f.tgl_opname DESC limit 1 ),'0000-00-00') 
                          AND    ( inv_inventaris_habispakai_distribusi.tgl_distribusi) <= ".'"'.$last.'"'."),0))) AS jumlah_awal,
                   inv_inventaris_habispakai_opname_item.harga, 
                   inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai, 
                   mst_inv_barang_habispakai.uraian, 
                   inv_inventaris_habispakai_opname.tgl_opname, 
                   inv_inventaris_habispakai_opname.petugas_nama, 
                   (inv_inventaris_habispakai_opname_item.jml_akhir - inv_inventaris_habispakai_opname_item.jml_awal) AS pengeluaranperhari
            FROM   (inv_inventaris_habispakai_opname) 
            JOIN   inv_inventaris_habispakai_opname_item 
            ON     inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname
            JOIN   mst_inv_barang_habispakai 
            ON     mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
            WHERE  inv_inventaris_habispakai_opname.code_cl_phc = ".'"'.$pusksmas.'"'."
            AND    inv_inventaris_habispakai_opname.tgl_opname = ".'"'.$tanggal.'"'."
            and    (inv_inventaris_habispakai_opname.tipe!='terimarusak'
            and    inv_inventaris_habispakai_opname.tipe!='retur')
            $jenisbhp $filtername $ord
     ");
            $datas = $query->result_array();  
           // print_r($datas);
            foreach ($datas as $brg) {
                $data[$brg['uraian']][$i] = $brg;
            }
        }
        //die(print_r($data));
        return $data;
    }
	function get_data_jenis()
    {
        $this->db->select('*');
        $query = $this->db->get('mst_inv_barang_habispakai_jenis');
        return $query->result();
    }
}
