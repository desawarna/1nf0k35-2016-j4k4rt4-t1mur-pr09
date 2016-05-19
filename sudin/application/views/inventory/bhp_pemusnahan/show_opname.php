<?php if($msg_opname!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $msg_opname; ?>
</div>
<?php } ?>
<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>inventory/bhp_pemusnahan/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div id="addopname"></div>
      <div class="box box-primary" id="grid">
	      	<div class="box-footer">
		      	<div class="row"> 
			      	<div class="col-md-12">
					 	<!--<button type="button" class="btn btn-primary" id="btn-add-opname"><i class='fa fa-plus-square'></i> &nbsp; Pemusnahan Baru</button>-->
					 	<button type="button" class="btn btn-success" id="btn-refreshopname"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			          <button type="button" id="btn-export-opname" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Export</button>
			      	</div>
		      	</div>
		    <div class="box-body">
		      	<div class="row">
			      <div class="col-md-6">
			      	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
				     	<div class="col-md-8">
					     	<select name="code_cl_phc" id="puskesmasopname" class="form-control">
								<option value="all" onchange="" >All</option>
							<?php foreach ($datapuskesmas as $row ) { ;?>
							<?php $select = $row->code == $this->session->userdata('filter_code_cl_phc') ? 'selected=selected' : '' ?>
								<option value="<?php echo $row->code; ?>" <?php echo $select; ?> ><?php echo $row->value; ?></option>
							<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
			     </div>
			      
			      <div class="col-md-3">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Bulan </label> </div>
				     	<div class="col-md-8">
				     		<select name="bulanopname" id="bulanopname" class="form-control">
			     				<option value="all">All</option>
								<?php foreach ($bulan as $val=>$key ) { ;?>
									<option value="<?php echo $val; ?>" ><?php echo $key; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
				  </div>
				   <div class="col-md-3">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Tahun </label> </div>
				     	<div class="col-md-8">
				     		<select name="tahunopname" id="tahunopname" class="form-control">
			     				<option value="all">All</option>
								<?php for ($i=date("Y");$i>=date("Y")-10;$i--) { ;?>
									<option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
				  </div>	
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Jenis Barang </label> </div>
				     	<div class="col-md-8">
				     		<select name="jenisbarangopname" id="jenisbarangopname" class="form-control">
			     				<option value="all">All</option>
								<?php foreach ($jenisbaranghabis as $val=>$key ) { ;?>
									<option value="<?php echo $val; ?>" ><?php echo $key; ?></option>
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
		        <div id="jqxgridopname"></div>
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
		$("select[name='jenisbarangopname']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_pemusnahan/filter_jenisbarang' ?>", 'jenisbarang='+$(this).val(),  function(){
				$("#jqxgridopname").jqxGrid('updatebounddata', 'cells');
			});
		});
		$("select[name='bulanopname']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_pemusnahan/filter_bulan' ?>", 'bulan='+$(this).val(),  function(){
				$("#jqxgridopname").jqxGrid('updatebounddata', 'cells');
			});
		});
		$("select[name='tahunopname']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_pemusnahan/filter_tahun' ?>", 'tahun='+$(this).val(),  function(){
				$("#jqxgridopname").jqxGrid('updatebounddata', 'cells');
			});
		});
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_inventaris_habispakai_opname', type: 'string' },
			{ name: 'code_cl_phc', type: 'string' },
			{ name: 'jenis_bhp', type: 'string' },
			{ name: 'petugas_nip', type: 'string' },
			{ name: 'petugas_nama', type: 'string' },
			{ name: 'catatan', type: 'number' },
			{ name: 'tgl_opname', type: 'date' },
			{ name: 'nomor_opname', type: 'string' },
			{ name: 'no', type: 'string' },
			{ name: 'saksi1_nama', type: 'string' },
			{ name: 'saksi2_nama', type: 'string' },
			{ name: 'last_opname', type: 'number' },
			{ name: 'edit', type: 'number' },
			{ name: 'delete', type: 'number' },
        ],
		url: "<?php echo site_url('inventory/bhp_pemusnahan/json/tidakdipakai'); ?>",
		cache: false,
			updateRow: function (rowID, rowData, commit) {
             
         },
		filter: function(){
			$("#jqxgridopname").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgridopname").jqxGrid('updatebounddata', 'sort');
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
     
		$('#btn-refreshopname').click(function () {
			$("#jqxgridopname").jqxGrid('clearfilters');
		});

		$("#jqxgridopname").jqxGrid(
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
				    var dataRecord = $("#jqxgridopname").jqxGrid('getrowdata', row)
				    if((dataRecord.edit==0)){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='editopname(\""+dataRecord.id_inv_inventaris_habispakai_opname+"\",\""+dataRecord.jenis_bhp+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				/*{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgridopname").jqxGrid('getrowdata', row);
				    if((dataRecord.delete==1) && (dataRecord.last_opname > 0)){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_opname(\""+dataRecord.id_inv_inventaris_habispakai_opname+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php  echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },*/
				{ text: 'Nomor', editable:false ,datafield: 'nomor_opname', columntype: 'textbox', filtertype: 'textbox', width: '18%' },
				{ text: 'Tanggal', align: 'center', cellsalign: 'center', columngroup: 'update',editable: false,datafield: 'tgl_opname', columntype: 'date', filtertype: 'none', cellsformat: 'dd-MM-yyyy', width: '12%'},
				{ text: 'Saksi 1', editable:false ,align: 'center', cellsalign: 'left', datafield: 'saksi1_nama', columntype: 'textbox', filtertype: 'textbox', width: '29%' },
				{ text: 'Saksi 2', editable:false ,align: 'center', cellsalign: 'left', datafield: 'saksi2_nama', columntype: 'textbox', filtertype: 'textbox', width: '23%' },
				{ text: 'Catatan', editable:false ,datafield: 'catatan', columntype: 'textbox', filtertype: 'textbox', width: '13%' ,align: 'center', cellsalign: 'right'}
            ]
		});
	
	function editopname(id,jenis){
		var idjenis = '0';
		if (jenis.toLowerCase()=="obat") {
			idjenis = '8';
		}else{
			idjenis = '0';
		}
  		$.get("<?php echo base_url().'inventory/bhp_pemusnahan/edit_opname/' ?>"+id+'/tidakdipakai',function (data) {
	          	$('#content3').html(data);
     	});

      return false;
	}

	function del_opname(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/bhp_pemusnahan/dodel_opname' ?>/"+id,  function(){
				alert('data berhasil dihapus');

				$("#jqxgridopname").jqxGrid('updatebounddata', 'cells');
			});
		}
	}
	
	$('#btn-add-opname').click(function(){
		var opname = '';
  		$.ajax({
	        url : '<?php echo site_url('inventory/bhp_pemusnahan/add_opname/tidakdipakai') ?>',
	        type : 'POST',
	     //   data : 'opname=' + opname,
	        success : function(data) {
	          	$('#content3').html(data);
	        }
     	});

      return false;

  	});

	$("#btn-export-opname").click(function(){
		
		var post = "";
		/*var filter = $("#jqxgridopname").jqxGrid('getfilterinformation');
		for(i=0; i < filter.length; i++){
			var fltr 	= filter[i];
			var value	= fltr.filter.getfilters()[0].value;
			var condition	= fltr.filter.getfilters()[0].condition;
			var filteroperation	= fltr.filter.getfilters()[0].operation;
			var filterdatafield	= fltr.filtercolumn;
			post = post+'&filtervalue'+i+'='+value;
			post = post+'&filtercondition'+i+'='+condition;
			post = post+'&filteroperation'+i+'='+filteroperation;
			post = post+'&filterdatafield'+i+'='+filterdatafield;
			post = post+'&'+filterdatafield+'operator=and';
		}
		post = post+'&filterscount='+i;
		
		var sortdatafield = $("#jqxgridopname").jqxGrid('getsortcolumn');
		if(sortdatafield != "" && sortdatafield != null){
			post = post + '&sortdatafield='+sortdatafield;
		}
		if(sortdatafield != null){
			var sortorder = $("#jqxgridopname").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgridopname").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			post = post+'&sortorder='+sortorder;
			
		}*/
		post = post+'&jenisbarang='+$("#jenisbarangopname option:selected").text()+'&nama_puskesmas='+$("#puskesmasopname option:selected").text()+'&bulan='+$("#bulanopname option:selected").text()+'&tahun='+$("#tahunopname option:selected").text();
		
		$.post("<?php echo base_url()?>inventory/bhp_pemusnahan/pengeluaran_export_expired/tidakdipakai",post,function(response){
			window.location.href=response;
		});
	});
	 $("select[name='code_cl_phc']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_pemusnahan/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
				$("#jqxgridopname").jqxGrid('updatebounddata', 'cells');
			});
	    });
</script>