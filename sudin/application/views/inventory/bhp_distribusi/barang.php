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
			{ name: 'jumlah', type: 'string' },
			{ name: 'subtotal', type: 'string' },
			{ name: 'harga', type: 'double' },
			{ name: 'tgl_update', type: 'date' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_distribusi/barang/'.$jenis_bhp); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
            commit(true);
			var arr = $.map(rowData, function(el) { return el });
			//alert(arr);
			//alert(arr[6]); alert(arr[8]);		//6 status
			var pengadaan= '<?php echo $kode; ?>';
			//alert(arr[]);

				$.post( '<?php echo base_url()?>inventory/bhp_distribusi/updatestatus_barang', {kode_proc:arr[7],pilihan_inv:arr[10],id_pengadaan:pengadaan},function( data ) {
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
				{ text: 'Pilih', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if (dataRecord.edit==1) {
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_add.gif' onclick='pilih(\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
                <?php if ($jenis_bhp=="8") { ?>
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '50%'},
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '20%'}
				<?php }else{
				?>
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '60%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '30%'}
				<?php } ?>
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


	});

	function close_popup(){
		$("#popup_barang").jqxWindow('close');
		ambil_total();
		$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'cells');
	}
	function ambil_total(argument) {
		$.ajax({
		url: "<?php echo base_url().'inventory/bhp_distribusi/total_distribusi/'.$kode ?>",
		dataType: "json",
		success:function(data)
		{ 
			$.each(data,function(index,elemet){
				$("#jumlah_total_").html(elemet.jumlah_tot);
			});
		}
		});

		return false;
	}
	function pilih(barang,batch){
		
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_distribusi/add_distribusi/'.$kode.'/'; ?>"+barang+'/'+batch , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 400,
			height: 320,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

</script>

<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang </div>
	<div id="popup_content">&nbsp;</div>
</div>

<div>
	<div style="width:100%;">
        <div id="jqxgrid_barang"></div>
	</div>
</div>