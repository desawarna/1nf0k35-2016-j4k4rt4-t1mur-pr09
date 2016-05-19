<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>  <i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-warning">
			<div class="box-body">
				<div class="row">
				  	<div class="col-md-12">
						<div class="form-group">
							<label>Pilih Tanggal</label>
							<div id="tgl"></div>
						</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Pilih Puskesmas / Ruangan</label>
				     		<select name="code_cl_phc" class="form-control" id="code_cl_phc">
								<?php foreach ($kodepuskesmas as $row ) { ;?>
								<option value="<?php echo $row->code; ?>" <?php //if($kode==$row->code) echo "selected"; ?> ><?php echo $row->value; ?></option>
							<?php	} ;?>
				     		</select>
				  		</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label>&nbsp;</label>
				     		<select name="code_ruangan" class="form-control" id="code_ruangan">
				     			<option value="">Pilih Ruangan</option>
				     		</select>
				  		</div>
				  	</div>
				  	<div class="col-md-12">
						<div class="form-group pull-left">
							<div style="padding-top:6px">
								<input type="checkbox" name="group" id="filter_group" value="1"> 
								Kelompokkan barang yang sama ? 
							</div>
						</div>
						<div class="form-group pull-right">
            				<button onClick="doExport();" type="button"  class="btn btn-warning"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>
						</div>
				  	</div>
				</div>
			</div>
		</div><!-- /.box -->
	</div><!-- /.box -->
</div><!-- /.box -->





<script>
	$(function () {	
		$("#menu_laporan").addClass("active");
		$("#menu_inventory_lap_kir").addClass("active");
		
		$('#code_cl_phc').change(function(){
	      	var code_cl_phc = $(this).val();
	      	var id_mst_inv_ruangan = "2";
	      	$.ajax({
		        url : '<?php echo site_url('inventory/inv_ruangan/get_ruangan') ?>',
		        type : 'POST',
		        data : 'code_cl_phc=' + code_cl_phc+'&id_mst_inv_ruangan=' + id_mst_inv_ruangan,
		        success : function(data) {
		          	$('#code_ruangan').html(data);
        		}
	    	});
			
			
	      	return false;
	    }).change();
    	$("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '31px', width: '100%'});

	});
	
	function doExport(){
		var code_cl_phc 		= $("#code_cl_phc").val();
		var id_mst_inv_ruangan 	= $("#code_ruangan").val();
		var tanggal 			= $("#inputtgl").val();
		if ($("#filter_group:checked").val()==1){
			group = '1';
		}else{
			group = '0';
		}
		
		var t = tanggal.split('-');
		var tgl = t[2]+'-'+t[1]+'-'+t[0];
		$.ajax({
		        url : '<?php echo site_url('inventory/lap_kir/set_detail_filter') ?>',
		        type : 'POST',
		        data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan +'&filter_tanggal='+tgl+'&filter_group='+group,
		        success : function(data) {
					if(data != ""){
						var d = data.split('_data_');
						$("#view_puskesmas").html(d[0]);
						$("#view_ruang").html(d[1]);
						$("#view_keterangan").html(d[2]);
					}

					$.ajax({
						url : '<?php echo site_url('inventory/inv_ruangan/export_detail') ?>',
						type : 'POST',
						data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan +'&filter_tanggal='+tgl+'&filter_group='+group,
						success : function(data) {
							if(data != ""){
								location.href = data;
							}
						}
					});		          	
        		}
	    	});
	}
	
</script>
