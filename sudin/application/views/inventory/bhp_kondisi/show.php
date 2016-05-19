
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>inventory/bhp_kondisi/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
	        <div class="box-header">
	          <h3 class="box-title">{title_form}</h3>
		    </div>

	      	<div class="box-footer">
		      	<div class="row"> 
			      	<div class="col-md-12">
					 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			          <button type="button" id="btn-export" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Export</button>
			      	</div>
		      	</div>
		    <div class="box-body">
		      	<div class="row">
			      <div class="col-md-6">
			      	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Jenis Barang </label> </div>
				     	<div class="col-md-8">
					     	<select name="jenisbarang" id="jenisbarang" class="form-control">
				     				<option value="all">All</option>
								<?php foreach ($jenisbaranghabis as $row ) { ;?>
								<?php $select = $row->id_mst_inv_barang_habispakai_jenis == $this->session->userdata('filter_jenisbarang') ? 'selected=selected' : '' ?>
									<option value="<?php echo $row->id_mst_inv_barang_habispakai_jenis; ?>"  <?php echo $select ?> ><?php echo $row->uraian; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
			     </div>
			      <div class="col-md-6">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
				     	<div class="col-md-8">
				     		<select name="code_cl_phc" id="puskesmas" class="form-control">
									<option value="all" onchange="" >All</option>
						<?php foreach ($datapuskesmas as $row ) { ;?>
						<?php $select = $row->code == $this->session->userdata('filter_code_cl_phc') ? 'selected=selected' : '' ?>
							<option value="<?php echo $row->code; ?>" <?php echo $select; ?> ><?php echo $row->value; ?></option>
						<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
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

	function close_popup(){
		$("#popup_barang").jqxWindow('close');
	}
	$(function () {	
		$("select[name='jenisbarang']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_kondisi/filter_jenisbarang' ?>", 'jenisbarang='+$(this).val(),  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		});
	    $("#menu_bahan_habis_pakai").addClass("active");
	    $("#menu_inventory_bhp_kondisi").addClass("active");
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_mst_inv_barang_habispakai', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'code_cl_phc', type: 'string' },
			{ name: 'jml_baik', type: 'string' },
			{ name: 'tgl_update', type: 'string' },
			{ name: 'jml_rusak', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'jml_tdkdipakai', type: 'string' },
			{ name: 'id_inv_inventaris_habispakai_opname', type: 'string' },
			{ name: 'pilihan_satuan', type: 'date' },
			{ name: 'id_mst_inv_barang_habispakai_jenis', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'edit', type: 'number' }
			
        ],
		url: "<?php echo site_url('inventory/bhp_kondisi/json'); ?>",
		cache: false,
			updateRow: function (rowID, rowData, commit) {
             
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
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==0){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit(\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\",\""+dataRecord.code_cl_phc+"\",\""+dataRecord.tgl_update+"\",\""+dataRecord.id_inv_inventaris_habispakai_opname+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nama Barang', editable:false ,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '28%' },
				{ text: 'Satuan', editable:false ,align: 'center', cellsalign: 'center', datafield: 'pilihan_satuan', columntype: 'textbox', filtertype: 'textbox', width: '14%' },
				{ text: 'Batch', editable:false ,align: 'center', cellsalign: 'center', columntype: 'textbox', filtertype: 'textbox', width: '8%',datafield: 'batch' },
				{ text: 'Baik', columngroup: 'jumlah',editable:false ,align: 'center', cellsalign: 'right', datafield: 'jml_baik', columntype: 'textbox', filtertype: 'none', width: '6%' },
				{ text: 'Rusak', columngroup: 'jumlah',editable:false ,align: 'center', cellsalign: 'right', datafield: 'jml_rusak', columntype: 'textbox', filtertype: 'none', width: '6%' },
				{ text: 'Tidak dipakai', columngroup: 'jumlah',editable:false ,datafield: 'jml_tdkdipakai', columntype: 'textbox', filtertype: 'none', width: '8%' ,align: 'center', cellsalign: 'right'},
				{ text: 'Harga Satuan (Rp.)', editable:false ,datafield: 'harga', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'right', width: '15%' },
				{ text: 'Last Update', align: 'center', cellsalign: 'center', columngroup: 'update',editable: false,datafield: 'tgl_update', columntype: 'date', filtertype: 'none', cellsformat: 'dd-MM-yyyy', width: '10%'},
            ],
            columngroups: 
            [
              { text: 'Jumlah', align: 'center', name: 'jumlah' },
            ]
		});

	  function timeline_kondisi_barang(id){
	    $.get("<?php echo base_url();?>inventory/bhp_kondisi/timeline_kondisi_barang/"+id , function(response) {
	      $("#timeline-barang").html(response);
	    });
	  }

	function edit(barang,batch,pusks,tanggal,opname){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/bhp_kondisi/kondisi_barang/'; ?>"+barang+'/'+batch+'/'+pusks+'/'+tanggal+'/'+opname , function(data) {
			//timeline_kondisi_barang(id);
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 500,
			height: 450,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}

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
		post = post+'&puskes='+$("#puskesmas option:selected").text()+'&jenisbarang='+$("#jenisbarang option:selected").text();
		
		$.post("<?php echo base_url()?>inventory/bhp_kondisi/pengadaan_export",post,function(response	){
			//alert(response);
			window.location.href=response;
		});
	});
	function close_popup_opname(){
		$("#popup_barang").jqxWindow('close');
	}
	$("select[name='code_cl_phc']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_kondisi/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
	    });
</script>