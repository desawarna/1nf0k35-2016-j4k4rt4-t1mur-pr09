
<script>

	$(function(){
		ambil_total();
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			
			{ name: 'id_mst_inv_barang', type: 'string' },
			{ name: 'nama_barang', type: 'string' },
			{ name: 'register', type: 'string' },
			{ name: 'tahun', type: 'number' },
			{ name: 'pilihan_keadaan_barang', type: 'string' },
			{ name: 'harga', type: 'number' },
			<?php
			foreach($kondisi as $k){
			?>
			{ name: '<?=$k->id?>', type: 'number' },
			<?php	
			}
			?>
			
        ],
		url: "<?php echo site_url('inventory/inv_ruangan/json_detail'); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
            commit(true);
			var arr = $.map(rowData, function(el) { return el });
			var pengadaan= '<?php echo $kode; ?>';
				$.post( '<?php echo base_url()?>inventory/pengadaanbarang/updatestatus_barang', {kode_proc:arr[6],pilihan_inv:arr[8],id_pengadaan:pengadaan},function( data ) {
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
			showfilterrow: false, filterable: false, sortable: false, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Kode Barang', align:'center', cellsalign:'center', editable: false, datafield: 'id_mst_inv_barang', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Nama Barang ', editable: false,datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '35%'},
				{ text: 'Register ', align:'center', cellsalign:'center', editable: false,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				{ text: 'Tahun ', align:'center', cellsalign:'center', editable: false,datafield: 'tahun', columntype: 'textbox', filtertype: 'textbox', width: '8%'},
				<?php 
				$w = 24/$n_kondisi;
				foreach($kondisi as $r){ ?>
					{ text: '<?=$r->id?>',  columngroup : 'kondisi',editable: false, datafield: '<?=$r->id?>', align:'center', cellsalign:'center', columntype: 'textbox', filtertype: 'textbox', width: '<?=$w?>%'},
				<?php } ?>
				
				{ text: 'Harga (Rp)', align:'center', cellsalign:'right', editable: false,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox', width: '15%',  cellsalign: 'right', cellsformat: 'f'}
           				
           ],
		   columngroups:
		   [
			{text: 'Kondisi', align: 'center', name:'kondisi'}
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
	/*8function ambil_total(){
	$("#success").load("<?php echo base_url().'inventory/pengadaanbarang/total_pengadaan/'.$kode ?>", function(response, status, xhr) {
	  if (status == "error") {
	    var msg = "Sorry but there was an error: ";
	    $("#error").html(msg + xhr.status + " " + xhr.statusText);
	  }else{
	    //alert(response);
	  }
	});
	  
	}*/
	function ambil_total()
	{
		$.ajax({
		url: "<?php echo base_url().'inventory/pengadaanbarang/total_pengadaan/'.$kode ?>",
		dataType: "json",
		success:function(data)
		{ 
			$.each(data,function(index,elemet){
				document.getElementById("jumlah_unit_").innerHTML = elemet.jumlah_unit;
				document.getElementById("nilai_pengadaan_").innerHTML = elemet.nilai_pengadaan;
				document.getElementById("waktu_dibuat_").innerHTML = elemet.waktu_dibuat;
				document.getElementById("terakhir_diubah_").innerHTML = elemet.terakhir_diubah;
			});
		}
		});
		/*$.ajax({
        url: "<?php echo base_url().'inventory/pengadaanbarang/total_pengadaan/'.$kode ?>",
        dataType: "json"
		}).success(function(data){
		    $('#jumlah_unit_').append(JSON.stringify(data));
		});*/


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

	function edit_barang(id_barang,barang_kembar_proc,id_inventaris_barang){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/pengadaanbarang/edit_barang/'.$kode.'/';?>" + id_barang+'/'+barang_kembar_proc+'/'+id_inventaris_barang, function(data) {
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
	function del_barang(id_barang,barang_kembar_proc){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/pengadaanbarang/dodelpermohonan/'.$kode.'/'; ?>" + id_barang+'/'+barang_kembar_proc,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
			});
			ambil_total();
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