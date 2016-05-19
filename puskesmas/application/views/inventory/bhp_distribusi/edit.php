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
<?php }  ?>

<div class="row">
  <form action="<?php echo base_url()?>inventory/bhp_distribusi/{action}/{kode}/" method="post" name="editform">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kode Lokasi</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
              <input type="text" class="form-control" name="kode_distribusi_" id="kode_distribusi_" placeholder="Kode Lokasi" readonly>
          <?php }else{?>
              <div id="kode_distribusi_"></div>
          <?php }?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Tanggal Distribusi</div>
          <div class="col-md-8">
          
          <?php if($action!="view") {?>
            <div id='tgl_distribusi' name="tgl_distribusi" value="<?php
              echo (isset($tgl_distribusi)) ? date("Y-m-d",strtotime($tgl_distribusi)) : "";
            ?>"></div>
          <?php }else{
                echo date("d-m-Y",strtotime($tgl_distribusi));
          }?>  
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Dokumen</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <input type="text" class="form-control" name="nomor_dokumen" id="nomor_dokumen" placeholder="Nomor Dokumen"  value="<?php 
                if(set_value('nomor_dokumen')=="" && isset($nomor_dokumen)){
                  echo $nomor_dokumen;
                }else{
                  echo  set_value('nomor_dokumen');
                }
                ?>">
          <?php }else{
              echo $nomor_dokumen;
               }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Periode</div>
          <div class="col-md-4 col-xs-6">
            <?php if($action!="view") {?>
            <select  name="thn_periode" type="text" class="form-control" disabled="">
              <?php $thn= explode("-", $bln_periode);  ?>
              <?php for($i=date('Y');$i>=2000;$i--){ ?>
                <?php $select = $i == $thn[0] ? 'selected' : '' ?>
                <option value="<?php echo $i ?>" <?php echo $select ?>><?php echo $i ?></option>
              <?php } ?>
            </select>
          <?php }else{
              $thn= explode("-", $bln_periode);
              echo $thn[0];
              }
          ?>
          </div>
          <div class="col-md-4 col-xs-6">
          <?php if($action!="view") {?>
            <select  name="bln_periode" type="text" class="form-control" disabled="">
            <?php $bln= explode("-", $bln_periode);  ?>
              <?php foreach($bulan as $x=>$y){ ?>
                <?php $select = $x == $bln[1] ? 'selected' : '' ?>
                <option value="<?php echo $x ?>" <?php echo $select ?>><?php echo $y ?></option>
              <?php } ?>
            </select>
          <?php }else{
              $bln= explode("-", $bln_periode);
              foreach($bulan as $x=>$y){
                echo ($x == $bln[1] ? $y : '');
              }
            }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Kategori Barang<?php echo $jenis_bhp;?></div>
          <div class="col-md-8">
         <?php if($action!="view") {?>
            <select  name="jenis_bhp" id="jenis_bhp" type="text" class="form-control" disabled="">
            <?php
              if ($jenis_bhp=="0") {
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
          <?php }else{
                if ($jenis_bhp=="0") {
                $select1 = "selected=selected";
                $select2 = "";
              }else{
                $select2 = "selected=selected";
                $select1 = "";
              }
                echo $jenis_bhp;
              }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <select  name="codepus" id="puskesmas" class="form-control">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == $code_cl_phc ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
          <?php }else{
                  foreach($kodepuskesmas as $pus){
                    echo ($pus->code == $code_cl_phc ? $pus->value: '');
                  }
              }
          ?>
          
          </div>
        </div>

      </div>
    </div>      

  </div>

  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nama Penerima</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <input type="text" class="form-control" name="penerima_nama" id="penerima_nama" placeholder="Nama Penerima" value="<?php 
                if(set_value('penerima_nama')=="" && isset($penerima_nama)){
                  echo $penerima_nama;
                }else{
                  echo  set_value('penerima_nama');
                }
                ?>">
          <?php }else{
                    echo $penerima_nama;
              }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">NIP Penerima</div>
          <div class="col-md-8">
            <?php if($action!="view") {?>
            <input type="text" class="form-control" name="penerima_nip" id="penerima_nip" placeholder="NIP Penerima" value="<?php 
                if(set_value('penerima_nip')=="" && isset($penerima_nip)){
                  echo $penerima_nip;
                }else{
                  echo  set_value('penerima_nip');
                }
                ?>">
          <?php }else{
                    echo $penerima_nip;
              }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Keterangan</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan / Keperluan"><?php 
              if(set_value('keterangan')=="" && isset($keterangan)){
                echo $keterangan;
              }else{
                echo  set_value('keterangan');
              }
              ?></textarea>
          <?php }else{
                    echo $keterangan;
              }
          ?>
          </div>  
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Jumlah Barang</div>
          <div class="col-md-8"> <div id="jumlah_total_"></div> </div>
        </div>

      </div>
      <div class="box-footer">
        <?php if(!isset($viewreadonly)){?>
          <button type="submit" class="btn btn-primary" id="btn-submit"><i class='fa fa-floppy-o'></i> &nbsp; Simpan</button>
        <?php }else{ ?>
          <button type="button" id="btn-export" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Export</button>
          <?php if($unlock==1){ ?>
            <button type="button" id="btn-edit" class="btn btn-success"><i class='fa fa-pencil-square-o'></i> &nbsp; Ubah Distribusi</button>
          <?php } ?>
        <?php } ?>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali </button>
      </div>
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->    
 </form>
<div class="row">

<?php if(!isset($viewreadonly)){?>
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-body">
        <label>Barang Diterima</label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php echo $barang_distribusi;?>
            </div>
        </div>
      </div>
    </div>
  </div>  

  <div class="col-md-6">
    <div class="box box-danger">
      <div class="box-body">
      <label>Daftar Barang Tersedia </label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php echo $barang;?>
            </div>
        </div>
      </div>
    </div>
  </div>
<?php }else{ ?>
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body">
        <label>Barang Diterima</label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php echo $barang_distribusi;?>
            </div>
        </div>
      </div>
    </div>
  </div>  
<?php } ?>

</div>

<script type="text/javascript">

$(function(){
  kodedistribusi();
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_distribusi";
    });

    $('#btn-edit').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_distribusi/edit/{kode}/{jenis_bhp}";
    });

    $("#menu_bahan_habis_pakai").addClass("active");
    $("#menu_inventory_bhp_distribusi").addClass("active");

    <?php if($action!="view"){?>
      $("#tgl_distribusi").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px' , disabled: true});
    
      $("#tgl_distribusi").change(function() {
          kodedistribusi($("#tgl_distribusi").val());
      });
    <?php } ?>
    });
/*lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]*/
/*lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]*/
    function kodedistribusi(tahun)
    { 
      if (tahun==null) {
        var tahun ='';
      }else{
        var tahun = tahun.substr(-2);
      }

      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_distribusi/kodedistribusi';?>",
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
         // alert( );
          var lokasi = elemet.kodeinv.split(".")
          <?php if($action!="view") {?>
          $("#kode_distribusi_").val(elemet.kodeinv);
          <?php }else{?>
          $("#kode_distribusi_").html(elemet.kodeinv);
          <?php }?>
        });
      }
      });

      return false;
    }
    $("#btn-export").click(function(){
    
    var post = "";
    post = post+'&jenis_bhp='+"<?php echo $jenis_bhp; ?>"+'&kode='+"<?php echo $kode; ?>";
    
    $.post("<?php echo base_url()?>inventory/bhp_distribusi/export_distribusi",post,function(response ){
      window.location.href=response;
    });
  });
</script>

      