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
          <div class="col-md-4" style="padding: 5px">Tanggal Pemusnahan</div>
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
          <div class="col-md-4" style="padding: 5px">Puskesmas</div>
          <div class="col-md-8">
          <?php if($action!="view") {?>
            <select id="puskesmas" name="puskesmas" class="form-control" disabled='disabled'>
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
            <input type="text" class="form-control" name="saksi1_nip" id="saksi1_nip" placeholder="NIP Saksi 1" value="<?php 
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
          <?php if($action!="view") {?>
            <textarea class="form-control" name="catatan" id="catatan" placeholder="Keterangan / Catatan"><?php 
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
                <input type="hidden" class="form-control" name="tipe_data" id="tipe_data" placeholder="Nama Penerima" value="<?php 
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
        <?php if(!isset($viewreadonly)){?>
          <!--<button type="submit" class="btn btn-primary" id="btn-submit"><i class='fa fa-floppy-o'></i> &nbsp; Simpan</button>-->
        <?php }else{ ?>
          <button type="button" id="btn-export" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Export</button>
          <?php if($unlock==1){ ?>
            <button type="button" id="btn-edit" class="btn btn-success"><i class='fa fa-pencil-square-o'></i> &nbsp; Ubah Distribusi</button>
          <?php } ?>
        <?php } ?>
        <button type="button" id="btn-kembali-expired" name="btn-kembali-expired" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali </button>
      </div>
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->    
 </form>
 </section>
 <section class="content">
<div class="row">

<?php if(!isset($viewreadonly)){?>
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-body">
        <label>Barang Pemusnahan Expired</label>
        <div class="div-grid">
            <div id="jqxTabs">
              <?php echo $barang_opname;?>
            </div>
        </div>
      </div>
    </div>
  </div>  

 <!-- <div class="col-md-6">
    <div class="box box-danger">
      <div class="box-body">
      <label>Daftar Barang Expired</label>
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
        <label>Barang bhp_pemusnahan</label>
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
</section>
<script type="text/javascript">

$(function(){
 $("#form-ss-edit").submit(function(){
      var data = new FormData();
      data.append('kode_distribusi_', $("#kode_distribusi_").val());
      data.append('tgl_opname', $("#tgl_opname").val());
      data.append('puskesmas', $("#puskesmas").val());
      data.append('nomor_opname', $("#nomor_opname").val());
      data.append('saksi1_nama', $("#saksi1_nama").val());
      data.append('saksi1_nip', $("#saksi1_nip").val());
      data.append('saksi2_nama', $("#saksi2_nama").val());
      data.append('saksi2_nip', $("#saksi2_nip").val());
      data.append('catatan', $("#catatan").val());
      data.append('tipe_data', $("#tipe_data").val());
      data.append('id_inv_inventaris_habispakai_opname', $("#id_inv_inventaris_habispakai_opname").val());
      $.ajax({
          cache : false,
          contentType : false,
          processData : false,
          type : 'POST',
          url : "<?php echo base_url()?>inventory/bhp_pemusnahan/{action}_expired/{kode}/{tipe_data}",
          data : data,
          success : function(responses){
              $("#content1").html(responses);
          }
      });
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


    $("#menu_bahan_habis_pakai").addClass("active");
    $("#menu_inventory_bhp_pemusnahan").addClass("active");

    <?php if($action!="view"){?>
      $("#tgl_opname").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px' , disabled: true});
    
      $("#tgl_opname").change(function() {
          kodedistribusi($("#tgl_opname").val());
      });
    <?php } ?>
    });

    $("#puskesmas").change(function(){
      kodedistribusi();
    });
    function kodedistribusi(tahun)
    { 
      if (tahun==null) {
        var tahun ='';
      }else{
        var tahun = tahun.substr(-2);
      }

      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_pemusnahan/kodedistribusi/';?>"+$("#puskesmas").val(),
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
    post = post+'&jenis_bhp='+"<?php echo '8'; ?>"+'&kode='+"<?php echo $kode; ?>";
    
    $.post("<?php echo base_url()?>inventory/bhp_pemusnahan/export_distribusi",post,function(response ){
      window.location.href=response;
    });
  });
</script>

      