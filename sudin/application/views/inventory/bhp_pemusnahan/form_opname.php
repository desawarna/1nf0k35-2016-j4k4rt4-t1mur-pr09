<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($alert_form_opname!=""){ ?>
  <div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h4>  <i class="icon fa fa-check"></i> Information!</h4>
    <?php echo $alert_form_opname?>
  </div>
<?php } ?>
<section class="content">
<div class="row">
  <form  method="post" id="form-ss-rusak" name="form-ss-rusak">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kode Lokasi</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="kode_distribusi_opname" id="kode_distribusi_opname" placeholder="Kode Lokasi" readonly>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Pemusnahan</div>
          <div class="col-md-8">
            <div id='tgl_opname_opname' name="tgl_opname_opname" value="<?php
              echo (set_value('tgl_opname_opname')!="") ? date("Y-m-d",strtotime(set_value('tgl_opname_opname'))) : "";
            ?>"></div>
          </div>
        </div>

        

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <select  id="namapuskesmas_opname" name="namapuskesmas_opname" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == set_value('namapuskesmas_opname') ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Expired</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="nomor_opname_opname" id="nomor_opname_opname" placeholder="Nomor Expired" value="<?php 
                if(set_value('nomor_opname_opname')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname_opname');
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
            <input type="text" class="form-control" name="saksi1_nama_opname" id="saksi1_nama_opname" placeholder="Nama Saksi 1" value="<?php 
                if(set_value('saksi1_nama_opname')=="" && isset($saksi1_nama)){
                  echo $saksi1_nama;
                }else{
                  echo  set_value('saksi1_nama_opname');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">NIP Saksi 1</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi1_nip_opname" id="saksi1_nip_opname" placeholder="NIP Saksi 1" value="<?php 
                if(set_value('saksi1_nip_opname')=="" && isset($saksi1_nip)){
                  echo $saksi1_nip;
                }else{
                  echo  set_value('saksi1_nip_opname');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">Nama Saksi 2</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi2_nama_opname" id="saksi2_nama_opname" placeholder="Nama Saksi 2" value="<?php 
                if(set_value('saksi2_nama_opname')=="" && isset($saksi2_nama)){
                  echo $saksi2_nama;
                }else{
                  echo  set_value('saksi2_nama_opname');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">NIP Saksi 2</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="saksi2_nip_opname" id="saksi2_nip_opname" placeholder="NIP Saksi 2" value="<?php 
                if(set_value('saksi2_nip_opname')=="" && isset($saksi2_nip_opname)){
                  echo $saksi2_nip_opname;
                }else{
                  echo  set_value('saksi2_nip_opname');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">Catatan</div>
          <div class="col-md-7">
          <textarea class="form-control" name="catatan_opname" id="catatan_opname" placeholder="Catatan / Keperluan"><?php 
              if(set_value('catatan_opname')=="" && isset($catatan)){
                echo $catatan;
              }else{
                echo  set_value('catatan_opname');
              }
              ?></textarea>
              <input type="hidden" id="last_opname_opname" name="last_opname_opname" />
              <input type="hidden" class="form-control" name="tipe_data_opname" id="tipe_data_opname" placeholder="tipe data" value="<?php 
                if(set_value('tipe_data_opname')=="" && isset($tipe_data_opname)){
                  echo $tipe_data_opname;
                }else{
                  echo  set_value('tipe_data_opname');
                }
                ?>">
          </div>  
        </div>

      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-kembali-rusak" name="btn-kembali-rusak" class="btn btn-warning"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
          

  </div><!-- /.form-box -->
  </form>  
</div><!-- /.register-box -->
</section>
<script type="text/javascript">
$(function(){
  cekopname();
    $("#form-ss-rusak").submit(function(){
      var tglper1 = $("#tgl_opname_opname").val().split('-');
      var tglper2 = $("#last_opname_opname").val().split('-');
      if (tglper2[2]+'-'+tglper2[1]+'-'+tglper2[0] >= tglper1[2]+'-'+tglper1[1]+'-'+tglper1[0]) {
      alert("Maaf! Data sudah di opname atau dimusnahkan pada "+$("#last_opname_opname").val()+','+'\n'+"Silahkan ganti ke tanggal berikutnya");

      }else{
            var data = new FormData();
            data.append('kode_distribusi_opname', $("#kode_distribusi_opname").val());
            data.append('tgl_opname_opname', $("#tgl_opname_opname").val());
            data.append('puskesmas', $('#namapuskesmas_opname').val());
            data.append('nomor_opname_opname', $("#nomor_opname_opname").val());
            data.append('saksi1_nama_opname', $("#saksi1_nama_opname").val());
            data.append('saksi1_nip_opname', $("#saksi1_nip_opname").val());
            data.append('saksi2_nama_opname', $("#saksi2_nama_opname").val());
            data.append('saksi2_nip_opname', $("#saksi2_nip_opname").val());
            data.append('catatan_opname', $("#catatan_opname").val());
            data.append('tipe_data_opname', $("#tipe_data_opname").val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : "<?php echo base_url()?>inventory/bhp_pemusnahan/{action}_opname/{tipe_data_opname}",
                data : data,
                success : function(response){
                    $("#content3").html(response);
                }
            });
            return false;
      }
            return false;
        });
    kodedistribusi();
    $("#btn-kembali-rusak").click(function(){
       $.ajax({
            url : '<?php echo site_url('inventory/bhp_pemusnahan/daftar_opname/') ?>',
          
          type : 'POST',
          success : function(data) {
                $("#content3").html(data);
          }
      });

      return false;
    });

    $("#tgl_opname_opname").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    $("#tgl_opname_opname").change(function() {
        kodedistribusi($("#tgl_opname_opname").val());
    });
    function cekopname(tgl,bhp){
     
      $.ajax({
          url : "<?php echo base_url().'inventory/bhp_pemusnahan/lastopnameumum/';?>"+bhp,
          success : function(data) {
             tglop = data.split('-');
              $("#last_opname_opname").val(tglop[2]+'-'+tglop[1]+'-'+tglop[0]);
          }
      });

      return false;
    }
    $("#namapuskesmas_opname").change(function(){
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
      url: "<?php echo base_url().'inventory/bhp_pemusnahan/kodedistribusi/';?>"+$("#namapuskesmas_opname").val(),
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
          var lokasi = elemet.kodeinv.split(".")
          $("#kode_distribusi_opname").val(lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]);
        });
      }
      });

      return false;
    }
    $("#saksi1_nama_opname").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nama/'; ?>',
      focus: function( event, ui ) {
        $("#saksi1_nama_opname").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#saksi1_nama_opname").val( ui.item.value );
 
        return false;
      } 
    });
   $("#saksi1_nip_opname").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nip/'; ?>',
      focus: function( event, ui ) {
        $("#saksi1_nip_opname").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
       $("#saksi1_nip_opname").val( ui.item.value );
        return false;
      } 
    });
    $("#saksi2_nama_opname").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nama/'; ?>',
      focus: function( event, ui ) {
        $("#saksi2_nama_opname").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#saksi2_nama_opname").val( ui.item.value );
 
        return false;
      } 
    });
    $("#saksi2_nip_opname").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pemusnahan/autocomplite_nip/'; ?>',
      focus: function( event, ui ) {
        $("#saksi2_nip_opname").val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#saksi2_nip_opname").val( ui.item.value );
        return false;
      } 
    });
  });
</script>
  