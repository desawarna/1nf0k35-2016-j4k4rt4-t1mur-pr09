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
      <button type="button" name="btn_keuangan_akun_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
      <button type="button" name="btn_keuangan_akun_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Uraian
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="akun_uraian" placeholder=" Uraian " value="<?php 
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
                  Saldo Normal
                </div>
                <div class="col-md-8">
                  <select name="akun_saldo" type="text" class="form-control">
                      <option value="debet">Debet</option>
                      <option value="kredit">Kredit</option>
                  </select>
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                 Urutan
                </div>

                <div class="col-md-4">
                  <select name="akun_urutan" type="text" class="form-control">
                       <option value="sebelum">Sebelum</option>
                       <option value="sesudah">Sesudah</option>
                  </select>
                </div>
                
                <div class="col-md-4">
                  <select  name="akun_urutan_induk" type="text" class="form-control">
                      <?php foreach($akun as $a) : ?>
                        <?php
                        if(set_value('id_mst_akun')=="" && isset($id_mst_akun)){
                          $id_mst_akun = $id_mst_akun;
                        }else{
                          $id_mst_akun = set_value('id_mst_akun');
                        }
                        $select = $a->id_mst_akun == $id_mst_akun ? 'selected' : '' ;
                        ?>
                        <option value="<?php echo $a->id_mst_akun ?>" <?php echo $select ?>><?php echo $a->uraian ?></option>
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

    $("[name='btn_keuangan_akun_close']").click(function(){
        $("#popup_keuangan_akun").jqxWindow('close');
    });

    $("[name='btn_keuangan_akun_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();
        
        data.append('uraian', $("[name='akun_uraian']").val());
        data.append('saldo_normal', $("[name='akun_saldo']").val());
        data.append('akun_urutan', $("[name='akun_urutan']").val());
        data.append('akun_urutan_induk', $("[name='akun_urutan_induk']").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_akun/induk_add" ?>',
            data : data,
            success : function(response){
              if(response=="OK"){
                $("#popup_keuangan_akun").jqxWindow('close');
                alert("Data berhasil disimpan.");
                $("#treeGrid").jqxTreeGrid('updateBoundData', 'filter');
              }else{
                $('#popup_keuangan_akun_content').html(response);
              }
            }
        });

        return false;
    });
  });
</script>
