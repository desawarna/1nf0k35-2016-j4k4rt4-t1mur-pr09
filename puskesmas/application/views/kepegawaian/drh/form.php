
<section class="content">
<form action="<?php echo base_url()?>kepegawaian/drh/add" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
      </div>

      <div class="box-footer" >
        <div class="row">
          <div class="col-sm-6">
            <?php if(validation_errors()!=""){ ?>
            <div class="alert alert-warning alert-dismissable">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4>  <i class="icon fa fa-check"></i> Information!</h4>
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
          </div>
          <div class="col-sm-6" style="text-align: right">
            <button type="submit" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
            <button type="button" class="btn btn-success" id="btn-return"><i class='fa fa-arrow-circle-o-left'></i> &nbsp; Kembali</button>
          </div>
        </div>
      </div>
    </div>
        <div class="row">

          <div class="col-md-6">
            <div class="box box-primary">
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Nama Lengkap *
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" value="<?php 
                  if(set_value('nama')=="" && isset($nama)){
                    echo $nama;
                  }else{
                    echo  set_value('nama');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Gelar Depan
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="gelar_depan" placeholder="Gelar Depan" value="<?php 
                  if(set_value('gelar_depan')=="" && isset($gelar_depan)){
                    echo $gelar_depan;
                  }else{
                    echo  set_value('gelar_depan');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Gelar Belakang
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="gelar_belakang" placeholder="Gelar Belakang" value="<?php 
                  if(set_value('gelar_belakang')=="" && isset($gelar_belakang)){
                    echo $gelar_belakang;
                  }else{
                    echo  set_value('gelar_belakang');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Jenis Kelamin *
                </div>
                  <?php
                    if(set_value('jenis_kelamin')=="" && isset($jenis_kelamin)){
                      $jenis_kelamin = $jenis_kelamin;
                    }else{
                      $jenis_kelamin = set_value('jenis_kelamin');
                    }
                  ?>
                <div class="col-sm-4">
                  <input type="radio" name="jenis_kelamin" value="L" class="iCheck-helper" <?php echo  ('L' == $jenis_kelamin) ? 'checked' : '' ?>> Laki-laki 
                </div>
                <div class="col-sm-4">
                  <input type="radio" name="jenis_kelamin" value="P" class="iCheck-helper" <?php echo  ('P' == $jenis_kelamin) ? 'checked' : '' ?>> Perempuan
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tempat Lahir
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="tmp_lahir" placeholder="Tempat Lahir" value="<?php 
                  if(set_value('tmp_lahir')=="" && isset($tmp_lahir)){
                    echo $tmp_lahir;
                  }else{
                    echo  set_value('tmp_lahir');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tanggal Lahir
                </div>
                <div class="col-md-8">
                  <div id='tgl_lhr' name="tgl_lhr" value="<?php
                    if(set_value('tgl_lhr')=="" && isset($tgl_lhr)){
                      $tgl_lhr = strtotime($tgl_lhr);
                    }else{
                      $tgl_lhr = strtotime(set_value('tgl_lhr'));
                    }
                    if($tgl_lhr=="") $tgl_lhr = time();
                    echo date("Y-m-d",$tgl_lhr);
                  ?>" >
                  </div>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Alamat
                </div>
                <div class="col-md-8">
                  <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat"><?php 
                      if(set_value('alamat')=="" && isset($alamat)){
                        echo $alamat;
                      }else{
                        echo  set_value('alamat');
                      }
                  ?></textarea>
                </div>
              </div>

              <br>
            </div>
          </div>

          <div class="col-md-6">
            <div class="box box-success">

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  NIK *
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="nik" placeholder="NIK" value="<?php 
                  if(set_value('nik')=="" && isset($nik)){
                    echo $nik;
                  }else{
                    echo  set_value('nik');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Kedudukan Hukum
                </div>
                <div class="col-md-8">
                  <select  name="kedudukan_hukum" type="text" class="form-control">
                  <?php
                    if(set_value('kedudukan_hukum')=="" && isset($kedudukan_hukum)){
                      $kedudukan_hukum = $kedudukan_hukum;
                    }else{
                      $kedudukan_hukum = set_value('kedudukan_hukum');
                    }
                  ?>
                    <option value="aktif" <?php echo  ('aktif' == $kedudukan_hukum) ? 'selected' : '' ?> >Aktif</option>
                    <option value="tidak aktif" <?php echo  ('tidak aktif' == $kedudukan_hukum) ? 'selected' : '' ?> >Tidak Aktif</option>
                  </select>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Nomor NPWP
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="npwp" placeholder="NPWP" value="<?php 
                  if(set_value('npwp')=="" && isset($npwp)){
                    echo $npwp;
                  }else{
                    echo  set_value('npwp');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tanggal NPWP
                </div>
                <div class="col-md-8">
                  <div id='npwp_tgl' name="npwp_tgl" value="<?php
                    if(set_value('npwp_tgl')=="" && isset($npwp_tgl)){
                      $npwp_tgl = strtotime($npwp_tgl);
                    }else{
                      $npwp_tgl = strtotime(set_value('npwp_tgl'));
                    }
                    if($npwp_tgl=="") $npwp_tgl = time();
                    echo date("Y-m-d",$npwp_tgl);
                  ?>" >
                  </div>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Kartu Pegawai
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="kartu_pegawai" placeholder="Kartu Pegawai" value="<?php 
                  if(set_value('kartu_pegawai')=="" && isset($kartu_pegawai)){
                    echo $kartu_pegawai;
                  }else{
                    echo  set_value('kartu_pegawai');
                  }
                  ?>">
                </div>
              </div>


              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Agama
                </div>
                <div class="col-md-8">
                  <select  name="kode_mst_agama" type="text" class="form-control">
                      <option value="">Pilih Agama</option>
                      <?php foreach($kode_ag as $ag) : ?>
                        <?php $select = $ag->kode == set_value('kode_mst_agama') ? 'selected' : '' ?>
                        <option value="<?php echo $ag->kode ?>" <?php echo $select ?>><?php echo $ag->value ?></option>
                      <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Golongan Darah
                </div>
                <div class="col-md-8">
                  <select  name="goldar" type="text" class="form-control">
                  <?php
                    if(set_value('goldar')=="" && isset($goldar)){
                      $goldar = $goldar;
                    }else{
                      $goldar = set_value('goldar');
                    }
                  ?>
                    <option value="A" <?php echo  ('A' == $goldar) ? 'selected' : '' ?> >A</option>
                    <option value="B" <?php echo  ('B' == $goldar) ? 'selected' : '' ?> >B</option>
                    <option value="AB" <?php echo  ('AB' == $goldar) ? 'selected' : '' ?> >AB</option>
                    <option value="O" <?php echo  ('O' == $goldar) ? 'selected' : '' ?> >O</option>
                  </select>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Status Menikah
                </div>
                <div class="col-md-8">
                  <select  name="kode_mst_nikah" type="text" class="form-control">
                      <?php foreach($kode_nk as $nk) : ?>
                        <?php $select = $nk->kode == set_value('kode_mst_nikah') ? 'selected' : '' ?>
                        <option value="<?php echo $nk->kode ?>" <?php echo $select ?>><?php echo $nk->value ?></option>
                      <?php endforeach ?>
                  </select>
                </div>
              </div>

              <br>
            </div>
          </div>

        </div>
    </div>
  </div>
</form>
</section>

<script>
  $(function () { 
    $("#tgl_lhr").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});
    $("#npwp_tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});


    $("#btn-return").click(function(){
      document.location.href="<?php echo base_url()?>kepegawaian/drh";
    });

    $("#menu_kepegawaian_drh").addClass("active");
    $("#menu_kepegawaian").addClass("active");
  });
</script>
