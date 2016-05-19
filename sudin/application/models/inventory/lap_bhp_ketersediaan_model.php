<?php
class Lap_bhp_ketersediaan_model extends CI_Model {

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
	function get_data_export($start=0,$limit=999999,$options=array())
    {
        $kodepuskesmas = $this->input->post('puskes');
        $tanggal1 = $this->input->post('filter_tanggal');
        $tanggal2 = $this->input->post('filter_tanggal1');
        $data = array();
        $this->db->having("jmlawal_opname > 0");
        $this->db->where("code_cl_phc",$kodepuskesmas);
        $this->db->group_by('inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai','inv_inventaris_habispakai_opname_item.batch');
       $this->db->select("mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.merek_tipe,(IFNULL((SELECT a.
    jml_akhir
    FROM(inv_inventaris_habispakai_opname_item
        a
        JOIN inv_inventaris_habispakai_opname
        b
        ON((a.
            id_inv_inventaris_habispakai_opname = b.
            id_inv_inventaris_habispakai_opname))) WHERE((a.
        batch = inv_inventaris_habispakai_opname_item.
        batch) AND(a.
        id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.
        id_mst_inv_barang_habispakai)) ORDER BY b.
    tgl_opname
    DESC LIMIT 1), 0) + IFNULL((SELECT SUM(inv_inventaris_habispakai_distribusi_item.
    jml) FROM(inv_inventaris_habispakai_distribusi_item
    JOIN inv_inventaris_habispakai_distribusi
    ON((inv_inventaris_habispakai_distribusi.
        id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi_item.
        id_inv_inventaris_habispakai_distribusi))) WHERE((inv_inventaris_habispakai_distribusi_item.
    id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.
    id_mst_inv_barang_habispakai) AND(inv_inventaris_habispakai_distribusi_item.
    batch = inv_inventaris_habispakai_opname_item.
    batch) AND(inv_inventaris_habispakai_distribusi.
    tgl_distribusi > IFNULL((SELECT d.
        tgl_opname
        FROM(inv_inventaris_habispakai_opname_item
            c
            JOIN inv_inventaris_habispakai_opname
            d
            ON((c.
                id_inv_inventaris_habispakai_opname = d.
                id_inv_inventaris_habispakai_opname))) WHERE((c.
            batch = inv_inventaris_habispakai_opname_item.
            batch) AND(c.
            id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.
            id_mst_inv_barang_habispakai)) ORDER BY d.
        tgl_opname
        DESC LIMIT 1), '0000-00-00')) )), 0)) AS jmlawal_opname,
        IF((IFNULL((SELECT a.
    tgl_opname
    FROM(inv_inventaris_habispakai_opname AS a
        LEFT JOIN inv_inventaris_habispakai_opname_item AS b
        ON((a.id_inv_inventaris_habispakai_opname = b.
            id_inv_inventaris_habispakai_opname))) WHERE((b.
        batch = inv_inventaris_habispakai_opname_item.
        batch) AND(b.
        id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.
        id_mst_inv_barang_habispakai)) ORDER BY a.
    tgl_opname
    DESC LIMIT 1), (CURDATE() + INTERVAL 1 DAY)) < CURDATE()), IFNULL((SELECT b.
    harga
    FROM(inv_inventaris_habispakai_opname AS a
        LEFT JOIN inv_inventaris_habispakai_opname_item AS b
        ON((a.
            id_inv_inventaris_habispakai_opname = b.
            id_inv_inventaris_habispakai_opname))) WHERE((b.
        batch = inv_inventaris_habispakai_opname_item.
        batch) AND(b.
        id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.
        id_mst_inv_barang_habispakai)) ORDER BY a.
    tgl_opname
    DESC LIMIT 1), 0), IFNULL((SELECT m.
    harga
    FROM(inv_inventaris_habispakai_pembelian
        s
        LEFT JOIN inv_inventaris_habispakai_pembelian_item
        m
        ON((s.
            id_inv_hasbispakai_pembelian = m.
            id_inv_hasbispakai_pembelian))) WHERE((m.
        batch = inv_inventaris_habispakai_opname_item.
        batch) AND(m.
        id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.
        id_mst_inv_barang_habispakai)) ORDER BY m.
    tgl_update
    DESC LIMIT 1), 0)) AS hargaterakhir ,inv_inventaris_habispakai_opname.catatan

             ",false);
        $this->db->join('inv_inventaris_habispakai_opname_item',"inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname",'left');
        $this->db->join('mst_inv_barang_habispakai',"mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai",'left');
        $query = $this->db->get('inv_inventaris_habispakai_opname',$limit,$start);
        return $query->result();
    }
}