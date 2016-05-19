
<form action="#" method="POST" name="frmPegawai">
  <div class="row" style="margin: 15px 5px 15px 5px">
    <div class="col-sm-8">
      <?php if(validation_errors()!=""){ ?>
      <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4>  <i class="icon fa fa-check"></i> Information!</h4>
        <?php echo validation_errors()?>
      </div>
      <?php } ?>


    </div>
    <div class="col-sm-4" style="text-align: right">
      <button type="button" name="btn_biodata_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">

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
                <div class="col-sm-4 col-xs-12" style="padding: 5px">
                  Gelar 
                </div>
                <div class="col-sm-4 col-xs-6">
                  <input type="text" class="form-control" name="gelar_depan" placeholder="Gelar Depan" value="<?php 
                  if(set_value('gelar_depan')=="" && isset($gelar_depan)){
                    echo $gelar_depan;
                  }else{
                    echo  set_value('gelar_depan');
                  }
                  ?>">
                </div>
                <div class="col-sm-4 col-xs-6">
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
                  Tempat Lahir
                </div>
                <div class="col-md-8">
                  <div class="input-group">
                    <input type="text" class="form-control" name="tmp_lahir" placeholder="Tempat Lahir" value="<?php 
                    if(set_value('tmp_lahir')=="" && isset($tmp_lahir)){
                      echo $tmp_lahir;
                    }else{
                      echo  set_value('tmp_lahir');
                    }
                    ?>" style="text-indent: 13px;" autocomplete="off">                  
                    <div class="input-group-addon">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>

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
                        <?php $select = $ag->kode == $kode_mst_agama ? 'selected' : '' ?>
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
                        <?php $select = $nk->kode == $kode_mst_nikah ? 'selected' : '' ?>
                        <option value="<?php echo $nk->kode ?>" <?php echo $select ?>><?php echo $nk->value ?></option>
                      <?php endforeach ?>
                  </select>
                </div>
              </div>

              <br>
            </div>
          </div>

  </div>
</form>

<script>
  $(function () { 
    tabIndex = 1;

    $("[name='btn_biodata_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('nama', $("[name='nama']").val());
        data.append('jenis_kelamin', $("[name='jenis_kelamin']:checked").val());
        data.append('nik', $("[name='nik']").val());
        
        data.append('gelar_depan', $("[name='gelar_depan']").val());
        data.append('gelar_belakang', $("[name='gelar_belakang']").val());
        data.append('tmp_lahir', $("[name='tmp_lahir']").val());
        data.append('tgl_lhr', $("[name='tgl_lhr']").val());
        data.append('alamat', $("[name='alamat']").val());
        data.append('kedudukan_hukum', $("[name='kedudukan_hukum']").val());
        data.append('npwp', $("[name='npwp']").val());
        data.append('npwp_tgl', $("[name='npwp_tgl']").val());
        data.append('kartu_pegawai', $("[name='kartu_pegawai']").val());
        data.append('kode_mst_agama', $("[name='kode_mst_agama']").val());
        data.append('goldar', $("[name='goldar']").val());
        data.append('kode_mst_nikah', $("[name='kode_mst_nikah']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."kepegawaian/drh/biodata/1/{id}"   ?>',
            data : data,
            success : function(response){
                $('#content' + tabIndex).html(response);
            }
        });

        return false;
    });

    $("[name='tmp_lahir']").jqxInput(
      {
      placeHolder: " Kota Kelahiran ",
      theme: 'classic',
      width: '100%',
      height: '30px',
      minLength: 2,
      source: function (query, response) {
        var dataAdapter = new $.jqx.dataAdapter
        (
          {
            datatype: "json",
              datafields: [
              { name: 'value', type: 'string'}
            ],
            url: '<?php echo base_url().'kepegawaian/drh/autocomplite_kota'; ?>'
          },
          {
            autoBind: true,
            formatData: function (data) {
              data.query = query;
              return data;
            },
            loadComplete: function (data) {
              if (data.length > 0) {
                response($.map(data, function (item) {
                  return item.value;
                }));
              }
            }
          });
      }
    });
  

  });
</script>
