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
  <form action="<?php echo base_url()?>inventory/bhp_pengadaan/add" method="post">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kode Lokasi</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="kode_inventaris_" id="kode_inventaris_" placeholder="Kode Lokasi" readonly>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Pengadaan</div>
          <div class="col-md-8">
            <div id='tgl' name="tgl" value="<?php
              echo (set_value('tgl')!="") ? date("Y-m-d",strtotime(set_value('tgl'))) : "";
            ?>"></div>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kategori Barang</div>
          <div class="col-md-8">
            <select  name="id_mst_inv_barang_habispakai_jenis" id="id_mst_inv_barang_habispakai_jenis" type="text" class="form-control">
              <?php foreach($kodejenis as $jenis) : ?>
                <?php $select = $jenis->id_mst_inv_barang_habispakai_jenis == set_value('id_mst_inv_barang_habispakai_jenis') ? 'selected' : '' ?>
                <option value="<?php echo $jenis->id_mst_inv_barang_habispakai_jenis ?>" <?php echo $select ?>><?php echo $jenis->uraian ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Jenis Transaksi</div>
          <div class="col-md-8">
            <select  name="jenistransaksi" type="text" class="form-control">
                <option value="pembelian" >Pembelian</option>
                <option value="penerimaan" >Penerimaan</option>
            </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Status</div>
          <div class="col-md-8">
            <select  name="status" type="text" class="form-control">
              <?php foreach($kodestatus as $stat) : ?>
                <?php $select = $stat->code == set_value('status') ? 'selected' : '' ?>
                <option value="<?php echo $stat->code ?>" <?php echo $select ?>><?php echo $stat->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <select  name="codepus" id="puskesmas" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == set_value('codepus') ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Pembelian</div>
          <div class="col-md-8">
          <div id='tgl2' name="tgl2" value="<?php
              echo (set_value('tgl2')!="") ? date("Y-m-d",strtotime(set_value('tgl2'))) : "";
            ?>"></div>
          </div>
        </div>

      </div>
    </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Periode</div>
          <div class="col-md-4 col-xs-6">
            <select  name="thn_periode" type="text" class="form-control">
              <?php for($i=date('Y');$i>=2000;$i--){ ?>
                <?php $select = $i == set_value('thn_periode') ? 'selected' : '' ?>
                <option value="<?php echo $i ?>" <?php echo $select ?>><?php echo $i ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4 col-xs-6">
            <select  name="bln_periode" type="text" class="form-control">
              <?php foreach($bulan as $x=>$y){ ?>
                <?php $select = $x == set_value('bln_periode') ? 'selected' : '' ?>
                <option value="<?php echo $x ?>" <?php echo $select ?>><?php echo $y ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Sumber Dana</div>
          <div class="col-md-4 col-xs-6">
            <select  name="pilihan_sumber_dana" type="text" class="form-control">
              <?php foreach($kodedana as $dana) : ?>
                <?php $select = $dana->code == set_value('pilihan_sumber_dana') ? 'selected' : '' ?>
                <option value="<?php echo $dana->code ?>" <?php echo $select ?>><?php echo $dana->value ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-md-4 col-xs-6">
            <select  name="thn_dana" type="text" class="form-control">
              <?php for($i=date('Y');$i>=2000;$i--){ ?>
                <?php $select = $i == set_value('thn_dana') ? 'selected' : '' ?>
                <option value="<?php echo $i ?>" <?php echo $select ?>><?php echo $i ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Instansi / PBF</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="pbf" id="pbf" placeholder="Instansi / PBF"  autocomplete="off" value="<?php 
                if(set_value('pbf')=="" && isset($nama_pbf)){
                  echo $nama_pbf;
                }else{
                  echo  set_value('pbf');
                }
                ?>">
            <input type="hidden" class="form-control" name="id_mst_inv_pbf_code" id="id_mst_inv_pbf_code" value="<?php 
                if(set_value('id_mst_inv_pbf_code')=="" && isset($mst_inv_pbf_code)){
                  echo $mst_inv_pbf_code;
                }else{
                  echo  set_value('id_mst_inv_pbf_code');
                }
                ?>">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Keterangan</div>
          <div class="col-md-8">
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
      <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
    </form>        

  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script type="text/javascript">
$(function(){
    kodeInvetaris();
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_pengadaan";
    });

    $("#menu_bahan_habis_pakai").addClass("active");
    $("#menu_inventory_bhp_pengadaan").addClass("active");

    $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    $("#tgl2").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    $("#tgl").change(function(){
        kodeInvetaris($("#tgl").val());
    });

    $("#pbf").autocomplete({
      minLength: 0,
      source:'<?php echo base_url().'inventory/bhp_pengadaan/autocomplite_bnf/'; ?>'+$("#id_mst_inv_barang_habispakai_jenis").val(),
      focus: function( event, ui ) {
        $("#pbf" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $("#pbf").val( ui.item.value );
        $("#id_mst_inv_pbf_code").val( ui.item.key );
 
        return false;
      } 
    });
  });
  //return item.+' | '+item.code;
  $("#puskesmas").change(function(){
    kodeInvetaris();
  })
  function kodeInvetaris(tahun)
    {
      if (tahun==null) {
        var tahun = <?php echo date("y");?>;  
      }else{
        var tahun = tahun.substr(-2);
      }
      
      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_pengadaan/kodeInvetaris/';?>"+$("#puskesmas").val(),
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
  