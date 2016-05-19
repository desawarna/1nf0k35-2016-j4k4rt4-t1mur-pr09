
<script>
	$(function(){

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'inv_inventaris_habispakai_opname_item', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'number' },
			{ name: 'uraian', type: 'string' },
			{ name: 'jml_awal', type: 'number' },
			{ name: 'jml_akhir', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'selisih', type: 'string' },
			{ name: 'jml_selisih', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_pemusnahan/json_opname_dalam/'.$kode); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_barang_opname").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_barang_opname").jqxGrid('updatebounddata', 'sort');
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
     
		$("#jqxgrid_barang_opname").jqxGrid(
		{	
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: false, filterable: false, sortable: true, autoheight: true, pageable: false, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '50%'},
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Jumlah Musnah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml_selisih', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Hapus', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang_opname").jqxGrid('getrowdata', row);
				    if (dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
           ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_barang_opname").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid_barang_opname").jqxGrid('updatebounddata', 'cells');
		});


	});
	function close_popup_master(){
		$("#popup_barang_master").jqxWindow('close');
		$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
		$("#jqxgrid_barang_opname").jqxGrid('updatebounddata', 'cells');
	}
	
	function del_barang(id_barang,kode_batch){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/bhp_pemusnahan/dodelpermohonan/'.$kode.'/'; ?>" + id_barang+'/'+kode_batch,  function(){
				alert('Data berhasil dihapus');
				$("#jqxgrid_barang_opname").jqxGrid('updatebounddata', 'cells');
				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
			});
			
		}
	}
	$("#btn-masteropname").click(function(){
		pilih_opname_master($("#jenis_bhp").val());
	});
	function pilih_opname_master(jenis){
		if (jenis.toLowerCase()=="obat") {
			idjenis = '8';
		}else{
			idjenis = '0';
		}
		$("#popup_barang_master #popup_content_master").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_pemusnahan/add_barang_opnamemaster/'.$kode.'/'?>"+idjenis, function(data) {
			$("#popup_content_master").html(data);
		});
		$("#popup_barang_master").jqxWindow({
			theme: theme, resizable: false,
			width: 600,
			height: 400,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang_master").jqxWindow('open');
	}
</script>

<div id="popup_barang_master" style="display:none">
	<div id="popup_title_master">Data Opname Barang Master</div>
	<div id="popup_content_master">&nbsp;</div>
</div>

<div>
	<div align="right">
	<!--<button type="button" id="btn-masteropname" class="btn btn-success"><i class='fa fa-plus-square'></i> &nbsp;Tambah</button>-->
	</div>
	<div class="box-body">
		<div style="width:100%;">
	        <div id="jqxgrid_barang_opname"></div>
		</div>
	</div>
</div>