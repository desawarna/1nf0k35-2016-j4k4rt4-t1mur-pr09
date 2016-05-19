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
  <!--<form action="" method="post" id="form-ss">-->
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kode Lokasi</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="kode_distribusi_retur" id="kode_distribusi_retur" placeholder="Kode Lokasi" readonly>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Retur</div>
          <div class="col-md-8">
            <div id='tgl_opname_retur' name="tgl_opname_retur" value="<?php
              echo ($tgl_opname!="") ? date("Y-m-d",strtotime($tgl_opname)) : "";
            ?>"></div>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Retur</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="nomor_opname_retur" id="nomor_opname_retur" placeholder="Nomor Dokumen" value="<?php 
                if(set_value('nomor_opname_retur')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname_retur');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Penanggung Jawab</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="penerima_nama_retur" id="penerima_nama_retur" placeholder="Nama Penanggung Jawab" value="<?php 
                if(set_value('penerima_nama_retur')=="" && isset($petugas_nama)){
                  echo $petugas_nama;
                }else{
                  echo  set_value('penerima_nama_retur');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">NIP Penanggung Jawab</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="penerima_nip_retur" id="penerima_nip_retur" placeholder="NIP Penanggung Jawab" value="<?php 
                if(set_value('penerima_nip_retur')=="" && isset($petugas_nip)){
                  echo $petugas_nip;
                }else{
                  echo  set_value('penerima_nip_retur');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">catatan_retur</div>
          <div class="col-md-8">
          <textarea class="form-control" disabled name="catatan_retur" id="catatan_retur" placeholder="catatan_retur / Keperluan"><?php 
              if(set_value('catatan_retur')=="" && isset($catatan)){
                echo $catatan;
              }else{
                echo  set_value('catatan_retur');
              }
              ?></textarea>
              <input type="hidden" id="last_opname_retur" name="last_opname_retur" />
          </div>  
        </div>        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">puskesmas_retur</div>
          <div class="col-md-8">
          <select  name="codepus" disabled id="puskesmas_retur" name="puskesmas_retur" class="form-control">
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
            <div id='tgl_faktur_retur' name="tgl_faktur_retur" value="<?php
              echo ($tgl_kwitansi!="") ? date("Y-m-d",strtotime($tgl_kwitansi)) : "";
            ?>"></div>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Faktur</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="nomor_opname_retur" id="nomor_opname_retur" placeholder="Nomor Faktur" value="<?php 
                if(set_value('nomor_opname_retur')=="" && isset($nomor_kwitansi)){
                  echo $nomor_kwitansi;
                }else{
                  echo  set_value('nomor_opname_retur');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Instansi / PBF</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="penerima_nama_retur" id="penerima_nama_retur" placeholder="Instansi / PBF" value="<?php 
                if(set_value('penerima_nama_retur')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('penerima_nama_retur');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nama Barang</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="penerima_nip_retur" id="penerima_nip_retur" placeholder="Nama Barang" value="<?php 
                if(set_value('penerima_nip_retur')=="" && isset($uraian)){
                  echo $uraian;
                }else{
                  echo  set_value('penerima_nip_retur');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Merek Barang</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="penerima_nip_retur" id="penerima_nip_retur" placeholder="Merek Barang" value="<?php 
                if(set_value('penerima_nip_retur')=="" && isset($merek_tipe)){
                  echo $merek_tipe;
                }else{
                  echo  set_value('penerima_nip_retur');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Batch</div>
          <div class="col-md-8">
            <input type="text" disabled class="form-control" name="penerima_nip_retur" id="penerima_nip_retur" placeholder="Batch" value="<?php 
                if(set_value('penerima_nip_retur')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('penerima_nip_retur');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Total Penerimaan</div>
          <div class="col-md-8">
            <input type="number" disabled class="form-control" name="penerima_nip_retur" id="penerima_nip_retur" placeholder="Total Penerimaan" value="<?php 
                if(set_value('penerima_nip_retur')=="" && isset($jml_awal)){
                  echo $jml_awal;
                }else{
                  echo  set_value('penerima_nip_retur');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Jumlah Retur</div>
          <div class="col-md-8">
            <input type="number" disabled class="form-control" name="penerima_nip_retur" id="penerima_nip_retur" placeholder="Jumlah Retur" value="<?php 
                if(set_value('penerima_nip_retur')=="" && isset($jml_akhir)){
                  echo $jml_awal - $jml_akhir;
                }else{
                  echo  set_value('penerima_nip_retur');
                }
                ?>">
          </div>
        </div>
      </div>
      <div class="box-footer">
        <?php if($tgl_opname >= $tgl_opnameterakhir){?>
        <button id="deletedata" class="btn btn-danger"><i class='fa fa-ban'></i> &nbsp; Batal & Hapus </button>
        <?php }?>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
    <!--</form>        -->

  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script type="text/javascript">

$(function(){
  $("#deletedata").click(function(){
      var confirms = confirm('Hapus Data ?');
      if (confirms==true) {
        $.get("<?php echo base_url().'inventory/bhp_retur/dodelpermohonan/'?>{id_inv_inventaris_habispakai_opname}",function(data){
            $("#content2").html(data);
        });
      }
  });
    /*$('#form-ss').submit(function(){
      if ($('#last_opname_retur').val() >= $('#tgl_opname_retur').val()) {
      alert("Maaf! Kategori barang "+$('#jenis_bhp').val()+" sudah di opname pada "+$('#last_opname_retur').val()+','+'\n'+"Silahkan ganti ke tanggal berikutnya");

      }else{
            var data = new FormData();
            data.append('kode_distribusi_retur', $('#kode_distribusi_retur').val());
            data.append('tgl_opname_retur', $('#tgl_opname_retur').val());
            data.append('nomor_opname_retur', $('#nomor_opname_retur').val());
            data.append('jenis_bhp', $('#jenis_bhp').val());
            data.append('puskesmas_retur', $('#puskesmas_retur').val());
            data.append('penerima_nama_retur', $('#penerima_nama_retur').val());
            data.append('penerima_nip_retur', $('#penerima_nip_retur').val());
            data.append('catatan_retur', $('#catatan_retur').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : "<?php echo base_url()?>inventory/bhp_retur/{action}_opname",
                data : data,
                success : function(response){
                  $('#addopname').html(response);
                }
            });
      }
            return false;
        });*/
    kodedistribusi();
    $('#btn-kembali').click(function(){
       $.ajax({
          url : '<?php echo site_url('inventory/bhp_retur/daftar_barangretur/') ?>',
          type : 'POST',
          success : function(data) {
              $('#content2').html(data);
          }
      });

      return false;
    });

    $("#tgl_faktur_retur").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px', disabled:true});
    $("#tgl_opname_retur").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px', disabled:true});
    $("#tgl_opname_retur").change(function() {
        kodedistribusi($("#tgl_opname_retur").val());
        
    });
    $("#jenis_bhp").change(function(){
        cekopname($('#tgl_opname_retur').val(),$(this).val());
    });
    function cekopname(tgl,bhp){
     
      $.ajax({
          url : "<?php echo base_url().'inventory/bhp_retur/lastopname/';?>"+bhp,
          success : function(data) {
             tglop = data.split('-');
              $("#last_opname_retur").val(tglop[2]+'-'+tglop[1]+'-'+tglop[0]);
          }
      });

      return false;
    }
    function kodedistribusi(tahun)
    {
      if (tahun==null) {
        var tahun = <?php echo date("y");?>;  
      }else{
        var tahun = tahun.substr(-2);
      }
      
      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_retur/kodedistribusi';?>",
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
          var lokasi = elemet.kodeinv.split(".")
          $("#kode_distribusi_retur").val(lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]);
        });
      }
      });

      return false;
    }
  });
</script>
  