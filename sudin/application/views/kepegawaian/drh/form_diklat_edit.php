	<script type="text/javascript">
            $(document).ready(function () {

			    $('#btn-close-diklat').click(function(){
			        close_popup_diklat();
			      });

    			$("#tgl_diklat").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});

			     $('#form-ss-diklat').submit(function(){
	            var data = new FormData();
	            $('#notice-content-diklat').html('<div class="alert-diklat">Mohon tunggu, proses simpan data....</div>');
	            $('#notice-diklat').show();

	            data.append('id_mst_peg_kursus', $('select[name="id_mst_peg_kursus"]').val());
	            data.append('nip_nit', $('input[name="nip_nit_diklat"]').val());
	            data.append('nama_diklat', $('input[name="nama_diklat"]').val());
	            data.append('lama_diklat', $('#lama_diklat').val());
	            data.append('tgl_diklat', $('#tgl_diklat').val());
	            data.append('tar_penyelenggara', $('#tar_penyelenggara').val());
	            $.ajax({
	                cache : false,
	                contentType : false,
	                processData : false,
	                type : 'POST',
	                url : '<?php echo base_url()."kepegawaian/drh/".$action."/".$id."/".$id_mst_peg_kursus ?>',
	                data : data,
	                success : function(response){
	                  var res  = response.split("|");
	                  if(res[0]=="OK"){
	                      $('#notice-diklat').hide();
	                      $('#notice-content-diklat').html('<div class="alert">'+res[1]+'</div>');
	                      $('#notice-diklat').show();
	                      $("#jqxgrid_diklat").jqxGrid('updatebounddata', 'cells');
	                      close_popup_diklat();
	                  }
	                  else if(res[0]=="Error"){
	                      $('#notice-diklat').hide();
	                      $('#notice-content-diklat').html('<div class="alert-diklat">'+res[1]+'</div>');
	                      $('#notice-diklat').show();
	                  }
	                  else{
	                      $('#popup_content_diklat').html(response);
	                  }
	              }
	            });

	            return false;
	        });


			

            });
        </script>


	<form action="<?php echo base_url()?>kepegawaian/drh/{action}/{id}/" id="form-ss-diklat" method="POST" name="">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
					<!-- <div class="notice" ><?php  $notice; ?></div> -->
						<div class="form-group">
							<label>Jenis Diklat</label>
							<select type="text" name="id_mst_peg_kursus" id="id_mst_peg_kursus" class="form-control" >
								<option value="">--Pilih Diklat--</option>
								<?php foreach ($peg_kursus as $row ) { ?>
								<option value="<?php echo $row->id_kursus; ?>" ><?php echo $row->nama_kursus; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Nama Diklat</label>
							<input type="text" class="form-control" id="nama_diklat" name="nama_diklat" placeholder="Nama Diklat" value="<?php 
				              if(set_value('nama_diklat')=="" && isset($nama_diklat)){
				                echo $nama_diklat;
				              }else{
				                echo  set_value('nama_diklat');
				              }
				            ?>">
						</div>
						</div>
						<div class="input-group">
					        <label>Lama Diklat</label>
					        <input type="text" class="form-control" id="lama_diklat" name="lama_diklat" placeholder="Lama diklat" value="<?php 
				              if(set_value('lama_diklat')=="" && isset($lama_diklat)){
				                echo $lama_diklat;
				              }else{
				                echo  set_value('lama_diklat');
				              }
				            ?>">
					    </div>
					      <br>
					    <div class="input-group">
					        <label>Tanggal Diklat</label>
					        <div type="date" id='tgl_diklat' name="tgl_diklat" value="<?php
				                echo (set_value('tgl_diklat')!="") ? date("Y-m-d",strtotime(set_value('tgl_diklat'))) : "";
				                ?>"></div>
					    </div>
					      <br>
					    <div class="input-group">
					        <label>Penyelenggara</label>
					        <input type="text" class="form-control" id="tar_penyelenggara" name="tar_penyelenggara" placeholder="Penyelenggara" value="<?php 
				              if(set_value('tar_penyelenggara')=="" && isset($tar_penyelenggara)){
				                echo $tar_penyelenggara;
				              }else{
				                echo  set_value('tar_penyelenggara');
				              }
				            ?>">
					    </div>
					      <br>
					</div>
						<div class="box-footer pull-right">
				          <button type="submit" class="btn btn-primary">Simpan</button>
				          <button type="reset" class="btn btn-warning">Ulang</button>
				          <button type="button" id="btn-close-diklat" class="btn btn-success" >Batal</button>
				        </div>
					<!-- <div id='jqxWidget'>
	        </div>
	        <div style="font-size: 13px; font-family: Verdana;" id="selectionlog">
	        </div> -->
				</div>
			</div>
		</div>
	</form>
