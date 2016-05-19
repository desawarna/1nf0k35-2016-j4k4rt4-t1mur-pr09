<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<form action="#" method="POST" name="frmPegawai">
  <div class="row" style="margin: 15px 5px 15px 5px">
    <div class="col-sm-8">
 
    </div>
    <div class="col-sm-12" style="text-align: right">
      <button type="button" name="btn_keuangan_add_sts" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Tambah STS</button>
      <button type="button" name="btn_keuangan_close" class="btn btn-primary"><i class='fa fa-close'></i> &nbsp; Batal</button>
    </div>
  </div>

  <div class="row" style="margin: 5px">
          <div class="col-md-12">
            <div class="box box-primary">

              <div class="row" style="margin: 5px">
                <div class="col-md-8">
                  <input type="hidden" class="form-control" name="sts_id" id="id_sts">
                </div>
              </div>
             
              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                 Nomor
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="sts_nomor" placeholder="Nomor" value="<?=$nomor?>">
                </div>
              </div>

              <div class="row" style="margin: 5px">
                <div class="col-md-4" style="padding: 5px">
                  Tanggal 
                </div>
                <div class="col-md-8">
                  <div id='tgl' name="sts_tgl" value="<?=date("m/d/Y")?>" >
                  </div>
                </div>
              </div>

              <br>
            </div>
          </div>
  </div>
</form>

<script>

 function kodeSTS(){
      $.ajax({
      url: "<?php echo base_url().'keuangan/sts/kodeSts';?>",
      dataType: "json",
      success:function(data){ 
        $.each(data,function(index,elemet){
          var sts = elemet.kodests.split(".")
          $("#id_sts").val(sts[0]);
        });
      }
      });
      return false;
  }

  $(function () { 
    tabIndex = 1;
    kodeSTS();

    $("[name='sts_tgl']").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:30});

    
   $("[name='btn_keuangan_close']").click(function(){
        $("#popup_keuangan_sts").jqxWindow('close');
    });

    $("[name='btn_keuangan_add_sts']").click(function(){
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('id_sts',          $("[name='sts_id']").val());
        data.append('nomor',           $("[name='sts_nomor']").val());
        data.append('tgl',             $("[name='sts_tgl']").val());
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."keuangan/sts/add_sts"   ?>',
            data : data ,
            success : function(response){
              a = response.split(" | ");
              if(a[0]=="OK"){
                $("#popup_keuangan_sts").jqxWindow('close');
                alert("Data STS berhasil disimpan.");
                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                window.location.href="<?php echo base_url().'keuangan/sts/detail';?>/" + a[1];
              }else{
                $('#popup_keuangan_sts_content').html(response);
              }
            }
         });

        return false;
    });
  });

</script>
