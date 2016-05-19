
<script type="text/javascript">

  $(function(){
      $('#btn-close-close-opname').click(function(){
        close_popup_opname();
      }); 

      $('#form-ss-opname').submit(function(){
          var data = new FormData();
          $('#notice_opname-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice_opname').show();
          data.append('id_mst_inv_barang_habispakai_jenis_opname', $('#id_mst_inv_barang_habispakai_jenis_opname').val());
          data.append('id_inv_inventaris_habispakai_opname_opname', $('#id_inv_inventaris_habispakai_opname_opname').val());
          data.append('id_mst_inv_barang_habispakai_opname', $('#id_mst_inv_barang_habispakai_opname').val());
          data.append('batch_opname', $('#batch_opname').val());
          data.append('uraian_opname', $('#uraian_opname').val());
          data.append('jumlah_opname', $('#jumlah_opname').val());
          data.append('harga_opname', $('#harga_opname').val());
          data.append('jumlah_opnameopname', $('#jumlah_opnameopname').val());
          data.append('merek_tipe_opname', $('#merek_tipe_opname').val());
          data.append('jumlah_opnamemusnah', $('#jumlah_opnamemusnah').val());
          data.append('jml_awalopname', $('#jml_awalopname').val())

          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_pemusnahan/".$action_opname."_barang_opname/{tanggal_opname_opname}/{kodeopname}/{idbarang_opname}/{batch_opname}" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice_opname').hide();
                    $('#notice_opname-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice_opname').show();
                    $("#jqxgrid_barang_opname_kanan").jqxGrid('updatebounddata', 'cells');
                    $("#jqxgrid_barang_opname_kiri").jqxGrid('updatebounddata', 'cells');
                    close_popup_opname();
                }
                else if(res[0]=="Error"){
                    $('#notice_opname').hide();
                    $('#notice_opname-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice_opname').show();
                }
                else{
                    $('#popup_content_kananopname').html(response);
                }
            }
          });
          return false;
      });
      var jmlasli = "<?php if(set_value('jumlah_opname')=="" && isset($jumlahtidakdipakaiterakhir)){
                            echo $jumlahtidakdipakaiterakhir;
                          }else{
                            echo  set_value('jumlah_opname');
                          } ?>";
      $("#jumlah_opnameopname").val(jmlasli - $("#jumlah_opnamemusnah").val());
      $("#jumlah_opnamemusnah").change(function(){
          if ($("#jumlah_opnamemusnah").val() < 0) {
            alert('Maaf, Jumlah pemusnahan tidak boleh minus');
            $("#jumlah_opnameopname").val(jmlasli);
            $("#jumlah_opnamemusnah").val(jmlasli);
          }
          if (parseInt($("#jumlah_opnamemusnah").val()) > parseInt(jmlasli)) {
            alert('Maaf, Jumlah pemusnahan tidak boleh lebih dari '+ jmlasli);
            $("#jumlah_opnameopname").val(jmlasli);
            $("#jumlah_opnamemusnah").val(jmlasli);
          }
          $("#jumlah_opnameopname").val(jmlasli- $(this).val());
      });

    });
</script>

<div style="padding:15px">
  <div id="notice_opname" class="alert alert-success alert-dismissable" <?php if ($notice_opname==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice_opname-content">{notice_opname}</div>
  </div>
  <div class="row">
    <?php echo form_open(current_url(), 'id="form-ss-opname"') ?>
          <div class="box-body">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Barang</div>
            <div class="col-md-8">
                <?php 
                  if(set_value('uraian_opname')=="" && isset($uraian)){
                    echo $uraian;
                  }else{
                    echo  set_value('uraian_opname');
                  }
                ?>
                <input type="hidden" class="form-control" name="id_inv_inventaris_habispakai_opname_opname" id="id_inv_inventaris_habispakai_opname_opname" placeholder="Nama Barang" value="<?php 
                if(set_value('id_inv_inventaris_habispakai_opname_opname')=="" && isset($kodeopname)){
                  echo $kodeopname;
                }else{
                  echo  set_value('id_inv_inventaris_habispakai_opname_opname');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Batch</div>
            <div class="col-md-8">
              <input type="hidden" class="form-control" name="batch_opname" id="batch_opname" placeholder="Nomor batch_opname" value="<?php 
                if(set_value('batch_opname')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch_opname');
                }
                ?>" readonly="readonly">
                 <?php 
                if(set_value('batch_opname')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch_opname');
                }
                ?>
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Merek Tipe</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="merek_tipe_opname" id="merek_tipe_opname" placeholder="merek_tipe_opname" value="<?php 
                if(set_value('merek_tipe_opname')=="" && isset($merek_tipe)){
                  echo $merek_tipe;
                }else{
                  echo  set_value('merek_tipe_opname');
                }
                ?>" readonly="" >
            </div>
          </div>
           <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Harga</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="harga_opname" id="harga_opname" placeholder="Harga" value="<?php 
                if(set_value('harga_opname')=="" && isset($hargaterakhir)){
                  echo $hargaterakhir;
                }else{
                  echo  set_value('harga_opname');
                }
                ?>" readonly="" >
            </div>
          </div>
          
              <input type="hidden" class="form-control" name="jumlah_opname" id="jumlah_opname" placeholder="jumlah_opname" value="<?php 
                if(set_value('jumlah_opname')=="" && isset($jumlahtidakdipakaiterakhir)){
                  echo $jumlahtidakdipakaiterakhir;
                }else{
                  echo  set_value('jumlah_opname');
                }
                ?>" readonly="readonly">
          
            <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis_opname" id="id_mst_inv_barang_habispakai_jenis_opname" placeholder="jumlah_opname" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis_opname')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis_opname');
                }
                ?>" readonly="readonly">
                <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_opname" id="id_mst_inv_barang_habispakai_opname" placeholder="jumlah_opname" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_opname')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_opname');
                }
                ?>" readonly="readonly">
          
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Pemusnahan</div>
            <div class="col-md-8">
              <input type="hidden" class="form-control" name="jumlah_opnameopname" id="jumlah_opnameopname" placeholder="jumlah_opname Opname" value="<?php 
                if(set_value('jumlah_opnameopname')=="" && isset($jumlahtidakdipakaiterakhir)){
                  echo $jumlahtidakdipakaiterakhir;
                }else{
                  echo  set_value('jumlah_opnameopname');
                }
                ?>">
                <input type="hidden" class="form-control" name="jml_awalopname" id="jml_awalopname" placeholder="jumlah_opname Opname" value="<?php 
                if(set_value('jml_awalopname')=="" && isset($jml_awalopname)){
                  echo $jml_awalopname;
                }else{
                  echo  set_value('jumlah_opnameopname');
                }
                ?>">
                 <input type="number" class="form-control" name="jumlah_opnamemusnah" id="jumlah_opnamemusnah" placeholder="jumlah_opname Pemusnahan" value="<?php 
                if(set_value('jumlah_opnamemusnah')=="" && isset($jumlahtidakdipakaiterakhir)){
                  echo $jumlahtidakdipakaiterakhir;
                }else{
                  echo  set_value('jumlah_opnamemusnah');
                }
                ?>">
            </div>
          </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close-close-opname" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>