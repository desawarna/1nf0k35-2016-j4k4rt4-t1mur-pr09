
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>inventory/bhp_pengadaan/dodel_multi" method="POST" name="">
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
		      	<?php //if($unlock==1){ ?>
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>inventory/bhp_pengadaan/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Penerimaan / Pengadaan Baru</button>
				<?php //} ?>		 	
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
	          <button type="button" id="btn-export" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Export</button>
		     </div>
		      <div class="col-md-4">
		     	<div class="row">
			     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
			     	<div class="col-md-8">
			     		<select name="code_cl_phc" id="puskesmas" class="form-control">
			     			<option value="all" >All</option>
							<?php foreach ($datapuskesmas as $row ) { ;?>
								<?php $select = $row->code == $this->session->userdata('filter_code_cl_phc') ? 'selected=selected' : '' ?>
								<option value="<?php echo $row->code; ?>" <?php echo $select; ?>><?php echo $row->value; ?></option>
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
	    $("#menu_bahan_habis_pakai").addClass("active");
	    $("#menu_inventory_bhp_pengadaan").addClass("active");
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_inv_hasbispakai_pembelian', type: 'string'},
			{ name: 'tgl_permohonan', type: 'date'},
			{ name: 'tgl_pembelian', type: 'date'},
			{ name: 'tgl_kwitansi', type: 'date'},
			{ name: 'nomor_kontrak', type: 'string'},
			{ name: 'nomor_kwitansi', type: 'string'},
			{ name: 'jenis_transaksi', type: 'string'},
			{ name: 'uraian', type: 'string'},
			{ name: 'code_cl_phc', type: 'string'},
			{ name: 'pilihan_status_pembelian', type: 'string'},
			{ name: 'value', type: 'string'},
			{ name: 'jumlah_unit', type: 'double'},
			{ name: 'total_harga', type: 'double'},
			{ name: 'nilai_pembelian', type: 'double'},
			{ name: 'keterangan', type: 'text'},
			{ name: 'detail', type: 'number'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/bhp_pengadaan/json'); ?>",
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
				{ text: 'View', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.detail==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='detail(\""+dataRecord.id_inv_hasbispakai_pembelian+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lockdo.gif'></a></div>";
					}
                 }
                },
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.id_inv_hasbispakai_pembelian+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
			    	if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id_inv_hasbispakai_pembelian+"\",\""+dataRecord.jumlah_unit+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Tgl. Pengadaan',editable:false , align: 'center', cellsalign: 'center', datafield: 'tgl_permohonan', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '11%' },
				{ text: 'Nomor Kontrak', align: 'center', editable:false ,datafield: 'nomor_kontrak', columntype: 'textbox', filtertype: 'textbox', width: '14%' },
				{ text: 'Jumlah Unit', editable:false ,align: 'center', cellsalign: 'right', datafield: 'jumlah_unit', columntype: 'textbox', filtertype: 'textbox', width: '9%' },
				{ text: 'Total Harga (Rp.)', editable:false ,align: 'center', cellsalign: 'right', datafield: 'nilai_pembelian', columntype: 'textbox', filtertype: 'textbox', width: '12%' },
				{ text: 'Jenis Pengadaan', editable:false ,align: 'center', cellsalign: 'center',datafield: 'jenis_transaksi', columntype: 'textbox', filtertype: 'textbox', width: '14%' },
				{ text: 'Status Pengadaan', editable:false ,align: 'center', cellsalign: 'center', datafield: 'value', columntype: 'textbox', filtertype: 'textbox', width: '14%' },
				{ text: 'Nomor Kwitansi', align: 'center', editable:false ,datafield: 'nomor_kwitansi', columntype: 'textbox', filtertype: 'textbox', width: '14%' }
            ]
		});

	function detail(id){
		document.location.href="<?php echo base_url().'inventory/bhp_pengadaan/detail';?>/" + id ;
	}

	function edit(id){
		document.location.href="<?php echo base_url().'inventory/bhp_pengadaan/edit';?>/" + id ;
	}

	function view(id){
		document.location.href="<?php echo base_url().'inventory/bhp_pengadaan/view';?>/" + id ;
	}

	function del(id,jumlah){
		if(jumlah>0){
			alert('Maaf, Data ini tidak bisa dihapus karena sudah ada pengadaan barang sebanyak '+ jumlah +'unit \n Jika ingin menghapus data ini silahkan hapus pengadaan barang didalamnya');
		}else{
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/bhp_pengadaan/dodel' ?>/"+id,  function(){
				alert('data berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
		}
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
			if(filterdatafield=="tgl_permohonan"){
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
		
		$.post("<?php echo base_url()?>inventory/bhp_pengadaan/pengadaan_export",post,function(response	){
			window.location.href=response;
		});
	});
	$("select[name='code_cl_phc']").change(function(){
		$.post("<?php echo base_url().'inventory/bhp_pengadaan/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });
</script>