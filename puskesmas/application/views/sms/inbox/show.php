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
    		<div class="col-md-9">
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
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
		$("#menu_sms_inbox").addClass("active");
	});

	function close_popup(){
		$("#popup").jqxWindow('close');
	}

	function move(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/inbox/move/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 440,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup").jqxWindow('open');
	}

	function reply(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/inbox/reply/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 440,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup").jqxWindow('open');
	}

	function detail(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/inbox/detail/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 440,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup").jqxWindow('open');
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'sms/inbox/dodel' ?>/" + id,  function(){
				alert('SMS berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'ID', type: 'number'},
			{ name: 'SenderNumber', type: 'string'},
			{ name: 'TextDecoded', type: 'string'},
			{ name: 'Processed', type: 'string'},
			{ name: 'ReceivingDateTime', type: 'date'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/inbox/json'); ?>",
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
				{ text: 'Reply', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_add.gif' onclick='reply(\""+dataRecord.ID+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='detail(\""+dataRecord.ID+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.ID+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nomor Pengirim', align: 'center', cellsalign: 'center', datafield: 'SenderNumber', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Isi Pesan', datafield: 'TextDecoded', columntype: 'textbox', filtertype: 'textbox', width: '45%' },
				{ text: 'Diproses', datafield: 'Processed', align: 'center', cellsalign: 'center', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Waktu Diterima', align: 'center', cellsalign: 'center', datafield: 'ReceivingDateTime', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy HH:mm:ss', width: '15%' }
            ]
		});


</script>