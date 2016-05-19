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
<form action="<?php echo base_url()?>mst/pegpendidikantingkat/{action}/{id}" method="POST" name="">
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
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/pegpendidikantingkat'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
                <label>ID Tingkat</label>
                <input type="text" class="form-control" name="id_tingkat" placeholder="Id Pendidikan" value="<?php 
                  if(set_value('id_tingkat')=="" && isset($id_tingkat)){
                    echo $id_tingkat;
                  }else{
                    echo  set_value('id_tingkat');
                  }
                  ?>">
            </div>
            <div class="form-group">
              <label>Tingkat Pendidikan</label>
              <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi" value="<?php 
                if(set_value('deskripsi')=="" && isset($deskripsi)){
                  echo $deskripsi;
                }else{
                  echo  set_value('deskripsi');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Tingkat</label>
              <input type="text" class="form-control" name="tingkat" placeholder="Tingkat" value="<?php 
                if(set_value('tingkat')=="" && isset($tingkat)){
                  echo $tingkat;
                }else{
                  echo  set_value('tingkat');
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
    $("#menu_mst_pegpendidikantingkat").addClass("active");
    $("#menu_master_data").addClass("active");
	});
</script>
