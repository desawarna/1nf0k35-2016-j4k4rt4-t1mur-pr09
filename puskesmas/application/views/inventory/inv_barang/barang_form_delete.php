<script type="text/javascript">
  function close_popup2(){
      $("#popup_barang").jqxWindow('close');
      $("#popup_barang2").jqxWindow('close');
      $("#inventaris_").click();
  }

  function submit(){
      var data = new FormData();
      $('#notice-content_delete').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
      $('#notice_delete').show();

      data.append('alasan_penghapusan', $("[name='alasan_penghapusan']").val());

      $.ajax({
          cache : false,
          contentType : false,
          processData : false,
          type : 'POST',
          url : '<?php echo base_url()."inventory/inv_barang/hapus_barang/".$id_barang."/".$kd_proc."/".$kd_inventaris ?>',
          data : data,
          success : function(response){
          
            var res  = response.split("|");
            if(res[0]=="OK"){
                $('#notice_delete').hide();
                $('#notice-content_delete').html('<div class="alert">'+res[1]+'</div>');
                $('#notice_delete').show();
                filter_jqxgrid_inv_barang();
                close_popup2();
            }
            else{
                $('#notice_delete').hide();
                $('#notice-content_delete').html('<div class="alert">Alasan hapus barang harus isi</div>');
                $('#notice_delete').show();
            }
        }
      });

      return false;    
  }
</script>

<div style="padding:15px">
  <div id="notice_delete" class="alert alert-success alert-dismissable" <?php if ($notice==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice-content_delete">{notice}</div>
  </div>
	<div class="row">
    <?php echo form_open(current_url(), 'id="form-hapus"') ?>
    <div class="col-md-12">
    <div class="box box-primary">
          <div class="box-body">
            <div class="form-group"> 
              <label>Kode Barang : <?php 
                if(set_value('code_mst_inv_barang')=="" && isset($id_mst_inv_barang)){
                  echo substr(chunk_split($id_mst_inv_barang, 2, '.'),0,14);
                }
                ?></label>
            </div>            
            <div class="form-group"> 
              <label>Register : <?php 
                if(set_value('register')=="" && isset($register)){
                  echo $register;
                }
                ?></label>
            </div>
            <div class="form-group">
              <label>
              <?php
              if(set_value('nama_barang')=="" && isset($nama_barang)){
                  echo $nama_barang;
                }
                ?></label>
            </div>
            <div class="form-group">
              <label>Alasan Hapus Barang : </label>
              <textarea class="form-control" id="alasan_penghapusan" name="alasan_penghapusan" placeholder="Keterangan "><?php 
                  if(set_value('alasan_penghapusan')=="" && isset($alasan_penghapusan)){
                    echo $alasan_penghapusan;
                  }else{
                    echo  set_value('alasan_penghapusan');
                  }
                  ?></textarea>
            </div>
        </div>
      </div>
    </div>  
    </div>
        <div class="box-footer">
            <button type="button" onClick="submit()" class="btn btn-primary">Hapus</button>
            <button type="button" onClick="close_popup2()" class="btn btn-warning">Batal</button>
        </div>
    </div>
    </div>
    </div>
</form>
</div>
