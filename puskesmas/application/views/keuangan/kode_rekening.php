<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>mst/agama/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
		 	<button data-toggle="modal" data-target="#ModalAdd" type="button" class="btn btn-primary" ><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
		 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
	     </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>

<script type="text/javascript">
	$(function () {	
		$("#menu_mst_agama").addClass("active");
		$("#menu_master_data").addClass("active");
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'code', type: 'int'},
			{ name: 'kode_rekening', type: 'string'},
			{ name: 'uraian', type: 'string'},
			{ name: 'tipe', type: 'string'},
			{ name: 'edit', type: 'string'},
			{ name: 'delete', type: 'string'}
        ],
		url: "<?php echo site_url('keuangan/sts/kode_rekening_json'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$('#btn-refresh').click(function () {
			$("#jqxgrid").jqxGrid('clearfilters');
		});

		$("#jqxgrid").jqxGrid(
		{		
			width: '100%%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				
				{ text: 'Edit', datafield: 'edit', columntype: 'textbox', filtertype: 'textbox', width: '5%' },
				{ text: 'Delete', datafield: 'delete', columntype: 'textbox', filtertype: 'textbox', width: '5%' },
				{ text: 'Kode', datafield: 'code', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Kode Rekening', datafield: 'kode_rekening', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Uraian', datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '30%' },
				{ text: 'Tipe', datafield: 'tipe', columntype: 'textbox', filtertype: 'textbox', width: '20%' }
            ]
		});

	function add_rekening(){
	
		var kode_rekening = document.getElementById("kode_rekening").value;		
		var uraian = document.getElementById("uraian").value;
		var tipe = document.getElementById("tipe").value;
		$.post( '<?php echo base_url()?>keuangan/master_sts/kode_rekening_add', {tipe:tipe, kode_rekening:kode_rekening, uraian:uraian},function( data ) {
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');					
				document.getElementById("kode_rekening").value='';
				document.getElementById("uraian").value='';
				document.getElementById("tipe").value = '';
			});
			
	}
	
	function update_rekening(){
	
		var kode_rekening = document.getElementById("kode_rekening2").value;		
		var uraian = document.getElementById("uraian2").value;
		var tipe = document.getElementById("tipe2").value;
		var code = document.getElementById("code").value;
		$.post( '<?php echo base_url()?>keuangan/master_sts/kode_rekening_update', {code:code, tipe:tipe, kode_rekening:kode_rekening, uraian:uraian},function( data ) {
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');					
				document.getElementById("kode_rekening2").value='';
				document.getElementById("uraian2").value='';
				document.getElementById("tipe2").value = '';
				document.getElementById("code").value = '';
			});
			
	}
	
	function delete_rekening(id){
		if(confirm('Apakah Anda yakin akan menghapus data ini ?')){
			$.post( '<?php echo base_url()?>keuangan/master_sts/kode_rekening_delete', {code:id},function( data ) {
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');								
			});
		}
		
			
	}
	
	function editform(id, kode, uraian, tipe){
		document.getElementById("kode_rekening2").value=kode;
		document.getElementById("uraian2").value=uraian;
		document.getElementById("tipe2").value = tipe;
		document.getElementById("code").value = id;
	}

	
</script>

<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Kode Rekening</h4>
      </div>
      <div class="modal-body">
	
		<div class="form-group">
		  <label for="exampleInputEmail1">Kode Rekening</label>
		  <input type="text"  id="kode_rekening" class="form-control" name="kode_rekening" id="exampleInputEmail1" placeholder="Kode Rekening" >		  
		</div>
		
		<div class="form-group">
		  <label for="exampleInputEmail1">Uraian</label>
		  <input type="text"  id="uraian" class="form-control" name="uraian" id="exampleInputEmail1" placeholder="Kode Rekening" >		  
		</div>
		
		<div class="form-group">
		  <label for="exampleInputEmail1">Tipe </label>
		  <select name="tipe" id="tipe" class="form-control">			
			<option >pilih tipe rekening</option>
			<option value="penerimaan">Penerimaan</option>
			<option value="pengeluaran">Pengeluaran</option>
		  </select>
		</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="add_rekening()" data-dismiss="modal" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Kode Rekening</h4>
      </div>
      <div class="modal-body">
		<input type="hidden" id="code" name="code">
		<div class="form-group">
		  <label for="exampleInputEmail1">Kode Rekening</label>
		  <input type="text"  id="kode_rekening2" class="form-control" name="kode_rekening" id="exampleInputEmail1" placeholder="Kode Rekening" >		  
		</div>
		
		<div class="form-group">
		  <label for="exampleInputEmail1">Uraian</label>
		  <input type="text"  id="uraian2" class="form-control" name="uraian" id="exampleInputEmail1" placeholder="Kode Rekening" >		  
		</div>
		
		<div class="form-group">
		  <label for="exampleInputEmail1">Tipe </label>
		  <select name="tipe" id="tipe2" class="form-control">			
			<option >pilih tipe rekening</option>
			<option value="penerimaan">Penerimaan</option>
			<option value="pengeluaran">Pengeluaran</option>
		  </select>
		</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="update_rekening()" data-dismiss="modal" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

