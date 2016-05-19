<section class="content">
<form action="<?php echo base_url()?>inventory/bhp_opname/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12 ">
      <!-- general form elements -->
      <div class="box box-primary">
          <div class="box-footer">
            <div class="row"> 

              <div class="col-md-3 pull-right">
                <button type="button" name="btn-simpan" class="btn btn-primary"></i> &nbsp; Simpan Perubahan</button>
              </div>

            </div>
        <div class="box-body">
            <div class="row">
            <div class="col-md-7">
              <div class="row">
              <div class="col-md-4" style="padding-top:5px;"><label> Akun Penerimaan STS </label> </div>
              <div class="col-md-8">
                <select  name="akun_penerimaan_sts" type="text" class="form-control">
                <?php foreach($akun_option as $penerimaan) : ?>
                    <?php
                        if(set_value('akun_penerimaan_sts')=="" && isset($akun_penerimaan_sts)){
                         $value = $akun_penerimaan_sts;
                        }else{
                         $value = set_value('akun_penerimaan_sts');
                        }
                        $select = $penerimaan->id_mst_akun == $value ? 'selected' : '' ;
                    ?>
                     <option value="<?php echo $penerimaan->id_mst_akun ?>" <?php echo $select ?>><?php echo $penerimaan->kode?>-<?php echo $penerimaan->uraian ?></option>
                      <?php endforeach ?>
                 </select>
               </div> 
            </div>
           </div>
        </div>
      </div>
     
     <div class="box-body">
     <div class="row">
              <div class="col-md-7">
            <div class="row">
              <div class="col-md-4" style="padding-top:5px;"><label> Akun Penyetoran STS </label> </div>
              <div class="col-md-8">
                <select  name="akun_penyetoran_sts" type="text" class="form-control">
                <?php foreach($akun_option as $penyetoran) : ?>
                    <?php
                        if(set_value('akun_penyetoran_sts')=="" && isset($akun_penyetoran_sts)){
                         $value = $akun_penyetoran_sts;
                        }else{
                         $value = set_value('akun_penyetoran_sts');
                        }
                        $select = $penyetoran->id_mst_akun == $value ? 'selected' : '' ;
                    ?>
                     <option value="<?php echo $penyetoran->id_mst_akun ?>" <?php echo $select ?>><?php echo $penyetoran->kode?>-<?php echo $penyetoran->uraian ?></option>
                      <?php endforeach ?>
                 </select>
               </div> 
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</form>
</section>

<script type="text/javascript">

      $("[name='btn-simpan']").click(function(){
        var data = new FormData();

        data.append('akun_penerimaan_sts',  $("[name='akun_penerimaan_sts']").val());
        data.append('akun_penyetoran_sts',  $("[name='akun_penyetoran_sts']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."mst/keuangan_sts/pengaturan_sts_save" ?>',
            data : data,
            success : function(response){
              if(response=="OK"){
                alert("Data berhasil disimpan.");
              }else{
                alert("Data gagal disimpan.");
              }
            }
        });

        return false;
    });
</script>


