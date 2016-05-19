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
    $.get("<?php echo base_url().'inventory/bhp_permintaan/add_barang_master/'; ?>" , function(data) {
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
      $('#btn-close').click(function(){
        close_popup();
      }); 

      $('#form-ss').submit(function(){
          var data = new FormData();
          $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice').show();
          data.append('id_mst_inv_barang', $('#id_mst_inv_barang').val());
          data.append('jqxinput', $('#jqxinput').val());
          data.append('jumlah', $('#jumlah').val());
          data.append('harga', $('#harga').val());
          data.append('subtotal', $('#subtotal').val());
          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_permintaan/".$action."_barang/".$kode."/".$obat."/" ?>',
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
        source:"<?php echo base_url().'inventory/bhp_permintaan/autocomplite_barang/'.$obat; ?>",
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
          return false;
        }
      }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<a><b><font size=2>" + item.label + "</font></b><br><font size=1>" + item.satuan + "</font></a>" )
          .appendTo( ul );
      };

      function deskripsi(data){
        $.ajax({
          url: "<?php echo base_url().'inventory/bhp_permintaan/deskripsi/' ?>"+data,
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
                if(set_value('jqxinput')=="" && isset($uraian)){ 
                  echo $uraian;
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
            <div class="col-md-4" style="padding: 5px">Sub Total</div>
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