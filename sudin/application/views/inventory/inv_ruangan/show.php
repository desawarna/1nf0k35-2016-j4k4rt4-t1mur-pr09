<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>inventory/inv_ruangan/dodel_multi" method="POST" name="">
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
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>inventory/inv_ruangan/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Ruangan</button>
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			 </div>
    		<div class="col-md-4">
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
		$("#menu_inventory_inv_ruangan").addClass("active");
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'code_cl_phc', type: 'string'},
			{ name: 'id_mst_inv_ruangan', type: 'string'},
			{ name: 'nama_ruangan', type: 'string'},
			{ name: 'keterangan', type: 'string'},
			{ name: 'puskesmas', type: 'string'},
			{ name: 'jml', type: 'double'},
			{ name: 'nilai', type: 'double'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('inventory/inv_ruangan/json'); ?>",
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
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='detail(\""+dataRecord.code_cl_phc+"/"+dataRecord.id_mst_inv_ruangan+"\");'></a></div>";
                 }
                },
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.code_cl_phc+"/"+dataRecord.id_mst_inv_ruangan+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.code_cl_phc+"/"+dataRecord.id_mst_inv_ruangan+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nama Ruangan', datafield: 'nama_ruangan', columntype: 'textbox', filtertype: 'textbox', width: '23%' },
				{ text: 'Jumlah Aset', filterable: false, align: 'center', cellsalign: 'right', datafield: 'jml', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Nilai Aset (Rp)', filterable: false, align: 'center', cellsalign: 'right', datafield: 'nilai', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Keterangan', datafield: 'keterangan', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Nama Puskesmas', filterable: false, datafield: 'puskesmas', columntype: 'textbox', filtertype: 'textbox', width: '20%' }
            ]
		});

	function detail(id){
		document.location.href="<?php echo base_url().'inventory/inv_ruangan/detail';?>/" + id;
	}

	function edit(id){
		document.location.href="<?php echo base_url().'inventory/inv_ruangan/edit';?>/" + id;
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'inventory/inv_ruangan/dodel' ?>/" + id,  function(){
				alert('data berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

	$(document).ready(function () {            
            // prepare the data
            var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'code', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
	        ],
			url: "<?php echo site_url('mst/kecamatan/json'); ?>",
			cache: false,
                data: {
                    featureClass: "P",
                    style: "full",
                    maxRows: 50,
                    username: "jqwidgets"
                }
            };

            $("select[name='code_cl_phc']").change(function(){
				$.post("<?php echo base_url().'inventory/inv_ruangan/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
					$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
				});
            });
        });

</script>