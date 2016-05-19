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
  <form action="<?php echo base_url()?>inventory/bhp_permintaan/{action}/{kode}/" method="post" name="editform">
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
          <div class="col-md-4" style="padding: 5px">Tanggal Permintaan</div>
          <div class="col-md-8">
          <?php if(isset($viewreadonly)){if($action='view'){ 
                echo "".date("d-m-Y",strtotime($tgl_permintaan)); }}else{ ?>
              <div id='tgl' name="tgl" value="<?php
              echo (!empty($tgl_permintaan)) ? date("Y-m-d",strtotime($tgl_permintaan)) : "";
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
          <div class="col-md-4" style="padding: 5px">Status</div>
          <div class="col-md-8">
            <select  name="status" id="status" type="text" class="form-control" >
              <?php foreach($kodestatus as $stat => $val) : ?>
                <?php $select = $stat == $status_permintaan ? 'selected=selected' : '' ?>
                <option value="<?php echo $stat ?>" <?php echo $select ?>><?php echo $val ?></option>
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
        <div class="col-md-4" style="padding: 5px">Tanggal Diterima</div>
        <div class="col-md-8">
        <?php 
        if(isset($viewreadonly)){
          if($action='view'){ echo "".date("d-m-Y",strtotime($tgl_diterima)); }
        }else{ ?>
          <div id='tgl1' name="tgl1" disabled value="<?php echo $tgl_diterima ?>" ></div>
         <?php  }?>
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
                <td>Nilai Permintaan</td>
                <td>
                  <div id="nilai_permintaan_"></div>
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
  kodeinvetaris();
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_permintaan";
    });

    $('#btn-edit').click(function(){
        window.location.href="<?php echo base_url()?>inventory/bhp_permintaan/edit/{kode}";
    });

    $("#menu_bahan_habis_pakai").addClass("active");
    $("#menu_inventory_bhp_permintaan").addClass("active");

    <?php if(!isset($viewreadonly)){?>
      $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
      $("#tgl1").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
      $("#tgl").change(function(){
          kodeinvetaris($("#tgl").val());
      });
    <?php } ?>
  });


function kodeinvetaris(tahun)
    {
      if (tahun==null) {
        var tahun = <?php echo date("y");?>;  
      }else{
        var tahun = tahun.substr(-2);
      }
      
      $.ajax({
      url: "<?php echo base_url().'inventory/bhp_permintaan/kodeinvetaris/';?>"+$("#puskesmas").val(),
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

      