<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div id="popup_barang_bhp" style="display:none">
	<div id="popup_title">Detail Opname Barang</div>
	<div id="popup_content_bhp">&nbsp;</div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
	      	<div class="box-footer">
		      	<div class="row"> 
			      	<div class="col-md-12">
					 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			          <button type="button" id="btn-export-retur" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Export</button>
			      	</div>
		      	</div>
		    <div class="box-body">
		      	<div class="row">
			      <div class="col-md-6">
			      	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
				     	<div class="col-md-8">
					     	<select name="code_cl_phc" id="puskesmas" class="form-control">
								<?php foreach ($datapuskesmas as $row ) { ;?>
									<option value="<?php echo $row->code; ?>" onchange="" ><?php echo $row->value; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
			     </div>
			      <div class="col-md-3">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Bulan </label> </div>
				     	<div class="col-md-8">
				     		<select name="bulan" id="bulan" class="form-control">
								<?php foreach ($bulan as $val=>$key ) { ;?>
								<?php $select = $val == date("m") ? 'selected=selected' : '' ?>
									<option value="<?php echo $val; ?>" <?php echo $select ?>><?php echo $key; ?></option>
								<?php	} ;?>
					     	</select>
					     </div>	
			     	</div>
				  </div>	
				  <div class="col-md-3">
			     	<div class="row">
				     	<div class="col-md-4" style="padding-top:5px;"><label> Tahun </label> </div>
				     	<div class="col-md-8">
				     		<select name="tahun" id="tahun" class="form-control">
								<?php for ($i=date("Y");$i>=date("Y")-10;$i--) { ;?>
									<?php $select = $i == date("Y") ? 'selected=selected' : '' ?>
									<option value="<?php echo $i; ?>" <?php echo $select ?>><?php echo $i; ?></option>
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
				     	<div class="col-md-4" style="padding-top:5px;"><label> Jenis Barang</label> </div>
				     	<div class="col-md-8">
				     		<select name="jenisbarang" id="jenisbarang" class="form-control">
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
		        <div id="jqxgridRetur"></div>
			</div>
    	</div>
      </div>
  </div>
</div>
</section>

<script type="text/javascript">
	$(function () {	
		$("select[name='jenisbarang']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_retur/filter_jenisbarang' ?>", 'jenisbarang='+$(this).val(),  function(){
				$("#jqxgridRetur").jqxGrid('updatebounddata', 'cells');
			});
		});
		$("select[name='bulan']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_retur/filter_bulan' ?>", 'bulan='+$(this).val(),  function(){
				$("#jqxgridRetur").jqxGrid('updatebounddata', 'cells');
			});
		});
		$("select[name='tahun']").change(function(){
			$.post("<?php echo base_url().'inventory/bhp_retur/filter_tahun' ?>", 'tahun='+$(this).val(),  function(){
				$("#jqxgridRetur").jqxGrid('updatebounddata', 'cells');
			});
		});
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_mst_inv_barang_habispakai', type: 'string' },
			{ name: 'batch', type: 'string' },
			{ name: 'uraian', type: 'string' },
			{ name: 'total_penerimaan', type: 'string' },
			{ name: 'jml_rusakakhir', type: 'string' },
			{ name: 'id_mst_inv_barang_habispakai_jenis', type: 'string' },
			{ name: 'nama', type: 'string' },
			{ name: 'tgl_pembelian_terakhir', type: 'string' },
			{ name: 'harga', type: 'string' },
			{ name: 'merek_tipe', type: 'string' },
			{ name: 'edit', type: 'number' },
			{ name: 'delete', type: 'number' }
        ],
		url: "<?php echo site_url('inventory/bhp_retur/json'); ?>",
		cache: false,
			updateRow: function (rowID, rowData, commit) {
             
         },
		filter: function(){
			$("#jqxgridRetur").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgridRetur").jqxGrid('updatebounddata', 'sort');
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
			$("#jqxgridRetur").jqxGrid('clearfilters');
		});

		$("#jqxgridRetur").jqxGrid(
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
				{ text: 'Retur', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgridRetur").jqxGrid('getrowdata', row)
				    if((dataRecord.id_mst_inv_barang_habispakai!=null)&&(dataRecord.tgl_opname!="<?php echo date('Y-m-d');?>")){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/return.png' onclick='add(\""+dataRecord.id_mst_inv_barang_habispakai_jenis+"\",\""+dataRecord.id_mst_inv_barang_habispakai+"\",\""+dataRecord.batch+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nama Barang', align: 'center', editable:false ,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '31%' },
				{ text: 'Merek', align: 'center', editable:false ,datafield: 'merek_tipe', columntype: 'textbox', filtertype: 'textbox', width: '16%' },
				{ text: 'Instansi / PBF', align: 'center', cellsalign: 'center', columngroup: 'update',editable: false, columntype: 'date', filtertype: 'none', cellsformat: 'dd-MM-yyyy', width: '20%',datafield: 'nama'},
				{ text: 'Tgl Terima',datafield: 'tgl_pembelian_terakhir', align: 'center', cellsalign: 'center', columngroup: 'update',editable: false, columntype: 'date', filtertype: 'none', cellsformat: 'dd-MM-yyyy', width: '10%'},
				{ text: 'Jml Terima',sortable: true,editable:false ,align: 'center', cellsalign: 'right', datafield: 'total_penerimaan', columntype: 'textbox', filtertype: 'none', width: '9%' },
				{ text: 'Jml Rusak',sortable: false,editable:false ,datafield: 'jml_rusakakhir', columntype: 'textbox', filtertype: 'none', width: '9%' ,align: 'center', cellsalign: 'right'}
            ]
		});

	function add(jenis,barang,batch){
		$.get("<?php echo base_url().'inventory/bhp_retur/add_retur/'?>"+jenis+'/'+barang+'/'+batch, function(data) {
			$("#content1").html(data);
		});
      return false;
	}

	$("#btn-export-retur").click(function(){
		
		var post = "";
		/*var filter = $("#jqxgridRetur").jqxGrid('getfilterinformation');
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
		
		var sortdatafield = $("#jqxgridRetur").jqxGrid('getsortcolumn');
		if(sortdatafield != "" && sortdatafield != null){
			post = post + '&sortdatafield='+sortdatafield;
		}
		if(sortdatafield != null){
			var sortorder = $("#jqxgridRetur").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgridRetur").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			post = post+'&sortorder='+sortorder;
			
		}*/
		post = post+'&jenisbarang='+$("#jenisbarang option:selected").text()+'&nama_puskesmas='+$("#puskesmas option:selected").text()+'&bulan='+$("#bulan option:selected").text()+'&tahun='+$("#tahun option:selected").text();
		
		$.post("<?php echo base_url()?>inventory/bhp_retur/laporan_opname_retur",post,function(response	){
			//alert(response);
			window.location.href=response;
		});
	});
</script>