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
      <button type="button" name="btn_kategori_transaksi_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
      <button type="button" name="btn_kategori_transaksi_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">
             
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Nama Kategori
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="kategori_trans_nama" placeholder="Nama Kategori" value="<?php 
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
                  Deskripsi
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="kategori_trans_deskripsi" placeholder="Deskripsi Kategori Transaksi" value="<?php 
                  if(set_value('deskripsi')=="" && isset($deskripsi)){
                    echo $deskripsi;
                  }else{
                    echo  set_value('deskripsi');
                  }
                  ?>">
                </div>
              </div>
              <br>
              <label>Pengaturan Transaksi Untuk Kategori Ini</label>

              <div class="row" style="margin: 5px">
                <div class="col-md-6">
                  <?php foreach($template as $t) : ?>
                    <input type="checkbox" name="kategori_trans_template" id="kategori_trans_template" value="<?php echo $t->id_mst_setting_transaksi_template;?>" 
                  <?php 
                  if(set_value('nilai')=="" && isset($nilai)){
                    $nilai = $nilai;
                  }else{
                    $nilai = set_value('nilai');
                  }
                  if($nilai == 1) echo "checked";
                  ?>> 
                    <?php echo $t->setting_judul ?>
                    </br>
                  <?php endforeach ?> 
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

    $("[name='btn_kategori_transaksi_close']").click(function(){
        $("#popup_kategori_transaksi").jqxWindow('close');
    });

  
    $("[name='btn_kategori_transaksi_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('nama',          $("[name='kategori_trans_nama']").val());
        data.append('deskripsi',     $("[name='kategori_trans_deskripsi']").val());
              
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_transaksi/kategori_transaksi_{action}/{id}"   ?>',
            data : data,
            success : function(response){
              if(response=="OK"){
                $("#popup_kategori_transaksi").jqxWindow('close');
                alert("Data berhasil disimpan.");
                $("#jqxgrid").jqxGrid('updatebounddata', 'filter');
              }else{
                $('#popup_kategori_transaksi_content').html(response);
              }
            }
        });

        return false;
    });

    $("[name='kategori_trans_template']").click(function(){
      var data = new FormData();
        data.append('nilai',   $("[name='kategori_trans_template']:checked").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_transaksi/template_{action}/{id}"?>',
            data : data,
            success : function(response){
                if(response=="OK"){
                  $("#kategori_trans_template").prop("checked", true);
                   alert("True");
                }else{
                  $("#kategori_trans_template").prop("checked", false);
                   alert("False");
              }
            }
        });
        return false;
    });

  });
</script>
