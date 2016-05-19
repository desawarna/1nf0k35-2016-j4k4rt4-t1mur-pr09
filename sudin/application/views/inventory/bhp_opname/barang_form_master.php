
<script type="text/javascript">

  $(function(){
    <?php 
    if (isset($obat)) {
      if ($obat=="8") {
    ?>
      $("[name='tgl_kadaluarsa_master']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    <?php
      }else{}
    }
    ?>
      $('#btn-close-master').click(function(){
        close_popup_master();
      }); 

      $('#form-ss_master').submit(function(){
        if ($('#jumlah_master').val()==$('#jumlah_masteropname').val()) {
          alert('Data jumlah tidak boleh sama dengan data opname')
        }else{
          var data = new FormData();
          $('#notice-content_master').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice_master').show();
          data.append('id_mst_inv_barang_habispakai_jenis_master', $('#id_mst_inv_barang_habispakai_jenis_master').val());
          data.append('id_inv_inventaris_habispakai_opname_master', $('#id_inv_inventaris_habispakai_opname_master').val());
          data.append('id_mst_inv_barang_habispakai_master', $('#id_mst_inv_barang_habispakai_master').val());
          data.append('batch_master', $('#batch_master').val());
          data.append('uraian_master', $('#uraian_master').val());
          data.append('jumlah_awal_opname', $('#jumlah_awal_opname').val());
          data.append('harga_master', $('#harga_master').val());
          data.append('jumlah_masteropname', $('#jumlah_masteropname').val());
          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_opname/".$action."_barang_opnamemaster/{kode}/{jenis_master}" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice_master').hide();
                    $('#notice-content_master').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice_master').show();
                    $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
                    close_popup_master();
                }
                else if(res[0]=="Error"){
                    $('#notice_master').hide();
                    $('#notice-content_master').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice_master').show();
                }
                else{
                    $('#popup_content_master').html(response);
                }
            }
          });
          }
          return false;
      });
      
      $("#jumlah_masteropname").change(function(){
          if ($(this).val() < 0) {
            alert('Maaf, jumlah_master oname tidak boleh minus');
            $("#jumlah_masteropname").val($('#jumlah_awal_opname').val());
          }
          $('#selisih_master').val($(this).val()-$('#jumlah_awal_opname').val());
      });
      $('#selisih_master').val($("#jumlah_masteropname").val()-$('#jumlah_awal_opname').val());
      $("#uraian_master").autocomplete({
        minLength: 0,
        source:"<?php echo base_url().'inventory/bhp_opname/autocomplite_barang_master/'.$jenis_master; ?>",
        focus: function( event, ui ) {
          $("#uraian_master" ).val(ui.item.value);
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
          $("#uraian_master").val( ui.item.value );
          $("#id_mst_inv_barang_habispakai_master").val(ui.item.key);
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
          url: "<?php echo base_url().'inventory/bhp_opname/detail_master/' ?>"+data,
          dataType: "json",
          success:function(data)
          { 
            $.each(data,function(index,elemet){
              $("#harga_master").val(elemet.hargabarang);
            });
          }
          });

          return false;
      }
    });
</script>

<div style="padding:15px">
  <div id="notice_master" class="alert alert-success alert-dismissable" <?php if ($notice_master==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice-content_master">{notice}</div>
  </div>
  <div class="row">
    <?php echo form_open(current_url(), 'id="form-ss_master"') ?>
          <div class="box-body">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Barang</div>
            <div class="col-md-8">
            <input type="text" id="uraian_master"  name="uraian_master" class="form-control" placeholder="Nama Barang"  value="<?php 
                  if(set_value('uraian_master')=="" && isset($uraian)){
                    echo $uraian;
                  }else{
                    echo  set_value('uraian_master');
                  }
                ?>">
                <input type="hidden" class="form-control" name="id_inv_inventaris_habispakai_opname_master" id="id_inv_inventaris_habispakai_opname_master" placeholder="Nama Barang" value="<?php 
                if(set_value('id_inv_inventaris_habispakai_opname_master')=="" && isset($kode)){
                  echo $kode;
                }else{
                  echo  set_value('id_inv_inventaris_habispakai_opname_master');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <?php 
            if (isset($jenis_master)) {
              if ($jenis_master=="8") {
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor batch</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="batch_master" id="batch_master" placeholder="Nomor batch" value="<?php 
                if(set_value('batch_master')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch_master');
                }
                ?>">
            </div>
          </div>
          <?php
           # code...
              }else{

              }
            }
          ?>
           <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Harga</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="harga_master" id="harga_master" placeholder="harga" value="<?php 
                if(set_value('harga_master')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga_master');
                }
                ?>" >
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Awal</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlah_awal_opname" id="jumlah_awal_opname" placeholder="jumlah" value="<?php 
                if(set_value('jumlah_awal_opname')=="" && isset($jmlawal)){
                  echo $jmlawal;
                }else{
                  echo  set_value('jumlah_awal_opname');
                }
                ?>">
            </div>
            <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis_master" id="id_mst_inv_barang_habispakai_jenis_master" placeholder="jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis_master')=="" && isset($jenis_master)){
                  echo $jenis_master;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis_master');
                }
                ?>" readonly="readonly">
                <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_master" id="id_mst_inv_barang_habispakai_master" placeholder="jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_master')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_master');
                }
                ?>" readonly="readonly">
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Opname</div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="jumlah_masteropname" id="jumlah_masteropname" placeholder="jumlah Opname" value="<?php 
                if(set_value('jumlah_masteropname')=="" && isset($jmlawal)){
                  echo $jmlawal;
                }else{
                  echo  set_value('jumlah_masteropname');
                }
                ?>">
            </div>
          </div>
        <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Selisih </div>
            <div class="col-md-8">
              <input type="number" class="form-control" name="selisih_master" id="selisih_master" placeholder="Selisih Opname" value="<?php 
                if(set_value('selisih_master')=="" && isset($jmlawal) && isset($jml_akhir)){
                  echo $jmlawal-$jml_akhir;
                }else{
                  echo  set_value('selisih_master');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close-master" class="btn btn-warning">Batal</button>
        </div>
    </div>
</form>
</div>
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>