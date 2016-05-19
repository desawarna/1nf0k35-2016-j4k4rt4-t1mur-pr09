<section class="content">
<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($alert_form!=""){ ?>
  <div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h4>  <i class="icon fa fa-check"></i> Information!</h4>
    <?php echo $alert_form?>
  </div>
<?php } ?>
<div class="row">
  <form action="" method="post" id="form-ss">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kode Lokasi</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="kode_distribusi_" id="kode_distribusi_" placeholder="Kode Lokasi" readonly>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Retur</div>
          <div class="col-md-8">
            <div id='tgl_opname' name="tgl_opname" value="<?php
              echo (set_value('tgl_opname')!="") ? date("Y-m-d",strtotime(set_value('tgl_opname'))) : "";
            ?>"></div>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Retur</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="nomor_opname" id="nomor_opname" autocomplete="off" placeholder="Nomor Dokumen" value="<?php 
                if(set_value('nomor_opname')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nama Penanggung Jawab</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="penerima_nama" id="penerima_nama" placeholder="Nama Penanggung Jawab" value="<?php 
                if(set_value('penerima_nama')=="" && isset($penerima_nama)){
                  echo $penerima_nama;
                }else{
                  echo  set_value('penerima_nama');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">NIP Penanggung Jawab</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="penerima_nip" id="penerima_nip" placeholder="NIP Penanggung Jawab" value="<?php 
                if(set_value('penerima_nip')=="" && isset($penerima_nip)){
                  echo $penerima_nip;
                }else{
                  echo  set_value('penerima_nip');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Catatan</div>
          <div class="col-md-8">
          <textarea class="form-control" name="catatan" id="catatan" placeholder="Catatan / Keperluan"><?php 
              if(set_value('catatan')=="" && isset($catatan)){
                echo $catatan;
              }else{
                echo  set_value('catatan');
              }
              ?></textarea>
              <input type="hidden" id="last_opname" name="last_opname" />
          </div>  
        </div>        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <select  name="codepus" id="puskesmas" name="puskesmas" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == set_value('codepus') ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

      </div>
    </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">

      <div class="box-body">
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Faktur</div>
          <div class="col-md-8">
            <div id='tgl_faktur' name="tgl_faktur" value="<?php
              echo ($tgl_kwitansi!="") ? date("Y-m-d",strtotime($tgl_kwitansi)) : "";
            ?>"></div>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Faktur</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="nomor_kwitansi" id="nomor_kwitansi" placeholder="Nomor Faktur" value="<?php 
                if(set_value('nomor_kwitansi')=="" && isset($nomor_kwitansi)){
                  echo $nomor_kwitansi;
                }else{
                  echo  set_value('nomor_kwitansi');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Instansi / PBF</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="instansi" id="instansi" placeholder="Instansi / PBF" value="<?php 
                if(set_value('instansi')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('instansi');
                }
                ?>">
                <input type="hidden" disabled class="form-control" name="id_instansi" id="id_instansi" placeholder="Instansi / PBF" value="<?php 
                if(set_value('id_instansi')=="" && isset($mst_inv_pbf_code)){
                  echo $mst_inv_pbf_code;
                }else{
                  echo  set_value('id_instansi');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nama Barang</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="uraian" id="uraian" placeholder="Nama Barang" value="<?php 
                if(set_value('uraian')=="" && isset($uraian)){
                  echo $uraian;
                }else{
                  echo  set_value('uraian');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Merek Barang</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="merek_tipe" id="merek_tipe" placeholder="Merek Barang" value="<?php 
                if(set_value('merek_tipe')=="" && isset($merek_tipe)){
                  echo $merek_tipe;
                }else{
                  echo  set_value('merek_tipe');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Batch</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="batch" id="batch" placeholder="Batch" value="<?php 
                if(set_value('batch')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Total Penerimaan</div>
          <div class="col-md-8">
            <input type="number" disabled class="form-control" name="total_penerimaan" id="total_penerimaan" placeholder="Total Penerimaan" value="<?php 
                if(set_value('total_penerimaan')=="" && isset($total_penerimaan)){
                  echo $total_penerimaan;
                }else{
                  echo  set_value('total_penerimaan');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Jumlah Retur</div>
          <div class="col-md-8">
            <input type="hidden" class="form-control" name="jml_rusakakhir_simpan" id="jml_rusakakhir_simpan" placeholder="Jumlah Retur" value="<?php 
                if(set_value('jml_rusakakhir_simpan')=="" && isset($jml_rusakakhir)){
                  echo $jml_rusakakhir;
                }else{
                  echo  set_value('jml_rusakakhir_simpan');
                }
                ?>">
                <input type="hidden" class="form-control" name="jml_rusaktotal" id="jml_rusaktotal" placeholder="Jumlah Retur" value="<?php 
                if(set_value('jml_rusaktotal')=="" && isset($jml_rusakakhir)){
                  echo $jml_rusakakhir;
                }else{
                  echo  set_value('jml_rusaktotal');
                }
                ?>">
                <input type="hidden" class="form-control" name="jml_awalopname" id="jml_awalopname" placeholder="Jumlah Retur" value="<?php 
                if(set_value('jml_awalopname')=="" && isset($jml_awalopname)){
                  echo $jml_awalopname;
                }else{
                  echo  set_value('jml_rusakakhir_simpan');
                }
                ?>">
                <input type="number" class="form-control" name="jml_rusakakhir" id="jml_rusakakhir" placeholder="Jumlah Retur" value="<?php 
                if(set_value('jml_rusakakhir')=="" && isset($jml_rusakakhir)){
                  echo $jml_rusakakhir;
                }else{
                  echo  set_value('jml_rusakakhir');
                }
                ?>">
                 <input type="hidden" class="form-control" name="hargaterakhir" id="hargaterakhir" placeholder="Jumlah Retur" value="<?php 
                if(set_value('hargaterakhir')=="" && isset($hargaterakhir)){
                  echo $hargaterakhir;
                }else{
                  echo  set_value('hargaterakhir');
                }
                ?>">
                 <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis" id="id_mst_inv_barang_habispakai_jenis" placeholder="Jumlah Retur" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis');
                }
                ?>">
          </div>
        </div>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan </button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
    </form>        

  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script type="text/javascript">
 var jmlasli = "<?php echo $jml_rusakakhir; ?>";
 var opname =  "<?php echo $jml_awalopname; ?>";
 $("#jml_awalopname").val(opname);
      $("#jml_rusakakhir_simpan").val(jmlasli - $("#jml_rusakakhir").val());
      $("#jml_rusakakhir").change(function(){
          if ($("#jml_rusakakhir").val() < 1) {
            alert('Maaf, Jumlah retur tidak boleh minus');
            $("#jml_rusakakhir").val(jmlasli);
            $("#jml_rusakakhir_simpan").val(jmlasli);
          }
          if (parseInt($("#jml_rusakakhir").val()) > parseInt(jmlasli)) {
            alert('Maaf, Jumlah retur tidak boleh lebih dari '+ jmlasli);
            $("#jml_rusakakhir").val(jmlasli);
            $("#jml_rusakakhir_simpan").val(jmlasli);
          }
          $("#jml_rusakakhir_simpan").val(jmlasli- $(this).val());
      });
$(function(){
  cekopname({jenis});
    $('#form-ss').submit(function(){
      var tgllast = $('#last_opname').val().split('-');
      var tglopn = $('#tgl_opname').val().split('-');
      if ( (tgllast[2]+'-'+tgllast[1]+'-'+tgllast[0]) >=  (tglopn[2]+'-'+tglopn[1]+'-'+tglopn[0])) {
        alert("Maaf! Barang ini sudah di musnahkan atau di retur pada "+$('#last_opname').val()+','+'\n'+"Silahkan ganti ke tanggal berikutnya");
      alert($('#last_opname').val());
      }else{
            var data = new FormData();
            data.append('kode_distribusi_', $('#kode_distribusi_').val());
            data.append('tgl_opname', $('#tgl_opname').val());
            data.append('nomor_opname', $('#nomor_opname').val());
            data.append('puskesmas', $('#puskesmas').val());
            data.append('jml_rusaktotal', $('#jml_rusaktotal').val());
            data.append('penerima_nama', $('#penerima_nama').val());
            data.append('penerima_nip', $('#penerima_nip').val());
            data.append('catatan', $('#catatan').val());
            data.append('instansi', $('#instansi').val());
            data.append('uraian', $('#uraian').val());
            data.append('batch', $('#batch').val());
            data.append('id_mst_inv_barang_habispakai_jenis', $('#id_mst_inv_barang_habispakai_jenis').val());
            data.append('jml_awalopname', $('#jml_awalopname').val());
            data.append('total_penerimaan', $('#total_penerimaan').val());
            data.append('jml_rusakakhir', $('#jml_rusakakhir').val());
            data.append('jml_rusakakhir_simpan', $('#jml_rusakakhir_simpan').val());
            data.append('id_instansi', $('#id_instansi').val());
            data.append('id_uraian', "<?php echo $barang;?>");
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : "<?php echo base_url()?>inventory/bhp_retur/{action}_retur/{jenis}/{barang}/{batch}",
                data : data,
                success : function(response){
                  $('#content1').html(response);
                }
            });
      }
            return false;
        });
    kodedistribusi();
    $('#btn-kembali').click(function(){
       $.ajax({
          url : '<?php echo site_url('inventory/bhp_retur/daftar_retur/') ?>',
          type : 'POST',
          success : function(data) {
              $('#content1').html(data);
          }
      });

      return false;
    });

    $("#tgl_faktur").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px', disabled:true});
    $("#tgl_opname").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    $("#tgl_opname").change(function() {
        kodedistribusi($("#tgl_opname").val());
        
    });
    function cekopname(bhp){
     
      $.ajax({
          url : "<?php echo base_url().'inventory/bhp_retur/lastopname/';?>"+bhp,
          success : function(data) {
             tglop = data.split('-');
              $("#last_opname").val(tglop[2]+'-'+tglop[1]+'-'+tglop[0]);
          }
      });

      return false;
    }
    $("#puskesmas").change(function(){
      kodedistribusi();
    });
    function kodedistribusi(tahun)
    {
      if (tahun==null) {
        var tahun = <?php echo date("y");?>;  
      }else{
        var tahun = tahun.substr(-2);
      }
      
      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_retur/kodedistribusi/';?>"+$("#puskesmas").val(),
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
          var lokasi = elemet.kodeinv.split(".")
          $("#kode_distribusi_").val(lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]);
        });
      }
      });

      return false;
    }
    $("#penerima_nama").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_retur/autocomplite_nama/'; ?>',
      focus: function( event, ui ) {
        $("#penerima_nama" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#penerima_nama").val( ui.item.value );
 
        return false;
      } 
    });
    $("#penerima_nip").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_retur/autocomplite_nip/'; ?>',
      focus: function( event, ui ) {
        $("#penerima_nip" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#penerima_nip").val( ui.item.value );
        return false;
      } 
    });
  });
</script>
  