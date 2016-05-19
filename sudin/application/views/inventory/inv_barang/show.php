
<script type="text/javascript">
	function filter_jqxgrid_inv_barang(){
			<?php 	if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){ 
						if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){ ?>
			     			$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){ ?>
			     			$("#jqxgrid_DataHapus").jqxGrid('updatebounddata', 'cells');
			    <?php   }	?>
		    <?php	}else  if(isset($filter_golongan_invetaris)){
				    		if($filter_golongan_invetaris=='0100000000'){ 
				    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){ ?>
									$("#kibA").click();
						<?php 	}else if($this->session->userdata('filterHAPUS')!=''){ ?>
									$("#hapusA").click();
						<?php   }	?>
				    <?php	}else if($filter_golongan_invetaris=='0200000000'){ 
				    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){?>
									$("#kibB").click();
						<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
									$("#hapusB").click();
						<?php   }?>		
				    <?php	}else if($filter_golongan_invetaris=='0300000000'){ 
				    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){	?>
									$("#kibC").click();
						<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
									$("#hapusC").click();
						<?php   }	?>
				    <?php	}else if($filter_golongan_invetaris=='0400000000'){ 
				    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){?>
									$("#kibD").click();
						<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
									$("#hapusD").click();
						<?php   }	?>
				    <?php	}else if($filter_golongan_invetaris=='0500000000'){ 
				    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){?>
									$("#kibE").click();
						<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
									$("#hapusE").click();
						<?php   }	?>
				    <?php	}else if($filter_golongan_invetaris=='0600000000'){ 
				    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterGIB')=='')){?>
									$("#kibF").click();
						<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
									$("#hapusF").click();
						<?php   }	
			    			}	 
						} 
				?> 

	}


	$(function(){
	<?php  if((!isset($filter_golongan_invetaris)) || ($filter_golongan_invetaris =='')){ ?>		
	   var databarang = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'pilihan_keadaan_barang', type: 'string' },
			{ name: 'nama_barang', type: 'string' },
			{ name: 'pilihan_komponen', type: 'string' },
			{ name: 'harga', type: 'double' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'pilihan_status_invetaris', type: 'string' },
			{ name: 'tanggal_pembelian', type: 'date' },
			{ name: 'foto_barang', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'keterangan_inventory', type: 'string' },
			{ name: 'tanggal_pengadaan', type: 'date' },
			{ name: 'tanggal_diterima', type: 'date' },
			{ name: 'tanggal_dihapus', type: 'date' },
			{ name: 'alasan_penghapusan', type: 'string' },
			{ name: 'pilihan_asal', type: 'string' },
			{ name: 'value', type: 'string' },
			{ name: 'waktu_dibuat', type: 'date' },
			{ name: 'terakhir_diubah', type: 'date' },
			{ name: 'jumlah', type: 'number' },
			{ name: 'totalharga', type: 'double' },
			{ name: 'puskesmas', type: 'string' },
			{ name: 'nama_ruangan', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/json_barang/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
            commit(true);
			var arr = $.map(rowData, function(el) { return el });
			//alert(arr[6]); alert(arr[8]);	alert(arr);	//6 status

			$.post( '<?php echo base_url()?>inventory/inv_barang/updatestatus_barang', {kode_proc:arr[3],pilihan_inv:arr[9]},function( data ) {
				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'filter');
			 });
         },
		filter: function(){
			$("#jqxgrid_barang").jqxGrid('updatebounddata', 'filter');
			
		},
		sort: function(){
			$("#jqxgrid_barang").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				databarang.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter_barang = new $.jqx.dataAdapter(databarang, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});


		$("#jqxgrid_barang").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: dataadapter_barang, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			<?php if(!isset($viewreadonly)){?>	{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang_all(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang_all(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Hapus', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/trash.png' onclick='hapus_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },<?php } ?>
                { text: 'IMG', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/barcode.png' onclick='barcode_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang', align: 'center', cellsalign: 'center', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Register', align: 'center', cellsalign: 'center', editable: false, datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%' },
				{ text: 'Nama Barang ', editable: false,datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '22%'},
				{ text: 'Harga (Rp.)', align: 'center', cellsalign: 'right', editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Puskesmas ', editable: false,datafield: 'puskesmas', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Ruangan ', editable: false, datafield: 'nama_ruangan', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: '<b><i class="fa fa-pencil-square-o"></i> Status</b>', align: 'center', cellsalign: 'center', datafield: 'value', width: '7%', columntype: 'dropdownlist',
                        createeditor: function (row, column, editor) {
                            // assign a new data source to the dropdownlist.
                            var list = [<?php foreach ($kodestatus_inv as $key) {
                            	if($key->value != 'Dihapus') echo '"'.$key->value.'",';
							} ?>];
                            editor.jqxDropDownList({ autoDropDownHeight: true, source: list });
                        },
                        // update the editor's value before saving it.
                        cellvaluechanging: function (row, column, columntype, oldvalue, newvalue) {
                            // return the old value, if the new value is empty.
                            if (newvalue == "") return oldvalue;
                        }
                 },
				{ text: 'Tanggal Terima',align: 'center', cellsalign: 'center',  editable: false,datafield: 'tanggal_diterima', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'}
           ]
		});



	   var databaranghapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'pilihan_keadaan_barang', type: 'string' },
			{ name: 'nama_barang', type: 'string' },
			{ name: 'pilihan_komponen', type: 'string' },
			{ name: 'harga', type: 'double' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'pilihan_status_invetaris', type: 'string' },
			{ name: 'tanggal_pembelian', type: 'date' },
			{ name: 'foto_barang', type: 'string' },
			{ name: 'keterangan_inventory', type: 'string' },
			{ name: 'tanggal_pengadaan', type: 'date' },
			{ name: 'tanggal_diterima', type: 'date' },
			{ name: 'tanggal_dihapus', type: 'date' },
			{ name: 'alasan_penghapusan', type: 'string' },
			{ name: 'pilihan_asal', type: 'string' },
			{ name: 'value', type: 'string' },
			{ name: 'waktu_dibuat', type: 'date' },
			{ name: 'terakhir_diubah', type: 'date' },
			{ name: 'jumlah', type: 'number' },
			{ name: 'totalharga', type: 'double' },
			{ name: 'puskesmas', type: 'string' },
			{ name: 'nama_ruangan', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/json_barang/'); ?>",
		cache: false,
		filter: function(){
			$("#jqxgrid_DataHapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_DataHapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				databaranghapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter_databaranghapus = new $.jqx.dataAdapter(databaranghapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});

		$("#jqxgrid_DataHapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: dataadapter_databaranghapus, theme: theme,columnsresize: true, showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			<?php if(!isset($viewreadonly)){?>	{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_DataHapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Return', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_DataHapus").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/return.png' onclick='return_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\");'></a></div>";
                 }
                },
				{ text: 'Del', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_DataHapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },<?php } ?>
				{ text: 'Kode Barang', align: 'center', cellsalign: 'center', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Register', align: 'center', cellsalign: 'center', editable: false, datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%' },
				{ text: 'Nama Barang ', editable: false,datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '22%'},
				{ text: 'Harga (Rp.)', align: 'center', cellsalign: 'right', editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Puskesmas ', editable: false,datafield: 'puskesmas', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Ruangan ', editable: false, datafield: 'nama_ruangan', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Dihapus',align: 'center', cellsalign: 'center', editable: false,datafield: 'tanggal_dihapus', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'}
           ]
		});
	<?php	}else  if(isset($filter_golongan_invetaris)){
	if($filter_golongan_invetaris=='0100000000'){ ?>
		var sourceGolonganA = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'satuan', type: 'string' },
			{ name: 'hak', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'penggunaan', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'text' },
			{ name: 'register', type: 'string' },
			{ name: 'id_ruangan', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'luas', type: 'double' },
			{ name: 'jumlah', type: 'double' },
			{ name: 'jumlah_satuan', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'alamat', type: 'text' },
			{ name: 'pilihan_satuan_barang', type: 'string' },
			{ name: 'pilihan_status_hak', type: 'number' },
			{ name: 'status_sertifikat_tanggal', type: 'date' },
			{ name: 'status_sertifikat_nomor', type: 'string' },
			{ name: 'pilihan_penggunaan', type: 'number' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_a/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_A").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_A").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganA.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_A = new $.jqx.dataAdapter(sourceGolonganA, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_A").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_A, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_A").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama/Jenis Barang ', columngroup: 'spesifikasi',editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Luas', align: 'center', cellsalign: 'right', columngroup: 'spesifikasi', editable: false,datafield: 'luas', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Satuan',align: 'center', cellsalign: 'center', columngroup: 'spesifikasi',editable: false, datafield: 'satuan', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Alamat',columngroup: 'spesifikasi',editable: false, datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', width: '18%'},
				{ text: 'Hak',columngroup: 'statustanah', editable: false,datafield: 'hak', columntype: 'textbox', filtertype: 'textbox', width: '23%'},
				{ text: 'Tgl Sertifikat', align: 'center', cellsalign: 'center', columngroup: 'statustanah',editable: false,datafield: 'status_sertifikat_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'No Sertifikat',columngroup: 'statustanah', editable: false,datafield: 'status_sertifikat_nomor', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Penggunaan', editable: false,datafield: 'penggunaan', columntype: 'textbox', filtertype: 'textbox', width: '18%'},
				{ text: 'Asal Usul', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Harga (Rp.)', align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '14%'}
				
				],
				 columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Spesifikasi Barang', align: 'center', name: 'spesifikasi' },
                  { text: 'Status Tanah',align: 'center', name: 'statustanah' },
                ]
		});


		var sourceGolonganA_hapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'satuan', type: 'string' },
			{ name: 'hak', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'penggunaan', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'text' },
			{ name: 'register', type: 'string' },
			{ name: 'id_ruangan', type: 'string' },
			{ name: 'luas', type: 'double' },
			{ name: 'jumlah', type: 'double' },
			{ name: 'id_inventaris_distribusi', type: 'double' },
			{ name: 'jumlah_satuan', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'alamat', type: 'text' },
			{ name: 'pilihan_satuan_barang', type: 'string' },
			{ name: 'pilihan_status_hak', type: 'number' },
			{ name: 'status_sertifikat_tanggal', type: 'date' },
			{ name: 'status_sertifikat_nomor', type: 'string' },
			{ name: 'pilihan_penggunaan', type: 'number' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_a/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_A_hapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_A_hapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganA_hapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_A_hapus = new $.jqx.dataAdapter(sourceGolonganA_hapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_A_hapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_A_hapus, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_A_hapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama/Jenis Barang ', columngroup: 'spesifikasi',editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Luas', align: 'center', cellsalign: 'right', columngroup: 'spesifikasi', editable: false,datafield: 'luas', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Satuan',align: 'center', cellsalign: 'center', columngroup: 'spesifikasi',editable: false, datafield: 'satuan', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Alamat',columngroup: 'spesifikasi',editable: false, datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', width: '18%'},
				{ text: 'Hak',columngroup: 'statustanah', editable: false,datafield: 'hak', columntype: 'textbox', filtertype: 'textbox', width: '23%'},
				{ text: 'Tgl Sertifikat', align: 'center', cellsalign: 'center', columngroup: 'statustanah',editable: false,datafield: 'status_sertifikat_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'No Sertifikat',columngroup: 'statustanah', editable: false,datafield: 'status_sertifikat_nomor', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Penggunaan', editable: false,datafield: 'penggunaan', columntype: 'textbox', filtertype: 'textbox', width: '18%'},
				{ text: 'Asal Usul', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Harga (Rp.)', align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '15%'}
				
				],
				 columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Spesifikasi Barang', align: 'center', name: 'spesifikasi' },
                  { text: 'Status Tanah',align: 'center', name: 'statustanah' },
                ]
		});

	<?php	}else if($filter_golongan_invetaris=='0200000000'){ ?>

		var sourceGolonganB = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'merek_type', type: 'text' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'penggunaan', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'keadaan_barang', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'identitas_barang', type: 'text' },
			{ name: 'bahan', type: 'string' },
			{ name: 'satuan', type: 'string' },
			{ name: 'pilihan_bahan', type: 'string' },
			{ name: 'ukuran_barang', type: 'string' },
			{ name: 'ukuran_satuan', type: 'string' },
			{ name: 'pilihan_satuan', type: 'string' },
			{ name: 'tanggal_bpkb', type: 'date' },
			{ name: 'nomor_bpkb', type: 'string' },
			{ name: 'no_polisi', type: 'string' },
			{ name: 'tanggal_perolehan', type: 'date' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_b/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_B").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_B").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganB.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_B = new $.jqx.dataAdapter(sourceGolonganB, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_B").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_B, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			<?php if(!isset($viewreadonly)){?>	{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_B").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },<?php } ?>
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama/Jenis Barang ',columngroup: 'spesifikasi', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Merek/Tipe ', columngroup: 'spesifikasi',editable: false,datafield: 'merek_type', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'No Sertifikat/No.Pabrik/No.Chasis/No.Mesin',columngroup: 'spesifikasi',editable: false, datafield: 'identitas_barang', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Pilihan Bahan', editable: false,datafield: 'bahan', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Asal/Cara Perolehan Barang', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '17%'},
				{ text: 'Thn Pembelian', align: 'center', cellsalign: 'center',editable: false,datafield: 'tanggal_perolehan', columntype: 'date', filtertype: 'date', cellsformat: 'yyyy', width: '9%'},
				{ text: 'Ukuran Barang / Konstruksi (P, S, D) ', editable: false,datafield: 'ukuran_barang', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Satuan', align: 'center', cellsalign: 'center', editable: false,datafield: 'satuan', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Keadaan Barang', align: 'center', cellsalign: 'center',editable: false,datafield: 'keadaan_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Jumlah', align: 'center', cellsalign: 'right',editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Harga', align: 'center', cellsalign: 'right',editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Tanggal BPKB',editable: false,datafield: 'tanggal_bpkb', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%'},
				{ text: 'Nomor BPKB ',align: 'center', cellsalign: 'center', editable: false,datafield: 'nomor_bpkb', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'No Polisi ',align: 'center', cellsalign: 'center', editable: false,datafield: 'no_polisi', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '14%'}
				],
				columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Spesifikasi Barang', align: 'center', name: 'spesifikasi' },
                  { text: 'Jumlah',align: 'center', name: 'jumlah' },
                ]
           
		});
		


		var sourceGolonganB_hapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'merek_type', type: 'text' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'penggunaan', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'keadaan_barang', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'identitas_barang', type: 'text' },
			{ name: 'bahan', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'satuan', type: 'string' },
			{ name: 'pilihan_bahan', type: 'string' },
			{ name: 'ukuran_barang', type: 'string' },
			{ name: 'ukuran_satuan', type: 'string' },
			{ name: 'pilihan_satuan', type: 'string' },
			{ name: 'tanggal_bpkb', type: 'date' },
			{ name: 'nomor_bpkb', type: 'string' },
			{ name: 'no_polisi', type: 'string' },
			{ name: 'tanggal_perolehan', type: 'date' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_b/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
				$("#jqxgrid_Golongan_B_hapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
				$("#jqxgrid_Golongan_B_hapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganB_hapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_B_hapus = new $.jqx.dataAdapter(sourceGolonganB_hapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});

		$("#jqxgrid_Golongan_B_hapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_B_hapus, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			<?php if(!isset($viewreadonly)){?>	{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_B_hapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },<?php } ?>
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama/Jenis Barang ',columngroup: 'spesifikasi', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Merek/Tipe ', columngroup: 'spesifikasi',editable: false,datafield: 'merek_type', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'No Sertifikat/No.Pabrik/No.Chasis/No.Mesin',columngroup: 'spesifikasi',editable: false, datafield: 'identitas_barang', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Pilihan Bahan', editable: false,datafield: 'bahan', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Asal/Cara Perolehan Barang', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '17%'},
				{ text: 'Thn Pembelian', align: 'center', cellsalign: 'center',editable: false,datafield: 'tanggal_perolehan', columntype: 'date', filtertype: 'date', cellsformat: 'yyyy', width: '9%'},
				{ text: 'Ukuran Barang / Konstruksi (P, S, D) ', editable: false,datafield: 'ukuran_barang', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Satuan', align: 'center', cellsalign: 'center', editable: false,datafield: 'satuan', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Keadaan Barang', align: 'center', cellsalign: 'center',editable: false,datafield: 'keadaan_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Jumlah', align: 'center', cellsalign: 'right',editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Harga', align: 'center', cellsalign: 'right',editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Tanggal BPKB',editable: false,datafield: 'tanggal_bpkb', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%'},
				{ text: 'Nomor BPKB ',align: 'center', cellsalign: 'center', editable: false,datafield: 'nomor_bpkb', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'No Polisi ',align: 'center', cellsalign: 'center', editable: false,datafield: 'no_polisi', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '15%'}
				],
				columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Spesifikasi Barang', align: 'center', name: 'spesifikasi' },
                  { text: 'Jumlah',align: 'center', name: 'jumlah' },
                ]
		});
		
	<?php	}else if($filter_golongan_invetaris=='0300000000'){ ?>

		var sourceGolonganC = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'luas_lantai', type: 'string' },
			{ name: 'hak', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'id_ruangan', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'tingkat', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'beton', type: 'string' },
			{ name: 'letak_lokasi_alamat', type: 'text' },
			{ name: 'pillihan_status_hak', type: 'string' },
			{ name: 'nomor_kode_tanah', type: 'string' },
			{ name: 'pilihan_kons_tingkat', type: 'string' },
			{ name: 'pilihan_kons_beton', type: 'string' },
			{ name: 'dokumen_tanggal', type: 'date' },
			{ name: 'dokumen_nomor', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_c/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_C").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_C").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganC.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_C = new $.jqx.dataAdapter(sourceGolonganC, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_C").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_C, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_C").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama Barang ', columngroup: 'spesifikasi',editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Luas Lantai ', align: 'center', cellsalign: 'center',columngroup: 'spesifikasi',editable: false,datafield: 'luas_lantai', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Lokasi Alamat',columngroup: 'spesifikasi',editable: false, datafield: 'letak_lokasi_alamat', columntype: 'textbox', filtertype: 'textbox', width: '17%'},
				{ text: 'Status Tanah', editable: false,datafield: 'hak', columntype: 'textbox', filtertype: 'textbox', width: '24%'},
				{ text: 'No. Kode Tanah ', editable: false,datafield: 'nomor_kode_tanah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tingkat / Tidak',align: 'center', cellsalign: 'center', columngroup: 'kontruksibangunan',editable: false,datafield: 'tingkat', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Beton / Tidak ',align: 'center', cellsalign: 'center', columngroup: 'kontruksibangunan',editable: false,datafield: 'beton', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Asal Usul',align: 'center', cellsalign: 'center', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Harga (Rp.)',align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Nomor Dokumen',align: 'center', cellsalign: 'center', editable: false,datafield: 'dokumen_nomor', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Dokumen',align: 'center', cellsalign: 'center',editable: false,datafield: 'dokumen_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '12%'}
           		],
				columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Spesifikasi Barang', align: 'center', name: 'spesifikasi' },
                  { text: 'Kontruksi Bangunan',align: 'center', name: 'kontruksibangunan' },
                ]
		});


		var sourceGolonganC_hapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'luas_lantai', type: 'string' },
			{ name: 'hak', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'id_ruangan', type: 'string' },
			{ name: 'tingkat', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'beton', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'letak_lokasi_alamat', type: 'text' },
			{ name: 'pillihan_status_hak', type: 'string' },
			{ name: 'nomor_kode_tanah', type: 'string' },
			{ name: 'pilihan_kons_tingkat', type: 'string' },
			{ name: 'pilihan_kons_beton', type: 'string' },
			{ name: 'dokumen_tanggal', type: 'date' },
			{ name: 'dokumen_nomor', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_c/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_C_hapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_C_hapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganC_hapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_C_hapus = new $.jqx.dataAdapter(sourceGolonganC_hapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_C_hapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_C_hapus, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_C_hapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama Barang ', columngroup: 'spesifikasi',editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Luas Lantai ', align: 'center', cellsalign: 'center',columngroup: 'spesifikasi',editable: false,datafield: 'luas_lantai', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Lokasi Alamat',columngroup: 'spesifikasi',editable: false, datafield: 'letak_lokasi_alamat', columntype: 'textbox', filtertype: 'textbox', width: '17%'},
				{ text: 'Status Tanah', editable: false,datafield: 'hak', columntype: 'textbox', filtertype: 'textbox', width: '24%'},
				{ text: 'No. Kode Tanah ', editable: false,datafield: 'nomor_kode_tanah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tingkat / Tidak',align: 'center', cellsalign: 'center', columngroup: 'kontruksibangunan',editable: false,datafield: 'tingkat', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Beton / Tidak ',align: 'center', cellsalign: 'center', columngroup: 'kontruksibangunan',editable: false,datafield: 'beton', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Asal Usul',align: 'center', cellsalign: 'center', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Harga (Rp.)',align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Nomor Dokumen',align: 'center', cellsalign: 'center', editable: false,datafield: 'dokumen_nomor', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Dokumen',align: 'center', cellsalign: 'center',editable: false,datafield: 'dokumen_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '13%'}
           		],
				columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Spesifikasi Barang', align: 'center', name: 'spesifikasi' },
                  { text: 'Kontruksi Bangunan',align: 'center', name: 'kontruksibangunan' },
                ]
		});
		
	<?php	}else if($filter_golongan_invetaris=='0400000000'){ ?>

		var sourceGolonganD = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'konstruksi', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'id_ruangan', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'panjang', type: 'double' },
			{ name: 'lebar', type: 'string' },
			{ name: 'luas', type: 'string' },
			{ name: 'tanah', type: 'tanah' },
			{ name: 'letak_lokasi_alamat', type: 'text' },
			{ name: 'dokumen_tanggal', type: 'date' },
			{ name: 'dokumen_nomor', type: 'string' },
			{ name: 'pilihan_status_tanah', type: 'string' },
			{ name: 'nomor_kode_tanah', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_d/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_D").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_D").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganD.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_D = new $.jqx.dataAdapter(sourceGolonganD, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_D").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_D, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_D").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Nama /Jenis Barang',editable: false, datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '22%' },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Kontruksi ', editable: false,datafield: 'konstruksi', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Panjang (km)',align: 'center', cellsalign: 'right', editable: false,datafield: 'panjang', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Lebar',align: 'center', cellsalign: 'right',editable: false, datafield: 'lebar', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Luas', align: 'center', cellsalign: 'right',editable: false,datafield: 'luas', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Lokasi', editable: false,datafield: 'letak_lokasi_alamat', columntype: 'textbox', filtertype: 'textbox', width: '16%'},
				{ text: 'Nomor Dokumen ', columngroup: 'dokumen',editable: false,datafield: 'dokumen_nomor', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Dokumen',align: 'center', cellsalign: 'center', columngroup: 'dokumen',editable: false,datafield: 'dokumen_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Status Tanah ', editable: false,datafield: 'tanah', columntype: 'textbox', filtertype: 'textbox', width: '18%'},
				{ text: 'No Kode Tanah ', editable: false,datafield: 'nomor_kode_tanah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Asal Usul ', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Harga (Rp)',align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '14%'}
           		],
				columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Dokumen',align: 'center', name: 'dokumen' },
                ]
		});


		var sourceGolonganD_hapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'konstruksi', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'id_ruangan', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'panjang', type: 'double' },
			{ name: 'lebar', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'luas', type: 'string' },
			{ name: 'tanah', type: 'tanah' },
			{ name: 'letak_lokasi_alamat', type: 'text' },
			{ name: 'dokumen_tanggal', type: 'date' },
			{ name: 'dokumen_nomor', type: 'string' },
			{ name: 'pilihan_status_tanah', type: 'string' },
			{ name: 'nomor_kode_tanah', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_d/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_D_hapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_D_hapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganD_hapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_D_hapus = new $.jqx.dataAdapter(sourceGolonganD_hapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_D_hapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_D_hapus, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_D_hapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Nama /Jenis Barang',editable: false, datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '22%' },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Kontruksi ', editable: false,datafield: 'konstruksi', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Panjang (km)',align: 'center', cellsalign: 'right', editable: false,datafield: 'panjang', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Lebar',align: 'center', cellsalign: 'right',editable: false, datafield: 'lebar', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Luas', align: 'center', cellsalign: 'right',editable: false,datafield: 'luas', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Lokasi', editable: false,datafield: 'letak_lokasi_alamat', columntype: 'textbox', filtertype: 'textbox', width: '16%'},
				{ text: 'Nomor Dokumen ', columngroup: 'dokumen',editable: false,datafield: 'dokumen_nomor', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Dokumen',align: 'center', cellsalign: 'center', columngroup: 'dokumen',editable: false,datafield: 'dokumen_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Status Tanah ', editable: false,datafield: 'tanah', columntype: 'textbox', filtertype: 'textbox', width: '18%'},
				{ text: 'No Kode Tanah ', editable: false,datafield: 'nomor_kode_tanah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Asal Usul ', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Harga (Rp)',align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '15%'}
           		],
				columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Dokumen',align: 'center', name: 'dokumen' },
                ]
		});
		
	<?php	}else if($filter_golongan_invetaris=='0500000000'){ ?>
	
		var sourceGolonganE = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'satuan', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'bahan', type: 'string' },
			{ name: 'flora_ukuran_satuan', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'text' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'buku_judul_pencipta', type: 'string' },
			{ name: 'buku_spesifikasi', type: 'double' },
			{ name: 'budaya_asal_daerah', type: 'string' },
			{ name: 'budaya_pencipta', type: 'string' },
			{ name: 'pilihan_budaya_bahan', type: 'string' },
			{ name: 'flora_fauna_jenis', type: 'string' },
			{ name: 'flora_fauna_ukuran', type: 'string' },
			{ name: 'pilihan_satuan', type: 'string' },
			{ name: 'tahun_cetak_beli', type: 'date' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/Golongan_E/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_E").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_E").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganE.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_E = new $.jqx.dataAdapter(sourceGolonganE, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_E").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_E, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_E").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama/Jenis Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Judul / Pencipta ', columngroup: 'buku',editable: false,datafield: 'buku_judul_pencipta', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Spesifikasi ',columngroup: 'buku',editable: false, datafield: 'buku_spesifikasi', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Asal Daerah', columngroup: 'budaya',editable: false,datafield: 'budaya_asal_daerah', columntype: 'textbox', filtertype: 'textbox', width: '14%'},
				{ text: 'Pencipta ',columngroup: 'budaya', editable: false,datafield: 'budaya_pencipta', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Bahan ', columngroup: 'budaya',editable: false,datafield: 'pilihan_budaya_bahan', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Jenis ',align: 'center', cellsalign: 'center', columngroup: 'hewan',editable: false,datafield: 'flora_fauna_jenis', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Ukuran ',align: 'center', cellsalign: 'right', columngroup: 'hewan',editable: false,datafield: 'flora_fauna_ukuran', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Satuan ',align: 'center', cellsalign: 'right', columngroup: 'hewan',editable: false,datafield: 'satuan', columntype: 'textbox', filtertype: 'textbox', width: '7%'},
				{ text: 'Jumlah ',align: 'center', cellsalign: 'right', editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Cetak/ Beli',align: 'center', cellsalign: 'center', editable: false,datafield: 'tahun_cetak_beli', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Asal Usul', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Harga',align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '12%'}
           		],
           		columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Buku / Perpustakaan',align: 'center', name: 'buku' },
                  { text: 'Barang Bercorak Kesenian / Kebudayaan',align: 'center', name: 'budaya' },
                  { text: 'Hewan / Ternak dan Tumbuhan',align: 'center', name: 'hewan' },
                ]
		});


	
		var sourceGolonganE_hapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'satuan', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'bahan', type: 'string' },
			{ name: 'flora_ukuran_satuan', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'text' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'buku_judul_pencipta', type: 'string' },
			{ name: 'buku_spesifikasi', type: 'double' },
			{ name: 'budaya_asal_daerah', type: 'string' },
			{ name: 'budaya_pencipta', type: 'string' },
			{ name: 'pilihan_budaya_bahan', type: 'string' },
			{ name: 'flora_fauna_jenis', type: 'string' },
			{ name: 'flora_fauna_ukuran', type: 'string' },
			{ name: 'pilihan_satuan', type: 'string' },
			{ name: 'tahun_cetak_beli', type: 'date' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/Golongan_E/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_E_hapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_E_hapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganE_hapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_E_hapus = new $.jqx.dataAdapter(sourceGolonganE_hapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
   		$("#jqxgrid_Golongan_E_hapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_E_hapus, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_E_hapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\";'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama/Jenis Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Judul / Pencipta ', columngroup: 'buku',editable: false,datafield: 'buku_judul_pencipta', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Spesifikasi ',columngroup: 'buku',editable: false, datafield: 'buku_spesifikasi', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Asal Daerah', columngroup: 'budaya',editable: false,datafield: 'budaya_asal_daerah', columntype: 'textbox', filtertype: 'textbox', width: '14%'},
				{ text: 'Pencipta ',columngroup: 'budaya', editable: false,datafield: 'budaya_pencipta', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Bahan ', columngroup: 'budaya',editable: false,datafield: 'pilihan_budaya_bahan', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Jenis ',align: 'center', cellsalign: 'center', columngroup: 'hewan',editable: false,datafield: 'flora_fauna_jenis', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Ukuran ',align: 'center', cellsalign: 'right', columngroup: 'hewan',editable: false,datafield: 'flora_fauna_ukuran', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Satuan ',align: 'center', cellsalign: 'right', columngroup: 'hewan',editable: false,datafield: 'satuan', columntype: 'textbox', filtertype: 'textbox', width: '7%'},
				{ text: 'Jumlah ',align: 'center', cellsalign: 'right', editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Cetak/ Beli',align: 'center', cellsalign: 'center', editable: false,datafield: 'tahun_cetak_beli', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Asal Usul', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Harga',align: 'center', cellsalign: 'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '13%'}
           		],
           		columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Buku / Perpustakaan',align: 'center', name: 'buku' },
                  { text: 'Barang Bercorak Kesenian / Kebudayaan',align: 'center', name: 'budaya' },
                  { text: 'Hewan / Ternak dan Tumbuhan',align: 'center', name: 'hewan' },
                ]
		});

	<?php	}else if($filter_golongan_invetaris=='0600000000'){ ?>
		
		var sourceGolonganF = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'bangunan', type: 'string' },
			{ name: 'pilihan_konstruksi_beton', type: 'double' },
			{ name: 'luas', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'text' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'tanah', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'id_inventaris_distribusi', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'beton', type: 'string' },
			{ name: 'tingkat', type: 'string' },
			{ name: 'lokasi', type: 'string' },
			{ name: 'dokumen_tanggal', type: 'date' },
			{ name: 'dokumen_nomor', type: 'string' },
			{ name: 'tanggal_mulai', type: 'date' },
			{ name: 'pilihan_status_tanah', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_f/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_F").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_F").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganF.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_F = new $.jqx.dataAdapter(sourceGolonganF, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_F").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_F, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_F").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama /Jenis Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Bangunan (P, SP, PD)',align: 'center', cellsalign: 'center',  editable: false,datafield: 'bangunan', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Bertingkat/Titak',align: 'center', cellsalign: 'center', columngroup: 'konturksi', editable: false,datafield: 'tingkat', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Beton/Tidak',align: 'center', cellsalign: 'center',  columngroup: 'konturksi',editable: false,datafield: 'beton', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Luas',align: 'center', cellsalign: 'right', editable: false, datafield: 'luas', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Letak / Lokasi', editable: false,datafield: 'lokasi', columntype: 'textbox', filtertype: 'textbox', width: '17%'},
				{ text: 'Tanggal', align: 'center', cellsalign: 'center',columngroup: 'dokumen',editable: false,datafield: 'dokumen_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Nomor',columngroup: 'dokumen', editable: false,datafield: 'dokumen_nomor', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Mulai',align: 'center', cellsalign: 'center',editable: false,datafield: 'tanggal_mulai', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Status Tanah ', editable: false,datafield: 'tanah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Asal Usul Pembiayaan', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Nilai Kontrak (Rp.)', align: 'center', cellsalign: 'right',editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
           		],
           		columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Kontruksi Bangunan',align: 'center', name: 'konturksi' },
                  { text: 'Dokumen',align: 'center', name: 'dokumen' },
                ]
		});


		
		var sourceGolonganF_hapus = { 
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'bangunan', type: 'string' },
			{ name: 'pilihan_konstruksi_beton', type: 'double' },
			{ name: 'luas', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'id_pl_phc', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'keterangan_pengadaan', type: 'text' },
			{ name: 'asal_usul', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'tanah', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'beton', type: 'string' },
			{ name: 'tingkat', type: 'string' },
			{ name: 'id_inventaris_distribusi', type: 'string' },
			{ name: 'lokasi', type: 'string' },
			{ name: 'dokumen_tanggal', type: 'date' },
			{ name: 'dokumen_nomor', type: 'string' },
			{ name: 'tanggal_mulai', type: 'date' },
			{ name: 'pilihan_status_tanah', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/json/golongan_f/'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_Golongan_F_hapus").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_Golongan_F_hapus").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				sourceGolonganF_hapus.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var data_golongan_F_hapus = new $.jqx.dataAdapter(sourceGolonganF_hapus, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     	
		$("#jqxgrid_Golongan_F_hapus").jqxGrid(
		{	
			width: '99.8%',
			selectionmode: 'singlerow',
			source: data_golongan_F_hapus, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_Golongan_F_hapus").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang+"\",\""+dataRecord.barang_kembar_proc+"\",\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.id_pengadaan+"\",\""+dataRecord.id_inventaris_distribusi+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang',align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Register ', align: 'center', cellsalign: 'center', columngroup: 'nomor', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Nama /Jenis Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Bangunan (P, SP, PD)',align: 'center', cellsalign: 'center',  editable: false,datafield: 'bangunan', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Bertingkat/Titak',align: 'center', cellsalign: 'center', columngroup: 'konturksi', editable: false,datafield: 'tingkat', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Beton/Tidak',align: 'center', cellsalign: 'center',  columngroup: 'konturksi',editable: false,datafield: 'beton', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Luas',align: 'center', cellsalign: 'right', editable: false, datafield: 'luas', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Letak / Lokasi', editable: false,datafield: 'lokasi', columntype: 'textbox', filtertype: 'textbox', width: '17%'},
				{ text: 'Tanggal', align: 'center', cellsalign: 'center',columngroup: 'dokumen',editable: false,datafield: 'dokumen_tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Nomor',columngroup: 'dokumen', editable: false,datafield: 'dokumen_nomor', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Tgl Mulai',align: 'center', cellsalign: 'center',editable: false,datafield: 'tanggal_mulai', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'},
				{ text: 'Status Tanah ', editable: false,datafield: 'tanah', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Asal Usul Pembiayaan', editable: false,datafield: 'asal_usul', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
				{ text: 'Nilai Kontrak (Rp.)', align: 'center', cellsalign: 'right',editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Keterangan', editable: false,datafield: 'keterangan_pengadaan', columntype: 'textbox', filtertype: 'textbox', width: '13%'},
           		],
           		columngroups: 
                [
                  { text: 'Nomor', align: 'center', name: 'nomor' },
                  { text: 'Kontruksi Bangunan',align: 'center', name: 'konturksi' },
                  { text: 'Dokumen',align: 'center', name: 'dokumen' },
                ]
		});
		<?php }} ?>


        $('#btn-refresh').click(function () {
			filter_jqxgrid_inv_barang();
		});

		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_barang").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
		});

 		$('#btn_add_barang').click(function () {
			add_barang();
		});
	});

	function add_barang(){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 1000,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
		$.get("<?php echo base_url().'inventory/inv_barang/add/'; ?>" , function(data) {
			$("#popup_barang #popup_content").html(data);
		});
	}

	function edit_barang(id_barang,barang_kembar_proc,id_inventaris_barang,id_pengadaan,id_distribusi){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 1000,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
		$.get("<?php echo base_url().'inventory/inv_barang/edit_barang/';?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang+'/'+id_pengadaan+'/'+id_distribusi, function(data) {
			$("#popup_barang #popup_content").html(data);
		});
	}
	function edit_barang_all(id_barang,barang_kembar_proc,id_inventaris_barang,id_pengadaan,id_distribusi){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 1000,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
		$.get("<?php echo base_url().'inventory/inv_barang/edit_barang_all/';?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang+'/'+id_pengadaan+'/'+id_distribusi, function(data) {
			$("#popup_barang #popup_content").html(data);
		});
	}
	function barcode_barang(id_barang,barang_kembar_proc,id_inventaris_barang,id_pengadaan,id_inventaris_distribusi){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 1000,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
		$.get("<?php echo base_url().'inventory/inv_barang/edit_barcode/';?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang+'/'+id_pengadaan+'/'+id_inventaris_distribusi, function(data) {
			$("#popup_barang #popup_content").html(data);
		});
	}

	function hapus_barang(id_barang,barang_kembar_proc,id_inventaris_barang,id_distribusi){
		$("#popup_barang2 #popup_content2").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$("#popup_barang2").jqxWindow({
			theme: theme, resizable: false,
			width: 600,
			height: 400,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang2").jqxWindow('open');
		$.get("<?php echo base_url().'inventory/inv_barang/hapus_barang/';?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang+'/'+id_distribusi, function(data) {
			$("#popup_barang2 #popup_content2").html(data);
		});
	}


	function return_barang(id_barang,barang_kembar_proc,id_inventaris_barang){
		var confirms = confirm("Batalkan penghapusan barang ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/inv_barang/doreturn_barang_item/'; ?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang,  function(res){
				res = res.split('|');
				if(res[0]=='OK'){
					alert('Data berhasil dikembalikan');
					$("#barang_hapus").click();
				}
			});
		
		}
	}

	function del_barang(id_barang,barang_kembar_proc,id_inventaris_barang){
		var confirms = confirm("Hapus data dari database ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/inv_barang/dodel_barang_item/'; ?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang,  function(res){
				res = res.split('|');
				if(res[0]=='OK'){
					alert('Data berhasil dihapus dari database');
					$("#barang_hapus").click();
				}
			});
		
		}
	}


