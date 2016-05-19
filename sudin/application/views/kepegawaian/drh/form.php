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
<script>
  $(function() {
    $('#row_dim').hide(); 
    $('#type').change(function(){
      if($('#type').val() == 'PNS') {
        $('#row_dim').show(); 
        $('#row_dim2').hide(); 
      } else {
        $('#row_dim').hide(); 
        $('#row_dim2').show(); 
      } 
    });

    $("#tgl_lhr").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});
    $("#tar_npwp_tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});

  });
</script>

<section class="content">
  <form action="<?php echo base_url()?>kepegawaian/drh/{action}/{id}" method="POST" name="">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">{title_form}</h3>
          </div><!-- /.box-header -->
          <label>--Informasi Utama--</label>
          <div class="box-body">
            <div class="form-group">
              <label>Status Masuk</label>
              <select name="status_masuk" id="type" >
                <option value="NonPNS">Non PNS</option>
                <option value="PNS">PNS</option>
              </select>
            </div>
            <div class="form-group">
              <label>Nomor Induk Pegawai / Nomor Induk Tenaga</label>
              <input type="number" class="form-control" name="nip_nit" placeholder="NIP/NIT" value="<?php 
              if(set_value('nip_nit')=="" && isset($nip_nit)){
                echo $nip_nit;
              }else{
                echo  set_value('nip_nit');
              }
              ?>">
            </div>
            <div class="row" id="row_dim">
              <div class="col-md-6 ">
                <div class="form-group ">
                  <label>NIP Lama</label>
                  <input type="number" class="form-control" name="nip_lama" placeholder="NIP Lama" value="<?php 
                  if(set_value('nip_lama')=="" && isset($nip_lama)){
                    echo $nip_lama;
                  }else{
                    echo  set_value('nip_lama');
                  }
                  ?>">
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="form-group">
                  <label>NIP Baru</label>
                  <input type="number" class="form-control" name="nip_baru" placeholder="NIP Baru" value="<?php 
                  if(set_value('nip_baru')=="" && isset($nip_baru)){
                    echo $nip_baru;
                  }else{
                    echo  set_value('nip_baru');
                  }
                  ?>">
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="form-group">
                  <label>NRK</label>
                  <input type="number" class="form-control" name="nrk" placeholder="NRK" value="<?php 
                  if(set_value('nrk')=="" && isset($nrk)){
                    echo $nrk;
                  }else{
                    echo  set_value('nrk');
                  }
                  ?>">
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="form-group">
                  <label>Kartu Pegawai</label>
                  <input type="number" class="form-control" name="karpeg" placeholder="Kartu Pegawai" value="<?php 
                  if(set_value('karpeg')=="" && isset($karpeg)){
                    echo $karpeg;
                  }else{
                    echo  set_value('karpeg');
                  }
                  ?>">
                </div>
              </div>
            </div>
            <div class="row" id="row_dim2">
              <div class="col-md-6 ">
                <div class="form-group">
                  <label>Nomor Induk Tenaga</label>
                  <input type="number" class="form-control" name="nit" placeholder="Nomor Induk Tenaga" value="<?php 
                  if(set_value('nit')=="" && isset($nit)){
                    echo $nit;
                  }else{
                    echo  set_value('nit');
                  }
                  ?>">
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="form-group">
                  <label>Nomor Induk Tenga phl</label>
                  <input type="number" class="form-control" name="nit_phl" placeholder="NIT PHL" value="<?php 
                  if(set_value('nit_phl')=="" && isset($nit_phl)){
                    echo $nit_phl;
                  }else{
                    echo  set_value('nit_phl');
                  }
                  ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" value="<?php 
                if(set_value('nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('nama');
                }
                ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Gelar</label>
                <input type="text" class="form-control" name="gelar" placeholder="Gelar" value="<?php 
                if(set_value('gelar')=="" && isset($gelar)){
                  echo $gelar;
                }else{
                  echo  set_value('gelar');
                }
                ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis Kelamin</label>
                <select type="text" class="form-control" name="tar_sex">
                  <option value="L">Laki-laki</option>
                  <option value="P">Perempuan</option>
                </select>                   
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Golongan Darah</label>
                <select type="text" class="form-control" name="goldar">
                  <option value="A">A</option>
                  <option value="AB">AB</option>
                  <option value="B">B</option>
                  <option value="O">O</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" class="form-control" name="tmp_lahir" placeholder="Tempat Lahir" value="<?php 
                if(set_value('tmp_lahir')=="" && isset($tmp_lahir)){
                  echo $tmp_lahir;
                }else{
                  echo  set_value('tmp_lahir');
                }
                ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <div id='tgl_lhr' name="tgl_lhr" value="<?php
                echo (set_value('tgl_lhr')!="") ? date("Y-m-d",strtotime(set_value('tgl_lhr'))) : "";
                ?>"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Agama</label>
              <select type="text" name="kode_mst_agama" class="form-control">
                <option value="">--pilih agama--</option>
                <?php foreach ($kode_ag as $row ) { ?>
                <option value="<?php echo $row->kode; ?>"><?php echo $row->value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Status Perkawinan</label>
              <select type="text" name="kode_mst_nikah" class="form-control">
                <option value="">--pilih status perkawinan--</option>
                <?php foreach ($kode_nk as $row ) { ?>
                <option value="<?php echo $row->kode; ?>"><?php echo $row->value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <label>--Keterangan Lainnya--</label>
          <div class="box-body">
            <div class="form-group">
              <div class="col-md-6">
                <label>No NPWP</label>
                <input type="text" class="form-control" name="tar_npwp" placeholder="No NPWP" value="<?php 
                if(set_value('tar_npwp')=="" && isset($tar_npwp)){
                  echo $tar_npwp;
                }else{
                  echo  set_value('tar_npwp');
                }
                ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal NPWP</label>
                <div id='tar_npwp_tgl' name="tar_npwp_tgl" value="<?php
                echo (set_value('tar_npwp_tgl')!="") ? date("Y-m-d",strtotime(set_value('tar_npwp_tgl'))) : "";
                ?>"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No KTP</label>
                <input type="number" class="form-control" name="ktp" placeholder="No Ktp" value="<?php 
                if(set_value('ktp')=="" && isset($ktp)){
                  echo $ktp;
                }else{
                  echo  set_value('ktp');
                }
                ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode Puskesmas</label>
                <select type="text" name="code_cl_phc" class="form-control">
                  <option value="">--pilih Puskesmas--</option>
                  <?php foreach ($kodepuskesmas as $row ) { ?>
                  <option value="<?php echo $row->code; ?>"><?php echo $row->value; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="box-footer pull-right">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <button type="reset" class="btn btn-warning">Ulang</button>
              <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>kepegawaian/drh'">Kembali</button>
            </div>
          </div>
          <div class="box box-success">
            <div class="box-body">
              <?php echo $form_tambahan;?>
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <br>

  </div>
  

</div><!-- /.box-body -->

</div><!-- /.box -->

</div>
</div><!-- /.box -->

</div><!-- /.box -->

</form>

</section>

<script>
  $(function () { 
    $("#menu_kepegawaian_drh").addClass("active");
    $("#menu_kepegawaian").addClass("active");
  });
</script>
