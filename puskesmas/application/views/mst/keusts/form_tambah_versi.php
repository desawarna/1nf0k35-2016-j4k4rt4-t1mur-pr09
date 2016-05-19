
<form action="#" method="POST" name="frmPegawai">
  <div class="row" style="margin: 15px 5px 15px 5px">
    <div class="col-sm-8">
 
    </div>
    <div class="col-sm-12" style="text-align: right">
      <button type="button" name="btn_keuangan_versi_save" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
      <button type="button" name="btn_keuangan_versi_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">
             
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                 Judul Versi
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="versi_nama" placeholder=" Judul Versi " value="<?php 
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
                  <input type="text" class="form-control" name="versi_deskripsi" placeholder=" Deskripsi " value="<?php 
                  if(set_value('deskripsi')=="" && isset($deskripsi)){
                    echo $deskripsi;
                  }else{
                    echo  set_value('deskripsi');
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

function getVersi(){
      $.ajax({
        url: "<?php echo base_url().'mst/keuangan_sts/get_versi';?>",
         success : function(data) {
          $("select[name='versi']").html(data);
        }
      });
      return false;
    }

  $(function () { 
    tabIndex = 1;
    
   $("[name='btn_keuangan_versi_close']").click(function(){
        $("#popup_keuangan_sts").jqxWindow('close');
    });

    $("[name='btn_keuangan_versi_save']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('nama',                  $("[name='versi_nama']").val());
        data.append('deskripsi',             $("[name='versi_deskripsi']").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_sts/versi_{action}/{id}"   ?>',
            data : data ,
            success : function(response){
              if(response=="OK"){
                $("select[name='versi']").html(data);
                getVersi();
                $("#popup_keuangan_sts").jqxWindow('close');
                alert("Data instansi berhasil disimpan.");
              }else{
                $('#popup_keuangan_sts_content').html(response);
              }
            }
        });

        return false;
    });
  });

</script>