</script>

<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>

<div id="popup_barang2" style="display:none">
	<div id="popup_title2">Data Barang</div>
	<div id="popup_content2">&nbsp;</div>
</div>

<div class="box box-success">
	<div class="box-header">
      <h3 class="box-title">{title_form}</h3>
    </div>
		<div class="box-footer">
	      <div class="col-md-9">
			<!--<button class="btn btn-primary" id='btn_add_barang' type='button'><i class='fa fa-plus-square-o'></i> &nbsp;Tambah Barang</button>-->
			<button type="button " class="btn btn-warning" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			<?php  //	if(!empty($filter_golongan_invetaris) || $filter_golongan_invetaris !=''){  ?>
			<button type="button" class="btn btn-success" id="btn-export"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>	
			<?php // } ?>
		  </div>
		</div>
		<div class="box-header">
		<div class="col-md-3">
     		<select name="golongan_invetaris" id="golongan_invetaris" class="form-control" style="width:90">
	 			 <option value="">Pilih KIB Inventaris </option>
	 			 <?php 
	 			 	for($baris=0;$baris<count($get_data_tanah);$baris++) {
	 			 		$select = $get_data_tanah[$baris][0] == $this->session->userdata('filter_golongan_invetaris') ? 'selected' : '';
	 			 		echo "<option value=\"".$get_data_tanah[$baris][0]."\" $select>";
	 			 			echo $get_data_tanah[$baris][1];
	 			 		echo "</option>";
	 			 	}

	 			 ?>
	     	</select>
		  </div>
		  <div class="col-md-3">
	     		<select name="code_cl_phc" class="form-control" id="code_cl_phc">
	     			<option value="all">All</option>
					<?php foreach ($datapuskesmas as $row ) { ;?>
					<?php $select = $row->code == $this->session->userdata('filter_cl_phc') ? 'selected=selected' : '' ?>
					<option value="<?php echo $row->code; ?>" <?php echo $select ?> ><?php echo $row->value; ?></option>
				<?php	} ;?>
	     	</select>
		  </div>
		  <?php if($filter_golongan_invetaris == "0500000000" || $filter_golongan_invetaris == "0200000000"){ ?>
		  <div class="col-md-3">
		  		<select name="code_ruangan" class="form-control" id="code_ruangan">
	     			<option value="">Pilih Ruangan</option>
	     		</select>
		  </div>
		  <?php } ?>
		 </div> 
 <div class="box-body">
	<div style="width:100%;">
	    <div class="div-grid">
	      <div id='jqxtabs'>
	      	<ul style='margin-left: 20px;'>
	    <?php 	if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){ 
	    	?>	
	     		
	            <li id="inventaris_">Inventaris</li>
	            <li id="barang_hapus">Barang Dihapuskan</li>

	    <?php	}else  if(isset($filter_golongan_invetaris)){

	    		if($filter_golongan_invetaris=='0100000000'){ ?>

	            <li id="kibA">KIB A</li>
	            <li id="hapusA">Barang Dihapuskan</li>

	    <?php	}else if($filter_golongan_invetaris=='0200000000'){ ?>

	            <li id="kibB">KIB B</li>
	            <li id="hapusB">Barang Dihapuskan</li>

	    <?php	}else if($filter_golongan_invetaris=='0300000000'){ ?>

	            <li id="kibC">KIB C</li>
	            <li id="hapusC">Barang Dihapuskan</li>

	    <?php	}else if($filter_golongan_invetaris=='0400000000'){ ?>

	            <li id="kibD">KIB D</li>
	            <li id="hapusD">Barang Dihapuskan</li>

	    <?php	}else if($filter_golongan_invetaris=='0500000000'){ ?>

	            <li id="kibE">KIB E</li>
	            <li id="hapusE">Barang Dihapuskan</li>

	    <?php	}else if($filter_golongan_invetaris=='0600000000'){ ?>

	            <li id="kibF">KIB F</li>
	            <li id="hapusF">Barang Dihapuskan</li>
	    <?php		}	 
				} ?> 
	        </ul>
	     <?php 		if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){ 
	     	?>	

			        		<div><div id="jqxgrid_barang"></div></div>
			        		<div><div id="jqxgrid_DataHapus"></div></div>

	    <?php		}else  if(isset($filter_golongan_invetaris)){
     				if($filter_golongan_invetaris=='0100000000'){ ?>

			        		<div><div id="jqxgrid_Golongan_A"></div></div>
			        		<div><div id="jqxgrid_Golongan_A_hapus"></div></div>

	    <?php		}else if($filter_golongan_invetaris=='0200000000'){ ?>
		        	
			        		<div><div id="jqxgrid_Golongan_B"></div></div>
			        		<div><div id="jqxgrid_Golongan_B_hapus"></div></div>

	    <?php		}else if($filter_golongan_invetaris=='0300000000'){ ?>

			        		<div><div id="jqxgrid_Golongan_C"></div></div>
			        		<div><div id="jqxgrid_Golongan_C_hapus"></div></div>

	    <?php		}else if($filter_golongan_invetaris=='0400000000'){ ?>

			        		<div><div id="jqxgrid_Golongan_D"></div></div>
			        		<div><div id="jqxgrid_Golongan_D_hapus"></div></div>

	    <?php		}else if($filter_golongan_invetaris=='0500000000'){ ?>

			        		<div><div id="jqxgrid_Golongan_E"></div></div>
			        		<div><div id="jqxgrid_Golongan_E_hapus"></div></div>

	    <?php		}else if($filter_golongan_invetaris=='0600000000'){ ?>

			        		<div><div id="jqxgrid_Golongan_F"></div></div>
			        		<div><div id="jqxgrid_Golongan_F_hapus"></div></div>
		<?php		}	 
				} ?>        		
	      </div>
	    </div>
	</div>
 </div>
