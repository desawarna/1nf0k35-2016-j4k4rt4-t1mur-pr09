
<script type="text/javascript">

  $(function(){
      $('#btn-close-opname').click(function(){
        close_popup_opname();
      }); 

      $('#form-ss').submit(function(){
        if ($('#jumlah').val()==$('#jumlahopname').val()) {
          alert('Data jumlah tidak boleh sama dengan data opname')
        }else{
          var data = new FormData();
          $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice').show();
          data.append('harga', $('#harga').val());
          data.append('jumlahopname', $('#jumlahopname').val());
          data.append('jml_rusak', $('#jml_rusak').val());
          data.append('jml_tdkdipakai', $('#jml_tdkdipakai').val());

          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_kondisi/kondisi_barang/$barang/$batch/$pus/$tgl/$opname" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice').hide();
                    $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice').show();
                    $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                    close_popup_opname();
                }
                else if(res[0]=="Error"){
                    $('#notice').hide();
                    $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice').show();
                }
                else{
                    $('#popup_content').html(response);
                }
            }
          });
          }
          return false;
      });
      var jmlasli = "<?php if(set_value('jumlah')=="" && isset($jmlawal)){
                            echo $jmlawal;
                          }else{
                            echo  set_value('jumlah');
                          } ?>";
      var jmlusak = "<?php if(set_value('jml_rusak')=="" && isset($jml_rusak)){
                            echo $jml_rusak;
                          }else{
                            echo  set_value('jml_rusak');
                          } ?>";
      var jmltkdipakai = "<?php if(set_value('jml_tdkdipakai')=="" && isset($jml_tdkdipakai)){
                            echo $jml_tdkdipakai;
                          }else{
                            echo  set_value('jml_tdkdipakai');
                          } ?>";
      $("#jumlahopname").change(function(){
          if ($(this).val() < 0) {
            alert('Maaf, jumlah oname tidak boleh minus');
            $("#jumlahopname").val(jmlasli);
          }
          $('#selisih').val($(this).val()-jmlasli);
          if((parseInt($("#jml_rusak").val()) + parseInt($("#jml_tdkdipakai").val())) > $("#jumlahopname").val()){
            $("#jml_rusak").add("#jml_tdkdipakai").val(0);
            alert("Maaf total jumlah rusak dan jumlah tidak dipakai tidak boleh melebihi jumlah opname");
          }
      });
      $('#selisih').val($("#jumlahopname").val()-jmlasli);
      $("#jml_rusak, #jml_tdkdipakai").change(function(){
        //alert('hai');
        if ($("#jml_rusak").val() < 0) {
            alert('Maaf, data jumlah rusak tidak boleh minus');
            $("#jml_rusak").val(jmlusak);
          }
          if ($("#jml_tdkdipakai").val() < 0) {
            alert('Maaf, data tidak dipakai tidak boleh minus');
            $("#jml_tdkdipakai").val(jmltkdipakai);
          }
          if((parseInt($("#jml_rusak").val()) + parseInt($("#jml_tdkdipakai").val())) > $("#jumlahopname").val()){
            $("#jml_rusak").val("<?php echo $jml_rusak;?>");
            $("#jml_tdkdipakai").val("<?php echo $jml_tdkdipakai;?>");
            alert("Maaf total jumlah rusak dan jumlah tidak dipakai tidak boleh melebihi jumlah opname");
          }
      });

    });
</script>

<div style="padding:15px">
  <div id="notice" class="alert alert-success alert-dismissable" <?php if ($notice==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-text="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice-content">{notice}</div>
  </div>
  <div class="row">
    <?php echo form_open(current_url(), 'id="form-ss"') ?>
          <div class="box-body">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Barang</div>
            <div class="col-md-8">
                <?php 
                  if(set_value('uraian')=="" && isset($uraian)){
                    echo $uraian;
                  }else{
                    echo  set_value('uraian');
                  }
                ?>
            </div>
          </div>
          <?php 
            if (isset($id_mst_inv_barang_habispakai_jenis)) {
              if ($id_mst_inv_barang_habispakai_jenis=="8") {
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Batch</div>
            <div class="col-md-8">
                 <?php 
                if(set_value('batch')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch');
                }
                ?>
            </div>
          </div>
          <?php
           # code...
              }else{

              }
            }
          ?>
           <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Harga</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" value="<?php 
                if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>" readonly="" >
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Opname</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlahopname" id="jumlahopname" placeholder="Jumlah Opname" value="<?php 
                if(set_value('jumlahopname')=="" && isset($jml_asli)){
                  echo $jml_asli;
                }else{
                  echo  set_value('jumlahopname');
                }
                ?>" readonly="">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Rusak</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jml_rusak" id="jml_rusak" placeholder="Jumlah Rusak" value="<?php 
                if(set_value('jml_rusak')=="" && isset($jml_rusak)){
                  echo $jml_rusak;
                }else{
                  echo  set_value('jml_rusak');
                }
                ?>">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Tidak Dipakai</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jml_tdkdipakai" id="jml_tdkdipakai" placeholder="Jumlah Tidak Dipakai" value="<?php 
                if(set_value('jml_tdkdipakai')=="" && isset($jml_tdkdipakai)){
                  echo $jml_tdkdipakai;
                }else{
                  echo  set_value('jml_tdkdipakai');
                }
                ?>">
            </div>
          </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close-opname" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>