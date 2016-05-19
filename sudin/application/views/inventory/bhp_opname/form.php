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
          <div class="col-md-4" style="padding: 5px">Tanggal Opname</div>
          <div class="col-md-8">
            <div id='tgl_opname' name="tgl_opname" value="<?php
              echo (set_value('tgl_opname')!="") ? date("Y-m-d",strtotime(set_value('tgl_opname'))) : "";
            ?>"></div>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Opname</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="nomor_opname" id="nomor_opname" placeholder="Nomor Dokumen" value="<?php 
                if(set_value('nomor_opname')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname');
                }
                ?>">
          </div>
        </div>
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kategori Barang</div>
          <div class="col-md-8">
            <select  name="jenis_bhp" id="jenis_bhp" type="text" class="form-control">
            <?php
              if (set_value('jenis_bhp')=="umum") {
                $select1 = "selected=selected";
                $select2 = "";
              }else{
                $select2 = "selected=selected";
                $select1 = "";
              }
            ?>
                <option value="obat" <?php echo $select2; ?>>Obat</option>
                <option value="umum" <?php echo $select1; ?>>Umum</option>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <select   id="puskesmastambah" name="puskesmastambah" class="form-control">
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
          <div class="col-md-5" style="padding: 5px">Nama Penanggungjawab</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="penerima_nama" id="penerima_nama" placeholder="Nama Penerima" value="<?php 
                if(set_value('penerima_nama')=="" && isset($penerima_nama)){
                  echo $penerima_nama;
                }else{
                  echo  set_value('penerima_nama');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">NIP Penanggungjawab</div>
          <div class="col-md-7">
            <input type="text" class="form-control" name="penerima_nip" id="penerima_nip" placeholder="NIP Penerima" value="<?php 
                if(set_value('penerima_nip')=="" && isset($penerima_nip)){
                  echo $penerima_nip;
                }else{
                  echo  set_value('penerima_nip');
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
          </div>  
        </div>

      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
          

  </div><!-- /.form-box -->
  </form>  
</div><!-- /.register-box -->
</section>
<script type="text/javascript">
$(function(){
  cekopname($('#tgl_opname').val(),$('#jenis_bhp').val());
    $('#form-ss').submit(function(){
      var tglper1 = $('#tgl_opname').val().split('-');
      var tglper2 = $('#last_opname').val().split('-');
      if (tglper2[2]+'-'+tglper2[1]+'-'+tglper2[0] >= tglper1[2]+'-'+tglper1[1]+'-'+tglper1[0]) {
      alert("Maaf! Kategori barang "+$('#jenis_bhp').val()+" sudah di opname pada "+$('#last_opname').val()+','+'\n'+"Silahkan ganti ke tanggal berikutnya");

      }else{
            var data = new FormData();
            data.append('kode_distribusi_', $('#kode_distribusi_').val());
            data.append('tgl_opname', $('#tgl_opname').val());
            data.append('nomor_opname', $('#nomor_opname').val());
            data.append('jenis_bhp', $('#jenis_bhp').val());
            data.append('puskesmastambah', $('#puskesmastambah').val());
            data.append('penerima_nama', $('#penerima_nama').val());
            data.append('penerima_nip', $('#penerima_nip').val());
            data.append('catatan', $('#catatan').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : "<?php echo base_url()?>inventory/bhp_opname/{action}_opname",
                data : data,
                success : function(response){
                  $('#content2').html(response);
                }
            });
            return false;
      }
            return false;
        });
    kodedistribusi();
    $('#btn-kembali').click(function(){
       $.ajax({
          url : '<?php echo site_url('inventory/bhp_opname/daftar_opname/') ?>',
          type : 'POST',
          success : function(data) {
              $('#content2').html(data);
          }
      });

      return false;
    });

    $("#tgl_opname").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    $("#tgl_opname").change(function() {
        kodedistribusi($("#tgl_opname").val());
        
    });
    $("#jenis_bhp").change(function(){
        cekopname($('#tgl_opname').val(),$(this).val());
    });
    function cekopname(tgl,bhp){
     
      $.ajax({
          url : "<?php echo base_url().'inventory/bhp_opname/lastopname/';?>"+bhp,
          success : function(data) {
             tglop = data.split('-');
              $("#last_opname").val(tglop[2]+'-'+tglop[1]+'-'+tglop[0]);
          }
      });

      return false;
    }
    $("#puskesmastambah").change(function(){
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
      url: "<?php echo base_url().'inventory/bhp_opname/kodedistribusi/';?>"+$("#puskesmastambah").val(),
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
      source:'<?php echo base_url().'inventory/bhp_opname/autocomplite_nama/'; ?>',
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
      source:'<?php echo base_url().'inventory/bhp_opname/autocomplite_nip/'; ?>',
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
  