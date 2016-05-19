</style>
<?php
if(isset($disable)){if($disable='disable'){?>

<script type="text/javascript">
  $("#dateInput").jqxDateTimeInput({ width: '300px', height: '25px' });
</script>
<?php }} ?>
<script type="text/javascript">  
    $(function(){
      $(document).ready(function() {
          $('#tblkondisi').DataTable();
      } );
      $('#btn-close').click(function(){
        close_popup();
      }); 
        $('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();
            data.append('id_mst_inv_barang_habispakai', $('#kode').val());
            data.append('jml', $('#stok').val());
            data.append('harga', $('#harga').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/bhp_kondisi/".$action."_barang/".$kode ?>',
                data : data,
                success : function(response){
                  var res  = response.split("|");
                  if(res[0]=="OK"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[2]+'</div>');
                      $('#notice').show();
                      $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                  }
                  else if(res[0]=="Error"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[2]+'</div>');
                      $('#notice').show();
                  }
                  else{
                      $('#popup_content').html(response);
                  }
              }
            });

            return false;
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
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Barang</label>
                  <div><?php echo $uraian;?></div>
                  <input type="hidden" class="form-control" name="kode" id="kode" placeholder="Kode" value="<?php 
                    if(set_value('kode')=="" && isset($kode)){
                      echo $kode;
                    }else{
                      echo  set_value('kode');
                    }
                    ?>">
                </div>
              </div>
              <div class=col-md-6"">
                <div class="form-group">
                  <label>Satuan</label>
                  <div><?php echo $nama_satuan;?></div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Stok</label>
                <input type="number" class="form-control" name="stok" id="stok" placeholder="Jumlah" value="<?php 
                  if(set_value('stok')=="" && (isset($jml)||isset($totaljumlah)||isset($jmlpengeluaran))){
                      if($tgl_update==date("Y-m-d")){
                        echo $jmlstok = $jml;
                      }else{
                        echo $jmlstok = ($jml+$totaljumlah)-$jmlpengeluaran;  
                      }
                  }else{
                    echo  set_value('stok');
                  }
                  ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Harga</label>
                <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga Satuan" value="<?php 
                  if(set_value('harga')=="" && isset($harga)){
                    if((isset($tgl_pembelian))||(isset($tgl_opname))){
                      if ($tgl_pembelian >= $tgl_opname) {
                        echo $harga_pembelian;
                      }else{
                        echo $harga_opname;
                      }
                    }else{
                      echo $harga;
                    }
                  }else{
                    echo  set_value('harga');
                  }
                  ?>">
              </div>
            </div>
          </div>
          <div class=col-md-12"">
        <div class="box-footer" style="float:right;">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i>Simpan</button>
            <button type="button" id="btn-close" class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i>Tutup</button>
        </div>
        </div>
    </div>
</form>
</div>
<div class="timeline-messages" id="timeline-barang"></div>