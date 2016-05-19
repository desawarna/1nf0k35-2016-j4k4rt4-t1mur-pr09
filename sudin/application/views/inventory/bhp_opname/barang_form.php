
<script type="text/javascript">

  $(function(){
    var tglopnameda = $('#tgl_opname').val().split('-');
    $('#tgl_update_opname').val(tglopnameda[2]+'-'+tglopnameda[1]+'-'+tglopnameda[0]);
    <?php 
    if (isset($obat)) {
      if ($obat=="8") {
    ?>
      $("[name='tgl_kadaluarsa']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    <?php
      }else{}
    }
    ?>
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
          data.append('id_mst_inv_barang_habispakai_jenis', $('#id_mst_inv_barang_habispakai_jenis').val());
          data.append('id_inv_inventaris_habispakai_opname', $('#id_inv_inventaris_habispakai_opname').val());
          data.append('id_mst_inv_barang_habispakai', $('#id_mst_inv_barang_habispakai').val());
          data.append('batch', $('#batch').val());
          data.append('uraian', $('#uraian').val());
          data.append('jumlah', $('#jumlah').val());
          data.append('harga', $('#harga').val());
          data.append('jumlahopname', $('#jumlahopname').val());
          data.append('jml_rusak', $('#jml_rusak').val());
          data.append('jml_tdkdipakai', $('#jml_tdkdipakai').val());
          data.append('tgl_update_opname', $('#tgl_update_opname').val());

          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_opname/".$action."_barang/{tanggal_opnam}" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice').hide();
                    $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice').show();
                    $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
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
      $("#jml_rusak").add("#jml_tdkdipakai").val(0);
      $("#jml_rusak, #jml_tdkdipakai").change(function(){
        //alert('hai');
        if ($("#jml_rusak").val() < 0) {
            alert('Maaf, data jumlah rusak tidak boleh minus');
            $("#jml_rusak").val(0);
          }
          if ($("#jml_tdkdipakai").val() < 0) {
            alert('Maaf, data tidak dipakai tidak boleh minus');
            $("#jml_tdkdipakai").val(0);
          }
          if((parseInt($("#jml_rusak").val()) + parseInt($("#jml_tdkdipakai").val())) > $("#jumlahopname").val()){
            $("#jml_rusak").add("#jml_tdkdipakai").val(0);
            alert("Maaf total jumlah rusak dan jumlah tidak dipakai tidak boleh melebihi jumlah opname");
          }
      });

    });
</script>

<div style="padding:15px">
  <div id="notice" class="alert alert-success alert-dismissable" <?php if ($notice==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
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
                <input type="hidden" class="form-control" name="id_inv_inventaris_habispakai_opname" id="id_inv_inventaris_habispakai_opname" placeholder="Nama Barang" value="<?php 
                if(set_value('id_inv_inventaris_habispakai_opname')=="" && isset($kode)){
                  echo $kode;
                }else{
                  echo  set_value('id_inv_inventaris_habispakai_opname');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <?php 
            if (isset($id_mst_inv_barang_habispakai_jenis)) {
              if ($id_mst_inv_barang_habispakai_jenis=="8") {
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Batch</div>
            <div class="col-md-8">
              <input type="hidden" class="form-control" name="batch" id="batch" placeholder="Nomor Batch" value="<?php 
                if(set_value('batch')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch');
                }
                ?>" readonly="readonly">
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
                ?>" >
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Awal</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jmlawal)){
                  echo $jmlawal;
                }else{
                  echo  set_value('jumlah');
                }
                ?>" readonly="readonly">
            </div>
            <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis" id="id_mst_inv_barang_habispakai_jenis" placeholder="Jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis');
                }
                ?>" readonly="readonly">
                <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai" id="id_mst_inv_barang_habispakai" placeholder="Jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai');
                }
                ?>" readonly="readonly">
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Opname</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlahopname" id="jumlahopname" placeholder="Jumlah Opname" value="<?php 
                if(set_value('jumlahopname')=="" && isset($jmlawal)){
                  echo $jmlawal;
                }else{
                  echo  set_value('jumlahopname');
                }
                ?>">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Selisih</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="selisih" id="selisih" placeholder="Selisih Opname" value="<?php 
                if(set_value('selisih')=="" && isset($jmlawal) && isset($jml_akhir)){
                  echo $jmlawal-$jml_akhir;
                }else{
                  echo  set_value('selisih');
                }
                ?>" readonly="readonly">
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
                 <input type="hidden" class="form-control" name="tgl_update_opname" id="tgl_update_opname" placeholder="tanggal update" value="<?php 
                if(set_value('tgl_update_opname')=="" && isset($tgl_opname)){
                  echo $tgl_opname;
                }else{
                  echo  set_value('tgl_update_opname');
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