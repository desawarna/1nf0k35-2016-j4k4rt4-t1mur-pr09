
<script type="text/javascript">
		$(document).ready(function () {               
                // Create a jqxDateTimeInput
				$('#tgl').datepicker({
					format: 'dd/mm/yyyy'					
				})
                
            });
	$("select[name='pilih_sts']").change(function(){		
		if($(this).val() != '0'){
			var data = $(this).val().split('#');
			//2015-11-12#P3172100103#KEL. UTAN KAYU UTARA#2400.00
			//tgl#code#nama#jumlah
			
			if(data[1].slice(-2) == "01"){
				document.getElementById("detail").value='Terima setoran tunai PKM '+data[2];
			}else{
				document.getElementById("detail").value='Terima setoran tunai PKL '+data[2];
			}
			document.getElementById("jumlah").value=data[3];
			
			
			document.getElementById("pilih_tipe").value = 'penerimaan';
			//document.getElementById("data_kode_rekening").disabled = true;
		}else{
			document.getElementById("detail").value='';
			document.getElementById("jumlah").value='';
			//document.getElementById("data_kode_rekening").disabled=false;
		}					
	});
	
	$("select[name='pilih_tipex']").change(function(){		
		if($(this).val() != '0'){
			$.post( '<?php echo base_url()?>keuangan/bku_penerimaan/get_rekening_by_type', {tipe:$(this).val()},function( data ) {
				$('#data_kode_rekening')
					.find('option')
					.remove()
					.end()					
				;
				var row = data.split('~');						
				
				var	select = document.getElementById('data_kode_rekening');

				for (var i = 0; i<row.length-1; i++){
					var col = row[i].split('#');
					var opt = document.createElement('option');
					opt.value = col[0];
					opt.innerHTML = col[1];
					select.appendChild(opt);
				}
				
					
			});
		}else{
			document.getElementById("data_kode_rekening").disabled=false;
		}					
	});

    $(function(){

      $('#btn-close').click(function(){
        close_popup();
      }); 
        $('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();

            data.append('tahun', $('#tahun').val());            
            data.append('kode_rekening', $('#data_kode_rekening').val());            
            data.append('target', $('#target').val());
			
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."keuangan/target_penerimaan/pop_rekening" ?>',
                data : data,
                success : function(response){
                  var res  = response.split("|");
                  if(res[0]=="OK"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#notice').show();

                      $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
                      close_popup();
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
            <div class="col-md-12">
			<?php
			if(empty($this->session->userdata('bku_penerimaan_tahun')) ){			
				$this->session->set_userdata('bku_penerimaan_tahun', date('Y'));
			} 
			
			?>
			<div class="form-group">
			  <label for="exampleInputPassword1">Tahun</label>			  
			  <input readonly value="<?=$this->session->userdata('bku_penerimaan_tahun')?>" type="text" id="tahun" name="tahun" class="form-control">
			</div>
			<div class="form-group">
			  <label for="exampleInputPassword1">Dari Rekening</label>
			  <select class="form-control" id="data_kode_rekening" name="data_kode_rekening">
			  <option value="0">Pilih Kode Rekening</option>
				<?php foreach($list_rekening as $ls){ ?>
					<option value="<?=$ls['code']?>"><?=$ls['kode_rekening']." ".$ls['uraian']?></option>
				<?php } ?>
			  </select>
			</div>
			<div class="form-group">
			  <label for="exampleInputPassword1">Jumlah</label>			  
			  <input type="text" name="target" id="target" class="form-control">
			</div>
			
			
			
		</div>
		
		
        </div>
        <div class="box-footer ">            
            <button type="button" id="btn-close" class="btn btn-warning pull-left">Batal</button>
			<button type="submit" class="btn btn-primary pull-right">Simpan</button>
        </div>
    </div>
</form>
</div>
