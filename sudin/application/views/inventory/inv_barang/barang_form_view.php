
<script type="text/javascript">

  <?php $kodebarang_ = substr($id_mst_inv_barang, 0,2);
  if($action!="barcode"){
      if($kodebarang_=='01') {?>  
            $("#status_sertifikat_tanggal").jqxDateTimeInput({ width: '300px', height: '25px' })
<?php  }else if($kodebarang_=='02') {?>
            $("#tanggal_bpkb").jqxDateTimeInput({ width: '300px', height: '25px' });
            $("#tanggal_perolehan").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='03') {?>
            $("#dokumen_tanggal").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='04') {?>
            $("#dokumen_tanggal1").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='05') {?>
             $("#tahun_cetak_beli").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='06') {?>
            $("#dokumen_tanggal2").jqxDateTimeInput({ width: '300px', height: '25px' });
            $("#tanggal_mulai").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php }
}?>   

  function close_popup(){
    $("#popup_barang").jqxWindow('close');
    $("#popup_barang2").jqxWindow('close');
    filter_jqxgrid_inv_barang();
  }
  function deleteimg(id,name){
    if(confirm("Anda yakin akan menghapus data ("+name+") ini?")){
      $.ajax({ 
        type: "POST",
        url: "<?php echo base_url()?>inventory/inv_barang/dodelimg/"+id+"/"+name,
        success: function(response){
           if(response==""){
             timeline_foto();          
           }else{
             alert('data error');
             timeline_foto();
          }
        }
       });    
    }
  }
    $(function(){
      timeline_foto();
      $('#btn-close').click(function(){
        close_popup();
      }); 
      $("[name='btn_simpan']").click(function(){
        var data = new FormData();
        jQuery.each($("[name='filename']")[0].files, function(i, file) {
          data.append('filename', file);
        });     

        $.ajax({ 
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          url: "<?php echo base_url()?>inventory/inv_barang/doupload/{kode}",
          data: data,
          success: function(response){
            var res=response.split("_");
             if(res[0]=="OK"){
               timeline_foto();
               document.getElementById("filename").value = "";
             }else{
                alert(res[1]);
                timeline_foto();
             }
          }
         });    
      });
    });
  function timeline_foto(){
    $.get("<?php echo base_url();?>inventory/inv_barang/timeline_foto/{kode}" , function(response) {
      $("#timeline-foto").html(response);
    });
  }

</script>


