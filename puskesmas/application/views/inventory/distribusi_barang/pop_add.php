
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
          <div class="box-body">
           <div class="row">
            <div class="col-md-6">
  			
  			     <div class="form-group">
                <label>Puskesmas tujuan</label>
                <select name="code_cl_phc2" class="form-control" id="code_cl_phc2">
                    <?php foreach ($datapuskesmas as $row ) { ;?>
                    <option value="<?php echo $row->code; ?>" onchange="" ><?php echo $row->value; ?></option>
                    <?php	} ;?>
                  </select>
              </div>
            </div>
            <div class="col-md-6">
  			     <div class="form-group">
                <label>Ruangan tujuan (opsional)</label>
                <select name="code_ruangan2" class="form-control" id="code_ruangan2">
                  <option value="0">Pilih Ruangan</option>
                </select>
              </div>
            </div>
  			   </div>
           <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal pemindahan</label>
                <div id='tgl' name="tgl" value="<?php echo date('d-m-Y')?>"></div>
                <!--<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo date('d-m-Y')?>"  placeholder="Tanggal" >-->
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ">           
                <label>Pindahkan Barang?</label><br>
                   <button type="button" id="btn-close" class="btn btn-primary">Batal</button>
                   <button type="submit" class="btn btn-danger">Pindah <i class='fa fa-sign-in'></i> </button>
              </div>
             </div>
           </div>
            <br>
			     <label>Daftar barang yang akan dipindahkan:</label>
            <table class="table table-bordered">
              <tr>
                <th align="center" width="5%">#</th>
                <th align="center" width="16%">Kode Barang</th>
                <th width="69%">Nama Barang</th>
                <th align="center" width="10%">Kondisi</th>
                
              </tr>
  					<input type="hidden" name="data_barang" value="<?php echo $data_barang?>" id="data_barang" >
  					<?php
  					$no = 1;
  					$data = explode("_tr_",$data_barang);
  					for($i=0; $i<count($data)-1; $i++ ){
  						$data2 = explode("_td_",$data[$i]);
              $kode = substr(chunk_split($data2[1], 2, '.'), 0,14);
  					?>
              <tr>
                <td align="center"><?php echo $no?>.</td>
                <td align="center"><?php echo $kode?></td>
                <td><?php echo str_replace("_", " ", $data2[2])?></td>                      
                <td align="center"><?php echo $data2[3]?></td>                      
              </tr>
  					<?php
  					$no++;
  					} ?>
            </table>
        </div>
    </div>
</form>
</div>

<script type="text/javascript">

$(function () {	
    $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '  31px'});
		//$("#tanggal").datepicker( "option", "dateFormat","dd-mm-yy");

		$('#btn-close').click(function(){
			close_popup();
		  });	

	    $('#code_cl_phc2').change(function(){
	      var code_cl_phc = $(this).val();
	      $.ajax({
	        url : '<?php echo site_url('inventory/distribusibarang/get_ruangan_pop') ?>',
	        type : 'POST',
	        data : 'code_cl_phc=' + code_cl_phc,
	        success : function(data) {
	          $('#code_ruangan2').html(data);
	        }
	      });

	      return false;
	    }).change();
		
		$('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();

            data.append('tanggal', $('#tgl').val());
            data.append('code_cl_phc2', $('#code_cl_phc2').val());
            data.append('code_ruangan2', $('#code_ruangan2').val());
            data.append('data_barang', $('#data_barang').val());
            
			
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/distribusibarang/pop_add/".$data_barang ?>',
                data : data,
                success : function(response){
                  var res  = response.split("|");
                  if(res[0]=="OK"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#notice').show();

                      $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                      close_popup();
                      $('#code_cl_phc').change();
                  }
                  else if(res[0]=="Error"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#notice').show();
                  }
                  else{
                      $('#popup_content').html(response);
                  }
              }
            });

            return false;
        });

		
	});

</script>