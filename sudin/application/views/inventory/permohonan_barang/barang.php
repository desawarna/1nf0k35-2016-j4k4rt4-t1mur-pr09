
<script>
var code_cl_phc = '<?php echo $code_cl_phc?>';
	$(function(){
		ambil_total();
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'no', type: 'number' },
			{ name: 'id_inv_permohonan_barang_item', type: 'number' },
			{ name: 'nama_barang', type: 'string' },
			{ name: 'jumlah', type: 'number' },
			{ name: 'harga', type: 'double' },
			{ name: 'subtotal', type: 'double' },
			{ name: 'keterangan', type: 'string' },
			{ name: 'id_inv_permohonan_barang', type: 'number' },
			{ name: 'code_mst_inv_barang', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/permohonanbarang/barang/'.$kode.'/'.$code_cl_phc); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
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
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit_barang(\""+dataRecord.id_inv_permohonan_barang+"\",\""+code_cl_phc+"\",\""+dataRecord.id_inv_permohonan_barang_item+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_inv_permohonan_barang_item+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'No', align: 'center', cellsalign: 'center', datafield: 'no', columntype: 'textbox', filtertype: 'none', width: '4%' },
				{ text: 'Kode Barang', align: 'center', cellsalign: 'center', datafield: 'code_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Nama Barang', datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '22%' },
				{ text: 'Jumlah Barang',  align: 'center', cellsalign: 'center', datafield: 'jumlah', columntype: 'textbox', filtertype: 'textbox', width: '9%'},
				{ text: 'Harga Barang (RP.)',  align: 'center', cellsalign: 'center', datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '14%'},
				{ text: 'Sub Total (Rp.)',  align: 'center', cellsalign: 'center', datafield: 'subtotal', columntype: 'textbox', filtertype: 'textbox', width: '14%'},
				{ text: 'Keterangan',datafield: 'keterangan', columntype: 'textbox', filtertype: 'textbox', width: '19%'}
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
	function ambil_total()
	{
		$.ajax({
		url: "<?php echo base_url().'inventory/permohonanbarang/total_permohonan/'.$kode ?>",
		dataType: "json",
		success:function(data)
		{ 
			$.each(data,function(index,elemet){
				$("#total_jumlah_").html(elemet.totaljumlah);
				$("#total_harga_").html(elemet.totalharga);
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
		$.get("<?php echo base_url().'inventory/permohonanbarang/add_barang/'.$kode.'/'.$code_cl_phc.'/'; ?>" , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 700,
			height: 700,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function edit_barang(kode,code_cl_phc,id_inv_permohonan_barang_item){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/permohonanbarang/edit_barang/'.$kode.'/'.$code_cl_phc.'/'; ?>" + id_inv_permohonan_barang_item, function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 700,
			height: 700,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function del_barang(id_inv_permohonan_barang_item){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/permohonanbarang/dodelpermohonan/'.$kode.'/'.$code_cl_phc.'/' ?>/" + id_inv_permohonan_barang_item,  function(){
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
			<button class="btn btn-success" id='btn_add_barang' type='button'><i class='fa fa-plus-square'></i> Tambah Barang</button>
		</div>
        <div id="jqxgrid_barang"></div>
	</div>
</div>