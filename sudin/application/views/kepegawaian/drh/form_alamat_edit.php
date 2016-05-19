	<script type="text/javascript">
            $(document).ready(function () {

            	
			    $("select[name='code_cl_province']").change(function() {
			      $("select[name='code_cl_district']").html("<option>-</option>");
			      $("select[name='code_cl_kec']").html("<option>-</option>");
			      $("select[name='code_cl_village']").html("<option>-</option>");
			      $.get('<?php echo base_url()?>kepegawaian/drh/kota/' + $('select[name=code_cl_province]').val()+'/0', function(response) {
			        var data = eval(response);
			        $("select[name='code_cl_district']").html(data.code_cl_district);
			      }, "json");
			    });

			    $("select[name='code_cl_district']").change(function() {
			      $("select[name='code_cl_kec']").html("<option>-</option>");
			      $("select[name='code_cl_village']").html("<option>-</option>");
			      $.get('<?php echo base_url()?>kepegawaian/drh/kecamatan/' + $('select[name=code_cl_district]').val()+'/0', function(response) {
			        var data = eval(response);
			        $("select[name='code_cl_kec']").html(data.code_cl_kec);
			      }, "json");
			    });

			    $("select[name='code_cl_kec']").change(function() {
			      $("select[name='code_cl_village']").html("<option>-</option>");
			      $.get('<?php echo base_url()?>kepegawaian/drh/desa/' + $('select[name=code_cl_kec]').val()+'/0', function(response) {
			        var data = eval(response);
			        $("select[name='code_cl_village']").html(data.code_cl_village);
			      }, "json");
			    });



			    $.get('<?php echo base_url()?>kepegawaian/drh/kota/{propinsi}/{kota}', function(response) {
			      var data = eval(response);
			      $("select[name='code_cl_district']").html(data.kota);
			    }, "json");

			    $.get('<?php echo base_url()?>kepegawaian/drh/kecamatan/{kota}/{kecamatan}', function(response) {
			      var data = eval(response);
			      $("select[name='code_cl_kec']").html(data.kecamatan);
			    }, "json");

			    $.get('<?php echo base_url()?>kepegawaian/drh/desa/{kecamatan}/{desa}', function(response) {
			      var data = eval(response);
			      $("select[name='code_cl_village']").html(data.code_cl_village);
			    }, "json");

			    $('#btn-close').click(function(){
			        close_popup();
			      });

			     $('#form-ss').submit(function(){
	            var data = new FormData();
	            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
	            $('#notice').show();

	            data.append('nip_nit', $('input[name="nip_nit"]').val());
	            data.append('urut', $('input[name="urut"]').val());
	            data.append('alamat', $('input[name="alamat"]').val());
	            data.append('rt', $('#rt').val());
	            data.append('rw', $('#rw').val());
	            data.append('code_cl_province', $('#propinsi').val());
	            data.append('code_cl_district', $('#kota').val());
	            data.append('code_cl_kec', $('#kecamatan').val());
	            data.append('code_cl_village', $('#desa').val());
	            $.ajax({
	                cache : false,
	                contentType : false,
	                processData : false,
	                type : 'POST',
	                url : '<?php echo base_url()."kepegawaian/drh/".$action."/".$id."/".$urut ?>',
	                data : data,
	                success : function(response){
	                  var res  = response.split("|");
	                  if(res[0]=="OK"){
	                      $('#notice').hide();
	                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
	                      $('#notice').show();
                          var id          = res[1]; 
                      	  edit_alamat(id,urut); 
	                      $("#jqxgrid_alamat").jqxGrid('updatebounddata', 'cells');
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


	<form action="<?php echo base_url()?>kepegawaian/drh/{action}/{id}/" id="form-ss" method="POST" name="">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="form-group">
							<label>Alamat</label>
							<input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" value="<?php 
				              if(set_value('alamat')=="" && isset($alamat)){
				                echo $alamat;
				              }else{
				                echo  set_value('alamat');
				              }
				            ?>">
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>RT</label>
							<input type="number" class="form-control" id="rt" name="rt" placeholder="RT" value="<?php 
				              if(set_value('rt')=="" && isset($rt)){
				                echo $rt;
				              }else{
				                echo  set_value('rt');
				              }
				            ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>RW</label>
							<input type="number" class="form-control" id="rw" name="rw" placeholder="RW" value="<?php 
				              if(set_value('rw')=="" && isset($rw)){
				                echo $rw;
				              }else{
				                echo  set_value('rw');
				              }
				            ?>">
						</div>
						</div>
						<div class="input-group">
					        <span class="input-group-addon">
					          <div style="width:80px">Provinsi</div>
					        </span>
					        <select class="form-control" id="propinsi" name="code_cl_province">{provinsi_option}</select>
					    </div>
					      <br>
					    <div class="input-group">
					        <span class="input-group-addon">
					          <div style="width:80px">Kota / Kab</div>
					        </span>
					        <select class="form-control" id="kota" name="code_cl_district"></select>
					    </div>
					      <br>
					    <div class="input-group">
					        <span class="input-group-addon">
					          <div style="width:80px">Kecamatan</div>
					        </span>
					        <select class="form-control" id="kecamatan" name="code_cl_kec"></select>
					    </div>
					      <br>
					    <div class="input-group">
					        <span class="input-group-addon">
					          <div style="width:80px">Desa</div>
					        </span>
					        <select class="form-control" id="desa" name="code_cl_village"></select>
					    </div>
					      
						
					</div>
						<div class="box-footer pull-right">
				          <button type="submit" class="btn btn-primary">Simpan</button>
				          <button type="reset" class="btn btn-warning">Ulang</button>
				          <button type="button" id="btn-close" class="btn btn-success" >Batal</button>
				        </div>
					<!-- <div id='jqxWidget'>
	        </div>
	        <div style="font-size: 13px; font-family: Verdana;" id="selectionlog">
	        </div> -->
				</div>
			</div>
		</div>
	</form>
