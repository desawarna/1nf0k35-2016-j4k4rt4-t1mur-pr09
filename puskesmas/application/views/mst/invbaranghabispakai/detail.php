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

<!--<div class="row">
	<!-- left column -->
	<!--<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-body">
						<div class="form-group">
							<label>Uraian</label><br/>
							<?php echo $uraian;?>
						</div>
			</div>
		</div><!-- /.box -->
	<!--</div><!-- /.box 
</div>	-->


<div class="box box-success">
		<div class="box-header">
          <h3 class="box-title"><div id="uraian"></div></h3>
	    </div>

      	<div class="box-footer">
	      <div class="col-md-7">
		 		<button onClick="add_barang();" type="button"  class="btn btn-success"><i class='fa fa-plus-square-o'></i> Tambah</button>
		 		<button type="button" class="btn btn-primary" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
				<button type="button" class="btn btn-warning" onClick="document.location.href='<?php echo base_url()?>mst/invbaranghabispakai'"><i class="fa fa-reply"></i> Kembali</button>
	     </div>
	     <div class="col-md-5">
	     	<div class="row">
		     	<div class="col-md-4" style="padding-top:5px;"><label> Jenis Barang </label> </div>
		     	<div class="col-md-6" style="float:right">
		     		<select name="jenisbarang" id="jenisbarang" class="form-control">
		     				<option value="all">All</option>
						<?php foreach ($jenisbarang as $row ) { ;?>
						<?php $select = $row->id_mst_inv_barang_habispakai_jenis == $kode ? 'selected=selected' : '' ?>
							<option value="<?php echo $row->id_mst_inv_barang_habispakai_jenis; ?>"  <?php echo $select ?> ><?php echo $row->uraian; ?></option>
						<?php	} ;?>
			     	</select>
			     </div>	
	     	</div>
		  </div>
		</div>
  <div class="box-body">
    <div class="div-grid">
        <div id="jqxTabs">
          <?php echo $barang;?>
        </div>
    </div>
  </div>
</div>


<script>
	$(function () {	
		$("#menu_master_data").addClass("active");
		$("#menu_mst_invbaranghabispakai").addClass("active");

		$("select[name='jenisbarang']").change(function(){
			 var e = document.getElementById("jenisbarang");
        	 var str = e.options[e.selectedIndex].text
			$.post("<?php echo base_url().'mst/invbaranghabispakai/filter_jenisbarang' ?>", 'jenisbarang='+$(this).val(),  function(){
				uraian(str);
				$("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');

			});
    	});
    	uraian();
	});
	function uraian(kode){
		if(kode==null){
			var e = document.getElementById("jenisbarang");
        	 var str = e.options[e.selectedIndex].text
			document.getElementById("uraian").innerHTML="Jenis Barang : "+str;
		}else{
			document.getElementById("uraian").innerHTML="Jenis Barang : "+kode;
		}
	}
</script>