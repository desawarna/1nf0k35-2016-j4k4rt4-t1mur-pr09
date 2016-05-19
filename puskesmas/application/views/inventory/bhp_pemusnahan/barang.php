<script>
	$(function(){
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_inventaris_habispakai_distribusi', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'number' },
			{ name: 'uraian', type: 'string' },
			{ name: 'code_cl_phc', type: 'string' },
			{ name: 'jenis_bhp', type: 'string' },
			{ name: 'tgl_distribusi', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai_jenis', type: 'string' },
			{ name: 'jml_distribusi', type: 'string' },
			{ name: 'jml_opname', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'tgl_opname', type: 'double' },
			{ name: 'jmlawal', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_pemusnahan/json_barang/'.'8'.'/'.$tgl_opname); ?>",
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
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_add.gif' onclick='pilih_opname(\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '50%'},
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jmlawal', columntype: 'textbox', filtertype: 'textbox', width: '20%'}
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

	function close_popup_opname(){
		$("#popup_barang_opname").jqxWindow('close');
		$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
		$("#jqxgrid_barang_opname").jqxGrid('updatebounddata', 'cells');
	}
	function pilih_opname(barang,batch){
		$tanggal_opname= $('#tgl_opname').val();
		$("#popup_barang_opname #popup_content_opname_opname").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_pemusnahan/add_barang/'.$tgl_opname.'/'.$kode.'/'; ?>"+barang+'/'+batch, function(data) {
			$("#popup_content_opname").html(data);
		});
		$("#popup_barang_opname").jqxWindow({
			theme: theme, resizable: false,
			width: 600,
			height: 400,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang_opname").jqxWindow('open');
	}

</script>

<div id="popup_barang_opname" style="display:none">
	<div id="popup_title">Data Barang </div>
	<div id="popup_content_opname">&nbsp;</div>
</div>

<div>
	<div style="width:100%;">
        <div id="jqxgrid_barang"></div>
	</div>
</div>