</div>
	
        
<script type="text/javascript">
	$(function(){
		<?php if(!isset($filterHAPUS) || $filterHAPUS !=''){ ?>
				var indexxx = $.jqx.cookie.cookie("jqxTabs_jqxWidget__");
		<?php }else{ ?>
				var indexxx = 0;
		<?php } ?>
        $('#jqxtabs').jqxTabs({selectedItem: indexxx, width: '100%',  position: 'top'});
        $("#jqxtabs").on('selected', function (event) {
            $.jqx.cookie.cookie("jqxTabs_jqxWidget__", event.args.item);
        });
         $.ajax({
        url : '<?php echo site_url('inventory/inv_barang/get_ruangan') ?>',
        type : 'POST',
        data : 'code_cl_phc=' + $('#code_cl_phc').val(),
        success : function(data) {
          $('#code_ruangan').html(data);
			filter_jqxgrid_inv_barang();
        } });
    $("#menu_inventory_inv_barang").addClass("active");
    $("#menu_aset_tetap").addClass("active");

    $('#code_cl_phc').change(function(){
      var code_cl_phc = $(this).val();
      var id_mst_inv_ruangan = '<?php echo set_value('code_ruangan')?>';
      $.ajax({
        url : '<?php echo site_url('inventory/inv_barang/get_ruangan') ?>',
        type : 'POST',
        data : 'code_cl_phc=' + code_cl_phc+'&id_mst_inv_ruangan=' + id_mst_inv_ruangan,
        success : function(data) {
          $('#code_ruangan').html(data);
			filter_jqxgrid_inv_barang();
        }
    });
      return false;
    }).change();

    $('#code_ruangan').change(function(){
      var id_mst_inv_ruangan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('inventory/inv_barang/get_ruangan_puskesmas') ?>',
        type : 'POST',
        data : 'idmstinvruangan=' + id_mst_inv_ruangan,
        success : function(data) {
			filter_jqxgrid_inv_barang();
        }
    });
      return false;
    }).change();

    $("#golongan_invetaris").change(function(){
		$.post("<?php echo base_url().'inventory/inv_barang/filter_golongan_invetaris' ?>", 'golongan_invetaris='+$(this).val(),  function(){
			location.reload(); 
		});
	});


    <?php 	if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){ ?>	
	     	$("#inventaris_").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#barang_hapus").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+ '0100000000',  function(){
					$("#jqxgrid_DataHapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});

    <?php	}else  if(isset($filter_golongan_invetaris)){
    		if($filter_golongan_invetaris=='0100000000'){ ?>

            $("#kibA").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_Golongan_A").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#hapusA").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+'0100000000',  function(){
					$("#jqxgrid_Golongan_A_hapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});

    <?php	}else if($filter_golongan_invetaris=='0200000000'){ ?>

            $("#kibB").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_Golongan_B").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#hapusB").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+'0100000000',  function(){
					$("#jqxgrid_Golongan_B_hapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});

    <?php	}else if($filter_golongan_invetaris=='0300000000'){ ?>

            $("#kibC").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_Golongan_C").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#hapusC").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+'0100000000',  function(){
					$("#jqxgrid_Golongan_C_hapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});

    <?php	}else if($filter_golongan_invetaris=='0400000000'){ ?>

            $("#kibD").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_Golongan_D").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#hapusD").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+'0100000000',  function(){
					$("#jqxgrid_Golongan_D_hapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});

    <?php	}else if($filter_golongan_invetaris=='0500000000'){ ?>

           $("#kibE").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_Golongan_E").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#hapusE").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+'0100000000',  function(){
					$("#jqxgrid_Golongan_E_hapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});

    <?php	}else if($filter_golongan_invetaris=='0600000000'){ ?>

            $("#kibF").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterGIB' ?>", 'filterGIB_='+ '0100000000',  function(){
					$("#jqxgrid_Golongan_F").jqxGrid('updatebounddata', 'cells');
				});
	     	});
	     	$("#hapusF").click(function(){
	     		$.post("<?php echo base_url().'inventory/inv_barang/filterHAPUS' ?>", 'filterHAPUS_='+'0100000000',  function(){
					$("#jqxgrid_Golongan_F_hapus").jqxGrid('updatebounddata', 'cells');
				});
	     	});
    <?php		}	 
			} ?> 
  });

	$("#btn-export").click(function(){
	<?php if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){ 
					if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){ ?>
		     			var jx = "#jqxgrid_barang";
			<?php 	}else if($this->session->userdata('filterHAPUS')!=''){ ?>
						var jx = "#jqxgrid_DataHapus";
		    <?php   }	?>
	<?php }else if(isset($filter_golongan_invetaris)){
		    		if($filter_golongan_invetaris=='0100000000'){ 
		    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){ ?>
							var jx = "#jqxgrid_Golongan_A";
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){ ?>
							var jx = "#jqxgrid_Golongan_A_hapus";
				<?php   }	?>
		    <?php	}else if($filter_golongan_invetaris=='0200000000'){ 
		    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){?>
							var jx = "#jqxgrid_Golongan_B";
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
							var jx = "#jqxgrid_Golongan_B_hapus";
				<?php   }?>		
		    <?php	}else if($filter_golongan_invetaris=='0300000000'){ 
		    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){	?>
							var jx ="#jqxgrid_Golongan_C";
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
							var jx = "#jqxgrid_Golongan_C_hapus";
				<?php   }	?>
		    <?php	}else if($filter_golongan_invetaris=='0400000000'){ 
		    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){?>
							var jx ="#jqxgrid_Golongan_D";
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
							var jx ="#jqxgrid_Golongan_D_hapus";
				<?php   }	?>
		    <?php	}else if($filter_golongan_invetaris=='0500000000'){ 
		    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterHAPUS')=='')){?>
							var jx ="#jqxgrid_Golongan_E";
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
							var jx ="#jqxgrid_Golongan_E_hapus";
				<?php   }	?>
		    <?php	}else if($filter_golongan_invetaris=='0600000000'){ 
		    			if(($this->session->userdata('filterGIB')!='')||($this->session->userdata('filterGIB')=='')){?>
							var jx ="#jqxgrid_Golongan_F";
				<?php 	}else if($this->session->userdata('filterHAPUS')!=''){?>
							var jx ="#jqxgrid_Golongan_F_hapus";
				<?php   }	
	    			}	 
				} 
		?> 

		var post = "";
		var filter = $(jx).jqxGrid('getfilterinformation');
		for(i=0; i < filter.length; i++){
			var fltr 	= filter[i];
			var value	= fltr.filter.getfilters()[0].value;
			var condition	= fltr.filter.getfilters()[0].condition;
			var filteroperation	= fltr.filter.getfilters()[0].operation;
			var filterdatafield	= fltr.filtercolumn;
	<?php
	if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){
	?>
			if(filterdatafield=="tanggal_diterima"){
					var d = new Date(value);
					var day = d.getDate();
					var month = d.getMonth()+1;
					var year = d.getFullYear();
					value =  year+'-'+month+'-'+day;
			}

	<?php }else if(isset($filter_golongan_invetaris)){
	if($filter_golongan_invetaris=='0100000000'){ ?> 
				if(filterdatafield=="status_sertifikat_tanggal"){
					var d = new Date(value);
					var day = d.getDate();
					var month = d.getMonth()+1;
					var year = d.getFullYear();
					value =  year+'-'+month+'-'+day;
				}
	<?php	}else if($filter_golongan_invetaris=='0200000000'){ ?>
				if(filterdatafield=="tanggal_bpkb"){
					var d = new Date(value);
					var day = d.getDate();
					var month = d.getMonth()+1;
					var year = d.getFullYear();
					value =  year+'-'+month+'-'+day;
				}
    <?php	}else if($filter_golongan_invetaris=='0300000000'){ ?>
					if(filterdatafield=="dokumen_tanggal"){
					var d = new Date(value);
					var day = d.getDate();
					var month = d.getMonth()+1;
					var year = d.getFullYear();
					value =  year+'-'+month+'-'+day;
					}
    <?php	}else if($filter_golongan_invetaris=='0400000000'){ ?>
					if(filterdatafield=="dokumen_tanggal"){
						var d = new Date(value);
						var day = d.getDate();
						var month = d.getMonth()+1;
						var year = d.getFullYear();
						value =  year+'-'+month+'-'+day;
					}
    <?php	}else if($filter_golongan_invetaris=='0500000000'){ ?>
					if(filterdatafield=="tahun_cetak_beli"){
						var d = new Date(value);
						var day = d.getDate();
						var month = d.getMonth()+1;
						var year = d.getFullYear();
						value =  year+'-'+month+'-'+day;
					}
    <?php	}else if($filter_golongan_invetaris=='0600000000'){ ?>
					if(filterdatafield=="tanggal_mulai"){
						var d = new Date(value);
						var day = d.getDate();
						var month = d.getMonth()+1;
						var year = d.getFullYear();
						value =  year+'-'+month+'-'+day;
					}
	<?php	}	 
		} 
			?> 	
			post = post+'&filtervalue'+i+'='+value;
			post = post+'&filtercondition'+i+'='+condition;
			post = post+'&filteroperation'+i+'='+filteroperation;
			post = post+'&filterdatafield'+i+'='+filterdatafield;
			post = post+'&'+filterdatafield+'operator=and';
		}
		post = post+'&filterscount='+i;
		
		var sortdatafield = $(jx).jqxGrid('getsortcolumn');
		if(sortdatafield != "" && sortdatafield != null){
			post = post + '&sortdatafield='+sortdatafield;
		}
		if(sortdatafield != null){
			var sortorder = $(jx).jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($(jx).jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			post = post+'&sortorder='+sortorder;
			
		}
		post = post+'&puskes='+$("#code_cl_phc option:selected").val();
		post = post+'&namepuskes='+$("#code_cl_phc option:selected").text();
		post = post+'&ruang='+$("#code_ruangan option:selected").text();
		<?php 	if(!isset($filter_golongan_invetaris) || $filter_golongan_invetaris ==''){ ?>
			
					$.post("<?php echo base_url()?>inventory/export/permohonan_export_inventori",post,function(response	){
						window.location.href=response;
						// alert(response);
					});
		<?php	}else  if(isset($filter_golongan_invetaris)){
			    		if($filter_golongan_invetaris=='0100000000'){ ?> 
								$.post("<?php echo base_url()?>inventory/export/permohonan_export_kiba",post,function(response	){
									window.location.href=response;
								});
			    <?php	}else if($filter_golongan_invetaris=='0200000000'){ ?>
				    	if($("#code_ruangan option:selected").text() == "Pilih Ruangan"){
				    		alert("Silahkan tentukan ruangan.");
				    		return false;
				    	}else{
								$.post("<?php echo base_url()?>inventory/export/permohonan_export_kibb",post,function(response	){
									window.location.href=response;
								});
						}
			    <?php	}else if($filter_golongan_invetaris=='0300000000'){ ?>
								$.post("<?php echo base_url()?>inventory/export/permohonan_export_kibc",post,function(response	){
									window.location.href=response;
								});
			    <?php	}else if($filter_golongan_invetaris=='0400000000'){ ?>
								$.post("<?php echo base_url()?>inventory/export/permohonan_export_kibd",post,function(response	){
									window.location.href=response;
								});
			    <?php	}else if($filter_golongan_invetaris=='0500000000'){ ?>
				    	if($("#code_ruangan option:selected").text() == "Pilih Ruangan"){
				    		alert("Silahkan tentukan ruangan.");
				    		return false;
				    	}else{
								$.post("<?php echo base_url()?>inventory/export/permohonan_export_kibe",post,function(response	){
									window.location.href=response;
								});
						}
			    <?php	}else if($filter_golongan_invetaris=='0600000000'){ ?>
								$.post("<?php echo base_url()?>inventory/export/permohonan_export_kibf",post,function(response	){
									window.location.href=response;
								});
		    	<?php	}	 
					} 
			?> 
	});
</script>
    
	
	