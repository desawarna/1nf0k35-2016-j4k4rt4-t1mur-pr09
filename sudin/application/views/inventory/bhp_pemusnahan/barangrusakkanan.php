<script>
	$(function(){
	   var source_rusak = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_hasbispakai_pembelian', type: 'string' },
			{ name: 'code_cl_phc', type: 'number' },
			{ name: 'tgl_kadaluarsa', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'number' },
			{ name: 'uraian', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'merek_tipe', type: 'string' },
			{ name: 'hargaterakhir', type: 'string' },
			{ name: 'jumlahrusak', type: 'string' },
			{ name: 'edit', type: 'number' },
			{ name: 'delete', type: 'number' },
        ],
		url: "<?php echo site_url('inventory/bhp_pemusnahan/json_rusakkanan/'.$kode_rusak.'/'.$tgl_opname.'/'.$code_cl_phc); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_barang_rusak_kanan").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_barang_rusak_kanan").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				source_rusak.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter_rusak = new $.jqx.dataAdapter(source_rusak, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$("#jqxgrid_barang_rusak_kanan").jqxGrid(
		{	
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter_rusak, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
				/*{ text: 'Pilih', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang_rusak_kanan").jqxGrid('getrowdata', row);
				    if (dataRecord.edit==1) {
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_add.gif' onclick='pilih_rusak(\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },*/
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '53%'},
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '24%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jumlahrusak', columntype: 'textbox', filtertype: 'textbox', width: '23%'}
           ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_barang_rusak_kanan").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid_barang_rusak_kanan").jqxGrid('updatebounddata', 'cells');
		});
 		$('#btn_add_barang').click(function () {
 				add_barang();
		});


	});

	function close_popup_rusak(){
		$("#popup_barang_rusak_kanan").jqxWindow('close');
		$("#jqxgrid_barang_rusak_kanan").jqxGrid('updatebounddata', 'cells');
		$("#jqxgrid_barang_rusak_kiri").jqxGrid('updatebounddata', 'cells');
	}
	function pilih_rusak(barang,batch){
		$tanggal_opname= $('#tgl_opname').val();
		$("#popup_barang_rusak_kanan #popup_content_rusak_kanan").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_pemusnahan/add_barang_rusak/'.$tgl_opname.'/'.$kode_rusak.'/'; ?>"+barang+'/'+batch, function(data) {
			$("#popup_content_rusak_kanan").html(data);
		});
		$("#popup_barang_rusak_kanan").jqxWindow({
			theme: theme, resizable: false,
			width: 600,
			height: 400,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang_rusak_kanan").jqxWindow('open');
	}

</script>

<div id="popup_barang_rusak_kanan" style="display:none">
	<div id="popup_title_rusak">Data Barang </div>
	<div id="popup_content_rusak_kanan">&nbsp;</div>
</div>

<div>
	<div style="width:100%;">
        <div id="jqxgrid_barang_rusak_kanan"></div>
	</div>
</div>