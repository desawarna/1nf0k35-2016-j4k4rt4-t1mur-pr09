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
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php }  ?>
<div id="grid"></div>
<section class="content">
<div class="row">
  <form action="" method="post" name="editform" id="form-ss-edit">
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
          <div class="col-md-4" style="padding: 5px">Tanggal Opname</div>
          <div class="col-md-8">
          
          <?php if($action!="view") {?>
            <div id='tgl_opname' name="tgl_opname" value="<?php
              echo ($tgl_opname) ? date("Y-m-d",strtotime($tgl_opname)) : "";
            ?>"></div>
          <?php }else{
                echo date("d-m-Y",strtotime($tgl_opname));
          }?>  
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Nomor Opname</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <input type="text" class="form-control" name="nomor_opname" id="nomor_opname" placeholder="Nomor Opname"  value="<?php 
                if(set_value('nomor_opname')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname');
                }
                ?>">
          <?php }else{
              echo $nomor_opname;
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
              if ($jenis_bhp=="umum") {
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
              
                echo $jenis_bhp;
              }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <select  name="codepus" id="puskesmastambah" class="form-control" disabled='disabled'>
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
          <div class="col-md-5" style="padding: 5px">Nama Penanggungjawab</div>
          <div class="col-md-7">
          <?php if($action!="view") {?>
            <input type="text" class="form-control" name="penerima_nama" id="penerima_nama" placeholder="Nama Penerima" value="<?php 
                if(set_value('penerima_nama')=="" && isset($petugas_nama)){
                  echo $petugas_nama;
                }else{
                  echo  set_value('penerima_nama');
                }
                ?>">
          <?php }else{
                    echo $petugas_nama;
              }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">NIP Penanggungjawab</div>
          <div class="col-md-7">
            <?php if($action!="view") {?>
            <input type="text" class="form-control" name="penerima_nip" id="penerima_nip" placeholder="NIP Penerima" value="<?php 
                if(set_value('penerima_nip')=="" && isset($petugas_nip)){
                  echo $petugas_nip;
                }else{
                  echo  set_value('penerima_nip');
                }
                ?>">
          <?php }else{
                    echo $petugas_nip;
              }
          ?>
          </div>
        </div>

        <div class="row" style="margin: 5px">
          <div class="col-md-5" style="padding: 5px">Catatan</div>
          <div class="col-md-7">
          <?php if($action!="view") {?>
            <textarea class="form-control" name="catatan" id="catatan" placeholder="Keterangan / Keperluan"><?php 
              if(set_value('catatan')=="" && isset($catatan)){
                echo $catatan;
              }else{
                echo  set_value('catatan');
              }
              ?></textarea>
          <?php }else{
                    echo $catatan;
              }
          ?>
          <input type="hidden" class="form-control" name="id_inv_inventaris_habispakai_opname" id="id_inv_inventaris_habispakai_opname" placeholder="Nama Penerima" value="<?php 
                if(set_value('id_inv_inventaris_habispakai_opname')=="" && isset($id_inv_inventaris_habispakai_opname)){
                  echo $id_inv_inventaris_habispakai_opname;
                }else{
                  echo  set_value('id_inv_inventaris_habispakai_opname');
                }
                ?>">
          </div>  
        </div>


      </div>
      <div class="box-footer">
        <?php if(!isset($viewreadonly)){?>
          <!--<button type="submit" class="btn btn-primary" id="btn-submit"><i class='fa fa-floppy-o'></i> &nbsp; Simpan</button>-->
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
 </section>
<div class="row">

<?php if(!isset($viewreadonly)){?>
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body">
        <label>Barang Opname</label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php echo $barang_opname;?>
            </div>
        </div>
      </div>
    </div>
  </div>  
<!--
  <div class="col-md-6">
    <div class="box box-danger">
      <div class="box-body">
      <label>Daftar Barang Distribusi </label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php /* echo $barang;?>
            </div>
        </div>
      </div>
    </div>
  </div>
<?php }else{ ?>
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body">
        <label>Barang Distribusi</label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php echo $barang_opname;?>
            </div>
        </div>
      </div>
    </div>
  </div>  
<?php */} ?>
-->
</div>
<script type="text/javascript">

$(function(){
  $('#form-ss-edit').submit(function(){
      var data = new FormData();
      data.append('kode_distribusi_', $('#kode_distribusi_').val());
      data.append('tgl_opname', $('#tgl_opname').val());
      data.append('nomor_opname', $('#nomor_opname').val());
      data.append('jenis_bhp', $('#jenis_bhp').val());
      data.append('puskesmastambah', $('#puskesmastambah').val());
      data.append('penerima_nama', $('#penerima_nama').val());
      data.append('penerima_nip', $('#penerima_nip').val());
      data.append('catatan', $('#catatan').val());
      data.append('id_inv_inventaris_habispakai_opname', $('#id_inv_inventaris_habispakai_opname').val());
      $.ajax({
          cache : false,
          contentType : false,
          processData : false,
          type : 'POST',
          url : "<?php echo base_url()?>inventory/bhp_opname/{action}_opname/{kode}/{jenisbarangbhp}",
          data : data,
          success : function(response){
            $('#addopname').html(response);
          }
      });
      return false;
  });
  kodedistribusi();
    $('#btn-kembali').click(function(){
        $.get('<?php echo base_url()?>inventory/bhp_opname/tab/2', function (data) {
            $('#addopname').hide();
              $('#content2').html(data);
      });
    });


    $("#menu_bahan_habis_pakai").addClass("active");
    $("#menu_inventory_bhp_opname").addClass("active");
    $("#puskesmastambah").change(function(){
      kodedistribusi();
    });
    <?php if($action!="view"){?>
      $("#tgl_opname").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px' , disabled: true});
    
      $("#tgl_opname").change(function() {
          kodedistribusi($("#tgl_opname").val());
      });
    <?php } ?>
    });

    function kodedistribusi(tahun)
    { 

      if (tahun==null) {
        var tahun ='';
      }else{
        var tahun = tahun.substr(-2);
      }

      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_opname/kodedistribusi/';?>"+$("#puskesmastambah").val(),
      dataType: "json",
      success:function(data)
      { 
        $.each(data,function(index,elemet){
         // alert( );
          var lokasi = elemet.kodeinv.split(".")
          <?php if($action!="view") {?>
          $("#kode_distribusi_").val(/*lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]*/elemet.kodeinv);
          <?php }else{?>
          $("#kode_distribusi_").html(/*lokasi[0]+"."+lokasi[1]+"."+lokasi[2]+"."+lokasi[3]+"."+lokasi[4]+"."+tahun+'.'+lokasi[5]*/elemet.kodeinv;
          <?php }?>
        });
      }
      });

      return false;
    }
    $("#btn-export").click(function(){
    
    var post = "";
    post = post+'&jenis_bhp='+"<?php echo $jenisbarangbhp; ?>"+'&kode='+"<?php echo $kode; ?>";
    
    $.post("<?php echo base_url()?>inventory/bhp_opname/export_distribusi",post,function(response ){
      window.location.href=response;
    });
  });
</script>

      