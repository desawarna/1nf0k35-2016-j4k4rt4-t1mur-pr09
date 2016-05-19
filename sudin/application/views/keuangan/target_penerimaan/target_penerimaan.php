

<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>



<section class="content">
<form action="<?php echo base_url()?>mst/agama/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
			<div class="col-md-6">
				<button id="btn_add_bku" type="button" class="btn btn-primary" ><i class='fa fa-plus'></i> &nbsp; Tambah Target</button>

			</div>
			<div class="col-md-2 pull-right">
			<select name="pilih_tahun" class="form-control">
				<option value="0">Pilih Tahun</option>
				<?php for($i=date('Y'); $i>date('Y')-5; $i--){?>
					<option <?=$this->session->userdata('bku_penerimaan_tahun')==$i?'selected':''?> value="<?=$i?>"><?=$i?></option>
				<?php } ?>				
			</select>
			</div>
			<div class="col-md-2 pull-right">
			
			<select name="pilih_bulan" class="form-control">
				<option value="0">Pilih Bulan</option>
				<?php 
				$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
				for($i=0; $i<12; $i++){?>
					<option <?=$this->session->userdata('bku_penerimaan_bulan')==$i+1?'selected':''?> value="<?=$i+1?>"><?=$bulan[$i]?></option>
				<?php } ?>				
			</select>
			</div>
	     </div>
        <div class="box-body">
			<div id="popup_barang" style="display:none">
				<div id="popup_title">Data Rekening</div>
				<div id="popup_content">&nbsp;</div>
			</div>

			<div>
				<div style="width:100%;">					
					<div id="jqxgrid_barang"></div>
				</div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>





