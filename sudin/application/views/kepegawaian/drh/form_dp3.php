	<script type="text/javascript">
            $(document).ready(function () {

			    $('#btn-close-dp3').click(function(){
			        close_popup_dp3();
			      });


			     $('#form-ss-dp3').submit(function(){
	            var data = new FormData();
	            $('#notice-content-dp3').html('<div class="alert-dp3">Mohon tunggu, proses simpan data....</div>');
	            $('#notice-dp3').show();

	            data.append('tahun', $('select[name="tahun"]').val());
	            data.append('setia', $('input[name="setia"]').val());
	            data.append('prestasi', $('input[name="prestasi"]').val());
	            data.append('tanggungjawab', $('input[name="tanggungjawab"]').val());
	            data.append('taat', $('input[name="taat"]').val());
	            data.append('jujur', $('input[name="jujur"]').val());
	            data.append('kerjasama', $('input[name="kerjasama"]').val());
	            data.append('pimpin', $('input[name="pimpin"]').val());
	            data.append('prakarsa', $('input[name="prakarsa"]').val());
	            data.append('jumlah', $('input[name="jumlah"]').val());
	            data.append('ratarata', $('input[name="ratarata"]').val());
	            $.ajax({
	                cache : false,
	                contentType : false,
	                processData : false,
	                type : 'POST',
	                url : '<?php echo base_url()."kepegawaian/drh/".$action."/".$id."/" ?>',
	                data : data,
	                success : function(response){
	                  var res  = response.split("|");
	                  if(res[0]=="OK"){
	                      $('#notice-dp3').hide();
	                      $('#notice-content-dp3').html('<div class="alert">'+res[1]+'</div>');
	                      $('#notice-dp3').show();
	                      $("#jqxgrid_dp3").jqxGrid('updatebounddata', 'cells');
	                      close_popup_dp3();
	                  }
	                  else if(res[0]=="Error"){
	                      $('#notice-dp3').hide();
	                      $('#notice-content-dp3').html('<div class="alert-dp3">'+res[1]+'</div>');
	                      $('#notice-dp3').show();
	                  }
	                  else{
	                      $('#popup_content_dp3').html(response);
	                  }
	              }
	            });

	            return false;
	        });


			

            });
        </script>


	<form action="<?php echo base_url()?>kepegawaian/drh/{action}/{id}/" id="form-ss-dp3" method="POST" name="">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
					<div class="notice" ><?php echo $notice; ?></div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Tahun</label>
							<select name="tahun" class="form-control">
								<option selected="selected"></option>
								<?php for ($i=date('Y'); $i >=date('Y')-32 ; $i-=1) { 
									echo "<option value='$i'> $i </option>"
								;} ?>
							</select>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Kesetiaan</label>
							<input type="text" class="form-control" id="setia" name="setia"  value="<?php 
				              if(set_value('setia')=="" && isset($setia)){
				                echo $setia;
				              }else{
				                echo  set_value('setia');
				              }
				            ?>">
						</div>
						</div>
						<div class="col-md-6" >
						<div class="input-group">
					        <label>Prestasi</label>
					        <input type="text" class="form-control" id="prestasi" name="prestasi" value="<?php 
				              if(set_value('prestasi')=="" && isset($prestasi)){
				                echo $prestasi;
				              }else{
				                echo  set_value('prestasi');
				              }
				            ?>">
					    </div>
					    </div>
					    <div class="col-md-6">
					    <div class="input-group">
					        <label>Tanggung Jawab</label>
					        <input type="text" class="form-control" id="tanggungjawab" name="tanggungjawab"  value="<?php 
				              if(set_value('tanggungjawab')=="" && isset($tanggungjawab)){
				                echo $tanggungjawab;
				              }else{
				                echo  set_value('tanggungjawab');
				              }
				            ?>">
					    </div>
					    </div>
					      <br>
					    <div class="col-md-6">
					    <div class="input-group">
					        <label>Ketaatan</label>
					        <input type="text" class="form-control" id="taat" name="taat"  value="<?php 
				              if(set_value('taat')=="" && isset($taat)){
				                echo $taat;
				              }else{
				                echo  set_value('taat');
				              }
				            ?>">
					    </div>
					    </div>
					      <br>
					    <div class="col-md-6">
					    <div class="input-group">
					        <label>Kejujuran</label>
					        <input type="text" class="form-control" id="jujur" name="jujur" value="<?php 
				              if(set_value('jujur')=="" && isset($jujur)){
				                echo $jujur;
				              }else{
				                echo  set_value('jujur');
				              }
				            ?>">
					    </div>
					    </div>
					      <br>
					    <div class="col-md-6">
					    <div class="input-group">
					        <label>Kerjasama</label>
					        <input type="text" class="form-control" id="kerjasama" name="kerjasama"  value="<?php 
				              if(set_value('kerjasama')=="" && isset($kerjasama)){
				                echo $kerjasama;
				              }else{
				                echo  set_value('kerjasama');
				              }
				            ?>">
					    </div>
					    </div>
					    <div class="col-md-6">
					    <div class="input-group">
					        <label>Kepemimpinan</label>
					        <input type="text" class="form-control" id="pimpin" name="pimpin"  value="<?php 
				              if(set_value('pimpin')=="" && isset($pimpin)){
				                echo $pimpin;
				              }else{
				                echo  set_value('pimpin');
				              }
				            ?>">
					    </div>
					    </div>
					      <br>
					    <div class="col-md-6">
					    <div class="input-group">
					        <label>Prakarsa</label>
					        <input type="text" class="form-control" id="prakarsa" name="prakarsa"  value="<?php 
				              if(set_value('prakarsa')=="" && isset($prakarsa)){
				                echo $prakarsa;
				              }else{
				                echo  set_value('prakarsa');
				              }
				            ?>">
					    </div>
					    </div>
					</div>
						<div class="box-footer pull-right">
				          <button type="submit" class="btn btn-primary">Simpan</button>
				          <button type="reset" class="btn btn-warning">Ulang</button>
				          <button type="button" id="btn-close-dp3" class="btn btn-success" >Batal</button>
				        </div>
					<!-- <div id='jqxWidget'>
	        </div>
	        <div style="font-size: 13px; font-family: Verdana;" id="selectionlog">
	        </div> -->
				</div>
			</div>
		</div>
	</form>
