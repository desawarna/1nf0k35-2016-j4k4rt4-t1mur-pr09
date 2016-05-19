
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>inventory/pengadaanbarang/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

      	<div class="box-footer">
		  <div class="col-md-3">
     		<select name="code_cl_phc" id="code_cl_phc" class="form-control">
     				<option value="all" onchange="" >All</option>
				<?php foreach ($datapuskesmas as $row ) { ;?>
				<?php $select = $row->code == $this->session->userdata('code_cl_phc') ? 'selected=selected' : '' ?>
					<option value="<?php echo $row->code; ?>" <?php echo $select; ?> ><?php echo $row->value; ?></option>
				<?php	} ;?>
	     	</select>
		  </div>
		  <div class="col-md-3">
	     		<select name="code_ruangan" class="form-control" id="code_ruangan">
     				<option value="0">Pilih Ruangan</option>
	     		</select>
		  </div>
	      <div class="col-md-6" style="text-align:right">
		 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
		 	<button type="button" onclick="doList()" class="btn btn-danger" id="btn-warning"><i class='fa fa-sign-in'></i> &nbsp; Alokasikan Aset	</button>
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
	    $("#menu_inventory_distribusibarang").addClass("active");
		
		
	    $('#code_cl_phc').change(function(){
			
	      var code_cl_phc = $(this).val();
	      $.ajax({
	        url : '<?php echo site_url('inventory/distribusibarang/get_ruangan') ?>',
	        type : 'POST',
	        data : 'code_cl_phc=' + code_cl_phc,
	        success : function(data) {
	          $('#code_ruangan').html(data);
	        }
	      });
			var selects = document.getElementById("code_ruangan");
			var selectedValue = selects.options[selects.selectedIndex].value;
		  $.ajax({
	        url : '<?php echo site_url('inventory/distribusibarang/set_filter') ?>',
	        type : 'POST',
	        data : 'code_cl_phc=' + code_cl_phc+'&code_ruangan=all',
	        success : function(data) {
	          	$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
	        }
	      });

	      return false;
	    }).change();
		
		$('#code_ruangan').change(function(){
	      var code_ruangan = $(this).val();
	      $.ajax({
	        url : '<?php echo site_url('inventory/distribusibarang/set_filter') ?>',
	        type : 'POST',
	        data : 'code_ruangan=' + code_ruangan,
	        success : function(data) {
	          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
	        }
	      });

	      return false;
	    }).change();
		
		
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'kode_barang', type: 'string'},
			{ name: 'kode', type: 'string'},
			{ name: 'register', type: 'string'},
			{ name: 'nama_barang', type: 'string'},
			{ name: 'harga', type: 'number'},
			{ name: 'kondisi', type: 'string'},
			{ name: 'id_barang', type: 'string'},	
			{ name: 'nama', type: 'string'}
        ],
		url: "<?php echo site_url('inventory/distribusibarang/json'); ?>",		
		cache: false,
		updateRow: function (rowID, rowData, commit) {
			 commit(true);
             var arr = $.map(rowData, function(el) { return el });																														
				//alert(arr);
			var id_ruang = document.getElementById('code_ruangan').value;
			var code_cl_phc = document.getElementById('code_cl_phc').value;
			$.post( '<?php echo base_url()?>inventory/distribusibarang/update_data', {id_ruang:id_ruang, code_cl_phc:code_cl_phc, id_barang:arr[6], register:arr[2], kondisi:arr[5]},function( data ) {
					
			});
					
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
				{ text: 'Pilih', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding-top:2px;text-align:center'><input type='checkbox' name='aset[]' value="+dataRecord.id_barang+"_td_"+dataRecord.kode_barang+"_td_"+dataRecord.nama+"_td_"+dataRecord.kondisi+" ></div>";
                 }
                },
				{ text: 'Kode Barang', align: 'center', cellsalign: 'center' , sortable: false,editable:false , datafield: 'kode', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Register', align: 'center', cellsalign: 'center',editable:true ,datafield: 'register', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Nama Barang',editable:false , datafield: 'nama_barang', columntype: 'textbox', filtertype: 'textbox', width: '40%' },
				{ text: 'Harga Satuan (Rp.)', align: 'center', editable:false , datafield: 'harga',columntype: 'textbox', filtertype: 'textbox', width: '15%', cellsalign: 'right', cellsformat: 'f'  },
				{
                        text: '<b><i class="fa fa-pencil-square-o"></i> Kondisi Barang</b>', filtertype: 'none', sortable: false, align: 'center', cellsalign: 'center', datafield: 'kondisi', width: '15%', columntype: 'dropdownlist',
                        createeditor: function (row, column, editor) {
                            // assign a new data source to the dropdownlist.
                            var list = [<?php foreach ($pilih_kondisi as $r) {?>
							//"<?php echo $r->id." - ".$r->val; ?>",
							"<?php echo $r->id." - ".$r->val; ?>",
							<?php } ?>];
                            editor.jqxDropDownList({ autoDropDownHeight: true, source: list });
                        },
                        // update the editor's value before saving it.
                        cellvaluechanging: function (row, column, columntype, oldvalue, newvalue) {
                            // return the old value, if the new value is empty.
                            if (newvalue == "") return oldvalue;
                        }
                 }
            ]
		});
		
	function close_popup(){
		$("#popup_barang").jqxWindow('close');
	}

	function add_barang(data_barang){
		$("#popup_barang #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'inventory/distribusibarang/pop_add'; ?>"+data_barang ,  function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_barang").jqxWindow({
			theme: theme, resizable: false,
			width: 700,
			height: 600,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_barang").jqxWindow('open');
	}
	function doList(){				
		var values = new Array();	
		var	data_barang = "/";
		$.each($("input[name='aset[]']:checked"), function() {
		  values.push($(this).val());		
		});
		//alert(values);
		
		if(values.length > 0){
			for(i=0; i<values.length; i++){
				data_barang = data_barang+values[i]+"_tr_";
			}
			add_barang(data_barang);
		}else{
			alert('Silahkan Pilih Barang Terlebih Dahulu');
		}
		//alert(data_barang);
		
		
		
		
		
	}
</script>

<div id="popup_barang" style="display:none">
	<div id="popup_title">Distribusi Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>