<script>
	$(function(){
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_hasbispakai_pembelian', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'number' },
			{ name: 'uraian', type: 'string' },
			{ name: 'jml', type: 'number' },
			{ name: 'jml_opname', type: 'number' },
			{ name: 'tgl_opname', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'subtotal', type: 'string' },
			{ name: 'harga', type: 'double' },
			{ name: 'tgl_update', type: 'date' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_distribusi/distribusibarang/'.$kode); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
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
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '50%'},
				{ text: 'Batch ',datafield: 'batch' ,align: 'center', editable: false, columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				<?php }else{
				?>
				{ text: 'Nama Barang ', editable: false,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '60%'},
				{ text: 'Jumlah ', align: 'center',cellsalign: 'right',editable: false,datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '30%'},
				<?php } ?>
				{ text: 'Hapus', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang_distribusi").jqxGrid('getrowdata', row);
				    if (dataRecord.edit==1 && (dataRecord.jml_opname > 0)){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_barang(\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
           ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_barang_distribusi").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'cells');
		});


	});

	
	function del_barang(id_barang,kode_batch){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/bhp_distribusi/dodelpermohonan/'.$kode.'/'; ?>" + id_barang+'/'+kode_batch,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_barang_distribusi").jqxGrid('updatebounddata', 'cells');
				ambil_total();
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
        <div id="jqxgrid_barang_distribusi"></div>
	</div>
</div>