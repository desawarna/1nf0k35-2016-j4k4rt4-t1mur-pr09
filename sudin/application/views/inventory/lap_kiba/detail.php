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
				  	<div class="col-md-12">
						<div class="form-group">
							<label>Pilih Puskesmas</label>
				     		<select name="code_cl_phc" class="form-control" id="code_cl_phc">
								<?php foreach ($kodepuskesmas as $row ) { ;?>
								<option value="<?php echo $row->code; ?>" <?php //if($kode==$row->code) echo "selected"; ?> ><?php echo $row->value; ?></option>
							<?php	} ;?>
				     		</select>
				  		</div>
				  	</div>
				  	<div class="col-md-12">
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
		$("#menu_inventory_lap_kiba").addClass("active");
		
    	$("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '31px', width: '100%'});

	});
	
	function doExport(){
		var namepuskes	= $("#code_cl_phc option:selected").text()
		var puskes 		= $("#code_cl_phc").val();
		var ruang 		= '';
		var tanggal		= $("#inputtgl").val();
		
		var t = tanggal.split('-');
		var tgl = t[2]+'-'+t[1]+'-'+t[0];
		$.ajax({
		        url : '<?php echo site_url('inventory/lap_kiba/permohonan_export_kiba') ?>',
		        type : 'POST',
		        data : 'namepuskes='+namepuskes+'&puskes=' + puskes+'&ruang=' + ruang +'&filter_tanggal='+tgl,
		        success : function(data) {
					if(data != ""){
						location.href = data;
					}
        		}
	    	});
	}
</script>
