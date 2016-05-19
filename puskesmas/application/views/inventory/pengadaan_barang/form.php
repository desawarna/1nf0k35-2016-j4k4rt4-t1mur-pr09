<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="row">
  <form action="<?php echo base_url()?>inventory/pengadaanbarang/add" method="post">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        <div class="form-group">
          <label>Kode Lokasi</label>
          <input type="text" class="form-control" name="kode_inventaris_" id="kode_inventaris_" placeholder="Kode Lokasi" value="<?php 
            if(set_value('kode_inventaris_')=="" && isset($id_pengadaan)){
               $s = array();
                  $s[0] = substr($id_pengadaan, 0,2);
                  $s[1] = substr($id_pengadaan, 2,2);
                  $s[2] = substr($id_pengadaan, 4,2);
                  $s[3] = substr($id_pengadaan, 6,2);
                  $s[4] = substr($id_pengadaan, 8,2);
                  $s[5] = substr($id_pengadaan, 10,2);
                  $s[6] = substr($id_pengadaan, 12,2);
                  echo implode(".", $s);
            }else{
              echo  set_value('kode_inventaris_');
            }
            ?>">
        </div>
        <div class="form-group">
          <label>Tanggal Pengadaan</label>
          <div id='tgl' name="tgl" value="<?php
              echo (set_value('tgl')!="") ? date("Y-m-d",strtotime(set_value('tgl'))) : "";
            ?>"></div>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select  name="status" type="text" class="form-control">
              </option>
              <?php foreach($kodestatus as $stat) : ?>
                <?php $select = $stat->code == set_value('status') ? 'selected' : '' ?>
                <option value="<?php echo $stat->code ?>" <?php echo $select ?>><?php echo $stat->value ?></option>
              <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label>Puskesmas</label>
          <select  name="codepus" id="puskesmas" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == set_value('codepus') ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"><?php 
              if(set_value('keterangan')=="" && isset($keterangan)){
                echo $keterangan;
              }else{
                echo  set_value('keterangan');
              }
              ?></textarea>
        </div>  
      </div>
    </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">
        <div class="form-group">
          <label>Nomor Kontrak</label>
          <input type="text" class="form-control" name="nomor_kontrak" placeholder="Nomor Kontrak" value="<?php 
            if(set_value('nomor_kontrak')=="" && isset($nomor_kontrak)){
              echo $nokontrak;
            }else{
              echo  set_value('nomor_kontrak');
            }
            ?>">
        </div>
        <div class="form-group">
          <label>Tanggal Kwitansi / Faktur</label>
          <div id='tgl1' name="tgl1" value="<?php
              echo (set_value('tgl1')!="") ? date("Y-m-d",strtotime(set_value('tgl1'))) : "";
            ?>"></div>
        </div>
        <div class="form-group">
          <label>Nomor Kwitansi / Faktur</label>
          <input type="text" class="form-control" name="nomor_kwitansi" placeholder="Nomor Kwitansi" value="<?php 
            if(set_value('nomor_kwitansi')=="" && isset($nomor_kwitansi)){
              echo $nokontrak;
            }else{
              echo  set_value('nomor_kwitansi');
            }
            ?>">
        </div>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
      </div>
      </div>
    </form>        

  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script type="text/javascript">
$(function(){
    kodeInvetaris();
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>inventory/pengadaanbarang";
    });

    $("#menu_aset_tetap").addClass("active");
    $("#menu_inventory_pengadaanbarang").addClass("active");

    $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});
    $("#tgl1").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});
    document.getElementById("tgl").onchange = function() {
        kodeInvetaris(document.getElementById("tgl").value);
    };
  });
  
  function kodeInvetaris(tahun)
    {
      if (tahun==null) {
        var tahun = <?php echo date("y");?>;  
      }else{
        var tahun = tahun.substr(-2);
      }
      
      $.ajax({
      url: "<?php echo base_url().'inventory/pengadaanbarang/kodeInvetaris';?>",
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
          var lokasi = elemet.kodeinv.split(".")
          $("#kode_inventaris_").val(lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]);
        });
      }
      });

      return false;
    }
</script>
