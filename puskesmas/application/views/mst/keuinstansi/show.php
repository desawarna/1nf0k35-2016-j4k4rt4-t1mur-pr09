<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>mst/keuangan_instansi/dodel_multi" method="POST" name="">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>
	      <div class="box-footer">
		 	<button type="button" class="btn btn-primary" onclick='add()'><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Instansi</button> 
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

<div id="popup_keuangan_instansi" style="display:none">
  <div id="popup_title">{title_form}</div>
  <div id="popup_keuangan_instansi_content">&nbsp;</div>
</div>

<script type="text/javascript">
	$(function () {	
		$("#menu_master_data").addClass("active");
		$("#menu_mst_keuangan_instansi").addClass("active");
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'code', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'tlp', type: 'string'},
			{ name: 'alamat', type: 'string'},
			{ name: 'status', type: 'string'},
			{ name: 'kategori', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('mst/keuangan_instansi/json_instansi'); ?>",
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
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail(\""+dataRecord.code+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif'></a></div>";
					}
                 }
                },
				
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.code+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox',align: 'center', width: '29%' },
				{ text: 'Kategori', datafield: 'kategori', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'center', width: '12%'},
				{ text: 'Alamat', datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', align: 'center',  width: '29%' },
				{ text: 'Telpon', datafield: 'tlp', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '10%' },
				{ text: 'Aktif', datafield: 'status', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '10%',  cellsrenderer: function (row) {
				   var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				   var aktif = dataRecord.status;
				   var str = "";
				 	if(aktif=='1'){
				 		str = "<input type='checkbox' checked>";
				 	}else{
				 		str = "<input type='checkbox'>";
				 	}
				 	return "<div style='width:100%;padding-top:2px;text-align:center'>"+str+"</div>";
				 }
				}
            ]
		});

	function detail(id){
	    $("#popup_keuangan_instansi #popup_keuangan_instansi_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
	      $.get("<?php echo base_url().'mst/keuangan_instansi/instansi_edit' ?>/"+ id, function(data) {
	        $("#popup_keuangan_instansi_content").html(data);
	      });
	      $("#popup_keuangan_instansi").jqxWindow({
	        theme: theme, resizable: false,
	        width: 600,
	        height: 400,
	        isModal: true, autoOpen: false, modalOpacity: 0.2
	      });
	      $("#popup_keuangan_instansi").jqxWindow('open');
    }

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'mst/keuangan_instansi/dodel' ?>/" + id,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

	function add(){
	    $("#popup_keuangan_instansi #popup_keuangan_instansi_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
	      $.get("<?php echo base_url().'mst/keuangan_instansi/instansi_add' ?>/", function(data) {
	        $("#popup_keuangan_instansi_content").html(data);
	      });
	      $("#popup_keuangan_instansi").jqxWindow({
	        theme: theme, resizable: false,
	        width: 600,
	        height: 400,
	        isModal: true, autoOpen: false, modalOpacity: 0.2
	      });
	      $("#popup_keuangan_instansi").jqxWindow('open');
    }

</script>

