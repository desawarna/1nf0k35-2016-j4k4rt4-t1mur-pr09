</style>
<?php
if(isset($disable)){if($disable='disable'){?>

<script type="text/javascript">
  $("#dateInput").jqxDateTimeInput({ width: '300px', height: '25px' });
</script>
<?php }} ?>
<script type="text/javascript">

  
  function toRp(a,b,c,d,e){
    e=function(f){return f.split('').reverse().join('')};b=e(parseInt(a,10).toString());
    for(c=0,d='';c<b.length;c++){
      d+=b[c];if((c+1)%3===0&&c!==(b.length-1)){d+='.';}
    }
    return'Rp.\t'+e(d)+',00'
  }
  

    $(function(){
      <?php 
    if (isset($id_mst_inv_barang_habispakai_jenis)) {
      if ($id_mst_inv_barang_habispakai_jenis=="8") {
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
            data.append('id_mst_inv_barang', $('#id_mst_inv_barang').val());
            data.append('tanggal_diterima', $('#dateInput').val());
             data.append('jqxinput', $('#jqxinput').val());
            data.append('nama_barang', $('#v_nama_barang').val());
            data.append('jumlah', $('#jumlah').val());
            data.append('harga', $('#harga').val());
            data.append('subtotal', $('#subtotal').val());
            data.append('tgl_kadaluarsa', $('#tgl_kadaluarsa').val());
            data.append('jml_rusak', $('#jml_rusak').val());
            data.append('batch', $('#batch').val());
            data.append('obat',  $('#obat').val());
            data.append('id_permohonan_barang', "<?php echo $kode;?>");
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/bhp_permintaan/".$action."_barang/".$obat."/".$kode."/" ?>',
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
          placeHolder: " Ketik Nama Barang ",
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
                  { name: 'id_mst_inv_barang_habispakai', type: 'string'},
                  { name: 'hargaterakhir', type: 'string'},
                  { name: 'harga', type: 'string'},
                ],
                url: '<?php echo base_url().'inventory/bhp_permintaan/autocomplite_barang'; ?>'
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
                      if (item.hargaterakhir==null) {
                        var hargabarang = item.harga;
                      }else{
                        var hargabarang = item.hargaterakhir;
                      }
                      return item.uraian+' | '+item.id_mst_inv_barang_habispakai+' | '+hargabarang;
                    }));
                  }
                }
              });
          }
        });
      
        $("#jqxinput").select(function(){
            var codebarang = $(this).val();
            var res = codebarang.split(" | ");
            $("#id_mst_inv_barang").val(res[1]);
            $("#harga").val(res[2]);
        });
        $("#harga").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            var subtotal =jumlah*harga;
            document.getElementById("subtotal").value = toRp(subtotal);
        });
        $("#jml_rusak").change(function(){
          if ( parseInt($("#jml_rusak").val()) > parseInt($("#jumlah").val())) {
            alert("Maaf, Data jumlah rusak tidak boleh lebih besar dari data jumlah");
            $("#jml_rusak").val("<?php
              if(set_value('jml_rusak')=="" && isset($jml_rusak)){
                  echo $jml_rusak;
                }else{
                  echo  set_value('jml_rusak');
                } ?>")
          }else{

          }
      });
        $("#jumlah").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            var subtotal =jumlah*harga;
            document.getElementById("subtotal").value = toRp(subtotal);
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
              <div class="col-md-4" style="padding: 5px">Nama Barang<?php echo $obat; ?></div>
              <div class="col-md-8">
              <input readonly="readonly" id="jqxinput" class="form-control" autocomplete="off" name="jqxinput" type="text" value="<?php 
                if(set_value('jqxinput')=="" && isset($uraian)){ 
                  echo $uraian;
                }else{
                  echo  set_value('jqxinput');
                }
                ?>"  <?php if(isset($disable)){if($disable='disable'){echo "readonly";}} ?>/>
              <input id="id_mst_inv_barang" class="form-control" name="id_mst_inv_barang" type="hidden" value="<?php 
                if(set_value('id_mst_inv_barang')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang');
                }
                ?>" />
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah</div>
            <div class="col-md-8">
              <input  type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jml)){
                  echo $jml;
                }else{
                  echo  set_value('jumlah');
                }
                ?>">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Harga Satuan</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga Satuan" value="<?php 
                if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Sub total</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="subtotal"  id="subtotal" placeholder="Sub Total" readonly="" value="<?php
              if(set_value('subtotal')=="" && isset($harga)){
                  echo $jml*$harga;
                }else{
                  echo  set_value('subtotal');
                }
                ?>">
            </div>
          </div>  
          <input type="hidden" name="obat" id="obat"  value="<?php 
            if(set_value('obat')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('obat');
                }
          ?>" />
             <?php //echo $id_mst_inv_barang_habispakai_jenis;
            if (isset($obat)) {
              if ($obat=="8") {
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Batch</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="batch" id="batch" placeholder="Nomor Batch" value="<?php 
                if(set_value('batch')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch');
                }
                ?>">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tanggal Kadaluarsa</div>
            <div class="col-md-8">
            <div id='tgl_kadaluarsa' name="tgl_kadaluarsa" value="<?php
                echo (set_value('tgl_kadaluarsa')!="") ? date("Y-m-d",strtotime(set_value('tgl_kadaluarsa'))) : "";
              ?>"></div>
            </div>
          </div>
           <?php
            # code...
              }else{

              }
            }
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Keadaan Rusak</div>
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
            <?php if(isset($disable)){if($disable='disable'){?>
            <div class="row" style="margin: 5px">
              <div class="col-md-4" style="padding: 5px">Tanggal Diterima</div>
              <div class="col-md-8">
              <div id='dateInput' name="tanggal_diterima" value="<?php
              echo (!empty($tanggal_diterima)) ? date("Y-m-d",strtotime($tanggal_diterima)) :  date("d-m-Y");
            ?>"></div>
            </div>
            </div>
            <?php }} ?>
           <!-- <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"><?php 
               /*   if(set_value('keterangan')=="" && isset($keterangan_permintaan)){
                    echo $keterangan_permintaan;
                  }else{
                    echo  set_value('keterangan');
                  }*/
                  ?></textarea>
            </div>-->
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
