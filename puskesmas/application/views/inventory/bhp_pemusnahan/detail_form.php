
<script type="text/javascript">

  $(function(){
    <?php 
    if (isset($obat)) {
      if ($obat=="8") {
    ?>
      $("[name='tgl_kadaluarsa']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme , height: '30px'});
    <?php
      }else{}
    }
    ?>
      $('#btn-close-bhp_musnahkan').click(function(){
        close_popup_bhp();
      }); 

      $('#form-ss').submit(function(){
        if ($('#jumlah').val()==$('#jumlahopname').val()) {
          alert('Data jumlah tidak boleh sama dengan data opname')
        }else{
          var data = new FormData();
          $('#notice-musnahkan-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
          $('#notice-musnahkan').show();
          data.append('id_mst_inv_barang_habispakai_jenis', $('#id_mst_inv_barang_habispakai_jenis').val());
          data.append('id_inv_inventaris_habispakai_opname', $('#id_inv_inventaris_habispakai_opname').val());
          data.append('id_mst_inv_barang_habispakai', $('#id_mst_inv_barang_habispakai').val());
          data.append('batch', $('#batch').val());
          data.append('uraian', $('#uraian').val());
          data.append('jumlah', $('#jumlah').val());
          data.append('harga', $('#harga').val());
          data.append('jumlahopname', $('#jumlahopname').val());
          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."inventory/bhp_opname/".$action_musnahkan."_barang/" ?>',
              data : data,
              success : function(response){
                var res  = response.split("|");
                if(res[0]=="OK"){
                    $('#notice-musnahkan').hide();
                    $('#notice-musnahkan-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice-musnahkan').show();
                    $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
                    close_popup_opname();
                }
                else if(res[0]=="Error"){
                    $('#notice-musnahkan').hide();
                    $('#notice-musnahkan-content').html('<div class="alert">'+res[1]+'</div>');
                    $('#notice-musnahkan').show();
                }
                else{
                    $('#popup_content').html(response);
                }
            }
          });
          }
          return false;
      });
      var jmlasli = "<?php if(set_value('jumlah')=="" && isset($jml_awal)){
                            echo $jml_awal;
                          }else{
                            echo  set_value('jumlah');
                          } ?>";
      $("#jumlahopname").change(function(){
          if ($(this).val() < 0) {
            alert('Maaf, jumlah oname tidak boleh minus');
            $("#jumlahopname").val(jmlasli);
          }
          $('#selisih').val(jmlasli-$(this).val());
      });
      $('#selisih').val(jmlasli-$("#jumlahopname").val());
    });
</script>

