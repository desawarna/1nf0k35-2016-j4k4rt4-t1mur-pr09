<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
    		<div class="col-md-4">
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>sms/pbk/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Nomor</button>
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			 </div>
    		<div class="col-md-5">
			 	<button type="button" class="btn btn-warning" id="btn_import"><i class='fa fa-download'></i> &nbsp; Import</button>
			 	<button type="button" class="btn btn-danger" id="btn-export"><i class='fa fa-save'></i> &nbsp; Export</button>
			 </div>
    		<div class="col-md-3">
	     		<select id="id_sms_grup" class="form-control">
	     			<option value="">-- Pilih Grup --</option>
					<?php foreach ($grupoption as $row ) { ;?>
						<option value="<?php echo $row->id_grup; ?>" ><?php echo $row->nama; ?></option>
					<?php }?>
	     	</select>
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
</section>
<div id="popup" style="display:none">
	<div id="popup_title">SMS</div>
	<div id="popup_content">&nbsp;</div>
</div>

<script type="text/javascript">
	$(function () {	
		$("#menu_sms_gateway").addClass("active");
		$("#menu_sms_pbk").addClass("active");

		$("#id_sms_grup").change(function(){
			$.post("<?php echo base_url().'sms/pbk/filter' ?>", 'id_sms_grup='+$(this).val(),  function(){
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
			
			$.post("<?php echo base_url()?>sms/pbk/export",post  ,function(response){
				window.location.href=response;
			});
		});

		$("#btn_import").click(function(){
			$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
			$.get("<?php echo base_url().'sms/pbk/import/'; ?>" , function(data) {
				$("#popup_content").html(data);
			});
			$("#popup").jqxWindow({
				theme: theme, resizable: false,
				width: 500,
				height: 400,
				isModal: true, autoOpen: false, modalOpacity: 0.2
			});
			$("#popup").jqxWindow('open');
	    });
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'no', type: 'number'},
			{ name: 'id', type: 'string'},
			{ name: 'nomor', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'nama_grup', type: 'string'},
			{ name: 'created_on', type: 'date'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/pbk/json'); ?>",
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
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.id+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'No', align: 'center', cellsalign: 'center', datafield: 'no', columntype: 'textbox', sortable: false, filtertype: 'none', width: '5%' },
				{ text: 'Nomor', datafield: 'nomor', columntype: 'textbox', filtertype: 'textbox', width: '25%' },
				{ text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Grup', datafield: 'nama_grup', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Tanggal Dibuat', align: 'center', cellsalign: 'center', datafield: 'created_on', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy HH:mm:ss', width: '20%' },
            ]
		});

	function close_popup(){
		$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		$("#popup").jqxWindow('close');
	}

	function edit(id){
		document.location.href="<?php echo base_url().'sms/pbk/edit';?>/" + id;
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'sms/pbk/dodel' ?>/" + id,  function(){
				alert('Nomor berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

</script>