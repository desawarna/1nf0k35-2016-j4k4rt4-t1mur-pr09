<script>
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
			{ name: 'pilihan_satuan', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'jumlah', type: 'string' },
			{ name: 'tgl_kadaluarsa', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_distribusi/distribusibarang/'.$kode); ?>",
		cache: false,
		filter: function(){
			$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'sort');
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
     
		$("#jqxgrid_barang_distribusi").jqxGrid(
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
			<?php if ($jenis_bhp=="8") { ?>
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '40%'},
				{ text: 'Kemasan / Satuan ', editable: false,columntype: 'textbox', filtertype: 'textbox', width: '20%',datafield: 'pilihan_satuan'},
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '15%'},
				{ text: 'Tgl Kadaluarsa ',align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '15%',datafield: 'tgl_kadaluarsa'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '10%'},
			<?php }else{ ?>
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '45%'},
				{ text: 'Kemasan / Satuan ', editable: false,columntype: 'textbox', filtertype: 'textbox', width: '35%',datafield: 'pilihan_satuan'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
			<?php } ?>
           ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_barang_distribusi").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'cells');
		});
 		


	});

	
	function edit_barang(id_permohonan,kode_barang){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_pengadaan/edit_barang/';?>"+id_permohonan+'/'+kode_barang, function(data) {
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
	function del_barang(id_permohonan,kode_barang){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/bhp_pengadaan/dodelpermohonan/'; ?>" + id_permohonan+'/'+kode_barang,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'cells');
				ambil_total();
				ambil_tanggalopname()
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
        <div id="jqxgrid_barang_distribusi"></div>
	</div>
</div>