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
							<label>Pilih Tahun</label>
			              	<?php echo form_dropdown('thn', $tahun, date('Y')," class=form-control");?>
						</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Pilih Bulan</label>
			              	<?php echo form_dropdown('bln', $bulan, date('m')," class=form-control");?>
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
		$("#menu_inventory_lap_lplpo").addClass("active");

	});
	
	function doExport(){
      var thn = $("[name='thn']").val();
      var bln = $("[name='bln']").val();
      var url = "{epuskesmas_server}includes/files/laporan/template/template.php?" + 'app_unit={kodepuskesmas}&auth={kodepuskesmas}&file=35&thn='+thn+'&bln='+bln+'&act=view_lplpo"+"&program=sp3&kode=sp3lplpo';

      window.open(url);
	}
</script>
