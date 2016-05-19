
<script>

	$(function(){
		ambil_total();
		
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inventaris_barang', type: 'string' },
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'id_pengadaan', type: 'number' },
			{ name: 'pilihan_keadaan_barang', type: 'string' },
			{ name: 'nama_barang', type: 'string' },
			{ name: 'pilihan_komponen', type: 'string' },
			{ name: 'harga', type: 'double' },
			{ name: 'keterangan_pengadaan', type: 'string' },
			{ name: 'pilihan_status_invetaris', type: 'string' },
			{ name: 'tanggal_pembelian', type: 'date' },
			{ name: 'foto_barang', type: 'string' },
			{ name: 'register_sampai', type: 'string' },
			{ name: 'barang_kembar_proc', type: 'string' },
			{ name: 'keterangan_inventory', type: 'string' },
			{ name: 'tanggal_pengadaan', type: 'date' },
			{ name: 'tanggal_diterima', type: 'date' },
			{ name: 'register', type: 'string' },
			{ name: 'tanggal_dihapus', type: 'date' },
			{ name: 'alasan_penghapusan', type: 'string' },
			{ name: 'pilihan_asal', type: 'string' },
			{ name: 'value', type: 'string' },
			{ name: 'waktu_dibuat', type: 'date' },
			{ name: 'terakhir_diubah', type: 'date' },
			{ name: 'jumlah', type: 'number' },
			{ name: 'totalharga', type: 'double' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/pengadaanbarang/barang/'.$kode); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
            commit(true);
			var arr = $.map(rowData, function(el) { return el });
			//alert(arr);
			//alert(arr[6]); alert(arr[8]);		//6 status
			var pengadaan= '<?php echo $kode; ?>';
			//alert(arr[]);

				$.post( '<?php echo base_url()?>inventory/pengadaanbarang/updatestatus_barang', {kode_proc:arr[7],pilihan_inv:arr[10],id_pengadaan:pengadaan},function( data ) {
						$("#jqxgrid_barang").jqxGrid('updateBoundData');
						
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
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$("#jqxgrid_barang").jqxGrid(
		{	
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
			<?php if(!isset($viewreadonly)){?>	{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false,editable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.barang_kembar_proc+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_inventaris_barang+"\",\""+dataRecord.barang_kembar_proc+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Kode Barang', align: 'center',cellsalign: 'center',editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Register ', editable: false,datafield: 'register_sampai', columntype: 'textbox', filtertype: 'none', width: '10%'},
				{ text: 'Nama Barang ', editable: false,datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '26%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'center',editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '6%'},
				{ text: 'Harga Satuan (Rp.)', align: 'center',cellsalign: 'right',editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
				{ text: 'Sub Total (Rp.)', align: 'center',cellsalign: 'right',editable: false,datafield: 'totalharga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
            <?php }else{ ?>
				{ text: 'Kode Barang', align: 'center',cellsalign: 'center',editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Register ', editable: false,datafield: 'register_sampai', columntype: 'textbox', filtertype: 'none', width: '10%'},
				{ text: 'Nama Barang ', editable: false,datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '31%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'center',editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Harga Satuan (Rp.)', align: 'center',cellsalign: 'right',editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
				{ text: 'Sub Total (Rp.)', align: 'center',cellsalign: 'right',editable: false,datafield: 'totalharga', columntype: 'textbox', filtertype: 'textbox', width: '12%'},
            <?php } ?>
				{
                        text: '<b><i class="fa fa-pencil-square-o"></i> Status</b>', align: 'center',cellsalign: 'center', datafield: 'value', width: '8%', columntype: 'dropdownlist',
                        createeditor: function (row, column, editor) {
                            // assign a new data source to the dropdownlist.
                            var list = [<?php foreach ($kodestatus_inv as $key) {?>
							"<?=$key->value?>",
							<?php } ?>];
                            editor.jqxDropDownList({ autoDropDownHeight: true, source: list });
                        },
                        // update the editor's value before saving it.
                        cellvaluechanging: function (row, column, columntype, oldvalue, newvalue) {
                            // return the old value, if the new value is empty.
                            if (newvalue == "") return oldvalue;
                        }
                 },
				{ text: 'Tanggal Terima',align: 'center',cellsalign: 'center', editable: false,datafield: 'tanggal_diterima', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '9%'}
           ]
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


		$("#btn-export").click(function(){
			var post = "";
			var filter = $("#jqxgrid_barang").jqxGrid('getfilterinformation');
			for(i=0; i < filter.length; i++){
				var fltr 	= filter[i];
				var value	= fltr.filter.getfilters()[0].value;
				var condition	= fltr.filter.getfilters()[0].condition;
				var filteroperation	= fltr.filter.getfilters()[0].operation;
				var filterdatafield	= fltr.filtercolumn;
				
				post = post+'&filtervalue'+i+'='+value;
				post = post+'&filtercondition'+i+'='+condition;
				post = post+'&filteroperation'+i+'='+filteroperation;
				post = post+'&filterdatafield'+i+'='+filterdatafield;
				post = post+'&'+filterdatafield+'operator=and';
			}
			post = post+'&filterscount='+i;
			
			var sortdatafield = $("#jqxgrid_barang").jqxGrid('getsortcolumn');
			if(sortdatafield != "" && sortdatafield != null){
				post = post + '&sortdatafield='+sortdatafield;
			}
			if(sortdatafield != null){
				var sortorder = $("#jqxgrid_barang").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgrid").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
				post = post+'&sortorder='+sortorder;
			}
			
			post = post+'&kode={kode}';
			
			$.post("<?php echo base_url()?>inventory/pengadaanbarang/pengadaan_detail_export",post  ,function(response){
				window.location.href=response;
			});
		});
	});
	
	function ambil_total()
	{
		$.ajax({
		url: "<?php echo base_url().'inventory/pengadaanbarang/total_pengadaan/'.$kode ?>",
		dataType: "json",
		success:function(data)
		{ 
			$.each(data,function(index,elemet){
				$("#jumlah_unit_").html(elemet.jumlah_unit);
				$("#nilai_pengadaan_").html(elemet.nilai_pengadaan);
				$("#waktu_dibuat_").html(elemet.waktu_dibuat);
				$("#terakhir_diubah_").html(elemet.terakhir_diubah);
			});
		}
		});

		return false;
	}

	function close_popup(){
		$("#popup_barang").jqxWindow('close');
		ambil_total();
	}

	function add_barang(){
		
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/pengadaanbarang/add_barang/'.$kode.'/'; ?>" , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 1000,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function edit_barang(id_inventaris_barang,kode_proc){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/pengadaanbarang/edit_barang/'.$kode.'/';?>"+id_inventaris_barang+'/'+kode_proc, function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 1000,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}
	function del_barang(kodeinventaris,barang_kembar_proc){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/pengadaanbarang/dodelpermohonan/'.$kode.'/'; ?>" + kodeinventaris+'/'+barang_kembar_proc,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
				ambil_total();
			});
			
		}
	}

</script>

<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>

<div>
	<div style="width:100%;">
		<div style="padding:5px" class="pull-right">
			<?php if(!isset($viewreadonly)){?><button class="btn btn-success" id='btn_add_barang' type='button'><i class='fa fa-plus-square'></i> Tambah Barang</button><?php } ?>
		</div>
        <div id="jqxgrid_barang"></div>
	</div>
</div>