<div style="padding:15px">
  <div id="notice-musnahkan" class="alert alert-success alert-dismissable" <?php if ($notice_musnahkan==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice-musnahkan-content">{notice_musnahkan}</div>
  </div>
  <div class="row">
    <?php echo form_open(current_url(), 'id="form-ss"') ?>
          <div class="box-body">
        <div class="box box-primary">
        <div class="row">
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tanggal Opname</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="tgl_opname" id="tgl_opname" placeholder="tanggal Barang" value="<?php 
                if(set_value('tgl_opname')=="" && isset($tgl_opname)){
                  echo date('d-m-Y',strtotime($tgl_opname));
                }else{
                  echo  set_value('tgl_opname');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Opname </div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="nomor_opname" id="nomor_opname" placeholder="Nomor Opname" value="<?php 
                if(set_value('nomor_opname')=="" && isset($nomor_opname)){
                  echo $nomor_opname;
                }else{
                  echo  set_value('nomor_opname');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Saksi 1</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="petugas_nama" id="petugas_nama" placeholder="Nama Saksi 1" value="<?php 
                if(set_value('petugas_nama')=="" && isset($saksi1_nama)){
                  echo $saksi1_nama;
                }else{
                  echo  set_value('petugas_nama');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Saksi 2</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="petugas_nip" id="petugas_nip" placeholder="Nama Saksi 2" value="<?php 
                if(set_value('petugas_nip')=="" && isset($saksi2_nama)){
                  echo $saksi2_nama;
                }else{
                  echo  set_value('petugas_nip');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nip Saksi Satu</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="petugas_nama" id="petugas_nama" placeholder="NIP Saksi 1" value="<?php 
                if(set_value('petugas_nama')=="" && isset($saksi1_nama)){
                  echo $saksi1_nama;
                }else{
                  echo  set_value('petugas_nama');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">NIP Saksi Dua</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="petugas_nip" id="petugas_nip" placeholder="NIP Saksi 2" value="<?php 
                if(set_value('petugas_nip')=="" && isset($saksi2_nip)){
                  echo $saksi2_nip;
                }else{
                  echo  set_value('petugas_nip');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Catatan</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="catatan" id="catatan" placeholder="petugas" value="<?php 
                if(set_value('catatan')=="" && isset($catatan)){
                  echo $catatan;
                }else{
                  echo  set_value('catatan');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Puskesmas</div>
            <div class="col-md-8">
              <select  name="codepus" id="puskesmas" name="puskesmas" class="form-control" disabled="">
              <?php foreach($kodepuskesmas as $pus) : ?>
                <?php $select = $pus->code == $code_cl_phc ? 'selected' : '' ?>
                <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
              <?php endforeach ?>
          </select>
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jenis Barang</div>
            <div class="col-md-8">
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
            </div>
          </div>
        </div>
        </div>
        </div>
        <div class="box box-warning">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Barang</div>
            <div class="col-md-8">
               <?php 
                    if(set_value('uraian')=="" && isset($uraian)){
                      echo $uraian;
                    }else{
                      echo  set_value('uraian');
                    }
                ?>
                <input type="hidden" class="form-control" name="id_inv_inventaris_habispakai_opname" id="id_inv_inventaris_habispakai_opname" placeholder="Nama Barang" value="<?php 
                if(set_value('id_inv_inventaris_habispakai_opname')=="" && isset($kode)){
                  echo $kode;
                }else{
                  echo  set_value('id_inv_inventaris_habispakai_opname');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Harga</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga" value="<?php 
                if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <?php 
            if (isset($id_mst_inv_barang_habispakai_jenis)) {
              if ($id_mst_inv_barang_habispakai_jenis=="8") {
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor Batch</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="batch" id="batch" placeholder="Nomor Batch" value="<?php 
                if(set_value('batch')=="" && isset($batch)){
                  echo $batch;
                }else{
                  echo  set_value('batch');
                }
                ?>" readonly="readonly">
            </div>
          </div>
          <?php
           # code...
              }else{

              }
            }
          ?>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jml_awal)){
                  echo $jml_awal;
                }else{
                  echo  set_value('jumlah');
                }
                ?>" readonly="readonly">
            </div>
            <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai_jenis" id="id_mst_inv_barang_habispakai_jenis" placeholder="Jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai_jenis')=="" && isset($id_mst_inv_barang_habispakai_jenis)){
                  echo $id_mst_inv_barang_habispakai_jenis;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai_jenis');
                }
                ?>" readonly="readonly">
                <input type="hidden" class="form-control" name="id_mst_inv_barang_habispakai" id="id_mst_inv_barang_habispakai" placeholder="Jumlah" value="<?php 
                if(set_value('id_mst_inv_barang_habispakai')=="" && isset($id_mst_inv_barang_habispakai)){
                  echo $id_mst_inv_barang_habispakai;
                }else{
                  echo  set_value('id_mst_inv_barang_habispakai');
                }
                ?>" readonly="readonly">
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jumlah Opname</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="jumlahopname" id="jumlahopname" placeholder="Jumlah Opname" value="<?php 
                if(set_value('jumlahopname')=="" && isset($jml_akhir)){
                  echo $jml_akhir;
                }else{
                  echo  set_value('jumlahopname');
                }
                ?>"  readonly="readonly">
            </div>
          </div>
        <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Selisih</div>
            <div class="col-md-8">
              <input type="text" class="form-control" name="selisih" id="selisih" placeholder="Selisih Opname" value="<?php 
                if(set_value('selisih')=="" && isset($jml_awal) && isset($jml_akhir)){
                  echo $jml_akhir-$jml_awal;
                }else{
                  echo  set_value('selisih');
                }
                ?>" readonly="readonly">
            </div>
          </div>
        </div>
        </div>
        <div class="box-footer">
         <!--   <button type="submit" class="btn btn-primary">Simpan</button>-->
            <button type="button" id="btn-close-bhp_musnahkan" class="btn btn-warning">Keluar</button>
        </div>
    </div>
</form>
</div>
<div id="popup_masterbarang" style="display:none">
  <div id="popup_mastertitle">Data master Barang</div>
  <div id="popup_mastercontent">&nbsp;</div>
</div>