

<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>

<div id="popup_barang2" style="display:none">
	<div id="popup_title2">Data Barang</div>
	<div id="popup_content2">&nbsp;</div>
</div>
<div class="col-md-6">
<div class="box box-success">
	<div class="box-header">
      <h3 class="box-title">{title_form}</h3>
    </div>
		<div class="box-footer">
	      <div class="col-md-9">
			<button type="button" class="btn btn-success" id="btn-export"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>	
		  </div>
		</div>
		<div class="box-header">
     		<select name="code_cl_phc" class="form-control" id="code_cl_phc">
	     			<option value="">Pilih Puskesmas</option>
					<?php foreach ($datapuskesmas as $row ) { ;?>
					<option value="<?php echo $row->code; ?>" onchange="" ><?php echo $row->value; ?></option>
				<?php	} ;?>
	     	</select><br/>
			<div id="tgl"></div>
		 </div>
</div> 
</div>
        
<script type="text/javascript">
	$(function(){
		$("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '31px', width: '100%'});
    $("#menu_inventory_lap_kiba").addClass("active");
    $("#menu_laporan").addClass("active");

    $('#code_cl_phc').change(function(){
      var code_cl_phc = $(this).val();
      var id_mst_inv_ruangan = '<?php echo set_value('code_ruangan')?>';
      $.ajax({
        url : '<?php echo site_url('inventory/inv_barang/get_ruangan') ?>',
        type : 'POST',
        data : 'code_cl_phc=' + code_cl_phc+'&id_mst_inv_ruangan=' + id_mst_inv_ruangan,
        success : function(data) {
          $('#code_ruangan').html(data);
			//filter_jqxgrid_inv_barang();
        }
    });
      return false;
    }).change();

    $('#code_ruangan').change(function(){
      var id_mst_inv_ruangan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('inventory/inv_barang/get_ruangan_puskesmas') ?>',
        type : 'POST',
        data : 'idmstinvruangan=' + id_mst_inv_ruangan,
        success : function(data) {
			//filter_jqxgrid_inv_barang();
        }
    });
      return false;
    }).change();

    
  });

	$("#btn-export").click(function(){
		var post = "";
		 var tanggal = $("#tgl").val();
		 
		$.post("<?php echo base_url()?>inventory/lap_kiba/permohonan_export_kiba",{tanggal:tanggal},function(response	){
			window.location.href=response;
		});
		
	});
</script>
    
	
	