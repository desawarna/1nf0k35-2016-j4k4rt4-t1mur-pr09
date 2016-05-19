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
<?php } //print_r($tgl_opname_);echo "<h1>hai</h1>"; ?>
<script>

function validateForm() {
  //alert('hai');onsubmit="return validateForm()" 
    var tanggalopname = $("#tgl2").val().split('-');
  //  alert(Date.parse(tanggalopname[2]-tanggalopname[1]-tanggalopname[0])+'<='+Date.parse($("#tgl__opname_").val()));
     if (Date.parse(tanggalopname[2]-tanggalopname[1]-tanggalopname[0])<=Date.parse($("#tgl__opname_").val())) {
        alert("Maaf! Data pembelian sudah di stock opname pada "+$("#tgl__opname_").val()+"\n"+"Silahkan ganti tanggal pembelian ke hari berikutnya!");
        return false;
    }else{
      /*alert($("#jenis_transaksi").val());
          var post = "";
          post = post+'&jenis_transaksi='+$("#jenis_transaksi").val()+'&keterangan='+$("#keterangan").val()+'&nomor_kontrak='+$("#nomor_kontrak").val()+'&tgl1='+$("#tgl1").val()+'&nomor_kwitansi='+$("#nomor_kwitansi").val()+'&thn_periode='+$("#thn_periode").val()+'&bln_periode='+$("#bln_periode").val()+'&pilihan_sumber_dana='+$("#pilihan_sumber_dana").val()+'&thn_dana='+$("#thn_dana").val()+'&id_inv_hasbispakai_pembelian='+$("#id_inv_hasbispakai_pembelian").val();
          $.post("<?php echo base_url()?>inventory/bhp_pengadaan/{action}/{kode}",post,function(response ){
          });*/
    }
}
</script>
<div class="row">
  <form action="<?php echo base_url()?>inventory/bhp_pengadaan/{action}/{kode}/" method="post" name="editform">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kode Lokasi</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="kode_inventaris_" id="kode_inventaris_" placeholder="Kode Lokasi" readonly="">
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Pengadaan</div>
          <div class="col-md-8">
          <?php if(isset($viewreadonly)){if($action='view'){ 
                echo "".date("d-m-Y",strtotime($tgl_permohonan)); }}else{ ?>
              <div id='tgl' name="tgl" value="<?php
              echo (!empty($tgl_permohonan)) ? date("Y-m-d",strtotime($tgl_permohonan)) : "";
            ?>"></div>
             <?php  }?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kategori Barang</div>
          <div class="col-md-8">
            <select  name="id_mst_inv_barang_habispakai_jenis" type="text" class="form-control" disabled="">
              <?php foreach($kodejenis as $jenis) : ?>
                <?php $select = $jenis->id_mst_inv_barang_habispakai_jenis == $id_mst_inv_barang_habispakai_jenis ? 'selected=selected' : '' ?>
                <option value="<?php echo $jenis->id_mst_inv_barang_habispakai_jenis ?>" <?php echo $select ?>><?php echo $jenis->uraian ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Jenis Transaksi</div>
          <div class="col-md-8">
            <select  name="jenis_transaksi" id="jenis_transaksi" type="text" class="form-control" >
              <?php 
                 if($jenis_transaksi=="pembelian"){
                    $select1 = "selected=selected" ;
                    $select2 = "" ;
                 }else{
                    $select2 = "selected=selected" ;
                    $select1 = "" ;
                 }
              ?>
                <option value="pembelian" <?php echo $select1 ?>>Pembelian</option>
                <option value="penerimaan" <?php echo $select2 ?>>Penerimaan</option>
            </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Status</div>
          <div class="col-md-8">
            <select  name="status" id="status" type="text" class="form-control" >
              <?php foreach($kodestatus as $stat) : ?>
                <?php $select = $stat->code == $pilihan_status_pembelian ? 'selected=selected' : '' ?>
                <option value="<?php echo $stat->code ?>" <?php echo $select ?>><?php echo $stat->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <select  name="codepus" id="puskesmas" class="form-control"  disabled="">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == $code_cl_phc ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Pembelian</div>
          <div class="col-md-8">
              <?php if(isset($viewreadonly)){if($action='view'){ 
                echo "".date("d-m-Y",strtotime($tgl_pembelian)); }}else{ ?>
              <div id='tgl2' name="tgl2" value="<?php
                  echo (!empty($tgl_pembelian)) ? date("Y-m-d",strtotime($tgl_pembelian)) : "";
                ?>">
              </div>
             <?php  }?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Periode</div>
          <div class="col-md-4 col-xs-6">
            <select  name="thn_periode" type="text" class="form-control"  >
              <?php 
              $tglperiode = explode("-", $bln_periode);
              for($i=date('Y');$i>=2000;$i--){ ?>
                <?php $select = $i == $tglperiode[0] ? 'selected' : '' ?>
                <option value="<?php echo $i ?>" <?php echo $select ?>><?php echo $i ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4 col-xs-6">
            <select  name="bln_periode" type="text" class="form-control"  >
              <?php 
              $tglperiode = explode("-", $bln_periode);
              foreach($bulan as $x=>$y){ ?>
                <?php $select = $x == $tglperiode[1] ? 'selected' : '' ?>
                <option value="<?php echo $x ?>" <?php echo $select ?>><?php echo $y ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Sumber Dana</div>
          <div class="col-md-4 col-xs-6">
            <select  name="pilihan_sumber_dana" type="text" class="form-control" 
              <?php foreach($kodedana as $dana) : ?>
                <?php $select = $dana->code == $pilihan_sumber_dana ? 'selected=selected' : '' ?>
                <option value="<?php echo $dana->code ?>" <?php echo $select ?>><?php echo $dana->value ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-md-4 col-xs-6">
            <select  name="thn_dana" type="text" class="form-control"  >
              <?php for($i=date('Y');$i>=2000;$i--){ ?>
                <?php $select = $i == $thn_dana ? 'selected' : '' ?>
                <option value="<?php echo $i ?>" <?php echo $select ?>><?php echo $i ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Instansi / PBF</div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="pbf" id="pbf" placeholder="Instansi / PBF" autocomplit="off" value="<?php 
                if(set_value('pbf')=="" && isset($nama_pbf)){
                  echo $nama_pbf;
                }else{
                  echo  set_value('pbf');
                }
                ?>">
            <input type="hidden" class="form-control" name="id_mst_inv_pbf_code" id="id_mst_inv_pbf_code" value="<?php echo $mst_inv_pbf_code; ?>">
          </div>
        </div>

      </div>
      </div>      

  </div><!-- /.form-box -->





  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">
      <div id="success"> 
        <div class="form-group">

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Kontrak</div>
            <div class="col-md-8">
            <?php if(!isset($viewreadonly)){ ?>
            <input type="text" class="form-control" name="nomor_kontrak" placeholder="Nomor Kontrak" value="<?php 
              if(set_value('nomor_kontrak')=="" && isset($nomor_kontrak)){
                echo $nomor_kontrak;
              }else{
                echo  set_value('nomor_kontrak');
              }
              ?>">
            <?php }else{ 
                echo "".$nomor_kontrak;
            } ?>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tanggal Kwitansi</div>
            <div class="col-md-8">
            <?php if(isset($viewreadonly)){if($action='view'){ 
            echo "".date("d-m-Y",strtotime($tgl_kwitansi)); }}else{ ?>
              <div id='tgl1' name="tgl1" disabled value="<?php
              echo $tgl_kwitansi;;//echo ($tgl_pengadaan!="") ? date("Y-m-d",strtotime($$tgl_pengadaan)) : "";
            ?>" ></div>
             <?php  }?>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Kwitansi</div>
            <div class="col-md-8">
            <?php if(!isset($viewreadonly)){ ?>
            <input type="text" class="form-control" name="nomor_kwitansi" id="nomor_kwitansi" placeholder="Nomor Kwitansi" value="<?php 
              if(set_value('nomor_kwitansi')=="" && isset($nomor_kwitansi)){
                echo $nomor_kwitansi;
              }else{
                echo  set_value('nomor_kwitansi');
              }
              ?>">
            <?php }else{ 
                echo "".$nomor_kwitansi;
            } ?>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Keterangan</div>
            <div class="col-md-8">
            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"  ><?php 
                if(set_value('keterangan')=="" && isset($keterangan)){
                  echo $keterangan;
                }else{
                  echo  set_value('keterangan');
                }
                ?></textarea>
            </div>  
          </div>

        </div>
          <table class="table table-condensed">
              <tr>
                <td>Jumlah Unit</td>
                <td>
                    <div id="jumlah_unit_"></div>
                </td>
              </tr>
              <tr>
                <td>Nilai Pengadaan</td>
                <td>
                  <div id="nilai_pengadaan_"></div>
                </td>
              </tr>
              <tr>
                <td>Waktu dibuat</td>
                <td>
                  <div id="waktu_dibuat_"></div>
                </td>
              </tr>
              <tr>
                <td>Terakhir di edit</td>
                <td>
                  <div id="terakhir_diubah_"></div>
                  <input type="hidden" id="tgl__opname_" />
                </td>
              </tr>
            </tbody>
          </table>
      </div>
      <div class="box-footer">
        <?php if(!isset($viewreadonly)){?>
          <button type="submit" class="btn btn-primary" id="btn-submit"><i class='fa fa-floppy-o'></i> &nbsp; Simpan</button>
        <?php }else{ ?>
          <button type="button" id="btn-export" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Export</button>
          <?php if($unlock==1){ ?>
            <button type="button" id="btn-edit" class="btn btn-success"><i class='fa fa-pencil-square-o'></i> &nbsp; Ubah Pengadaan</button>
          <?php } ?>
        <?php } ?>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali </button>
      </div>
      </div>
    </form>        
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->    
 </form>    
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
  kodeInvetaris();
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_pengadaan";
    });

    $('#btn-edit').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_pengadaan/edit/{kode}";
    });

    $("#menu_bahan_habis_pakai").addClass("active");
    $("#menu_inventory_bhp_pengadaan").addClass("active");

    <?php if(!isset($viewreadonly)){?>
      $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
      $("#tgl1").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
      $("#tgl2").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    
      $("#tgl").change(function(){
          kodeInvetaris($("#tgl").val());
      });
    <?php } ?>


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
$("#puskesmas").change(function(){
    kodeInvetaris();
  })
    function kodeInvetaris(tahun)
    { 
      if (tahun==null) {
        var tahun = "<?php echo $tgl_permohonan?>".substring(2,4);
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

      