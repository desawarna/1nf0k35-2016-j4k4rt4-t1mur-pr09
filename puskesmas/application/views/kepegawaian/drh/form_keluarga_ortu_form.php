
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
      <button type="button" name="btn_biodata_ortu_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
      <button type="button" name="btn_biodata_ortu_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Status Keluarga
                </div>
                <div class="col-md-8">
                  <select  name="ortu_id_mst_peg_keluarga" type="text" class="form-control">
                      <?php foreach($kode_kel as $kel) : ?>
                        <?php
                        if(set_value('id_mst_peg_keluarga')=="" && isset($id_mst_peg_keluarga)){
                          $id_mst_peg_keluarga = $id_mst_peg_keluarga;
                        }else{
                          $id_mst_peg_keluarga = set_value('id_mst_peg_keluarga');
                        }
                        $select = $kel->id_keluarga == $id_mst_peg_keluarga ? 'selected' : '' ;
                        ?>
                        <option value="<?php echo $kel->id_keluarga ?>" <?php echo $select ?>><?php echo $kel->nama_keluarga ?></option>
                      <?php endforeach ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Nama Lengkap *
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="ortu_nama" placeholder="Nama Lengkap" value="<?php 
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
                  Jenis Kelamin
                </div>
                <div class="col-sm-4">
                <?php
                    if(set_value('jenis_kelamin')=="" && isset($jenis_kelamin)){
                      $jenis_kelamin = $jenis_kelamin;
                    }else{
                      $jenis_kelamin = set_value('jenis_kelamin');
                    }
                    $jenis_kelamin = $jenis_kelamin=="" ? "L" : $jenis_kelamin;

                ?>
                  <input type="radio" name="ortu_jenis_kelamin" value="L" class="iCheck-helper" <?php echo  ('L' == $jenis_kelamin) ? 'checked' : '' ?>> Laki-laki 
                </div>
                <div class="col-sm-4">
                  <input type="radio" name="ortu_jenis_kelamin" value="P" class="iCheck-helper" <?php echo  ('P' == $jenis_kelamin) ? 'checked' : '' ?>> Perempuan
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tempat Lahir
                </div>
                <div class="col-md-8">
                  <div class="input-group">
                    <input type="text" class="form-control" name="ortu_tmp_lahir" placeholder="Tempat Lahir" value="<?php 
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
                  <div id='tgl_lahir' name="ortu_tgl_lahir" value="<?php
                    if(set_value('tgl_lahir')=="" && isset($tgl_lahir)){
                      $tgl_lahir = strtotime($tgl_lahir);
                    }else{
                      $tgl_lahir = strtotime(set_value('tgl_lahir'));
                    }

                    if($tgl_lahir=="") $tgl_lahir = time();
                    echo date("Y-m-d",$tgl_lahir);
                  ?>" >
                  </div>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Status PNS
                </div>
                <div class="col-md-8">
                  <input type="checkbox" name="ortu_status_pns" value="1" <?php 
                  if(set_value('status_pns')=="" && isset($status_pns)){
                    $status_pns = $status_pns;
                  }else{
                    $status_pns = set_value('status_pns');
                  }
                  if($status_pns == 1) echo "checked";
                  ?>>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Status Hidup
                </div>
                <div class="col-md-8">
                  <input type="checkbox" name="ortu_status_hidup" value="1" <?php 
                  if(set_value('status_hidup')=="" && isset($status_hidup)){
                    $status_hidup = $status_hidup;
                  }else{
                    $status_hidup = set_value('status_hidup');
                  }
                  if($status_hidup == 1) echo "checked";
                  ?>>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  BPJS
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="ortu_bpjs" placeholder="BPJS" value="<?php 
                  if(set_value('bpjs')=="" && isset($bpjs)){
                    echo $bpjs;
                  }else{
                    echo  set_value('bpjs');
                  }
                  ?>">
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

    $("[name='ortu_tgl_lahir']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    $("[name='btn_biodata_ortu_close']").click(function(){
        $("#popup_keluarga_ortu").jqxWindow('close');
    });

    $("[name='ortu_tmp_lahir']").jqxInput(
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
  
    $("[name='btn_biodata_ortu_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('id_mst_peg_keluarga',  $("[name='ortu_id_mst_peg_keluarga']").val());
        data.append('nama',                 $("[name='ortu_nama']").val());
        data.append('jenis_kelamin',        $("[name='ortu_jenis_kelamin']:checked").val());
        data.append('code_cl_district',     $("[name='ortu_tmp_lahir']").val());
        data.append('tgl_lahir',            $("[name='ortu_tgl_lahir']").val());
        data.append('bpjs',                 $("[name='ortu_bpjs']").val());
        data.append('status_hidup',         $("[name='ortu_status_hidup']:checked").val());
        data.append('status_pns',           $("[name='ortu_status_pns']:checked").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."kepegawaian/drh_keluarga/biodata_keluarga_ortu_{action}/{id}/{urut}"   ?>',
            data : data,
            success : function(response){
              if(response=="OK"){
                $("#popup_keluarga_ortu").jqxWindow('close');
                alert("Data keluarga berhasil disimpan.");
                $("#jqxgridKeluarga").jqxGrid('updatebounddata', 'filter');
              }else{
                $('#popup_keluarga_ortu_content').html(response);
              }
            }
        });

        return false;
    });

  });
</script>
