
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
  
  function tambahmaster(){
    $("#popup_masterbarang #popup_mastercontent").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
    $.get("<?php echo base_url().'inventory/bhp_pengadaan/add_barang_master/'; ?>" , function(data) {
      $("#popup_mastercontent").html(data);
    });

    $("#popup_masterbarang").jqxWindow({
      theme: theme, resizable: false,
      width: 500,
      height: 500,
      isModal: true, autoOpen: false, modalOpacity: 0.2
    });
    $("#popup_masterbarang").jqxWindow('open');
  }

  $(function(){
    <?php 
    if (isset($obat)) {
      if ($obat=="8") {
    ?>
    var dates = new Date();
    var tahuns = dates.getFullYear()+1;
    var bulans = dates.getMonth(); 
    var haris  = dates.getDate();
      $("#tgl_kadaluarsa").jqxDateTimeInput({ width: '150px', height: '25px', formatString: 'dd-MM-yyyy', theme: theme, value: new Date(tahuns,bulans,haris) })
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
          data.append('jqxinput', $('#jqxinput').val());
          data.append('tanggal_diterima', $('#dateInput').val());
          data.append('tgl_kadaluarsa', $('#tgl_kadaluarsa').val());
          data.append('nama_barang', $('#v_nama_barang').val());
          data.append('jumlah', $('#jumlah').val());
          data.append('jml_rusak', $('#jml_rusak').val());
          data.append('batch', $('#batch').val());
          data.append('harga', $('#harga').val());
          data.append('obat', "<?php echo $obat;?>");
          data.append('subtotal', $('#subtotal').val());
          data.append('id_permohonan_barang', "<?php echo $kode;?>");
          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_pengadaan/".$action."_barang/".$kode."/".$obat."/" ?>',
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
      $("#jqxinput").autocomplete({
        minLength: 0,
        source:"<?php echo base_url().'inventory/bhp_pengadaan/autocomplite_barang/'.$obat; ?>",
        focus: function( event, ui ) {
          $("#jqxinput" ).val(ui.item.value);
          return false;
        },
        open: function(event, ui) {
                        $(".ui-autocomplete").css("position: absolute");
                        $(".ui-autocomplete").css("top: 0");
                        $(".ui-autocomplete").css("left: 0");
                        $(".ui-autocomplete").css("cursor: default");
                        $(".ui-autocomplete").css("z-index","999999");
                        $(".ui-autocomplete").css("font-weight : bold");
                    },
        select: function( event, ui ) {
          $("#jqxinput").val( ui.item.value );
          $("#id_mst_inv_barang").val(ui.item.key);
          deskripsi(ui.item.key);
          cekdata(ui.item.key);
          return false;
        }
      }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<a><b><font size=2>" + item.label + "</font></b><br><font size=1>" + item.satuan + "</font></a>" )
          .appendTo( ul );
      };
      function cekdata(key) {
        
      }
      function deskripsi(data){
        $.ajax({
          url: "<?php echo base_url().'inventory/bhp_pengadaan/deskripsi/' ?>"+data,
          dataType: "json",
          success:function(data)
          { 
            $.each(data,function(index,elemet){
              $("#harga").val(elemet.hargabarang);
            });
          }
          });

          return false;
      }

     /* $("#jqxinput").jqxInput(
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
                { name: 'harga_opname', type: 'string'},
                { name: 'harga_pembelian', type: 'string'},
                { name: 'tgl_opname', type: 'date'},
                { name: 'tgl_pembelian', type: 'date'},
              ],
              url: "<?php //echo base_url().'inventory/bhp_pengadaan/autocomplite_barang/'.$obat; ?>"
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
                    if ((item.tgl_pembelian!=null)||(item.tgl_opname!=null)) {
                      if(item.tgl_opname==null){
                        tgl_opname = 0;
                      }else{
                        tgl_opname = Date.parse(item.tgl_opname);
                      }

                      if (item.tgl_pembelian==null) {
                        tgl_pembelian = 0
                      }else{
                        tgl_pembelian = Date.parse(item.tgl_pembelian);
                      }
                      if( tgl_pembelian>= tgl_opname){
                        var hargabarang = item.harga_pembelian;  
                      }else{
                        var hargabarang = item.harga_opname;  
                      }
                    }else{
                      if (item.harga==null) {
                        var hargaasli =0;
                      }else{
                        var hargaasli =item.harga;
                      }

                      var hargabarang = hargaasli;
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
      });*/
      $("#harga").change(function(){
          var jumlah = document.getElementById("jumlah").value;
          var harga = document.getElementById("harga").value;
          var subtotal =jumlah*harga;
          document.getElementById("subtotal").value = toRp(subtotal);
          if ( $(this).val() < 0) {
              $("#harga").val(0);
              alert('Maaf data harga boleh kurang dari nol');
          }
      });
      $("#jml_rusak").change(function(){
          if ( parseInt($("#jml_rusak").val()) > parseInt($("#jumlah").val())) {
            alert("Maaf, Data jumlah rusak tidak boleh lebih besar dari data jumlah");
            $("#jml_rusak").val('0')
          }
          if ( $(this).val() < 0) {
              $("#jml_rusak").val(0);
              alert('Maaf data jumlah rusak tidak boleh kurang dari nol');
          }
      });
      $("#jumlah").change(function(){
          var jumlah = document.getElementById("jumlah").value;
          var harga = document.getElementById("harga").value;
          var subtotal =jumlah*harga;
          document.getElementById("subtotal").value = toRp(subtotal);
          if ( $(this).val() < 0) {
              $("#jumlah").val(0);
              alert('Maaf data jumlah tidak boleh kurang dari nol');
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
          <div class="row">
          <div class="col-md-9">
            <div class="form-group">
              <label>Nama Barang</label>
              <input id="jqxinput" class="form-control" autocomplete="off" name="jqxinput" type="text" value="<?php 
                if(set_value('jqxinput')=="" && isset($id_mst_inv_barang_habispakai)){ 
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('jqxinput');
                }
                ?>" <?php if(isset($disable)){if($disable='disable'){echo "readonly";}} ?>/>
            </div>
          </div>
          <div class="col-md-3" style="padding-top:20px;">
            <button type="button" class="btn btn-success" id="btn-refresh" onclick="tambahmaster()"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
          </div>
          </div>
              <input id="id_mst_inv_barang" class="form-control" name="id_mst_inv_barang" type="hidden" value="<?php 
                if(set_value('id_mst_inv_barang')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang');
                }
                ?>" />

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jumlah)){
                  echo $jumlah;
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
            <div class="col-md-4" style="padding: 5px">Sub Total</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="subtotal"  id="subtotal" placeholder="Sub Total" readonly="" value="<?php
              if(set_value('subtotal')=="" && isset($harga)){
                  echo $jumlah*$harga;
                }else{
                  echo  set_value('subtotal');
                }
                ?>">
            </div>
          </div>
          <?php 
            if (isset($obat)) {
              if ($obat=="8") {
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
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>