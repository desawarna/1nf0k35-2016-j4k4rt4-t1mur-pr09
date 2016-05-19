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


<section class="content">
<form action="<?php echo base_url()?>inventory/inv_ruangan/{action}/{kode}/{id}" method="POST" >
	<div class="row">
		<!-- left column -->
		<div class="col-md-6">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{title_form}</h3>
				</div><!-- /.box-header -->

					<div class="box-footer pull-right" style="width:100%">
						<button type="submit" class="btn btn-primary">Simpan</button>
						<button type="reset" class="btn btn-warning">Ulang</button>
						<button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>inventory/inv_ruangan'">Kembali</button>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Puskesmas<h1></h1></label><br/>
							<?php foreach($kodepuskesmas as $pus) : ?>
								<?php echo $pus->code == $code_cl_phc ? $pus->value : '' ?>
							<?php endforeach ?>
						</div>
						<div class="form-group">
							<label>Nama Ruangan</label>
							<input type="text" class="form-control" name="nama_ruangan" placeholder="Nama Ruangan" value="<?php 
								if(set_value('nama_ruangan')=="" && isset($nama_ruangan)){
									echo $nama_ruangan;
								}else{
									echo  set_value('nama_ruangan');
								}
								?>">
						</div>
						<div class="form-group">
							<label>Keterangan</label>
							<input type="text" class="form-control" name="keterangan" placeholder="Keterangan" value="<?php 
								if(set_value('keterangan')=="" && isset($keterangan)){
									echo $keterangan;
								}else{
									echo  set_value('keterangan');
								}
								?>">
						</div>
						
					</div>
					</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div><!-- /.box -->
	</div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
		$("#menu_aset_tetap").addClass("active");
		$("#menu_inventory_inv_ruangan").addClass("active");
	});
</script>
