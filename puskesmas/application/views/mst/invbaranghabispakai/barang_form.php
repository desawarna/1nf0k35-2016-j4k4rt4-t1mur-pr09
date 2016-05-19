</style>
<?php
if(isset($disable)){if($disable='disable'){?>

<script type="text/javascript">
  $("#dateInput").jqxDateTimeInput({ width: '300px', height: '25px' });
</script>
<?php }} ?>
<script type="text/javascript">

    $(function(){
      $('#btn-close').click(function(){
        close_popup();
      }); 
        $('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();
            data.append('id_mst_inv_barang_habispakai_jenis', $('#id_jenis').val());
            data.append('code', $('#code').val());
            data.append('uraian', $('#uraian_').val());
            data.append('merek_tipe', $('#merk').val());
            data.append('negara_asal', $('#negara').val());
            data.append('pilihan_satuan', $('#pilihan_satuan').val());
            data.append('harga', $('#harga').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."mst/invbaranghabispakai/".$action."_barang/".$kode."/" ?>',
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
              <input id="id_jenis" class="form-control" name="id_jenis" type="hidden" value="<?php 
               echo  $kode; ?>"/>
            <div class="form-group">
              <label>Kode</label>
              <input type="text" class="form-control" id="code" name="code"  placeholder="Kode" value="<?php
              if(set_value('code')=="" && isset($code)){
                  echo $code;
                }else{
                  echo  set_value('code');
                }
                ?>">
                <input type="hidden" class="form-control" id="id_mst_inv_barang_habispakai" name="id_mst_inv_barang_habispakai"  placeholder="Kode" value="<?php
                  if(set_value('id_mst_inv_barang_habispakai')=="" && isset($id_mst_inv_barang_habispakai)){
                      echo $id_mst_inv_barang_habispakai;
                    }else{
                      echo  set_value('id_mst_inv_barang_habispakai');
                    }
                ?>">
            </div>
            <div class="form-group">
              <label>Uraian</label>
              <input type="text" class="form-control" name="uraian_" id="uraian_" placeholder="Uraian" value="<?php 
                if(set_value('uraian_')=="" && isset($uraian)){
                  echo $uraian;
                }else{
                  echo  set_value('uraian_');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Merek Tipe</label>
              <input type="text" class="form-control" name="merk" id="merk" placeholder="Merek Tipe" value="<?php 
                if(set_value('merk')=="" && isset($merek_tipe)){
                  echo $merek_tipe;
                }else{
                  echo  set_value('merk');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Negara Asal</label>
              <input type="text" class="form-control" name="negara"  id="negara" placeholder="Negara Asal" value="<?php
              if(set_value('negara')=="" && isset($negara_asal)){
                  echo $negara_asal;
                }else{
                  echo  set_value('negara');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Satuan</label>
              <input type="text" class="form-control" name="pilihan_satuan"  id="pilihan_satuan" placeholder="Satuan Barang" value="<?php
              if(set_value('pilihan_satuan')=="" && isset($pilihan_satuan)){
                  echo $pilihan_satuan;
                }else{
                  echo  set_value('pilihan_satuan');
                }
                ?>">
            </div>       
            <div class="form-group">
              <label>Harga</label>
              <input type="number" class="form-control" name="harga"  id="harga" placeholder="Harga" value="<?php
              if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>">
            </div> 
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
