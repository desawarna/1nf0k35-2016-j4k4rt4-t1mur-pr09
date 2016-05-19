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
	<!-- left column -->
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12" style="min-height:196px">
						<div class="form-group">
							<label>Puskesmas</label>
							<div id="view_puskesmas">
							<?php foreach($kodepuskesmas as $pus) : ?>
								<?php echo $pus->code == $code_cl_phc ? $pus->value : '' ?>
							<?php endforeach ?>
							</div>
						</div>
						<div class="form-group">
							<label>Nama Ruangan</label>
							<div id="view_ruang" >{nama_ruangan}</div>
						</div>
						<div class="form-group">
							<label>Keterangan</label>
							<div id="view_keterangan">{keterangan}</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.box -->
	</div><!-- /.box -->
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
				     			<option value="">Pilih Puskesmas</option>
								<?php foreach ($kodepuskesmas as $row ) { ;?>
								<option value="<?php echo $row->code; ?>" <?php if($kode==$row->code) echo "selected"; ?> ><?php echo $row->value; ?></option>
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
							
            				<button onClick="doFilter();" type="button" class="btn btn-info">Filter</button>
            				<button onClick="doExport();" type="button"  class="btn btn-warning">Export</button>
            				<button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>inventory/inv_ruangan'">Kembali</button>
						</div>
				  	</div>
				</div>
			</div>
		</div><!-- /.box -->
	</div><!-- /.box -->
</div><!-- /.box -->


<div class="box box-success">
  <div class="box-body">
    <div class="div-grid">
        <div id="jqxTabs">
          <?php echo $barang;?>
        </div>
    </div>
  </div>
</div>


<script>
	$(function () {	
		$("#menu_aset_tetap").addClass("active");
		$("#menu_inventory_inv_ruangan").addClass("active");
		var ceklis				= "<?php echo $this->session->userdata('filter_group');?>";
		
		if (ceklis==1) {
			$("#filter_group").attr('checked', true);
		}else{
			$("#filter_group").attr('checked', false);
		}
		$('#code_cl_phc').change(function(){
	      	var code_cl_phc = $(this).val();
	      	var id_mst_inv_ruangan = "{id}";
	      	$.ajax({
		        url : '<?php echo site_url('inventory/inv_ruangan/get_ruangan') ?>',
		        type : 'POST',
		        data : 'code_cl_phc=' + code_cl_phc+'&id_mst_inv_ruangan=' + id_mst_inv_ruangan,
		        success : function(data) {
		          	$('#code_ruangan').html(data);
					filter_ruangan();
        		}
	    	});
			
			
	      	return false;
	    }).change();
		/*
	    $('#code_cl_phc').change(function(){
	      	var code_cl_phc = $(this).val();
	      	var id_mst_inv_ruangan = document.getElementById("code_ruangan").value;
	      	$.ajax({
		        url : '<?php echo site_url('inventory/inv_ruangan/get_ruangan') ?>',
		        type : 'POST',
		        data : 'code_cl_phc=' + code_cl_phc+'&id_mst_inv_ruangan=' + id_mst_inv_ruangan,
		        success : function(data) {
		          	$('#code_ruangan').html(data);
					filter_ruangan();
        		}
	    	});
			
			$.ajax({
		        url : '<?php echo site_url('inventory/inv_ruangan/set_detail_filter') ?>',
		        type : 'POST',
		        data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan,
		        success : function(data) {
		          	$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
        		}
	    	});
			
	      	return false;
	    }).change();

	    $('#code_ruangan').change(function(){
	      	var id_mst_inv_ruangan = $(this).val();
			var code_cl_phc = document.getElementById("code_cl_phc").value;
			filter_ruangan(id_mst_inv_ruangan);
			$.ajax({
		        url : '<?php echo site_url('inventory/inv_ruangan/set_detail_filter') ?>',
		        type : 'POST',
		        data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan,
		        success : function(data) {
		          	$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
        		}
	    	});
	      	return false;
	    }).change();
		
		$('#inputtgl').change(function(){
	      	var tgl = $(this).val();
			
			$.ajax({
		        url : '<?php echo site_url('inventory/inv_ruangan/set_detail_filter') ?>',
		        type : 'POST',
		        data : 'filter_tanggal=' + tgl ,
		        success : function(data) {
		          	$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
        		}
	    	});
	      	return false;
	    }).change();
		*/
    	$("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '31px', width: '100%'});

	});

	function doFilter(){
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
		        url : '<?php echo site_url('inventory/inv_ruangan/set_detail_filter') ?>',
		        type : 'POST',
		        data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan +'&filter_tanggal='+tgl+'&filter_group='+group,
		        success : function(data) {
					if(data != ""){
						var d = data.split('_data_');
						$("#view_puskesmas").html(d[0]);
						$("#view_ruang").html(d[1]);
						$("#view_keterangan").html(d[2]);
					}
		          	$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
        		}
	    	});
	}
	
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
		        url : '<?php echo site_url('inventory/inv_ruangan/set_detail_filter') ?>',
		        type : 'POST',
		        data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan +'&filter_tanggal='+tgl+'&filter_group='+group,
		        success : function(data) {
					if(data != ""){
						var d = data.split('_data_');
						$("#view_puskesmas").html(d[0]);
						$("#view_ruang").html(d[1]);
						$("#view_keterangan").html(d[2]);
					}
		          	$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');

					$.ajax({
						url : '<?php echo site_url('inventory/inv_ruangan/export_detail') ?>',
						type : 'POST',
						data : 'filter_code_cl_phc=' + code_cl_phc+'&filter_id_ruang=' + id_mst_inv_ruangan +'&filter_tanggal='+tgl+'&filter_group='+group,
						success : function(data) {
							if(data != ""){
								location.href = data;
								// alert(data);
							}
						}
					});		          	
        		}
	    	});
	}
	
	function filter_ruangan(id_mst_inv_ruangan){
	}
</script>
