
<script type="text/javascript">

  $(function(){
   // alert($("#tgl_distribusi").val());
    var tgldis = $("#tgl_distribusi").val().split("-")
    $("#tanggal_distribusi").val(tgldis[2]+'-'+tgldis[1]+'-'+tgldis[0]);
    <?php 
    if (isset($obat)) {
      if ($obat=="8") {
    ?>
      $("[name='tgl_kadaluarsa']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    <?php
      }else{}
    }
    ?>
      $('#btn-close').click(function(){
        close_popup();
      }); 

      $('#form-ss').submit(function(){
          var data = new FormData();
          $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice').show();
          data.append('id_mst_inv_barang_habispakai_jenis', $('#id_mst_inv_barang_habispakai_jenis').val());
          data.append('batch', $('#batch').val());
          data.append('uraian', $('#uraian').val());
          data.append('jumlah', $('#jumlah').val());
          data.append('batch', $('#batch').val());
          data.append('tgl_distribusi', $('#tanggal_distribusi').val());
          data.append('jumlahdistribusi', $('#jumlahdistribusi').val());
          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_distribusi/".$action."_distribusi/".$id_distribusi."/".$kode."/".$batch."/" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice').hide();
                    $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice').show();
                    $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
                    close_popup();
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

          return false;
      });
      $("#jumlahdistribusi").change(function(){
          var jmlasli = "<?php if(set_value('jumlah')=="" && isset($jumlah)){
                            echo $jumlah;
                          }else{
                            echo  set_value('jumlah');
                          } ?>";
          if(parseInt($("#jumlah").val()) < parseInt($("#jumlahdistribusi").val())){
              alert("Maaf, data distribusi tidak boleh melebihi data jumlah");
              $("#jumlah").val(jmlasli);
              $("#jumlahdistribusi").val(jmlasli);
          }
          if ( $(this).val() < 0) {
              $("#jumlahdistribusi").val(jmlasli);
              alert('Maaf data distribusi boleh kurang dari nol');
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
              <input type="text" class="form-control" name="uraian" id="uraian" placeholder="Nama Barang" value="<?php 
                if(set_value('uraian')=="" && isset($uraian)){
                  echo $uraian;
                }else{
                  echo  set_value('uraian');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jumlah)){
                  echo $jumlah;
                }else{
                  echo  set_value('jumlah');
                }
                ?>" readonly="readonly">
             <!--   <input type="hidden" class="form-control" name="tanggal_distribusi" id="tanggal_distribusi" placeholder="tgl" value="<?php /*
                if(set_value('tanggal_distribusi')=="" && isset($tgl_distribusi)){
                  echo $tgl_distribusi;
                }else{
                  echo  set_value('tanggal_distribusi');
                }*/
                ?>" readonly="readonly"> -->
            </div>
            <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis" id="id_mst_inv_barang_habispakai_jenis" placeholder="Jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis');
                }
                ?>" readonly="readonly">
          </div>
          <?php 
            if (isset($id_mst_inv_barang_habispakai_jenis)) {
              if ($id_mst_inv_barang_habispakai_jenis=="8") {
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Batch</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="batch" id="batch" placeholder="Nomor Batch" value="<?php 
                if(set_value('batch')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <?php
           # code...
              }else{

              }
            }
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Distribusi</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlahdistribusi" id="jumlahdistribusi" placeholder="Jumlah Rusak" value="<?php 
                if(set_value('jumlahdistribusi')=="" && isset($jumlah)){
                  echo $jumlah;
                }else{
                  echo  set_value('jumlahdistribusi');
                }
                ?>">
                <input type="hidden" class="form-control" name="tanggal_distribusi" id="tanggal_distribusi" placeholder="Jumlah Rusak" value="<?php 
                if(set_value('tanggal_distribusi')=="" ){
                  echo  set_value('tanggal_distribusi');
                }
                ?>">
            </div>
          </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>