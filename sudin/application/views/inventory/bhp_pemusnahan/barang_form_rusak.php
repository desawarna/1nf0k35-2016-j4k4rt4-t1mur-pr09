
<script type="text/javascript">

  $(function(){
      $('#btn-close-close-rusak').click(function(){
        close_popup_rusak();
      }); 

      $('#form-ss-rusak').submit(function(){
          var data = new FormData();
          $('#notice_rusak-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice_rusak').show();
          data.append('id_mst_inv_barang_habispakai_jenis_rusak', $('#id_mst_inv_barang_habispakai_jenis_rusak').val());
          data.append('id_inv_inventaris_habispakai_opname_rusak', $('#id_inv_inventaris_habispakai_opname_rusak').val());
          data.append('id_mst_inv_barang_habispakai_rusak', $('#id_mst_inv_barang_habispakai_rusak').val());
          data.append('batch_rusak', $('#batch_rusak').val());
          data.append('uraian_rusak', $('#uraian_rusak').val());
          data.append('jumlah_rusak', $('#jumlah_rusak').val());
          data.append('harga_rusak', $('#harga_rusak').val());
          data.append('jumlah_rusakopname', $('#jumlah_rusakopname').val());
          data.append('merek_tipe_rusak', $('#merek_tipe_rusak').val());
          data.append('jml_awalopname', $('#jml_awalopname').val());
          data.append('jumlah_rusakmusnah', $('#jumlah_rusakmusnah').val());

          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_pemusnahan/".$action_rusak."_barang_rusak/{tanggal_opname_rusak}/{koderusak}/{idbarang_rusak}/{batch_rusak}" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice_rusak').hide();
                    $('#notice_rusak-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice_rusak').show();
                    $("#jqxgrid_barang_rusak_kiri").jqxGrid('updatebounddata', 'cells');
                    close_popup_rusak();
                }
                else if(res[0]=="Error"){
                    $('#notice_rusak').hide();
                    $('#notice_rusak-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice_rusak').show();
                }
                else{
                    $('#popup_content_kananrusak').html(response);
                }
            }
          });
          return false;
      });
      var jmlasli = "<?php if(set_value('jumlah_rusak')=="" && isset($jumlahrusak)){
                            echo $jumlahrusak;
                          }else{
                            echo  set_value('jumlah_rusak');
                          } ?>";
      $("#jumlah_rusakopname").val(jmlasli - $("#jumlah_rusakmusnah").val());
      $("#jumlah_rusakmusnah").change(function(){
          if ($("#jumlah_rusakmusnah").val() < 0) {
            alert('Maaf, Jumlah pemusnahan tidak boleh minus');
            $("#jumlah_rusakopname").val(jmlasli);
            $("#jumlah_rusakmusnah").val(jmlasli);
          }
          if (parseInt($("#jumlah_rusakmusnah").val()) > parseInt(jmlasli)) {
            alert('Maaf, Jumlah pemusnahan tidak boleh lebih dari '+ jmlasli);
            $("#jumlah_rusakopname").val(jmlasli);
            $("#jumlah_rusakmusnah").val(jmlasli);
          }
          $("#jumlah_rusakopname").val(jmlasli- $(this).val());
      });

    });
</script>

<div style="padding:15px">
  <div id="notice_rusak" class="alert alert-success alert-dismissable" <?php if ($notice_rusak==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice_rusak-content">{notice_rusak}</div>
  </div>
  <div class="row">
    <?php echo form_open(current_url(), 'id="form-ss-rusak"') ?>
          <div class="box-body">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Barang</div>
            <div class="col-md-8">
                <?php 
                  if(set_value('uraian_rusak')=="" && isset($uraian)){
                    echo $uraian;
                  }else{
                    echo  set_value('uraian_rusak');
                  }
                ?>
                <input type="hidden" class="form-control" name="id_inv_inventaris_habispakai_opname_rusak" id="id_inv_inventaris_habispakai_opname_rusak" placeholder="Nama Barang" value="<?php 
                if(set_value('id_inv_inventaris_habispakai_opname_rusak')=="" && isset($koderusak)){
                  echo $koderusak;
                }else{
                  echo  set_value('id_inv_inventaris_habispakai_opname_rusak');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Batch</div>
            <div class="col-md-8">
              <input type="hidden" class="form-control" name="batch_rusak" id="batch_rusak" placeholder="Nomor batch_rusak" value="<?php 
                if(set_value('batch_rusak')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch_rusak');
                }
                ?>" readonly="readonly">
                 <?php 
                if(set_value('batch_rusak')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch_rusak');
                }
                ?>
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Merek Tipe</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="merek_tipe_rusak" id="merek_tipe_rusak" placeholder="Merek Tipe" value="<?php 
                if(set_value('merek_tipe_rusak')=="" && isset($merek_tipe)){
                  echo $merek_tipe;
                }else{
                  echo  set_value('merek_tipe_rusak');
                }
                ?>" readonly="" >
            </div>
          </div>
           <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Harga</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="harga_rusak" id="harga_rusak" placeholder="Harga" value="<?php 
                if(set_value('harga_rusak')=="" && isset($hargaterakhir)){
                  echo $hargaterakhir;
                }else{
                  echo  set_value('harga_rusak');
                }
                ?>" readonly="" >
            </div>
          </div>
          
              <input type="hidden" class="form-control" name="jumlah_rusak" id="jumlah_rusak" placeholder="Jumlah Rusak" value="<?php 
                if(set_value('jumlah_rusak')=="" && isset($jumlahrusak)){
                  echo $jumlahrusak;
                }else{
                  echo  set_value('jumlah_rusak');
                }
                ?>" readonly="readonly">
          
            <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis_rusak" id="id_mst_inv_barang_habispakai_jenis_rusak" placeholder="jumlah_rusak" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis_rusak')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis_rusak');
                }
                ?>" readonly="readonly">
                <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_rusak" id="id_mst_inv_barang_habispakai_rusak" placeholder="jumlah_rusak" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_rusak')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_rusak');
                }
                ?>" readonly="readonly">
          
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Pemusnahan</div>
            <div class="col-md-8">
              <input type="hidden" class="form-control" name="jumlah_rusakopname" id="jumlah_rusakopname" placeholder="jumlah_rusak Opname" value="<?php 
                if(set_value('jumlah_rusakopname')=="" && isset($jumlahrusak)){
                  echo $jumlahrusak;
                }else{
                  echo  set_value('jumlah_rusakopname');
                }
                ?>">
                 <input type="number" class="form-control" name="jumlah_rusakmusnah" id="jumlah_rusakmusnah" placeholder="Jumlah Pemusnahan" value="<?php 
                if(set_value('jumlah_rusakmusnah')=="" && isset($jumlahrusak)){
                  echo $jumlahrusak;
                }else{
                  echo  set_value('jumlah_rusakmusnah');
                }
                ?>">
                 <input type="hidden" class="form-control" name="jml_awalopname" id="jml_awalopname" placeholder="jumlah_rusak Pemusnahan" value="<?php 
                if(set_value('jml_awalopname')=="" && isset($jml_awalopname)){
                  echo $jml_awalopname;
                }else{
                  echo  set_value('jml_awalopname');
                }
                ?>">
            </div>
          </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close-close-rusak" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>