</style>
<script type="text/javascript">
    $(function(){
    
      $('#btn-close').click(function(){
        close_popup();
      });

      /*$('#code_mst_inv_barang').change(function(){
          var code = $(this).val();
          $.ajax({
            url : '<?php echo base_url().'inventory/permohonanbarang/get_nama' ?>',
            type : 'POST',
            data : 'code=' + code,
            success : function(data) {
              $('input[name="nama_barang"]').val(data);
            }
          });

          return false;
        });*/ 
             
        $('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();
            data.append('id_inv_permohonan_barang', $('input[name="id_inv_permohonan_barang"]').val());
            data.append('jumlah', $('input[name="jumlah"]').val());
            data.append('nama_barang', $('input[name="nama_barang"]').val());
            data.append('code_mst_inv_barang', $('#v_kode_barang').val());
            data.append('jqxinput', $('#jqxinput').val());
            data.append('harga', $('#harga').val());
            data.append('merk_tipe', $('#merk_tipe').val());
            data.append('rekening', $('#rekening').val());
            data.append('pilihan_satuan_barang', $('#pilihan_satuan_barang').val());
            data.append('keterangan', $('#keterangan').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/permohonanbarang/".$action."_barang/".$kode."/".$code_cl_phc."/".$id_inv_permohonan_barang_item ?>',
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

        $("#jqxinput").jqxInput(
          {
          placeHolder: " Ketik Kode atau Nama Barang ",
          theme: 'classic',
          width: '100%',
          height: '30px',
          minLength: 2,
          source: function (query, response) {
            var dataAdapter = new $.jqx.dataAdapter
            (
              {
                datatype: "json",
                  datafields: [
                  { name: 'uraian', type: 'string'},
                  { name: 'code', type: 'string'},
                  { name: 'code_tampil', type: 'string'}
                ],
                url: '<?php echo base_url().'inventory/permohonanbarang/autocomplite_barang'; ?>'
              },
              {
                autoBind: true,
                formatData: function (data) {
                  data.query = query;
                  return data;
                },
                loadComplete: function (data) {
                  if (data.length > 0) {
                    response($.map(data, function (item) {
                      return item.code_tampil +' | '+item.uraian;
                    }));
                  }
                }
              });
          }
        });

        $("#jqxinput").select(function(){
            var codebarang = $(this).val();
            var res = codebarang.split(" | ");
            $("#v_nama_barang").val(res[1]);
            $("#v_kode_barang").val(res[0].replace(/\./g,""));
           
        });

        $("#harga").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            if((jumlah=="")||jumlah=="0"){
              var subtotal = harga;
            }else{
              var subtotal = jumlah*harga;  
            }
            document.getElementById("subtotal").value = toRp(subtotal);
        });
        $("#jumlah").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            var subtotal =jumlah*harga;
            document.getElementById("subtotal").value = toRp(subtotal);
        });
            document.getElementById("subtotal").value = document.getElementById("jumlah").value * document.getElementById("harga").value;
    });
    function toRp(a,b,c,d,e){
      e=function(f){return f.split('').reverse().join('')};b=e(parseInt(a,10).toString());
      for(c=0,d='';c<b.length;c++){
        d+=b[c];if((c+1)%3===0&&c!==(b.length-1)){d+='.';}
      }
      return'Rp.\t'+e(d)+',00'
    }
    
    
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
            <div class="form-group">
              <label>Pilih Jenis Barang</label>
              <input id="jqxinput" class="form-control" autocomplete="off" name="jqxinput" type="text" value="<?php 
                if(set_value('jqxinput')=="" && isset($code_mst_inv_barang)){
                  $s = array();
                  $s[0] = substr($code_mst_inv_barang, 0,2);
                  $s[1] = substr($code_mst_inv_barang, 2,2);
                  $s[2] = substr($code_mst_inv_barang, 4,2);
                  $s[3] = substr($code_mst_inv_barang, 6,2);
                  $s[4] = substr($code_mst_inv_barang, 8,2);
                  echo implode(".", $s).' | '.$nama_barang;
                }else{
                  echo  set_value('jqxinput');
                }
                ?>" <?php if(isset($disable)){if($disable='disable'){echo "readonly";}} ?>/>
              <input id="v_kode_barang" class="form-control" name="code_mst_inv_barang" type="hidden" value="<?php 
                if(set_value('code_mst_inv_barang')=="" && isset($code_mst_inv_barang)){
                  echo $code_mst_inv_barang;
                }else{
                  echo  set_value('code_mst_inv_barang');
                }
                ?>" />
              <!--<input type="text" class="form-control" id="code_mst_inv_barang" name="code_mst_inv_barang"> 
                  <select  name="code_mst_inv_barang" id="code_mst_inv_barang" class="form-control">
                  <option value=""
                  </option>
                  <?php /*foreach($kodebarang as $barang) : ?>
                    <?php 
                    if(isset($code_mst_inv_barang) && $code_mst_inv_barang==$barang->code){
                      $select = $barang->code == $code_mst_inv_barang ? 'selected' : '';
                    }elseif(set_value('code_mst_inv_barang')!=""){
                      $select = $barang->code == set_value('code_mst_inv_barang') ? 'selected' : '';
                    }else{
                      $select ='';
                    } 
                    ?>
                    <option value="<?php echo $barang->code ?>" <?php echo $select ?>><?php echo $barang->code.' - '.$barang->uraian ?></option>
                  <?php endforeach */?>
              </select>-->
            </div>
            <div class="form-group">
              <label>Nama Barang</label>
              <input type="text" class="autocomplete form-control" id="v_nama_barang" name="nama_barang"  placeholder="Nama Barang" value="<?php
              if(set_value('nama_barang')=="" && isset($nama_barang)){
                  echo $nama_barang;
                }else{
                  echo  set_value('nama_barang');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Jumlah</label>
              <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jumlah)){
                  echo $jumlah;
                }else{
                  echo  set_value('jumlah');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Satuan</label>
              <select  name="pilihan_satuan_barang" type="text" class="form-control" id="pilihan_satuan_barang">
                <option value="">Pilih Satuan Barang</option>
                </option>
                <?php foreach($pilihan_satuan_barang_ as $barang) : ?>
                  <?php $select = $barang->code == $pilihan_satuan_barang ? 'selected' : '' ?>
                  <option value="<?php echo $barang->code ?>" <?php echo $select ?>><?php echo $barang->value ?></option>
                <?php endforeach ?>
            </select>
            </div>
            <div class="form-group">
              <label>Harga</label>
              <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" value="<?php 
                if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Total harga</label>
              <input type="text" class="form-control" name="subtotal" id="subtotal" disabled>
            </div>
            <div class="form-group">
              <label>Merek Tipe</label>
              <input type="text" class="form-control" name="merk_tipe" id="merk_tipe" placeholder="Merek Tipe" value="<?php 
                if(set_value('merk_tipe')=="" && isset($merk_tipe)){
                  echo $merk_tipe;
                }else{
                  echo  set_value('merk_tipe');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Rekening</label>
              <input type="text" class="form-control" name="rekening" id="rekening" placeholder="Rekening" value="<?php 
                if(set_value('rekening')=="" && isset($rekening)){
                  echo $rekening;
                }else{
                  echo  set_value('rekening');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"><?php 
                  if(set_value('keterangan')=="" && isset($keterangan)){
                    echo $keterangan;
                  }else{
                    echo  set_value('keterangan');
                  }
                  ?></textarea>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