<div style="padding:15px">
  <div id="notice" class="alert alert-success alert-dismissable" <?php if ($notice==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice-content">{notice}</div>
  </div>
  <div class="row">
    <?php echo form_open(current_url(), 'id="form-ss"') ?>
    <div class="col-md-6">
    <div class="box box-primary">
          <div class="box-body">
            <div class="form-group"> 
              <label>Kode Barang</label>
              <br><?php 
                  $S = array();
                  $s = array();
                  $S[0] = substr($id_inventaris_barang, 0,2);
                  $S[1] = substr($id_inventaris_barang, 2,2);
                  $S[2] = substr($id_inventaris_barang, 4,2);
                  $S[3] = substr($id_inventaris_barang, 6,2);
                  $S[4] = substr($id_inventaris_barang, 8,2);
                  $S[5] = substr($id_inventaris_barang, 10,2);
                  $S[6] = substr($id_inventaris_barang, 12,3);
                  $s[7] = substr($id_inventaris_barang, 15,2);
                  $s[8] = substr($id_inventaris_barang, 17,2);
                  $s[9] = substr($id_inventaris_barang, 19,2);
                  $s[10] = substr($id_inventaris_barang, 21,2);
                  $s[11] = substr($id_inventaris_barang, 23,2);
                  $s[12] = substr($id_inventaris_barang, 25,4);
                 // $s[13] = substr($id_inventaris_barang, 26,2);
                  echo implode(".", $S).' - '.implode(".", $s);
                ?><br><br>
            </div>
            <div class="form-group">
              <label>Nama Barang</label>
              <br><?php
              if(set_value('nama_barang')=="" && isset($nama_barang)){
                  echo $nama_barang;
                }else{
                  echo  set_value('nama_barang');
                }
                ?>
            </div>
            <div class="form-group">
              <label>Jumlah</label>
              <br><?php 
                if(set_value('jumlah')=="" && isset($jumlah)){
                  echo $jumlah;
                }else{
                  echo  set_value('jumlah');
                }
                ?>
            </div>
            <div class="form-group">
              <label>Harga Satuan</label>
              <br><?php 
                  echo number_format($harga);
                ?>
            </div>
            <div class="form-group">
              <label>Sub Total</label>
              <br><?php
                  echo number_format($jumlah*$harga);
              ?>
            </div>
            <?php if(isset($disable)){if($disable='disable'){?>
            <div class="form-group">
              <label>Tanggal Diterima</label>
              <br><?php
              echo date("d-m-Y",strtotime($tanggal_diterima));
              ?>
            </div>
            <div class="form-group">
              <label>Status Inventaris</label>
              <br>
                <?php foreach($kodestatus_inv as $stat) : ?>
                  <?php echo ($stat->code == $pilihan_status_invetaris) ?  $stat->value : '' ?>
                <?php endforeach ?>
            </div>
            <?php }} ?>
            <div class="form-group">
              <label>Keterangan</label>
              <br><?php 
                    echo $keterangan_pengadaan;
              ?>
            </div>
            <?php if($inventaris['tanggal_dihapus']!="" && $inventaris['tanggal_dihapus']!="0000-00-00"){?>
            <div class="form-group">
              <label>Tanggal Dihapus</label>
              <br><?php 
              echo date("d-m-Y",strtotime($inventaris['tanggal_dihapus']));
              ?>
            </div>
            <?php } ?>            
              <?php if($inventaris['alasan_penghapusan']!=""){?>
            <div class="form-group">
              <label>Alasan Penghapusan</label>
              <br><?php 
                    echo $inventaris['alasan_penghapusan'];
              ?>
            </div>
            <?php } ?>
        </div>
        <div class="box-footer">
            <button type="button" id="btn-close" class="btn btn-warning">Tutup</button>
        </div>

        </div>
        </div>
    <div class="col-md-6">
    <div class="box box-warning">
    <div class="box-body">

    <!--body from edit-->
    <?php  
      if($action=="barcode"){
        ?>   
        <script type="text/javascript">

        function print1(strid)
        {
          if(confirm("Do you want to print?"))
          {
            var values = document.getElementById(strid);
            var printing =
            window.open('','','left=0,top=0,width=500,height=350,toolbar=0,scrollbars=0,sta­?tus=0');
            printing.document.write(values.innerHTML);
            printing.document.close();
            printing.focus();
            printing.print();
          }
        }
        </script>
<style type="text/css">
  #myModal-header{
    background-color: #3498db;
  }
  #myModal-title{
      color: white;
  }
