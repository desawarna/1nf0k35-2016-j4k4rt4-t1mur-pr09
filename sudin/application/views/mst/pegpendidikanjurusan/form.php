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
<form action="<?php echo base_url()?>mst/pegpendidikanjurusan/{action}/{id}" method="POST" name="">
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
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/pegpendidikanjurusan'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>ID Jurusan</label>
              <input type="text" class="form-control" name="id_jurusan" placeholder="ID jurusan" value="<?php 
                if(set_value('id_jurusan')=="" && isset($id_jurusan)){
                  echo $id_jurusan;
                }else{
                  echo  set_value('id_jurusan');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Tingkat Pendidikan</label>
              <select type="text" class="form-control" name="id_tingkatpendidikan" >
                <?php foreach ($id_tingkatpendidikan as $row ) { ?>
                  <option value="<?php echo $row->id_tingkat; ?>"><?php echo $row->tingkat; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label>Rumpun Pendidikan</label>
              <select type="text" class="form-control" name="id_rumpunpendidikan" >
                <?php foreach ($id_rumpunpendidikan as $row ) { ?>
                  <option value="<?php echo $row->id_rumpun; ?>"><?php echo $row->nama_rumpun; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label>Nama Jurusan</label>
              <input type="text" class="form-control" name="nama_jurusan" placeholder="Kelompok Eselon" value="<?php 
                if(set_value('nama_jurusan')=="" && isset($nama_jurusan)){
                  echo $nama_jurusan;
                }else{
                  echo  set_value('nama_jurusan');
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
    $("#menu_mst_pegpendidikanjurusan").addClass("active");
    $("#menu_master_data").addClass("active");
	});
</script>
