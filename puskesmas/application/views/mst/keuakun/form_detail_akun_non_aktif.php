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
      <button type="button" name="btn_akun_non_aktif_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
      <button type="button" name="btn_akun_non_aktif_delete" class="btn btn-danger"><i class='fa fa-close'></i> &nbsp; Hapus Akun</button>
      <button type="button" name="aktifkan_status" class="btn btn-warning"><i class='fa fa-close'></i> &nbsp; Aktifkan</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                  Kode Akun
                </div>
                <div class="col-md-6">
                  <input type="hidden" id="kode" value="<?=$kode?>">
                  <?php
                    if(set_value('kode')=="" && isset($kode)){
                      echo $kode;
                    }else{
                      echo('-');
                    }
                  ?>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                  Uraian
                </div>
                <div class="col-md-6">
                  <input type="hidden" id="uraian" value="<?=$uraian?>">
                  <?php
                    if(set_value('uraian')=="" && isset($uraian)){
                     echo $uraian;
                    }else{
                      echo  set_value('uraian');
                    }
                  ?>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                  Saldo Normal
                </div>
                <div class="col-md-6">
                  <input type="hidden" id="saldo_normal" value="<?=$saldo_normal?>">
                  <?php
                    if(set_value('saldo_normal')=="" && isset($saldo_normal)){
                      echo ucwords($saldo_normal);
                    }else{
                      echo  set_value('saldo_normal');
                    }
                  ?>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                  Keterangan
                </div>
                <div class="col-md-6">
                  <input type="hidden" id="keterangan" value="<?=$keterangan?>">
                  <?php 
                    if(set_value('keterangan')=="" && isset($keterangan)){
                      echo $keterangan;
                    }else{
                      echo('-');
                    }
                  ?>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                 Mendukung Anggaran
                </div>
                <div class="col-md-6">
                  <input type="checkbox" name="akun_mendukung_anggaran" id="akun_mendukung_anggaran" disabled value="1" <?php 
                  if(set_value('mendukung_anggaran')=="" && isset($mendukung_anggaran)){
                    $mendukung_anggaran = $mendukung_anggaran;
                  }else{
                    $mendukung_anggaran = set_value('mendukung_anggaran');
                  }
                  if($mendukung_anggaran == 1) echo "checked";
                  ?>>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                  Mendukung Target Penerimaan
                </div>
                <div class="col-md-6">
                  <input type="checkbox" name="akun_mendukung_target" id="akun_mendukung_target" disabled value="1" <?php 
                  if(set_value('mendukung_target')=="" && isset($mendukung_target)){
                    $mendukung_target = $mendukung_target;
                  }else{
                    $mendukung_target = set_value('mendukung_target');
                  }
                  if($mendukung_target == 1) echo "checked";
                  ?>>
                </div>
              </div>

              <br>
            </div>
          </div>
  </div>
</form>
<script>
  $(document).ready(function () {   
    tabIndex = 1;

    $("[name='aktifkan_status']").click(function(){
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_akun/aktifkan_akun/{id}"   ?>',
            success : function(response){
              if(response!="OK"){
                $("[name='aktifkan_status']").show();
                $("#popup_keuangan_akun_non_aktif_detail").jqxWindow('close');
                $("#treeGrid_akun_non_aktif").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                $("#popup_keuangan_akun_non_aktif_detail").jqxWindow('close');
                $("#treeGrid_akun_non_aktif").jqxTreeGrid('updateBoundData', 'filter');
              }
            }
        });
        return false;
    });

  });
</script>
