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
      <button type="button" name="btn_keuangan_induk_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
      <button type="button" name="btn_keuangan_induk_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                 Kode Anggaran
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="induk_kode_anggaran" placeholder=" Kode Anggaran " value="<?php 
                  if(set_value('kode_anggaran')=="" && isset($kode_anggaran)){
                    echo $kode_anggaran;
                  }else{
                    echo  set_value('kode_anggaran');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Uraian
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="induk_uraian" placeholder=" Uraian " value="<?php 
                  if(set_value('uraian')=="" && isset($uraian)){
                    echo $uraian;
                  }else{
                    echo  set_value('uraian');
                  }
                  ?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                 Kode Rekening
                </div>
                <div class="col-md-8">
                  <select  name="induk_kode_rek" type="text" class="form-control">
                      <?php foreach($kode_rek as $rek) : ?>
                        <?php
                        if(set_value('id_mst_akun')=="" && isset($id_mst_akun)){
                          $id_mst_akun = $id_mst_akun;
                        }else{
                          $id_mst_akun = set_value('id_mst_akun');
                        }
                        $select = $rek->id_mst_akun == $id_mst_akun ? 'selected' : '' ;
                        ?>
                        <option value="<?php echo $rek->id_mst_akun ?>" <?php echo $select ?>><?php echo $rek->kode." - ". $rek->uraian ?></option>
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

    $("[name='btn_keuangan_induk_close']").click(function(){
        $("#popup_keuangan_sts_induk").jqxWindow('close');
    });

    $("[name='btn_keuangan_induk_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();
        
        data.append('kode_anggaran',   $("[name='induk_kode_anggaran']").val());
        data.append('uraian',          $("[name='induk_uraian']").val());
        data.append('id_mst_akun',     $("[name='induk_kode_rek']").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_sts/induk_{action}/{id}"   ?>',
            data : data,
            success : function(response){
              if(response=="OK"){
                $("#popup_keuangan_sts_induk").jqxWindow('close');
                alert("Data berhasil disimpan.");
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                $('#popup_keuangan_sts_induk_content').html(response);
              }
            }
        });

        return false;
    });

  });
</script>
