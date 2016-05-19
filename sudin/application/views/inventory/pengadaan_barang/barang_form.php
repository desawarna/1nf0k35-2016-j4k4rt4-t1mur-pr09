</style>
<?php
if(isset($disable)){if($disable='disable'){?>

<script type="text/javascript">
  $("#dateInput").jqxDateTimeInput({ width: '300px', height: '25px' });
</script>
<?php }} ?>
<script type="text/javascript">

function edit_barang(id_inventaris_barang,kodeproc){
    $("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
    $.get("<?php echo base_url().'inventory/pengadaanbarang/edit_barang/'.$kode.'/';?>"+id_inventaris_barang+'/'+kodeproc, function(data) {
      $("#popup_content").html(data);
    });
    $("#popup_barang").jqxWindow({
      theme: theme, resizable: false,
      width: 1000,
      height: 600,
      isModal: true, autoOpen: false, modalOpacity: 0.2
    });
    $("#popup_barang").jqxWindow('open');
  } 
  
  function toRp(a,b,c,d,e){
    e=function(f){return f.split('').reverse().join('')};b=e(parseInt(a,10).toString());
    for(c=0,d='';c<b.length;c++){
      d+=b[c];if((c+1)%3===0&&c!==(b.length-1)){d+='.';}
    }
    return'Rp.\t'+e(d)+',00'
  }
  function kodeinventaris(kode){
    if(kode==null){
      document.getElementById("v_kode_invetaris").value = document.getElementById("kode_inventaris_").value;
      document.getElementById("id_inventaris_barang").value ='';
    }else{
      document.getElementById("v_kode_invetaris").value = document.getElementById("kode_inventaris_").value;
      document.getElementById("id_inventaris_barang").value =kode;
    }
    
  }
  

    $(function(){
      kodeinventaris();
      $('#btn-close').click(function(){
        close_popup();
      }); 
        $('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();
            data.append('id_mst_inv_barang', $('#v_kode_barang').val());
            data.append('tanggal_diterima', $('#dateInput').val());
            data.append('nama_barang', $('#v_nama_barang').val());
            data.append('jumlah', $('#jumlah').val());
            data.append('harga', $('#harga').val());
            data.append('id_inventaris_barang', $('#v_kode_invetaris').val()+'.'+$('#id_inventaris_barang').val());
            //data.append('keterangan_pengadaan', $('#keterangan').val());
            var id_pengadaan_ = '<?php echo $kode; ?>'; 
            var id_barang_    = $('#v_kode_barang').val();
            var kd_proc_      = 0;
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/pengadaanbarang/".$action."_barang/".$kode."/".$code_cl_phc ?>',
                data : data,
                success : function(response){
                  var res  = response.split("|");
                  if(res[0]=="OK"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#notice').show();
                      $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
                      close_popup();
                      edit_barang(res[1],res[2]); 
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
            kodeinventaris(res[0]);
        });
        $("#harga").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            var subtotal =jumlah*harga;
            document.getElementById("subtotal").value = toRp(subtotal);
        });
        $("#jumlah").change(function(){
            var idbarang = $("#id_inventaris_barang").val().split(".");
            if ((idbarang[0]=='01')  || (idbarang[0]=='03')|| (idbarang[0]=='04')|| (idbarang[0]=='06')) {
              if ($("#jumlah").val() > 1) {
                alert('Maaf Jumlah KIB ini tidak boleh lebih dari satu');
                $("#jumlah").val(1);
              }
            }
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
            <div class="continer">
              <div class="row">           
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Kode Lokasi</label>
                    <input type="text" class="form-control" id="v_kode_invetaris" name="v_kode_invetaris"  placeholder="Kode Inventaris Barang" value="<?php
                    if(set_value('v_kode_invetaris')=="" && isset($id_inventaris_barang)){
                        echo $id_inventaris_barang;
                      }else{
                        echo  set_value('v_kode_invetaris');
                      }
                      ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" class="form-control" id="id_inventaris_barang" name="id_inventaris_barang"  placeholder="Kode Inventaris Barang" value="<?php
                    if(set_value('id_inventaris_barang')=="" && isset($id_inventaris_barang)){
                        echo $id_inventaris_barang;
                      }else{
                        echo  set_value('id_inventaris_barang');
                      }
                      ?>" readonly=''>
                  </div>
                </div>
              </div>  
            </div>
            <div class="form-group">
              <label>Jenis Barang</label>
              <input id="jqxinput" class="form-control" autocomplete="off" name="code_mst_inv" type="text" value="<?php 
                if(set_value('code_mst_inv')=="" && isset($id_mst_inv_barang)){
                  $s = array();
                  $s[0] = substr($id_mst_inv_barang, 0,2);
                  $s[1] = substr($id_mst_inv_barang, 2,2);
                  $s[2] = substr($id_mst_inv_barang, 4,2);
                  $s[3] = substr($id_mst_inv_barang, 6,2);
                  $s[4] = substr($id_mst_inv_barang, 8,2);
                  echo implode(".", $s).' | '.$nama_barang;
                }else{
                  echo  set_value('code_mst_inv');
                }
                ?>" <?php if(isset($disable)){if($disable='disable'){echo "readonly";}} ?>/>
              <input id="v_kode_barang" class="form-control" name="code_mst_inv_barang" type="hidden" value="<?php 
                if(set_value('code_mst_inv_barang')=="" && isset($id_mst_inv_barang)){
                  echo $id_mst_inv_barang;
                }else{
                  echo  set_value('code_mst_inv_barang');
                }
                ?>" />
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
              <label>Harga Satuan</label>
              <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga Satuan" value="<?php 
                if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Sub Total</label>
              <input type="text" class="form-control" name="subtotal"  id="subtotal" placeholder="Sub Total" readonly="" value="<?php
              if(set_value('subtotal')=="" && isset($harga)){
                  echo $jumlah*$harga;
                }else{
                  echo  set_value('subtotal');
                }
                ?>">
            </div>
            <?php if(isset($disable)){if($disable='disable'){?>
            <div class="form-group">
              <label>Tanggal</label>
              <div id='dateInput' name="tanggal_diterima" value="<?php
              echo (!empty($tanggal_diterima)) ? date("Y-m-d",strtotime($tanggal_diterima)) :  date("d-m-Y");
            ?>"></div>
            </div>
            <?php }} ?>
           <!-- <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"><?php 
               /*   if(set_value('keterangan')=="" && isset($keterangan_pengadaan)){
                    echo $keterangan_pengadaan;
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
