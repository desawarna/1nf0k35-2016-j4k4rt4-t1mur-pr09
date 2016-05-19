<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>  <i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-warning">
			<div class="box-body">
				<div class="row">
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Pilih Tanggal</label>
				     		<div id="tgl"></div>
				  		</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label>&nbsp;</label>
				     		<div id="tgl1"></div>
				  		</div>
				  	</div>
				  	<div class="col-md-12">
						<div class="form-group">
							<label>Pilih Puskesmas</label>
				     		<select name="code_cl_phc" class="form-control" id="code_cl_phc">
								<?php foreach ($kodepuskesmas as $row ) { ;?>
								<option value="<?php echo $row->code; ?>" <?php //if($kode==$row->code) echo "selected"; ?> ><?php echo $row->value; ?></option>
							<?php	} ;?>
				     		</select>
				  		</div>
				  	</div>
				  	<div class="col-md-12">
				  	<div class="form-group">
							<label>Jenis Barang</label>
					  		<select name="jenisbarang" id="jenisbarang" class="form-control">
				     				<option value="all">All</option>
								<?php foreach ($jenisbaranghabis as $row ) { ;?>
								<?php $select = $row->id_mst_inv_barang_habispakai_jenis == set_value('jenisbarang') ? 'selected=selected' : '' ?>
									<option value="<?php echo $row->id_mst_inv_barang_habispakai_jenis; ?>"  <?php echo $select ?> ><?php echo $row->uraian; ?></option>
								<?php	} ;?>
					     	</select>
				     	</div>
				     </div>
				  	<div class="col-md-12">
						<div class="form-group pull-right">
            				<button onClick="doExport();" type="button"  class="btn btn-warning"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>
						</div>
				  	</div>
				</div>
			</div>
		</div><!-- /.box -->
	</div><!-- /.box -->
</div><!-- /.box -->

<script>
	$(function () {	
		$("#menu_laporan").addClass("active");
		$("#menu_inventory_lap_bhp_pengeluaran").addClass("active");

		$("#tgl").jqxDateTimeInput({
			formatString: 'dd-MM-yyyy', 
			theme: theme, 
			height: '31px', width: '100%'
 		});	
 		var date = new Date();
		$('#tgl ').jqxDateTimeInput('setDate', new Date(date.getFullYear(), date.getMonth(), 1));
		$("#tgl1").jqxDateTimeInput({
			formatString: 'dd-MM-yyyy', 
			theme: theme, 
			height: '31px', width: '100%'
 		});	
    	$("#tgl1").jqxDateTimeInput('setDate', new Date(date.getFullYear(), date.getMonth()+1,0));

	});
	$('#tgl ').change(function(){
		var tgl = $(this).val().split("-") ;
		var bulan = parseInt(tgl[1]) + parseInt(1);
		var tang = tgl[0];
		var th = tgl[2];
		//alert(tgl[2]+'-'+tgl[0]+'-'+tgl[1]+' = '+th+'-'+bulan+'-'+tang);parseInt(days)
		$("#tgl1").val(tgl[2]+'-'+bulan+'-'+0);		
	});
	$('#code_cl_phc').change(function(){
      var code_cl_phc = $(this).val();
      var id_mst_inv_ruangan = '<?php echo set_value('code_ruangan')?>';
      $.ajax({
        url : '<?php echo site_url('inventory/inv_barang/get_ruangan') ?>',
        type : 'POST',
        data : 'code_cl_phc=' + code_cl_phc+'&id_mst_inv_ruangan=' + id_mst_inv_ruangan,
        success : function(data) {
          $('#code_ruangan').html(data);
        }
    });
      return false;
    }).change();

	function doExport(){
		var namepuskes	= $("#code_cl_phc option:selected").text()
		var puskes 		= $("#code_cl_phc").val();
		var ruang 		= $("#code_ruangan").val();
		var tanggal		= $("#tgl").val();
		var tanggal1 	= $("#tgl1").val();
		var jenisbarang 	= $("#jenisbarang").val();
		
		var t = tanggal.split('-');
		var tgl = t[2]+'-'+t[1]+'-'+t[0];
		var t1 = tanggal1.split('-');
		var tgl1 = t1[2]+'-'+t1[1]+'-'+t1[0];
		$.ajax({
		        url : '<?php echo site_url('inventory/lap_bhp_pengeluaran/permohonan_export') ?>',
		        type : 'POST',
		        data : 'namepuskes='+namepuskes+'&puskes=' + puskes+'&filter_tanggal='+tgl+'&filter_tanggal1='+tgl1+'&jenisbarang='+jenisbarang,
		        success : function(data) {
					if(data != ""){
						//alert(data);
						location.href = data;
					}
        		}
	    	});
	}
</script>
