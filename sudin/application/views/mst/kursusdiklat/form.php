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
<form action="<?php echo base_url()?>mst/kursusdiklat/{action}/{id}" method="POST" name="">
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
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/kursusdiklat'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>ID Kursus</label>
              <input type="text" class="form-control" name="id_kursus" placeholder="Nama Struktur" value="<?php 
                if(set_value('id_kursus')=="" && isset($id_kursus)){
                  echo $id_kursus;
                }else{
                  echo  set_value('id_kursus');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Nama Kursus</label>
              <input type="text" class="form-control" name="nama_kursus" placeholder="Nama Kursus" value="<?php 
                if(set_value('nama_kursus')=="" && isset($nama_kursus)){
                  echo $nama_kursus;
                }else{
                  echo  set_value('nama_kursus');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Jenis</label>
              <select type="text" class="form-control" name="jenis">
                <option value="">--</option>
                <option value="diklat">Diklat</option>
                <option value="kursus">Kursus</option>
                <option value="dikjang">Dikjang</option>
                <option value="prajabatan">Prajabatan</option>
              </select>
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
    $("#menu_mst_kursusdiklat").addClass("active");
    $("#menu_master_data").addClass("active");
	});
</script>
