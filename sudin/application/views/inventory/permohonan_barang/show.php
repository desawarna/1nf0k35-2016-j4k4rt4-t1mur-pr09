<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>inventory/permohonanbarang/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

      	<div class="box-footer">
	      <div class="col-md-8">
		 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>inventory/permohonanbarang/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
		 	<button type="button" class="btn btn-warning" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
		 	<button type="button" class="btn btn-success" id="btn-export"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>
	     </div>
	     <div class="col-md-4">
	     	<div class="row">
		     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
		     	<div class="col-md-8">
		     		<select name="code_cl_phc" id="puskesmas" class="form-control">
						<option value="all" onchange="" >All</option>
							<?php foreach ($datapuskesmas as $row ) { ;?>
							<?php $select = $row->code == set_value('codepus') ? 'selected' : '' ?>
								<option value="<?php echo $row->code; ?>" onchange="" ><?php echo $row->value; ?></option>
							<?php	} ;?>
			     	</select>
			     </div>	
	     	</div>
		  </div>
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
	    $("#menu_aset_tetap").addClass("active");
	    $("#menu_inventory_permohonanbarang").addClass("active");
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'no', type: 'number'},
			{ name: 'code_cl_phc', type: 'string'},
			{ name: 'id_inv_permohonan_barang', type: 'string'},
			{ name: 'tanggal_permohonan', type: 'date'},
			{ name: 'jumlah_unit', type: 'string'},
			{ name: 'nama_ruangan', type: 'string'},
			{ name: 'keterangan', type: 'text'},
			{ name: 'value', type: 'string'},
			{ name: 'totalharga', type: 'double'},
			{ name: 'pilihan_status_pengadaan', type: 'number'},
			{ name: 'detail', type: 'number'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/permohonanbarang/json'); ?>",
		cache: false,
			updateRow: function (rowID, rowData, commit) {
             // synchronize with the server - send update command
             // call commit with parameter true if the synchronization with the server is successful 
             // and with parameter false if the synchronization failed.					
			
            commit(true);
			var arr = $.map(rowData, function(el) { return el });
			//alert(arr);		//6 status

			//cek tipe inputan 
			//object -> input
			//number -> update
			//if(typeof(arr[2]) === 'object'){
				//var arr2 = $.map(arr[8], function(el) { return el });
				//input data
//alert(arr);
				$.post( '<?php echo base_url()?>inventory/permohonanbarang/updatestatus', {pilihan_status_pengadaan:arr[6],inv_permohonan_barang:arr[2]},function( data ) {
						$("#jqxgrid").jqxGrid('updateBoundData');
						
				 });
			//}
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
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100', '200'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'View', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='detail(\""+dataRecord.id_inv_permohonan_barang+"\",\""+dataRecord.code_cl_phc+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lockdo.gif'></a></div>";
					}
                 }
                },
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.id_inv_permohonan_barang+"\",\""+dataRecord.code_cl_phc+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id_inv_permohonan_barang+"\",\""+dataRecord.code_cl_phc+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
                { text: 'No', align: 'center', cellsalign: 'center',  datafield: 'no',editable:false ,sortable: false, filtertype: 'none', width: '4%' },
				{ text: 'Tgl. Permohonan', align: 'center', cellsalign: 'center', editable:false , datafield: 'tanggal_permohonan', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '12%' },
				{ text: 'Lokasi / Ruangan', editable:false ,datafield: 'nama_ruangan', columntype: 'textbox', filtertype: 'textbox', width: '17%' },
				{ text: 'Jumlah Barang', align: 'center', cellsalign: 'center', editable:false ,datafield: 'jumlah_unit', columntype: 'textbox', filtertype: 'textbox', width: '12%' },
				{ text: 'Total Harga (Rp.)', align: 'center', cellsalign: 'center', editable:false ,datafield: 'totalharga', columntype: 'textbox', filtertype: 'none', width: '16%' },
				{
	                text: '<b><i class="fa fa-pencil-square-o"></i> Status </b>', align: 'center', cellsalign: 'center', datafield: 'value', width: '12%', columntype: 'dropdownlist',
	                createeditor: function (row, column, editor) {
	                    // assign a new data source to the dropdownlist.
	                    var list = [<?php foreach ($statusdata as $key) {?>
						"<?=$key['value']?>",
						<?php } ?>];
	                    editor.jqxDropDownList({ autoDropDownHeight: true, source: list });
	                },
	                // update the editor's value before saving it.
	                cellvaluechanging: function (row, column, columntype, oldvalue, newvalue) {
	                    // return the old value, if the new value is empty.
	                    if (newvalue == "") return oldvalue;
	                }
                },
				{ text: 'Keterangan', editable:false ,datafield: 'keterangan', columntype: 'textbox', filtertype: 'textbox', width: '15%' }
            ]
		});

	function detail(id,code_cl_phc){
		document.location.href="<?php echo base_url().'inventory/permohonanbarang/detail';?>/" + id + "/" + code_cl_phc;
	}

	function edit(id,code_cl_phc){
		document.location.href="<?php echo base_url().'inventory/permohonanbarang/edit';?>/" + id + "/" + code_cl_phc;
	}

	function view(id,code_cl_phc){
		document.location.href="<?php echo base_url().'inventory/permohonanbarang/view';?>/" + id + "/" + code_cl_phc;
	}

	function del(id,code_cl_phc){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/permohonanbarang/dodel' ?>/" + id + "/" + code_cl_phc,  function(){
				alert('data berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}
	$("select[name='code_cl_phc']").change(function(){
		$.post("<?php echo base_url().'inventory/permohonanbarang/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });
			
	$("#btn-export").click(function(){
		
		var post = "";
		var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
		for(i=0; i < filter.length; i++){
			var fltr 	= filter[i];
			var value	= fltr.filter.getfilters()[0].value;
			var condition	= fltr.filter.getfilters()[0].condition;
			var filteroperation	= fltr.filter.getfilters()[0].operation;
			var filterdatafield	= fltr.filtercolumn;
			if(filterdatafield=="tgl"){
				var d = new Date(value);
				var day = d.getDate();
				var month = d.getMonth();
				var year = d.getYear();
				value = year+'-'+month+'-'+day;
				
			}
			post = post+'&filtervalue'+i+'='+value;
			post = post+'&filtercondition'+i+'='+condition;
			post = post+'&filteroperation'+i+'='+filteroperation;
			post = post+'&filterdatafield'+i+'='+filterdatafield;
			post = post+'&'+filterdatafield+'operator=and';
		}
		post = post+'&filterscount='+i;
		
		var sortdatafield = $("#jqxgrid").jqxGrid('getsortcolumn');
		if(sortdatafield != "" && sortdatafield != null){
			post = post + '&sortdatafield='+sortdatafield;
		}
		if(sortdatafield != null){
			var sortorder = $("#jqxgrid").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgrid").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			post = post+'&sortorder='+sortorder;
			
		}
		post = post+'&puskes='+$("#puskesmas option:selected").text();
		
		$.post("<?php echo base_url()?>inventory/permohonanbarang/permohonan_export",post,function(response	){
			window.location.href=response;
		});
	});
</script>











