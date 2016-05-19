<?php
class Qrcodes extends CI_Controller {

    public function __construct(){
		parent::__construct();
		require_once(APPPATH.'third_party/phpqrcode/qrlib.php');
		$this->load->model('inventory/inv_barang_model');
	}

	function draw($kode_proc=0,$id_barang=0,$kd_inventaris=0,$id_distribusi=0){
		$tabel=substr($id_barang, 0,2);
		if ($tabel=="01") {
			$tabel ="inv_inventaris_barang_a";
		}else if ($tabel=="02") {
			$tabel ="inv_inventaris_barang_b";
		}else if ($tabel=="03") {
			$tabel ="inv_inventaris_barang_c";
		}else if ($tabel=="04") {
			$tabel ="inv_inventaris_barang_d";
		}else if ($tabel=="05") {
			$tabel ="inv_inventaris_barang_e";
		}else if ($tabel=="06") {
			$tabel ="inv_inventaris_barang_f";
		}

		$data = $this->inv_barang_model->get_data_barang_edit_table_all($kode_proc,$id_barang,$kd_inventaris,$tabel,$id_distribusi); 

	      $s = array();
	      $s[0] = substr($data['barang_kembar_proc'], 0,2);
	      $s[1] = substr($data['barang_kembar_proc'], 2,2);
	      $s[2] = substr($data['barang_kembar_proc'], 4,2);
	      $s[3] = substr($data['barang_kembar_proc'], 6,2);
	      $s[4] = substr($data['barang_kembar_proc'], 8,2);
	      $s[5] = substr($data['barang_kembar_proc'], 10,2);
	      $s[6] = substr($data['barang_kembar_proc'], 12,2);
	      $s[7] = substr($data['barang_kembar_proc'], 14,2);
	      $s[8] = substr($data['barang_kembar_proc'], 16,2);
	      $s[9] = substr($data['barang_kembar_proc'], 18,2);
	      $s[10] = substr($data['barang_kembar_proc'], 20,2);
	      $s[11] = substr($data['barang_kembar_proc'], 22,2);
	      $s[12] = substr($data['barang_kembar_proc'], 24,2);
	      $s[14] = substr($data['barang_kembar_proc'], 26,2);
	      $kode_proc = implode(".", $s);
		$data = array(
			"Nama:".$data['nama_barang'],
			"Kode: ".$kode_proc.".".$data['id_inventaris_barang'],
			"Harga:"."Rp.".number_format($data['harga'],2),
			"Pembelian:".date("d-m-Y",strtotime($data['tanggal_pembelian'])),
			"Jumlah:".$data['jumlah'],
			);

	    $PNG_TEMP_DIR = APPPATH.'../public/temp/';
	    
	    //html PNG location prefix
	    $PNG_WEB_DIR = base_url().'public/temp/';
    	if (!file_exists($PNG_TEMP_DIR))
	        mkdir($PNG_TEMP_DIR);


	    $filename = $PNG_TEMP_DIR.$id_barang.'.png';
	    $errorCorrectionLevel = 'L';
	    $matrixPointSize = 3;

	    $text = implode("\n", $data);
	    QRcode::png($text, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        header('Content-Type: image/png');
		readfile($PNG_WEB_DIR.basename($filename));
//echo $kode_proc;

   	}

}
