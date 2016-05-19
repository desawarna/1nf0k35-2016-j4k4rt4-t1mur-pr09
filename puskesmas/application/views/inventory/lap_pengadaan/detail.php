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
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Pilih Tanggal</label>
							<div id="tgl"></div>
						</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label></label>
							<div id="tgl1"></div>
						</div>
				  	</div>
				  	<div class="col-md-12">
						<div class="form-group">
							<label>Pilih Puskesmas</label>
							<select  name="puskesmas" id="puskesmas" type="text" class="form-control">
							      <?php foreach($datapuskesmas as $stat) : ?>
							        <?php $select = $stat->code == set_value('status') ? 'selected' : '' ?>
							        <option value="<?php echo $stat->code ?>" <?php echo $select ?>><?php echo $stat->value ?></option>
							      <?php endforeach ?>
							  </select>
						</div>
				  	</div>
				  	<div class="col-md-12">
						<div class="form-group">
							<label>Pilih Status</label>
							<select  name="status" id="status" type="text" class="form-control">
							      <option value="">Pilih Status</option>
							      <?php foreach($kodestatus as $stat) : ?>
							        <?php $select = $stat->code == set_value('status') ? 'selected' : '' ?>
							        <option value="<?php echo $stat->code ?>" <?php echo $select ?>><?php echo $stat->value ?></option>
							      <?php endforeach ?>
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
		$("#menu_inventory_lap_pengadaan").addClass("active");
		$("#tgl").jqxDateTimeInput({
			formatString: 'dd-MM-yyyy', 
			theme: theme, 
			height: '31px', width: '100%'
 		});	
 		var date = new Date();
		$('#tgl ').jqxDateTimeInput('setDate', new Date(date.getFullYear(), date.getMonth(), 1));
		$("#tgl1").jqxDateTimeInput({
			formatString: 'dd-MM-yyyy', 
			theme: theme, 
			height: '31px', width: '100%'
 		});	
    	$("#tgl1").jqxDateTimeInput('setDate', new Date(date.getFullYear(), date.getMonth()+1,0));

	});
	
	function doExport(){
		var status 		= $("#status").val();
		var tanggal		= $("#tgl").val();
		var tanggal1		= $("#tgl1").val();
		var puskesmas		= $("#puskesmas").val();
		
		var t = tanggal.split('-');
		var tgl = t[2]+'-'+t[1]+'-'+t[0];
		var t1 = tanggal1.split('-');
		var tgl1 = t1[2]+'-'+t1[1]+'-'+t1[0];
		$.ajax({
		        url : '<?php echo site_url('inventory/lap_pengadaan/permohonan_export') ?>',
		        type : 'POST',
		        data : 'status='+status+'&filter_tanggal='+tgl+'&filter_tanggal1='+tgl1+'&puskesmas='+puskesmas,
		        success : function(data) {
					if(data != ""){
						location.href = data;
					}
        		}
	    	});
	}
</script>
