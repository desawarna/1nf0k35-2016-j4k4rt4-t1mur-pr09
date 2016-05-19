
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

            data.append('tgl', $('#tgl').val());
            data.append('sts', $('#pilih_sts').val());
            data.append('kode_rekening', $('#data_kode_rekening').val());
            data.append('tipe', $('#pilih_tipe').val());
            data.append('uraian', $('#detail').val());
            data.append('jumlah', $('#jumlah').val());
            data.append('catatan', $('#catatan').val());
            data.append('data_detail', $('#data_detail').val());
            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."keuangan/bku_penerimaan/pop_bku_add" ?>',
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
            <div class="col-md-6">
			<div class="form-group">
			  <label for="exampleInputEmail1">Tanggal</label>
			  <input id='tgl' value="<?php echo date("d/m/Y") ?>" type="text" required class="form-control"  placeholder="">
			  
			  
			</div>
			<div class="form-group">
			  <label for="exampleInputPassword1">Dari STS</label>
			  <select <?=$setor==1?'disabled':''?> id="pilih_sts" name="pilih_sts" class="form-control">
					<option value="0">Pilih STS</option>
			  <?php foreach($list_sts as $ls){ ?>
					<option value="<?php echo $ls['tgl']."#".$ls['code_cl_phc']."#".$ls['value']."#".$ls['total'] ?>"> <?php echo $ls['value']." - Rp ".$ls['total'] ?> </option>
			  <?php } ?>
				
				
			  </select>
			</div>
			<div class="form-group">
			  <label for="exampleInputPassword1">Kode Rekening</label>			  
			  <select class="form-control" id="data_kode_rekening" name="data_kode_rekening">
			  <option value="0">Pilih Kode Rekening</option>
				<?php foreach($list_rekening as $ls){ ?>
					<option value="<?=$ls['code']?>"><?=$ls['kode_rekening']." ".$ls['uraian']?></option>
				<?php } ?>
			  </select>
			</div>
			
			
		</div>
		
		<div class="col-md-6">
			<div class="form-group">
			  <label for="exampleInputPassword1">Tipe</label>
			  
			
			<?php if($setor == 1){ ?>
				<input type="text" readonly class="form-control" value="pengeluaran" name="pilih_tipe" id="pilih_tipe" >
				
			<?php }else{	?>
				<select <?=$setor==1?'disabled':''?> class="form-control" name="pilih_tipe" id="pilih_tipe">
					<option value="0"> Pilih Tipe</option>
					<option value="penerimaan"> Penerimaan</option>
					<option value="pengeluaran"> Pengeluaran</option>
				  </select>
			<?php }	?>
			</div>
			<input type="hidden" value="<?=$setor==1?$list_detail:'0'?>" name="data_detail" id="data_detail" >
			<div class="form-group">
			  <label  for="exampleInputPassword1">Uraian</label>
			  <input required value="<?=$setor==1?$uraian:''?>" type="text" id="detail"  name="uraian" class="form-control" id="exampleInputPassword1" placeholder="Uraian">
			</div>
			<div class="form-group">
			  <label for="exampleInputPassword1">Jumlah</label>
			  <input required value="<?=$setor==1?$total:''?>" type="text" id="jumlah" class="form-control" id="exampleInputPassword1" placeholder="Nominal">
			</div>
			<div class="form-group">
			  <label for="exampleInputPassword1">Catatan</label>
			  <input type="text" id="catatan" name="catatan" class="form-control" id="exampleInputPassword1" placeholder="Catatan">
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
