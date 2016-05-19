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
<section class="content">
<div class="row">
  <form  method="post" id="form-ss" name="form-ss">
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
          <div class="col-md-4" style="padding: 5px">Tanggal Pemusnahan</div>
          <div class="col-md-8">
            <div id='tgl_opname' name="tgl_opname" value="<?php
              echo (set_value('tgl_opname')!="") ? date("Y-m-d",strtotime(set_value('tgl_opname'))) : "";
            ?>"></div>
          </div>
        </div>

        

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <select  id="namapuskesmas" name="namapuskesmas" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == set_value('namapuskesmas') ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Expired</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="nomor_opname" id="nomor_opname" placeholder="Nomor Expired" value="<?php 
                if(set_value('nomor_opname')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname');
                }
                ?>">
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">

      <div class="box-body">
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">Nama Saksi 1</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi1_nama" id="saksi1_nama" placeholder="Nama Saksi 1" value="<?php 
                if(set_value('saksi1_nama')=="" && isset($saksi1_nama)){
                  echo $saksi1_nama;
                }else{
                  echo  set_value('saksi1_nama');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">NIP Saksi 1</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi1_nip" id="saksi1_nip" placeholder="NIP Saksi1" value="<?php 
                if(set_value('saksi1_nip')=="" && isset($saksi1_nip)){
                  echo $saksi1_nip;
                }else{
                  echo  set_value('saksi1_nip');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">Nama Saksi 2</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi2_nama" id="saksi2_nama" placeholder="Nama Saksi 2" value="<?php 
                if(set_value('saksi2_nama')=="" && isset($saksi2_nama)){
                  echo $saksi2_nama;
                }else{
                  echo  set_value('saksi2_nama');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">NIP Saksi 2</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi2_nip" id="saksi2_nip" placeholder="NIP Saksi 2" value="<?php 
                if(set_value('saksi2_nip')=="" && isset($saksi2_nip)){
                  echo $saksi2_nip;
                }else{
                  echo  set_value('saksi2_nip');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">Catatan</div>
          <div class="col-md-7">
          <textarea class="form-control" name="catatan" id="catatan" placeholder="Catatan / Keperluan"><?php 
              if(set_value('catatan')=="" && isset($catatan)){
                echo $catatan;
              }else{
                echo  set_value('catatan');
              }
              ?></textarea>
              <input type="hidden" id="last_opname" name="last_opname" />
              <input type="hidden" class="form-control" name="tipe_data" id="tipe_data" placeholder="Tipe Data" value="<?php 
                if(set_value('tipe_data')=="" && isset($tipe_data)){
                  echo $tipe_data;
                }else{
                  echo  set_value('tipe_data');
                }
                ?>">
          </div>  
        </div>

      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-kembali-expired" name="btn-kembali-expired" class="btn btn-warning"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
          

  </div><!-- /.form-box -->
  </form>  
</div><!-- /.register-box -->
</section>
<script type="text/javascript">
$(function(){
  cekopname($("#tgl_opname").val(),$("#jenis_bhp").val());
    $("#form-ss").submit(function(){
      var tglper1 = $("#tgl_opname").val().split('-');
      var tglper2 = $("#last_opname").val().split('-');
      if (tglper2[2]+'-'+tglper2[1]+'-'+tglper2[0] >= tglper1[2]+'-'+tglper1[1]+'-'+tglper1[0]) {
      alert("Maaf! Data sudah di opname atau dimusnahkan pada "+$("#last_opname").val()+','+'\n'+"Silahkan ganti ke tanggal berikutnya");

      }else{
            var data = new FormData();
            data.append('kode_distribusi_', $("#kode_distribusi_").val());
            data.append('tgl_opname', $("#tgl_opname").val());
            data.append('puskesmas', $('#namapuskesmas').val());
            data.append('nomor_opname', $("#nomor_opname").val());
            data.append('saksi1_nama', $("#saksi1_nama").val());
            data.append('saksi1_nip', $("#saksi1_nip").val());
            data.append('saksi2_nama', $("#saksi2_nama").val());
            data.append('saksi2_nip', $("#saksi2_nip").val());
            data.append('catatan', $("#catatan").val());
            data.append('tipe_data', $("#tipe_data").val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : "<?php echo base_url()?>inventory/bhp_pemusnahan/{action}_expired/{tipe_data}",
                data : data,
                success : function(response){
                    $("#content1").html(response);
                }
            });
            return false;
      }
            return false;
        });
    kodedistribusi();
    $("#btn-kembali-expired").click(function(){
       $.ajax({
            url : '<?php echo site_url('inventory/bhp_pemusnahan/daftar_expired/') ?>',
          
          type : 'POST',
          success : function(data) {
                $("#content1").html(data);
          }
      });

      return false;
    });

    $("#tgl_opname").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    $("#tgl_opname").change(function() {
        kodedistribusi($("#tgl_opname").val());
    });
    $("#jenis_bhp").change(function(){
        cekopname($("#tgl_opname").val(),$(this).val());
    });
    function cekopname(tgl,bhp){
     
      $.ajax({
          url : "<?php echo base_url().'inventory/bhp_pemusnahan/lastopname/';?>"+bhp,
          success : function(data) {
             tglop = data.split('-');
              $("#last_opname").val(tglop[2]+'-'+tglop[1]+'-'+tglop[0]);
          }
      });

      return false;
    }
    $("#namapuskesmas").change(function(){
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
      url: "<?php echo base_url().'inventory/bhp_pemusnahan/kodedistribusi/';?>"+$("#namapuskesmas").val(),
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
    $("#saksi1_nama").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nama/'; ?>',
      focus: function( event, ui ) {
        $("#saksi1_nama").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#saksi1_nama").val( ui.item.value );
 
        return false;
      } 
    });
   $("#saksi1_nip").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nip/'; ?>',
      focus: function( event, ui ) {
        $("#saksi1_nip").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
       $("#saksi1_nip").val( ui.item.value );
        return false;
      } 
    });
    $("#saksi2_nama").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nama/'; ?>',
      focus: function( event, ui ) {
        $("#saksi2_nama").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#saksi2_nama").val( ui.item.value );
 
        return false;
      } 
    });
    $("#saksi2_nip").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nip/'; ?>',
      focus: function( event, ui ) {
        $("#saksi2_nip").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#saksi2_nip").val( ui.item.value );
        return false;
      } 
    });
  });
</script>
  