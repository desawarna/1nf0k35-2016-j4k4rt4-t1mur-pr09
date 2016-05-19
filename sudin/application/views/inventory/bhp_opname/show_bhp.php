<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div id="popup_barang_bhp" style="display:none">
	<div id="popup_title">Detail Opname Barang</div>
	<div id="popup_content_bhp">&nbsp;</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>inventory/bhp_opname/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
	      	<div class="box-footer">
		      	<div class="row"> 
			      	<div class="col-md-12">
			      		<?php //if($unlock==1){ ?>
					<!-- 	<button type="button" class="btn btn-primary" onclick="add(0)"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Pengeluaran</button>-->
						<?php //} ?>		 	
					 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			          <button type="button" id="btn-export" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Export</button>
			      	</div>
		      	</div>
		    <div class="box-body">
		      	<div class="row">
			      <div class="col-md-6">
			      	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
				     	<div class="col-md-8">
					     	<select name="code_cl_phc" id="puskesmas" class="form-control">
				     				<option value="all" onchange="" >All</option>
								<?php foreach ($datapuskesmas as $row ) { ;?>
								<?php $select = $row->code == $this->session->userdata('filter_code_cl_phc') ? 'selected=selected' : '' ?>
									<option value="<?php echo $row->code; ?>" <?php echo $select; ?> ><?php echo $row->value; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
			     </div>
			      <div class="col-md-3">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Bulan </label> </div>
				     	<div class="col-md-8">
				     		<select name="bulan" id="bulan" class="form-control">
								<?php foreach ($bulan as $val=>$key ) { ;?>
								<?php $select = $val == date("m") ? 'selected=selected' : '' ?>
									<option value="<?php echo $val; ?>" <?php echo $select ?>><?php echo $key; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
				  </div>	
				  <div class="col-md-3">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Tahun </label> </div>
				     	<div class="col-md-8">
				     		<select name="tahun" id="tahun" class="form-control">
								<?php for ($i=date("Y");$i>=date("Y")-10;$i--) { ;?>
									<?php $select = $i == date("Y") ? 'selected=selected' : '' ?>
									<option value="<?php echo $i; ?>" <?php echo $select ?>><?php echo $i; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
				  </div>		  
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Jenis Barang</label> </div>
				     	<div class="col-md-8">
				     		<select name="jenisbarang" id="jenisbarang" class="form-control">
			     				<option value="all">All</option>
								<?php foreach ($jenisbaranghabis as $val=>$key ) { ;?>
									<option value="<?php echo $val; ?>" ><?php echo $key; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
				  </div>
				</div>
			</div>
		</div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgridBhp"></div>
			</div>
    	</div>
      </div>
  </div>
</div>
</form>
</section>

<script type="text/javascript">

	function close_popup_bhp(){
	$("#popup_barang_bhp").jqxWindow('close');
	}
	$(function () {	
		$("select[name='jenisbarang']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_opname/filter_jenisbarang' ?>", 'jenisbarang='+$(this).val(),  function(){
				$("#jqxgridBhp").jqxGrid('updatebounddata', 'cells');
			});
		});
		$("select[name='bulan']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_opname/filter_bulan' ?>", 'bulan='+$(this).val(),  function(){
				$("#jqxgridBhp").jqxGrid('updatebounddata', 'cells');
			});
		});
		$("select[name='tahun']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_opname/filter_tahun' ?>", 'tahun='+$(this).val(),  function(){
				$("#jqxgridBhp").jqxGrid('updatebounddata', 'cells');
			});
		});
	    $("#menu_bahan_habis_pakai").addClass("active");
	    $("#menu_inventory_bhp_opname").addClass("active");
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_inventaris_habispakai_opname', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'jml_awal', type: 'number' },
			{ name: 'jml_akhir', type: 'number' },
			{ name: 'jmlawal_opname', type: 'number' },
			{ name: 'jmlakhir_opname', type: 'number' },
			{ name: 'sumselisih', type: 'number' },
			{ name: 'harga', type: 'string' },
			{ name: 'merek_tipe', type: 'string' },
			{ name: 'tgl_opname', type: 'string' },
			{ name: 'jml_selisih', type: 'number' }
        ],
		url: "<?php echo site_url('inventory/bhp_opname/json_opname'); ?>",
		cache: false,
			updateRow: function (rowID, rowData, commit) {
             
         },
		filter: function(){
			$("#jqxgridBhp").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgridBhp").jqxGrid('updatebounddata', 'sort');
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
			$("#jqxgridBhp").jqxGrid('clearfilters');
		});

		$("#jqxgridBhp").jqxGrid(
		{		
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100', '200'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgridBhp").jqxGrid('getrowdata', row)
				    if((dataRecord.id_mst_inv_barang_habispakai!=null)&&(dataRecord.tgl_opname!="<?php echo date('Y-m-d');?>")){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='add(\""+dataRecord.id_inv_inventaris_habispakai_opname+"\",\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nama Barang', editable:false ,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '40%' },
				{ text: 'Merek', editable:false ,datafield: 'merek_tipe', columntype: 'textbox', filtertype: 'textbox', width: '18%' },
				{ text: 'Last Update', align: 'center', cellsalign: 'center', columngroup: 'update',editable: false,datafield: 'tgl_opname', columntype: 'date', filtertype: 'none', cellsformat: 'dd-MM-yyyy', width: '10%'},
				{ text: 'Jumlah Awal',sortable: true,editable:false ,align: 'center', cellsalign: 'right', datafield: 'jmlawal_opname', columntype: 'textbox', filtertype: 'none', width: '9%' },
				{ text: 'Jumlah Akhir',sortable: true,editable:false ,align: 'center', cellsalign: 'right', datafield: 'jmlakhir_opname', columntype: 'textbox', filtertype: 'none', width: '9%' },
				{ text: 'Selisih',sortable: false,editable:false ,datafield: 'sumselisih', columntype: 'textbox', filtertype: 'none', width: '9%' ,align: 'center', cellsalign: 'right'}
            ]
		});
	  function timeline_pengeluaran_barang(id){
	    $.get("<?php echo base_url();?>inventory/bhp_opname/timeline_pengeluaran_barang/"+id , function(response) {
	      $("#timeline-barang").html(response);
	    });
	  }
	
	function add(id,barang,batch){
		$("#popup_barang_bhp #popup_content_bhp").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_opname/detailbhp/'; ?>"+id+'/'+barang+'/'+batch , function(data) {
			$("#popup_content_bhp").html(data);
		});
		$("#popup_barang_bhp").jqxWindow({
			theme: theme, resizable: false,
			width: 1100,
			height: 570,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang_bhp").jqxWindow('open');
	}

	$("#btn-export").click(function(){
		if ($("#puskesmas").val()=='all') {
			alert('silahkan pilih puskesmas terlebih dahulu');
		}else{
			var post = "";
			post = post+'&jenisbarang='+$("#jenisbarang option:selected").text()+'&nama_puskesmas='+$("#puskesmas option:selected").text()+'&bulan='+$("#bulan option:selected").text()+'&tahun='+$("#tahun option:selected").text()+'&puskesmas='+$("#puskesmas").val();
			//alert(post);
			
			$.post("<?php echo base_url()?>inventory/bhp_opname/laporan_opname",post,function(response	){
				// alert(response);
				window.location.href=response;
			});
		}
	});
	$("select[name='code_cl_phc']").change(function(){
		$.post("<?php echo base_url().'inventory/bhp_opname/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
			$("#jqxgridBhp").jqxGrid('updatebounddata', 'cells');
		});
	});
</script>