<div class="modal fade" id="ModalSetor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Kode Rekening</h4>
      </div>
      <div class="modal-body">
	
		<div class="form-group">
		  <label for="exampleInputEmail1">Kode Rekening</label>
		  <input type="text"  id="kode_rekening" class="form-control" name="kode_rekening" id="exampleInputEmail1" placeholder="Kode Rekening" >		  
		</div>
		
		<div class="form-group">
		  <label for="exampleInputEmail1">Uraian</label>
		  <input type="text"  id="uraian" value="Setoran Tunai ke Bank " class="form-control" name="uraian" id="exampleInputEmail1" placeholder="Kode Rekening" >		  
		</div>
		
		<div class="form-group">
		  <label for="exampleInputEmail1">Tipe </label>
		  <select name="tipe" id="tipe" class="form-control">			
			<option >pilih tipe rekening</option>
			<option value="penerimaan">Penerimaan</option>
			<option value="pengeluaran">Pengeluaran</option>
		  </select>
		</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="add_rekening()" data-dismiss="modal" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>
	
	$(function(){
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [			
				{ name: 'no', 			type: 'number' },
				{ name: 'code', 		type: 'string' },			
				{ name: 'kode_rekening',type: 'string' },
				{ name: 'uraian', 		type: 'string' },
				{ name: 'tahun', 		type: 'number' },
				{ name: 'target', 		type: 'number' },
				{ name: 'input_a', 		type: 'number' },			
				{ name: 'input_b', 		type: 'number' },
				{ name: 'total_input', 	type: 'number' },
				{ name: 'output_a', 	type: 'number' },
				{ name: 'output_b', 	type: 'number' },
				{ name: 'total_output', type: 'number' },
				{ name: 'total_akhir', type: 'number' }
			],
		url: "<?php echo site_url('keuangan/target_penerimaan/api_target_penerimaan'); ?>",
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
			showstatusbar: true,
			statusbarheight: 50,
			source: dataadapter, 
			showaggregates: true,
			theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: false, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
				{ text: 'Delete', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_barang").jqxGrid('getrowdata', row);				    
					
						return "<div style='width:100%;padding-top:2px;text-align:center'><a onclick=\"doDelete('"+dataRecord.code+"','"+dataRecord.tahun+"')\" href='#'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' );'></a></div>";											
										
					
                 }
                },
				
				{ text: 'No', align: 'center', datafield: 'no', columntype: 'textbox', filtertype: 'none', width: '5%' },
				{ text: 'Rekening', datafield: 'kode_rekening', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Uraian ', datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '35%'},
				{ text: 'target',datafield: 'target', columntype: 'textbox', cellsalign: 'right', cellsformat: 'f', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }},
				{ text: 'Bulan Lalu', columngroup: 'Penerimaan', cellsalign: 'right', cellsformat: 'f', datafield: 'input_a', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }
					
				},
				{ text: 'Bulan ini', columngroup: 'Penerimaan', cellsalign: 'right', cellsformat: 'f', datafield: 'input_b', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }
				},
				{ text: 's/d Bulan ini',columngroup: 'Penerimaan', cellsalign: 'right', cellsformat: 'f', datafield: 'total_input', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }},
				{ text: 'Bulan Lalu', columngroup: 'Pengeluaran', cellsalign: 'right', cellsformat: 'f', datafield: 'output_a', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }},
				{ text: 'Bulan ini',columngroup: 'Pengeluaran', cellsalign: 'right', cellsformat: 'f', datafield: 'output_b', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }},
				{ text: 's/d Bulan ini', columngroup: 'Pengeluaran', cellsalign: 'right', cellsformat: 'f', datafield: 'total_output', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }},
				{ text: 'Jumlah Setoran <br/> s/d Bulan ini',cellsalign: 'right',  cellsformat: 'f', datafield: 'total_akhir', columntype: 'textbox', filtertype: 'textbox', width: '15%', aggregates: ['sum'],
					aggregatesrenderer: function (aggregates) {
						  var renderstring = "";
						  $.each(aggregates, function (key, value) {
							  
							  renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;"> Total: ' + value +'</div>';
						  });
						  return renderstring;
					  }}
           ],
		   columngroups: 
                [
                  { text: 'Penerimaan', align: 'center', name: 'Penerimaan' },
                  { text: 'Pengeluaran', align: 'center', name: 'Pengeluaran' }
                  
                ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_barang").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
		});

 		$('#btn_add_bku').click(function () {
			add_bku();
		});
		
		

	});
	function doDelete(id, tahun){
		if(confirm("Apakah Anda yakin akan menghapus data ini ?")){
			$.post("<?php echo base_url().'keuangan/target_penerimaan/delete_target'; ?>" ,{id:id, tahun:tahun}, function(data) {
				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
			});
		}
		
	}
	
	function doList(){				
		var values = new Array();		
		$.each($("input[name='datalist[]']:checked"), function() {
		  values.push($(this).val());		
		});
		//alert(values);
		if(values.length > 0){
			$.post("<?php echo base_url().'keuangan/bku_penerimaan/bku_setor'; ?>" ,{data_all:values}, function(data) {
				//$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
				if(data == "beda"){
					alert('Maaf, Data yang akan disetor harus sama jenisnya. Data STS tidak boleh di setor bersamaan dengan nonSTS !');
				}else{
					add_bku_setor(data);
				}
				
			});
		}
		
		
	}
	function close_popup(){
		$("#popup_barang").jqxWindow('close');
	}

	function add_bku(){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'keuangan/target_penerimaan/pop_rekening'; ?>" , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: '30%',
			height: 360,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}
	
	function add_bku_setor(data_setor){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'keuangan/bku_penerimaan/pop_bku_add/'; ?>"+data_setor , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: '75%',
			height: 425,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function edit_barang(kode,code_cl_phc,id_inv_permohonan_barang_item){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/permohonanbarang/edit_barang/2/P3172100201'; ?>" + id_inv_permohonan_barang_item, function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 500,
			height: 460,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

	function del_barang(id_inv_permohonan_barang_item){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/permohonanbarang/dodelpermohonan/2/P3172100201' ?>/" + id_inv_permohonan_barang_item,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
			});
		}
	}
	
	$("select[name='pilih_bulan']").change(function(){		
		if($(this).val() != '0'){			
			$.post( '<?php echo base_url()?>keuangan/bku_penerimaan/set_filter_bulan', {bulan:$(this).val()},function( data ) {								
				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
			});
		}				
	});
	
	$("select[name='pilih_tahun']").change(function(){			
		if($(this).val() != '0'){
			$.post( '<?php echo base_url()?>keuangan/bku_penerimaan/set_filter_tahun', {tahun:$(this).val()},function( data ) {				
				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
			});
		}				
	});

</script>
