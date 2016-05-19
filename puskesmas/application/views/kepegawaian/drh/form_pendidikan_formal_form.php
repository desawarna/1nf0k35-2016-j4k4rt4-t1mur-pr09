
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

      <?php if($alert_form!=""){ ?>
      <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4>  <i class="icon fa fa-check"></i> Information!</h4>
        <?php echo $alert_form?>
      </div>
      <?php } ?>
    </div>
    <div class="col-sm-12" style="text-align: right">
      <button type="button" name="btn_pendidikan_formal_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
      <button type="button" name="btn_pendidikan_formal_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Rumpun Pendidikan
                </div>
                <div class="col-md-8">
                  <select  name="id_rumpun" type="text" class="form-control" <?php if($action == "edit") echo "disabled"?>>
                    <option value=''>-</option>
                      <?php foreach($kode_rumpun as $rumpun) : ?>
                        <?php
                        if(set_value('id_tingkat')=="" && isset($id_tingkat)){
                          $id_tingkat = $id_tingkat;
                        }else{
                          $id_tingkat = set_value('id_tingkat');
                        }

                        if(set_value('id_jurusan')=="" && isset($id_jurusan)){
                          $id_jurusan = $id_jurusan;
                        }else{
                          $id_jurusan = set_value('id_jurusan');
                        }

                        if(set_value('id_rumpun')=="" && isset($id_rumpun)){
                          $id_rumpun = $id_rumpun;
                        }else{
                          $id_rumpun = set_value('id_rumpun');
                        }

                        if($action=="edit"){
                          $id_rumpun  = $jurusan['id_mst_peg_rumpunpendidikan'];
                          $id_tingkat = $jurusan['id_mst_peg_tingkatpendidikan'];
                        }

                        $select = $rumpun->id_rumpun == $id_rumpun ? 'selected' : '' ;
                        ?>
                        <option value="<?php echo $rumpun->id_rumpun ?>" <?php echo $select ?>><?php echo $rumpun->nama_rumpun ?></option>
                      <?php endforeach ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tingkat Pendidikan
                </div>
                <div class="col-md-8">
                  <select  name="id_tingkat" type="text" class="form-control" <?php if($action == "edit") echo "disabled"?>>
                    <option value=''>-</option>
                  </select>
                </div>
              </div>
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Program Studi
                </div>
                <div class="col-md-8">
                  <select  name="id_jurusan" type="text" class="form-control" <?php if($action == "edit") echo "disabled"?>>
                    <option value=''>-</option>
                  </select>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Nomor Ijazah
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="ijazah_no" placeholder="Nomor Ijazah" value="<?php 
                  if(set_value('ijazah_no')=="" && isset($ijazah_no)){
                    echo $ijazah_no;
                  }else{
                    echo  set_value('ijazah_no');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tanggal Lulus
                </div>
                <div class="col-md-8">
                  <div id='ijazah_tgl' name="formal_ijazah_tgl" value="<?php
                    if(set_value('ijazah_tgl')=="" && isset($ijazah_tgl)){
                      $ijazah_tgl = strtotime($ijazah_tgl);
                    }else{
                      $ijazah_tgl = strtotime(set_value('ijazah_tgl'));
                    }

                    if($ijazah_tgl=="") $ijazah_tgl = time();
                    echo date("Y-m-d",$ijazah_tgl);
                  ?>" >
                  </div>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Nama Sekolah
                </div>
                <div class="col-md-8">

                  <div class="input-group">
                    <input type="text" class="form-control" name="sekolah_nama" placeholder="Nama Sekolah" value="<?php 
                    if(set_value('sekolah_nama')=="" && isset($sekolah_nama)){
                      echo $sekolah_nama;
                    }else{
                      echo  set_value('sekolah_nama');
                    }
                    ?>" style="text-indent: 13px;"  autocomplete="off">
                    <div class="input-group-addon">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Lokasi Sekolah
                </div>
                <div class="col-md-8">

                  <div class="input-group">
                    <input type="text" class="form-control" name="sekolah_lokasi" placeholder="Lokasi Sekolah" value="<?php 
                    if(set_value('sekolah_lokasi')=="" && isset($sekolah_lokasi)){
                      echo $sekolah_lokasi;
                    }else{
                      echo  set_value('sekolah_lokasi');
                    }
                    ?>" style="text-indent: 13px;" autocomplete="off">                  
                    <div class="input-group-addon">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-xs-4" style="padding: 5px">
                  Gelar
                </div>
                <div class="col-xs-4">
                  <input type="text" class="form-control" name="formal_gelar_depan" placeholder="Gelar Depan" value="<?php 
                  if(set_value('gelar_depan')=="" && isset($gelar_depan)){
                    echo $gelar_depan;
                  }else{
                    echo  set_value('gelar_depan');
                  }
                  ?>">
                </div>
                <div class="col-xs-4">
                  <input type="text" class="form-control" name="formal_gelar_belakang" placeholder="Gelar Belakang" value="<?php 
                  if(set_value('gelar_belakang')=="" && isset($gelar_belakang)){
                    echo $gelar_belakang;
                  }else{
                    echo  set_value('gelar_belakang');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-12" style="padding: 5px">
                  <input type="checkbox" name="status_pendidikan_cpns" value="1" <?php 
                  if(set_value('status_pendidikan_cpns')=="" && isset($status_pendidikan_cpns)){
                    $status_pendidikan_cpns = $status_pendidikan_cpns;
                  }else{
                    $status_pendidikan_cpns = set_value('status_pendidikan_cpns');
                  }
                  if($status_pendidikan_cpns == 1) echo "checked";
                  ?>>
                  &nbsp;  Pendidikan pengangkatan sebagai CPNS
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

    $("[name='formal_ijazah_tgl']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("[name='btn_pendidikan_formal_close']").click(function(){
        $("#popup_pendidikan_formal").jqxWindow('close');
    });

    $("[name='id_rumpun']").change(function(){
    
      var id_rumpun = $(this).val();
      $.ajax({
        url : '<?php echo site_url('kepegawaian/drh_pedidikan/get_tingkat/'.$id_tingkat) ?>',
        type : 'POST',
        data : 'id_rumpun=' + id_rumpun,
        success : function(data) {
          $("[name='id_tingkat']").html(data);
          $("[name='id_tingkat']").change();
        }
      });

      return false;
    }).change();
  
    $("[name='id_tingkat']").change(function(){
    
      var id_rumpun = $("[name='id_rumpun']").val();
      var id_tingkat = $(this).val();
      $.ajax({
        url : '<?php echo site_url('kepegawaian/drh_pedidikan/get_jurusan/'.$id_jurusan) ?>',
        type : 'POST',
        data : 'id_tingkat=' + id_tingkat + '&id_rumpun=' + id_rumpun,
        success : function(data) {
          $("[name='id_jurusan']").html(data);
        }
      });

      return false;
    });

    $("[name='sekolah_nama']").jqxInput(
      {
      placeHolder: " Nama Sekolah ",
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
              { name: 'sekolah_nama', type: 'string'}
            ],
            url: '<?php echo base_url().'kepegawaian/drh_pedidikan/autocomplite_sekolah'; ?>'
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
                  return item.sekolah_nama;
                }));
              }
            }
          });
      }
    });
  
    $("[name='sekolah_lokasi']").jqxInput(
      {
      placeHolder: " Kota Lokasi Sekolah ",
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
  
    $("[name='btn_pendidikan_formal_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('id_rumpun', $("[name='id_rumpun']").val());
        data.append('id_tingkat', $("[name='id_tingkat']").val());
        data.append('id_jurusan', $("[name='id_jurusan']").val());
        data.append('ijazah_no',              $("[name='ijazah_no']").val());
        data.append('ijazah_tgl',             $("[name='formal_ijazah_tgl']").val());
        data.append('sekolah_nama',           $("[name='sekolah_nama']").val());
        data.append('sekolah_lokasi',         $("[name='sekolah_lokasi']").val());
        data.append('gelar_depan',            $("[name='formal_gelar_depan']").val());
        data.append('gelar_belakang',         $("[name='formal_gelar_belakang']").val());
        data.append('status_pendidikan_cpns', $("[name='status_pendidikan_cpns']:checked").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."kepegawaian/drh_pedidikan/biodata_pendidikan_formal_{action}/{id}/{id_jurusan}"   ?>',
            data : data,
            success : function(response){
              if(response=="OK"){
                $("#popup_pendidikan_formal").jqxWindow('close');
                alert("Data pendidikan berhasil disimpan.");
                $("#jqxgridPendidikan").jqxGrid('updatebounddata', 'filter');
              }else{
                $('#popup_pendidikan_formal_content').html(response);
              }
            }
        });

        return false;
    });

  });
</script>
