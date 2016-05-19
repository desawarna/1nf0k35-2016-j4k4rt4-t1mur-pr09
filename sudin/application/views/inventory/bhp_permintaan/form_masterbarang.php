
<script type="text/javascript">

    $(function(){
      $('#btn-close-master').click(function(){
        $("#popup_masterbarang").jqxWindow('close');
      }); 
        $('#form-ss-master').submit(function(){
            var datamaster = new FormData();
            $('#noticemaster-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#noticemaster').show();
            datamaster.append('id_mst_inv_barang_habispakai_jenis', $('#pilihan_jenis_barang_master').val());
            datamaster.append('code_master', $('#code_master').val());
            datamaster.append('uraian_master', $('#uraian_master').val());
            datamaster.append('merk_master', $('#merk_master').val());
            datamaster.append('negara_master', $('#negara_master').val());
            datamaster.append('pilihan_satuan_barang_master', $('#pilihan_satuan').val());
            datamaster.append('harga_master', $('#harga_master').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/bhp_permintaan/".$action."_barang_master/" ?>',
                data : datamaster,
                success : function(response){
                  var res  = response.split("|");
                  if(res[0]=="OK"){
                      $('#noticemastermaster').hide();
                      $('#noticemaster-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#noticemaster').show();
                      $("#popup_masterbarang").jqxWindow('close');
                  }
                  else if(res[0]=="Error"){
                      $('#noticemaster').hide();
                      $('#noticemaster-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#noticemaster').show();
                  }
                  else{
                      $('#popup_mastercontent').html(response);
                  }
              }
            });

            return false;
        });
        
    });
</script>

<div style="padding:15px">
  <div id="noticemaster" class="alert alert-success alert-dismissable" <?php if ($noticemaster==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="noticemaster-content">{noticemaster}</div>
  </div>
	<div class="row">
    <?php echo form_open(current_url(), 'id="form-ss-master"') ?>
          <div class="box-body">
            <div class="form-group">
              <label>Jenis Barang</label>
              <select id="pilihan_jenis_barang_master" name="pilihan_jenis_barang_master" class="form-control" >
                <?php foreach($pilihan_jenis_barang as $jenisbarang) : ?>
                  <option value="<?php echo $jenisbarang->id_mst_inv_barang_habispakai_jenis ?>"><?php echo $jenisbarang->uraian ?></option>
                <?php endforeach ?>
              </select>
            </div>     
            <div class="form-group">
              <label>Kode</label>
              <input type="text" class="form-control" id="code_master" name="code_master"  placeholder="Kode" value="<?php
              if(set_value('code_master')=="" && isset($code)){
                  echo $code;
                }else{
                  echo  set_value('code_master');
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
              <input type="text" class="form-control" name="uraian_master" id="uraian_master" placeholder="Uraian" value="<?php 
                if(set_value('uraian_master')=="" && isset($uraian)){
                  echo $uraian;
                }else{
                  echo  set_value('uraian_master');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Merek Tipe</label>
              <input type="text" class="form-control" name="merk_master" id="merk_master" placeholder="Merek Tipe" value="<?php 
                if(set_value('merk_master')=="" && isset($merek_tipe)){
                  echo $merek_tipe;
                }else{
                  echo  set_value('merk_master');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Negara Asal</label>
              <input type="text" class="form-control" name="negara_master"  id="negara_master" placeholder="Negara Asal" value="<?php
              if(set_value('negara_master')=="" && isset($negara_asal)){
                  echo $negara_asal;
                }else{
                  echo  set_value('negara_master');
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
              <input type="number" class="form-control" name="harga_master"  id="harga_master" placeholder="Harga" value="<?php
              if(set_value('harga_master')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga_master');
                }
                ?>">
            </div> 
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close-master" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
