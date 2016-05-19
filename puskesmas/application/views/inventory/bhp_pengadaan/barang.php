
<script>

	$(function(){
		ambil_total();
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_hasbispakai_pembelian', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'number' },
			{ name: 'uraian', type: 'string' },
			{ name: 'jml', type: 'number' },
			{ name: 'tgl_opname', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'subtotal', type: 'string' },
			{ name: 'jml_rusak', type: 'string' },
			{ name: 'harga', type: 'double' },
			{ name: 'tgl_update', type: 'date' },
			{ name: 'jml_distribusi', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_pengadaan/barang/'.$kode); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
            commit(true);
			var arr = $.map(rowData, function(el) { return el });
			//alert(arr);
			//alert(arr[6]); alert(arr[8]);		//6 status
			var pengadaan= '<?php echo $kode; ?>';
			//alert(arr[]);

				$.post( '<?php echo base_url()?>inventory/bhp_pengadaan/updatestatus_barang', {kode_proc:arr[7],pilihan_inv:arr[10],id_pengadaan:pengadaan},function( data ) {
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
				    if (({pilihan_status_pembelian}==2) || (dataRecord.jml_distribusi>0)) {
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}else{
			    		return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_inv_hasbispakai_pembelian+"\",\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";	
					}
                 }
                },
				{ text: 'Del', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if (({pilihan_status_pembelian}==2) || (dataRecord.jml_distribusi>0)) {
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_inv_hasbispakai_pembelian+"\",\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}
                 }
                },
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '25%'},
				<?php if (isset($id_mst_inv_barang_habispakai_jenis)&&($id_mst_inv_barang_habispakai_jenis=='8')) { ?>
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Jumlah Rusak', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml_rusak', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Harga Satuan (Rp.)', align: 'center',cellsalign: 'right',editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Sub Total (Rp.)', align: 'center',cellsalign: 'right',editable: false,datafield: 'subtotal', columntype: 'textbox', filtertype: 'none', width: '15%'},
				<?php }else{ ?>
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
				{ text: 'Jumlah Rusak', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml_rusak', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Harga Satuan (Rp.)', align: 'center',cellsalign: 'right',editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Sub Total (Rp.)', align: 'center',cellsalign: 'right',editable: false,datafield: 'subtotal', columntype: 'textbox', filtertype: 'none', width: '15%'},
            <?php } }else{ ?>
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '25%'},
				<?php if (isset($id_mst_inv_barang_habispakai_jenis)&&($id_mst_inv_barang_habispakai_jenis=='8')) { ?>
				{ text: 'Batch ',datafield: 'batch', align: 'center',cellsalign: 'left', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '9%'},
				{ text: 'Jumlah Rusak', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml_rusak', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Harga Satuan (Rp.)', align: 'center',cellsalign: 'right',editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Sub Total (Rp.)', align: 'center',cellsalign: 'right',editable: false,datafield: 'subtotal', columntype: 'textbox', filtertype: 'none', width: '15%'},
				<?php }else{ ?>
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
				{ text: 'Jumlah Rusak', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml_rusak', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
				{ text: 'Harga Satuan (Rp.)', align: 'center',cellsalign: 'right',editable: false, datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Sub Total (Rp.)', align: 'center',cellsalign: 'right',editable: false,datafield: 'subtotal', columntype: 'textbox', filtertype: 'none', width: '18%'},
            <?php } } ?>
				{ text: 'Tanggal Update',align: 'center',cellsalign: 'center', editable: false,datafield: 'tgl_update', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '16%'}
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
			
			$.post("<?php echo base_url()?>inventory/bhp_pengadaan/pengadaan_detail_export",post  ,function(response){
				//alert(response);
				window.location.href=response;
			});
		});
	});

	function ambil_total()
	{
		$.ajax({
		url: "<?php echo base_url().'inventory/bhp_pengadaan/total_pengadaan/'.$kode ?>",
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
		$.get("<?php echo base_url().'inventory/bhp_pengadaan/add_barang/'.$kode.'/'.$id_mst_inv_barang_habispakai_jenis.'/'; ?>" , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 500,
			height: 480,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function edit_barang(id_permohonan,kode_barang,batch){
	/*	$.get("<?php // echo base_url().'inventory/bhp_pengadaan/cekdelete/'; ?>" + id_permohonan+'/'+kode_barang+'/'+batch,  function(data){
			if(data=='1'){
				alert('Maaf, Data tidak bisa di ubah karena sudah di distribusikan');
			}else{*/
				$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
				$.get("<?php echo base_url().'inventory/bhp_pengadaan/edit_barang/'.$id_mst_inv_barang_habispakai_jenis.'/';?>"+id_permohonan+'/'+kode_barang, function(data) {
					$("#popup_content").html(data);
				});
				$("#popup_barang").jqxWindow({
					theme: theme, resizable: false,
					width: 500,
					height: 480,
					isModal: true, autoOpen: false, modalOpacity: 0.2
				});
				$("#popup_barang").jqxWindow('open');
		/*	}
		});*/
	}
	function del_barang(id_permohonan,kode_barang,batch){
	/*	$.get("<?php // echo base_url().'inventory/bhp_pengadaan/cekdelete/'; ?>" + id_permohonan+'/'+kode_barang+'/'+batch,  function(data){
			if(data=='1'){
				alert('Maaf, Data tidak bisa di hapus karena sudah di distribusikan');
			}else{*/
				var confirms = confirm("Hapus Data ?");
				if(confirms == true){
					$.post("<?php echo base_url().'inventory/bhp_pengadaan/dodelpermohonan/'; ?>" + id_permohonan+'/'+kode_barang+'/'+batch,  function(){
						alert('Data berhasil dihapus');

						$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
						ambil_total();
					});
					
				}		
		/*	}
		});
		*/
	}

</script>

<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>

<div>
	<div style="width:100%;">
		<div style="padding:5px" class="pull-right">
			<?php if((!isset($viewreadonly))&&($pilihan_status_pembelian!=2)){?><button class="btn btn-success" id='btn_add_barang' type='button'><i class='fa fa-plus-square'></i> Tambah Barang</button><?php  } ?>
		</div>
        <div id="jqxgrid_barang"></div>
	</div>
</div>