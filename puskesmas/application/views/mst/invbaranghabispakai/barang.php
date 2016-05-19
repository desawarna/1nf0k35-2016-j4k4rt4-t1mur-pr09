
<script>

	$(function(){
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			
			{ name: 'id_mst_inv_barang_habispakai_jenis', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'no', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'string' },
			{ name: 'code', type: 'string' },
			{ name: 'negara_asal', type: 'number' },
			{ name: 'merek_tipe', type: 'string' },
			{ name: 'jenisuraian', type: 'string' },
			{ name: 'pilihan_satuan', type: 'string' },
			{ name: 'value', type: 'string' },
			{ name: 'edit', type: 'number' },
			{ name: 'delete', type: 'number' },
			
        ],
		url: "<?php echo site_url('mst/invbaranghabispakai/json_detail/'.$kode); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
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
     	$('#btn-refresh').click(function () {
			$("#jqxgrid_barang").jqxGrid('clearfilters');
		});
		$("#jqxgrid_barang").jqxGrid(
		{	
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_mst_inv_barang_habispakai+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_mst_inv_barang_habispakai+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Kode', align: 'center', cellsalign: 'left', datafield: 'code', columntype: 'textbox', filtertype: 'none', width: '12%' },
				//{ text: 'Kode', align: 'center', cellsalign: 'left', datafield: 'code', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Uraian', datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '30%' },
				{ text: 'Merek Tipe',  align: 'center', cellsalign: 'left', datafield: 'merek_tipe', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
				{ text: 'Satuan',  datafield: 'pilihan_satuan', align: 'center', cellsalign: 'left', columntype: 'textbox', filtertype: 'textbox', width: '24%'},
				{ text: 'Jenis Barang',  align: 'center', cellsalign: 'left', datafield: 'jenisuraian', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
           				
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
	}

	function add_barang(){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'mst/invbaranghabispakai/add_barang/'.$kode.'/'; ?>" , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 500,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function edit_barang(kode){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'mst/invbaranghabispakai/edit_barang/';?>" + kode, function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 500,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}
	function del_barang(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'mst/invbaranghabispakai/dodelbarang/'; ?>"+id,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
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

        <div id="jqxgrid_barang"></div>
	</div>
</div>