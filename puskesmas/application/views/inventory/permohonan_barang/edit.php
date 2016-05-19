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
  <form action="<?php echo base_url()?>inventory/permohonanbarang/{action}/{kode}/{code_cl_phc}" method="post">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        <div class="form-group">
          <label>Kode Lokasi</label>
          <input type="text" id="id_inv_permohonan_barang" name="id_inv_permohonan_barang" placeholder="Kode Lokasi"  class="form-control" value="<?php  
            if(set_value('id_inv_permohonan_barang')=="" && isset($id_inv_permohonan_barang)){
                    $s = array();
                  $s[0] = substr($id_inv_permohonan_barang, 0,2);
                  $s[1] = substr($id_inv_permohonan_barang, 2,2);
                  $s[2] = substr($id_inv_permohonan_barang, 4,2);
                  $s[3] = substr($id_inv_permohonan_barang, 6,2);
                  $s[4] = substr($id_inv_permohonan_barang, 8,2);
                  $s[5] = substr($id_inv_permohonan_barang, 10,2);
                  $s[6] = substr($id_inv_permohonan_barang, 12,3);
                  echo implode(".", $s);
            }else{
              echo  set_value('id_inv_permohonan_barang');
            }
          ?>" readonly='' />
        </div>
        <div class="form-group">
          <label>Tanggal Pengajuan</label>
          <div id='tgl' name="tgl" value="<?php
              echo date("Y-m-d",strtotime($tanggal_permohonan));
            ?>"></div>
        </div>
        <div class="form-group">
          <label>Puskesmas Pemohon</label>
          <select  name="codepus" id="puskesmas" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <option value="<?php echo $pus->code ?>" ><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label>Ruangan</label> <i>optional</i>
          <select name="ruangan" id="ruangan"  class="form-control">
              <option value="">Pilih Ruangan</option>
          </select>
        </div>
      </div>
    </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">
        <div class="form-group">
          <label>Status Permohonan</label>
          <select  name="statuspengadaan" id="statuspengadaan" class="form-control">
              <?php foreach($statusdata as $stat) : ?>
                <?php $select = $stat['code']  == $pilihan_status_pengadaan ? 'selected=selected' : '' ?>
                <option value="<?php echo $stat['code'] ?>"<?php echo $select ?> ><?php echo $stat['value'] ?></option>
              <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <textarea class="form-control" name="keterangan" placeholder="Keterangan"><?php 
              if(set_value('keterangan')=="" && isset($keterangan)){
                echo $keterangan;
              }else{
                echo  set_value('keterangan');
              }
              ?></textarea>
        </div>
        <table width="100%">
          <tr>
            <th width="25%">Total Jumlah</th>
            <td>:</td>
            <td width="70%"><div id="total_jumlah_"></div></td>
          </tr>
          <tr>
            <th>Total Harga</th>
            <td>:</td>
            <td><div id="total_harga_"></div></td>
          </tr>
        </table>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
      </div>
      </div>
    </form>        

  </div><!-- /.form-box -->
</div><!-- /.register-box -->      
<div class="box box-success">
  <div class="box-body">
    <div class="div-grid">
        <div id="jqxTabs">
          <?php echo $barang;?>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>inventory/permohonanbarang";
    });

    $("#menu_inventory_permohonanbarang").addClass("active");
    $("#menu_aset_tetap").addClass("active");

    $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});

    $.ajax({
      url : '<?php echo site_url('inventory/permohonanbarang/get_ruangan') ?>',
      type : 'POST',
      data : 'code_cl_phc={code_cl_phc}&id_mst_inv_ruangan={id_mst_inv_ruangan}',
      success : function(data) {
        $('#ruangan').html(data);
      }
    }); 
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
      url: "<?php echo base_url().'inventory/permohonanbarang/kodePermohonan';?>",
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
          var lokasi = elemet.kodeper.split(".")
          $("#id_inv_permohonan_barang").val(lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]);
        });
      }
      });

      return false;
    }
</script>
