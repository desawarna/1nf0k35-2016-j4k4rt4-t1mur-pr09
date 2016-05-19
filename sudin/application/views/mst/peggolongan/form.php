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
<form action="<?php echo base_url()?>mst/peggolongan/{action}/{id}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/peggolongan'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Id Golongan</label>
              <input type="text" class="form-control" name="id_golongan" placeholder="ID Golongan" value="<?php 
                if(set_value('id_golongan')=="" && isset($id_golongan)){
                  echo $id_golongan;
                }else{
                  echo  set_value('id_golongan');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Ruang</label>
              <input type="text" class="form-control" name="ruang" placeholder="Ruang" value="<?php 
                if(set_value('ruang')=="" && isset($ruang)){
                  echo $ruang;
                }else{
                  echo  set_value('ruang');
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
    $("#menu_mst_peggolongan").addClass("active");
    $("#menu_master_data").addClass("active");
	});
</script>
