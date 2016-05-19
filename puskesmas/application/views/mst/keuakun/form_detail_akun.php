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
      <button type="button" name="btn_keuangan_akun_close" disabled="" class="btn btn-success"><i class='fa fa-search'></i> &nbsp; Lihat Buku Besar</button>
      <button type="button" name="non_aktifkan_status" class="btn btn-danger"><i class='fa fa-times-circle-o'></i> &nbsp; Non Aktifkan</button>
      <button type="button" name="btn_keuangan_akun_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
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
                Mendukung Transaksi
                </div>
                <div class="col-md-6">
                  <input type="checkbox" name="akun_mendukung_transaksi" id="akun_mendukung_transaksi" value="1" <?php 
                  if(set_value('mendukung_transaksi')=="" && isset($mendukung_transaksi)){
                    $mendukung_transaksi = $mendukung_transaksi;
                  }else{
                    $mendukung_transaksi = set_value('mendukung_transaksi');
                  }
                  if($mendukung_transaksi == 1) echo "checked";
                  ?>>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-6" style="padding: 5px">
                 Mendukung Anggaran
                </div>
                <div class="col-md-6">
                  <input type="checkbox" name="akun_mendukung_anggaran" id="akun_mendukung_anggaran" value="1" <?php 
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
                  <input type="checkbox" name="akun_mendukung_target" id="akun_mendukung_target" value="1" <?php 
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

    $("[name='btn_keuangan_akun_close']").click(function(){
        $("#popup_keuangan_akun_detail").jqxWindow('close');
    });

    $("[name='non_aktifkan_status']").click(function(){
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_akun/non_aktif_akun/{id}"   ?>',
            success : function(response){
              if(response=="OK"){
                  $("[name='non_aktifkan_status']").show();
                $("#popup_keuangan_akun_detail").jqxWindow('close');
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                $("#popup_keuangan_akun_detail").jqxWindow('close');
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }
            }
        });
        return false;
    });

    $("[name='akun_mendukung_target']").click(function(){
      var data = new FormData();
        data.append('mendukung_target',        $("[name='akun_mendukung_target']:checked").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_akun/mendukung_target_update/{id}"   ?>',
            data : data,
            success : function(response){
              a = response.split("|");
              if(a[0]=="OK"){
                if(a[1]=='1'){
                  $("#akun_mendukung_target").prop("checked", true);
                }else{
                  $("#akun_mendukung_target").prop("checked", false);
                };
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                alert("Mendukung target belum berhasil di aktifkan.");

              }
            }
        });
        return false;
    });

    $("[name='akun_mendukung_anggaran']").click(function(){
      var data = new FormData();
        data.append('mendukung_anggaran',        $("[name='akun_mendukung_anggaran']:checked").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_akun/mendukung_anggaran_update/{id}"   ?>',
            data : data,
            success : function(response){
              a = response.split("|");
              if(a[0]=="OK"){
                if(a[1]=='1'){
                   $("#akun_mendukung_anggaran").prop("checked", true);
                }else{
                   $("#akun_mendukung_anggaran").prop("checked", false);
                };
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                alert("Mendukung anggaran belum berhasil di aktifkan.");
              }
            }
        });
        return false;
    });

    $("[name='akun_mendukung_transaksi']").click(function(){
      var data = new FormData();
        data.append('mendukung_transaksi',        $("[name='akun_mendukung_transaksi']:checked").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_akun/mendukung_transaksi_update/{id}"   ?>',
            data : data,
            success : function(response){
               a = response.split("|");
              if(a[0]=="OK"){
                if (a[1]=='1') {
                    $("#akun_mendukung_transaksi").prop("checked", true);
                }else{
                    $("#akun_mendukung_transaksi").prop("checked", false);
                };
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                alert("Mendukung transaksi belum berhasil di aktifkan.");

              }
            }
        });
        return false;
    });

  });
</script>