</style>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

  
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Print Barcode</h4>
      </div>
      <div class="modal-body">
      <div id="print2">   
          <table width="100%" cellpadding='2' cellspacing='2' border="0">
              <tr> 
                <th  align="center" colspan="1">
                  <h4 align="center">
                    <img src="<?php echo base_url()?>public/themes/sik/dist/img/logo.gif" width="50px" height="50px">
                  </h4>
                </th>
                <th colspan="3" align="center"><h4>BARANG MILIK DINAS KESEHATAN <BR> KABUPATEN<?php echo $ditrict; ?></h4></th>
              </tr>
          </table>
          <table width="100%" cellpadding='2' cellspacing='2' border="0">
              <tr>
                  <th rowspan="4" width="30%">
                    <img src="<?php echo base_url()?>inventory/qrcodes/draw/<?php echo $kd_proc.'/'.$id_barang.'/'.$kode.'/'.$id_distribusi; ?>" ><br><br>
                  </th>
                  <th align="left" width="20%">Kode Lokasi</th>
                  <th align="left" width="2%">:&nbsp;</th>
                  <th align="left" width="48%"><?php $kodelokasi= substr($kode,0,14);
                  $s = array();
                  $s[0] = substr($kodelokasi, 0,2);
                  $s[1] = substr($kodelokasi, 2,2);
                  $s[2] = substr($kodelokasi, 4,2);
                  $s[3] = substr($kodelokasi, 6,2);
                  $s[4] = substr($kodelokasi, 8,2);
                  $s[5] = substr($kodelokasi, 10,2);
                  $s[6] = substr($kodelokasi, 12,2);
                  echo implode(".", $s);
                ?></th>
              </tr>
              <tr  align="left"> 
                  <th>Kode Barang</th>
                  <th>:&nbsp;</th>
                  <th><?php $rest = substr ($kode,14,28);
                  $s = array();
                  $s[0] = substr($rest, 0,2);
                  $s[1] = substr($rest, 2,2);
                  $s[2] = substr($rest, 4,2);
                  $s[3] = substr($rest, 6,2);
                  $s[4] = substr($rest, 8,2);
                  $s[5] = substr($rest, 10,4);
                  echo implode(".", $s);
                ?></th>
              </tr>
              <tr  align="left">
                  <th colspan="3"><?php echo $nama_barang; ?></th>
              </tr>
              <tr>
                  <th colspan="4" align="right"><img src="<?php echo base_url()?>inventory/barcode/draw/<?php echo $kode; ?>"></th>
              </tr>
          </table>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="return print1('print2')" > <i class="glyphicon glyphicon-print"></i> Print Barcode</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

                <div class="form-group">
                  <label>QR Code / Barcode</label>
                  <br>
                  <img src="<?php echo base_url()?>inventory/qrcodes/draw/<?php echo $kd_proc.'/'.$id_barang.'/'.$kode.'/'.$id_distribusi; ?>" ><br><br>
                  <img src="<?php echo base_url()?>inventory/barcode/draw/<?php echo $kode; ?>" >
                  <button type="button" class="btn btn-info btn-sl" data-toggle="modal" data-target="#myModal" style="float:right;">Print</button>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <label>Galeri Foto</label>
                    <input type="file" class="form-control" id="filename" name="filename" value="<?php 
                      if(set_value('filename')=="" && isset($filename)){
                        echo $filename;
                      }else{
                        echo  set_value('filename');
                      }
                    ?>"/> 
                  </div>
                  <div class="form-group">
                    <button type="button" name="btn_simpan" class="btn btn-primary"> Upload Foto</button>
                    <button type="reset" class="btn btn-warning"> Ulang </button>
                  </div>
                  <div class="form-group">
                    <div id="timeline-foto"></div>
                  </div>
                 </div>
       <?php     
      }else{

        $kodebarang_ = substr($id_mst_inv_barang, 0,2);
        if($kodebarang_=='01') { ?>
          <div class="form-group">
            <label>Luas</label>
            <input type="number" class="form-control" name="luas" id="luas" placeholder="Luas"  value="<?php
            if(set_value('luas')=="" && isset($luas)){
                echo $luas;
              }else{
                echo  set_value('luas');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" placeholder="alamat"><?php 
            if(set_value('alamat')=="" && isset($alamat)){
              echo $alamat;
            }else{
              echo  set_value('alamat');
            }
            ?></textarea>
          </div>
          <div class="form-group">
            <label>Pilihan Status Barang</label>
            <select  name="pilihan_satuan_barang" type="text" class="form-control" id="pilihan_satuan_barang">
                <option value="">Pilih Satuan Barang</option>
                </option>
                <?php foreach($pilihan_satuan_barang_ as $barang) : ?>
                  <?php $select = $barang->code == $pilihan_satuan_barang ? 'selected' : '' ?>
                  <option value="<?php echo $barang->code ?>" <?php echo $select ?>><?php echo $barang->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Pilihan Status Hak</label>
            <select  name="pilihan_status_hak" type="text" class="form-control" id="pilihan_status_hak">
                <option value="">Pilih Status Hak</option>
                </option>
                <?php foreach($pilihan_status_hak_ as $hak) : ?>
                  <?php $select = $hak->code == $pilihan_status_hak ? 'selected' : '' ?>
                  <option value="<?php echo $hak->code ?>" <?php echo $select ?>><?php echo $hak->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal Sertifikat</label>
            <div id='status_sertifikat_tanggal' name="status_sertifikat_tanggal" value="<?php
            echo (!empty($status_sertifikat_tanggal)) ? date("Y-m-d",strtotime($status_sertifikat_tanggal)) :  date("d-m-Y");
          ?>"></div>
          </div>
          <div class="form-group">
            <label>Nomor Sertifikat</label>
            <input type="text" class="form-control" name="status_sertifikat_nomor" id="status_sertifikat_nomor" placeholder="Nomor Sertifikat"  value="<?php
            if(set_value('status_sertifikat_nomor')=="" && isset($status_sertifikat_nomor)){
                echo $status_sertifikat_nomor;
              }else{
                echo  set_value('status_sertifikat_nomor');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Penggunaan</label>
            <select  name="pilihan_penggunaan" type="text" class="form-control" id="pilihan_penggunaan">
                <option value="">Pilih Status Penggunaan</option>
                </option>
                <?php foreach($pilihan_penggunaan_ as $pengguna) : ?>
                  <?php $select = $pengguna->code == $pilihan_penggunaan ? 'selected' : '' ?>
                  <option value="<?php echo $pengguna->code ?>" <?php echo $select ?>><?php echo $pengguna->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Pilihan Asal Usul</label>
            <select  name="pilihan_asal_usul" type="text" class="form-control" id="pilihan_asal_usul">
                <option value="">Pilihan Asal Usul</option>
                </option>
                <?php foreach($pilihan_asal_usul_ as $asal) : ?>
                  <?php $select = $asal->code == $pilihan_asal ? 'selected' : '' ?>
                  <option value="<?php echo $asal->code ?>" <?php echo $select ?>><?php echo $asal->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <?php  }else if($kodebarang_=='02') {?>

          <div class="form-group">
            <label>Merek / Tipe</label>
            <textarea class="form-control"  name="merek_type" placeholder="Merek Tipe" id="merek_type"><?php 
            if(set_value('merek_type')=="" && isset($merek_type)){
              echo $merek_type;
            }else{
              echo  set_value('merek_type');
            }
            ?></textarea>
          </div>
          <div class="form-group">
            <label>Identitas Barang</label>
            <textarea class="form-control"  name="identitas_barang" id="identitas_barang" placeholder="Identitas Barang"><?php 
            if(set_value('identitas_barang')=="" && isset($identitas_barang)){
              echo $identitas_barang;
            }else{
              echo  set_value('identitas_barang');
            }
            ?></textarea>
          </div>
          <div class="form-group">
            <label>Bahan</label>
            <select  name="pilihan_bahan" type="text" class="form-control" id="pilihan_bahan">
                <option value="">Pilih Status Barang</option>
                </option>
                <?php foreach($pilihan_bahan_ as $bahan) : ?>
                  <?php $select = $bahan->code == $pilihan_bahan ? 'selected' : '' ?>
                  <option value="<?php echo $bahan->code ?>" <?php echo $select ?>><?php echo $bahan->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Ukuran Barang</label>
            <input type="text" class="form-control" name="ukuran_barang" id="ukuran_barang" placeholder="Ukuran Barang"  value="<?php
            if(set_value('ukuran_barang')=="" && isset($ukuran_barang)){
                echo $ukuran_barang;
              }else{
                echo  set_value('ukuran_barang');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Satuan</label>
            <select  name="pilihan_satuan" type="text" class="form-control" id="pilihan_satuan">
                <option value="">Pilih Status Satuan</option>
                </option>
                <?php foreach($pilihan_satuan_ as $satuan) : ?>
                  <?php $select = $satuan->code == $pilihan_satuan ? 'selected' : '' ?>
                  <option value="<?php echo $satuan->code ?>" <?php echo $select ?>><?php echo $satuan->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal BPKB</label>
            <div id='tanggal_bpkb' name="tanggal_bpkb" value="<?php
            echo (!empty($tanggal_bpkb)) ? date("Y-m-d",strtotime($tanggal_bpkb)) :  date("d-m-Y");
          ?>"></div>
          </div>
          <div class="form-group">
            <label>Nomor BPKB</label>
            <input type="text" class="form-control" name="nomor_bpkb" id="nomor_bpkb"  placeholder="Nomor BPKB"  value="<?php
            if(set_value('nomor_bpkb')=="" && isset($nomor_bpkb)){
                echo $nomor_bpkb;
              }else{
                echo  set_value('nomor_bpkb');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Nomor Polisi</label>
            <input type="text" class="form-control" name="no_polisi" id="no_polisi" placeholder="Nomor Polisi"  value="<?php
            if(set_value('no_polisi')=="" && isset($no_polisi)){
                echo $no_polisi;
              }else{
                echo  set_value('no_polisi');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Tanggal Perolehan</label>
            <div id='tanggal_perolehan' name="tanggal_perolehan" value="<?php
            echo (!empty($tanggal_perolehan)) ? date("Y-m-d",strtotime($tanggal_perolehan)) :  date("d-m-Y");
          ?>"></div>
          </div>
          <div class="form-group">
            <label>Pilihan Asal Usul</label>
            <select  name="pilihan_asal_usul" type="text" class="form-control" id="pilihan_asal_usul1">
                <option value="">Pilihan Asal Usul</option>
                </option>
                <?php foreach($pilihan_asal_usul_ as $asal) : ?>
                  <?php $select = $asal->code == $pilihan_asal ? 'selected' : '' ?>
                  <option value="<?php echo $asal->code ?>" <?php echo $select ?>><?php echo $asal->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          
          <?php  }else if($kodebarang_=='03') {?>
            
          <div class="form-group">
            <label>Luas Lantai</label>
            <input type="number" class="form-control" name="luas_lantai" id="luas_lantai"  placeholder="Luas Lantai"  value="<?php
            if(set_value('luas_lantai')=="" && isset($luas_lantai)){
                echo $luas_lantai;
              }else{
                echo  set_value('luas_lantai');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Alamat Lokasi</label>
            <textarea class="form-control" id="letak_lokasi_alamat" name="letak_lokasi_alamat" placeholder="Lokasi Alamat"><?php 
                if(set_value('letak_lokasi_alamat')=="" && isset($letak_lokasi_alamat)){
                  echo $letak_lokasi_alamat;
                }else{
                  echo  set_value('letak_lokasi_alamat');
                }
                ?></textarea>
          </div>
          <div class="form-group">
            <label>Pilihan Status Hak</label>
            <select  name="pillihan_status_hak" type="text" class="form-control" id="pillihan_status_hak">
                <option value="">Pilih Status Hak</option>
                </option>
                <?php foreach($pillihan_status_hak_ as $hak) : ?>
                  <?php $select = $hak->code == $pillihan_status_hak ? 'selected' : '' ?>
                  <option value="<?php echo $hak->code ?>" <?php echo $select ?>><?php echo $hak->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Nomor Kode Tanah</label>
            <input type="text" class="form-control" name="nomor_kode_tanah" id="nomor_kode_tanah" placeholder="Nomor Kode Tanah"  value="<?php
            if(set_value('nomor_kode_tanah')=="" && isset($nomor_kode_tanah)){
                echo $nomor_kode_tanah;
              }else{
                echo  set_value('nomor_kode_tanah');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Kontruksi Tingkat</label>
            <select  name="pilihan_kons_tingkat" type="text" class="form-control" id="pilihan_kons_tingkat">
                <option value="">Pilih Kontruksi Tingkat</option>
                </option>
                <?php foreach($pilihan_kons_tingkat_ as $tingkat) : ?>
                  <?php $select = $tingkat->code == $pilihan_kons_tingkat ? 'selected' : '' ?>
                  <option value="<?php echo $tingkat->code ?>" <?php echo $select ?>><?php echo $tingkat->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Pilihan Kontruksi Beton</label>
            <select  name="pilihan_kons_beton" type="text" class="form-control" id="pilihan_kons_beton">
                <option value="">Pilihan Kontruksi Beton</option>
                </option>
                <?php foreach($pilihan_kons_beton_ as $beton) : ?>
                  <?php $select = $beton->code == $pilihan_kons_beton ? 'selected' : '' ?>
                  <option value="<?php echo $beton->code ?>" <?php echo $select ?>><?php echo $beton->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal Dokumen</label>
            <div id='dokumen_tanggal' name="dokumen_tanggal" value="<?php
            echo (!empty($dokumen_tanggal)) ? date("Y-m-d",strtotime($dokumen_tanggal)) :  date("d-m-Y");
          ?>"></div>
          </div>
          <div class="form-group">
            <label>Nomor Dokumen</label>
            <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor" placeholder="Nomor Dokumen"  value="<?php
            if(set_value('dokumen_nomor')=="" && isset($dokumen_nomor)){
                echo $dokumen_nomor;
              }else{
                echo  set_value('dokumen_nomor');
              }
              ?>">
          </div>

          <div class="form-group">
            <label>Pilihan Asal Usul</label>
            <select  name="pilihan_asal_usul" type="text" class="form-control" id="pilihan_asal_usul2">
                <option value="">Pilihan Asal Usul</option>
                </option>
                <?php foreach($pilihan_asal_usul_ as $asal) : ?>
                  <?php $select = $asal->code == $pilihan_asal ? 'selected' : '' ?>
                  <option value="<?php echo $asal->code ?>" <?php echo $select ?>><?php echo $asal->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <?php  }else if($kodebarang_=='04') {?>
          
          <div class="form-group">
            <label>Kontruksi</label>
            <textarea class="form-control" id="konstruksi" name="konstruksi" placeholder="Kontruksi"><?php 
                if(set_value('konstruksi')=="" && isset($konstruksi)){
                  echo $konstruksi;
                }else{
                  echo  set_value('konstruksi');
                }
                ?></textarea>
          </div>
          <div class="form-group">
            <label>Panjang</label>
            <input type="number" class="form-control" name="panjang" id="panjang" placeholder="Panjang"  value="<?php
            if(set_value('panjang')=="" && isset($panjang)){
                echo $panjang;
              }else{
                echo  set_value('panjang');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Lebar</label>
            <input type="number" class="form-control" name="lebar" id="lebar" placeholder="Lebar"  value="<?php
            if(set_value('lebar')=="" && isset($lebar)){
                echo $lebar;
              }else{
                echo  set_value('lebar');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Luas</label>
            <input type="number" class="form-control" name="luas" id="luas1"  placeholder="Luas"  value="<?php
            if(set_value('luas')=="" && isset($luas)){
                echo $luas;
              }else{
                echo  set_value('luas');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Alamat Lokasi</label>
            <textarea class="form-control" id="letak_lokasi_alamat1" name="letak_lokasi_alamat" placeholder="Alamat Lokasi"><?php 
                if(set_value('letak_lokasi_alamat')=="" && isset($letak_lokasi_alamat)){
                  echo $letak_lokasi_alamat;
                }else{
                  echo  set_value('letak_lokasi_alamat');
                }
                ?></textarea>
          </div>
          <div class="form-group">
                  <label>Tanggal Dokumen</label>
                  <div id='dokumen_tanggal1' name="dokumen_tanggal" value="<?php
                  echo (!empty($dokumen_tanggal)) ? date("Y-m-d",strtotime($dokumen_tanggal)) :  date("d-m-Y");
                ?>"></div>
                </div>
          <div class="form-group">
            <label>Nomor Dokumen</label>
            <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor1" placeholder="dokumen_nomor"  value="<?php
            if(set_value('dokumen_nomor')=="" && isset($dokumen_nomor)){
                echo $dokumen_nomor;
              }else{
                echo  set_value('dokumen_nomor');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Status Tanah</label>
            <select  name="pilihan_status_tanah" type="text" class="form-control" id="pilihan_status_tanah">
                <option value="">Pilihan Status Tanah</option>
                </option>
                <?php foreach($pilihan_status_tanah_ as $tanah) : ?>
                  <?php $select = $tanah->code == $pilihan_status_tanah ? 'selected' : '' ?>
                  <option value="<?php echo $tanah->code ?>" <?php echo $select ?>><?php echo $tanah->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Nomor Kode Tanah</label>
            <input type="text" class="form-control" name="nomor_kode_tanah" id="nomor_kode_tanah1" placeholder="Nomor Kode Tanah"  value="<?php
            if(set_value('nomor_kode_tanah')=="" && isset($nomor_kode_tanah)){
                echo $nomor_kode_tanah;
              }else{
                echo  set_value('nomor_kode_tanah');
              }
              ?>">
          </div>

          <div class="form-group">
            <label>Pilihan Asal Usul</label>
            <select  name="pilihan_asal_usul" type="text" class="form-control" id="pilihan_asal_usul3">
                <option value="">Pilihan Asal Usul</option>
                </option>
                <?php foreach($pilihan_asal_usul_ as $asal) : ?>
                  <?php $select = $asal->code == $pilihan_asal ? 'selected' : '' ?>
                  <option value="<?php echo $asal->code ?>" <?php echo $select ?>><?php echo $asal->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <?php  }else if($kodebarang_=='05') {?>
          
          <div class="form-group">
            <label>Judul Buku Pencipta</label>
            <input type="text" class="form-control" name="buku_judul_pencipta" id="buku_judul_pencipta" placeholder="Judul Buku Pencipta"  value="<?php
            if(set_value('buku_judul_pencipta')=="" && isset($buku_judul_pencipta)){
                echo $buku_judul_pencipta;
              }else{
                echo  set_value('buku_judul_pencipta');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Spesifikasi Buku</label>
            <textarea class="form-control" id="buku_spesifikasi" name="buku_spesifikasi" placeholder="Spesifikasi Buku"><?php 
                if(set_value('buku_spesifikasi')=="" && isset($buku_spesifikasi)){
                  echo $buku_spesifikasi;
                }else{
                  echo  set_value('buku_spesifikasi');
                }
                ?></textarea>
          </div>
          <div class="form-group">
            <label>Budaya Asal Daerah</label>
            <input type="text" class="form-control" name="budaya_asal_daerah" id="budaya_asal_daerah"  placeholder="Budaya Asal Daerah"  value="<?php
            if(set_value('budaya_asal_daerah')=="" && isset($budaya_asal_daerah)){
                echo $budaya_asal_daerah;
              }else{
                echo  set_value('budaya_asal_daerah');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pencipta Budaya</label>
            <input type="text" class="form-control" name="budaya_pencipta" id="budaya_pencipta"  placeholder="Pencipta Budaya"  value="<?php
            if(set_value('budaya_pencipta')=="" && isset($budaya_pencipta)){
                echo $budaya_pencipta;
              }else{
                echo  set_value('budaya_pencipta');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Budaya Bahan</label>
            <select  name="pilihan_budaya_bahan" type="text" class="form-control" id="pilihan_budaya_bahan">
                <option value="">Pilihan Budaya Bahan</option>
                </option>
                <?php foreach($pilihan_budaya_bahan_ as $bahan) : ?>
                  <?php $select = $bahan->code == $pilihan_budaya_bahan ? 'selected' : '' ?>
                  <option value="<?php echo $bahan->code ?>" <?php echo $select ?>><?php echo $bahan->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Jenis Flora dan Fauna</label>
            <input type="text" class="form-control" name="flora_fauna_jenis" id="flora_fauna_jenis" placeholder="Jenis Flora dan Fauna"  value="<?php
            if(set_value('flora_fauna_jenis')=="" && isset($flora_fauna_jenis)){
                echo $flora_fauna_jenis;
              }else{
                echo  set_value('flora_fauna_jenis');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Ukuran Flora dan Fauna</label>
            <input type="number" class="form-control" name="flora_fauna_ukuran" id="flora_fauna_ukuran"  placeholder="Ukuran Flora dan Fauna"  value="<?php
            if(set_value('buku_judul_pencipta')=="" && isset($flora_fauna_ukuran)){
                echo $flora_fauna_ukuran;
              }else{
                echo  set_value('flora_fauna_ukuran');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Satuan</label>
            <select  name="pilihan_satuan" type="text" class="form-control" id="pilihan_satuan1">
                <option value="">Pilihan Status Tanah</option>
                </option>
                <?php foreach($pilihan_satuan_ as $satuan) : ?>
                  <?php $select = $satuan->code == $pilihan_satuan ? 'selected' : '' ?>
                  <option value="<?php echo $satuan->code ?>" <?php echo $select ?>><?php echo $satuan->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tahun Cetak Beli</label>
            <div id='tahun_cetak_beli' name="tahun_cetak_beli" value="<?php
            echo (!empty($tahun_cetak_beli)) ? date("Y-m-d",strtotime($tahun_cetak_beli)) :  date("d-m-Y");
          ?>"></div>
          </div>
          <div class="form-group">
            <label>Pilihan Asal Usul</label>
            <select  name="pilihan_asal_usul" type="text" class="form-control" id="pilihan_asal_usul4">
                <option value="">Pilihan Asal Usul</option>
                </option>
                <?php foreach($pilihan_asal_usul_ as $asal) : ?>
                  <?php $select = $asal->code == $pilihan_asal ? 'selected' : '' ?>
                  <option value="<?php echo $asal->code ?>" <?php echo $select ?>><?php echo $asal->value ?></option>
                <?php endforeach ?>
            </select>
          </div>

          <?php  }else if($kodebarang_=='06') {?>
          
          <div class="form-group">
            <label>Bangunan</label>
            <input type="text" class="form-control" name="bangunan" id="bangunan" placeholder="Bangunan"  value="<?php
            if(set_value('bangunan')=="" && isset($bangunan)){
                echo $bangunan;
              }else{
                echo  set_value('bangunan');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Pilihan Kontruksi Bertingkat</label>
            <select  name="pilihan_konstruksi_bertingkat" type="text" class="form-control" id="pilihan_konstruksi_bertingkat">
                <option value="">Pilihan Kontruksi Bertingkat</option>
                </option>
                <?php foreach($pilihan_konstruksi_bertingkat_ as $tingkat) : ?>
                  <?php $select = $tingkat->code == $pilihan_konstruksi_bertingkat ? 'selected' : '' ?>
                  <option value="<?php echo $tingkat->code ?>" <?php echo $select ?>><?php echo $tingkat->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Pilihan Kontruksi Beton</label>
            <select  name="pilihan_konstruksi_beton" type="text" class="form-control" id="pilihan_konstruksi_beton">
                <option value="">Pilihan Kontruksi Beton</option>
                </option>
                <?php foreach($pilihan_konstruksi_beton_ as $beton) : ?>
                  <?php $select = $beton->code == $pilihan_konstruksi_beton ? 'selected' : '' ?>
                  <option value="<?php echo $beton->code ?>" <?php echo $select ?>><?php echo $beton->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Luas</label>
            <input type="text" class="form-control" name="luas"  placeholder="Luas" id="luas2" value="<?php
            if(set_value('luas')=="" && isset($luas)){
                echo $luas;
              }else{
                echo  set_value('luas');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Lokasi</label>
            <textarea class="form-control" id="lokasi" name="lokasi" id="lokasi" placeholder="Lokasi"><?php 
                if(set_value('lokasi')=="" && isset($lokasi)){
                  echo $lokasi;
                }else{
                  echo  set_value('lokasi');
                }
                ?></textarea>
          </div>
          <div class="form-group">
            <label>Tanggal Dokumen</label>
            <div id='dokumen_tanggal2' name="dokumen_tanggal" value="<?php
            echo (!empty($dokumen_tanggal)) ? date("Y-m-d",strtotime($dokumen_tanggal)) :  date("d-m-Y");
          ?>"></div>
          <div class="form-group">
            <label>Nomor Dokumen</label>
            <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor2" placeholder="Nomor Dokumen"  value="<?php
            if(set_value('dokumen_nomor')=="" && isset($dokumen_nomor)){
                echo $dokumen_nomor;
              }else{
                echo  set_value('dokumen_nomor');
              }
              ?>">
          </div>
          <div class="form-group">
            <label>Tanggal Mulai</label>
            <div id='tanggal_mulai' name="tanggal_mulai" value="<?php
            echo (!empty($tanggal_mulai)) ? date("Y-m-d",strtotime($tanggal_mulai)) :  date("d-m-Y");
          ?>"></div>
          <div class="form-group">
            <label>Pilihan Status Tanah</label>
            <select  name="pilihan_status_tanah" type="text" class="form-control" id="pilihan_status_tanah1">
                <option value="">Pilihan Status Tanah</option>
                </option>
                <?php foreach($pilihan_status_tanah_ as $tanah) : ?>
                  <?php $select = $tanah->code == $pilihan_status_tanah ? 'selected' : '' ?>
                  <option value="<?php echo $tanah->code ?>" <?php echo $select ?>><?php echo $tanah->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Pilihan Asal Usul</label>
            <select  name="pilihan_asal_usul" type="text" class="form-control" id="pilihan_asal_usul5">
                <option value="">Pilihan Asal Usul</option>
                </option>
                <?php foreach($pilihan_asal_usul_ as $asal) : ?>
                  <?php $select = $asal->code == $pilihan_asal ? 'selected' : '' ?>
                  <option value="<?php echo $asal->code ?>" <?php echo $select ?>><?php echo $asal->value ?></option>
                <?php endforeach ?>
            </select>
          </div>
          <?php 
          }
        }
           ?>
<!--end from edit-->
    </div>
    </div>
    </div>
    </div>
</form>
</div>
