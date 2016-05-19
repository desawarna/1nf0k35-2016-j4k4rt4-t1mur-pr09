
<?php 
echo $this->session->userdata('puskes');
if($this->session->flashdata('alert')!=""){ ?>
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
		
	    <div class="box-body">
		
			<div class="col-md-3 pull-left">
				<button id="doExpand" class="btn  btn-warning " >Expand All</button>	
				<button id="doCollapse" onclick="" class="btn  btn-warning " >Collapse All</button>	
			</div>
			
			<div class="col-md-2 pull-left">
				<?php
				$kodepuskesmas = $this->session->userdata('puskesmas');
				if(substr($kodepuskesmas, -2)=="01"){ ?>
					<a href="<?php echo base_url(); ?>keuangan/master_sts/anggaran" class="btn btn-success" >
						Manajemen Anggaran 
					</a>	
					<?php
				}
				?>			
			</div>
			<div class="col-md-3 pull-right">
				<select class="form-control" name="pilih_type">				
					<option value="0">Select Puskesmas</option>
					<?php
					foreach($data_puskesmas as $p){ 
						if($p['code'] == $this->session->userdata['puskes']){
						?>
							<option selected value="<?=$p['code']?>" ><?=$p['value']?></option>
						<?php					
						}else{
						?>	
							<option value="<?=$p['code']?>" ><?=$p['value']?></option>
						<?php
						}
						?>
					<?php } ?>
				</select>
			</div>
	    </div>
		
        <div class="box-body">
			<div class="default">
				<div id="treeGrid"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>

</section>


	<script type="text/javascript">
		function getDemoTheme() {
			var theme = document.body ? $.data(document.body, 'theme') : null
			if (theme == null) {
				theme = '';
			}
			else {
				return theme;
			}
			var themestart = window.location.toString().indexOf('?');
			if (themestart == -1) {
				return '';
			}

			var theme = window.location.toString().substring(1 + themestart);
			if (theme.indexOf('(') >= 0)
			{
				theme = theme.substring(1);
			}
			if (theme.indexOf(')') >= 0) {
				theme = theme.substring(0, theme.indexOf(')'));
			}

			var url = "<?=base_url()?>jqwidgets/styles/jqx." + theme + '.css';

			if (document.createStyleSheet != undefined) {
				var hasStyle = false;
				$.each(document.styleSheets, function (index, value) {
					if (value.href != undefined && value.href.indexOf(theme) != -1) {
						hasStyle = true;
						return false;
					}
				});
				if (!hasStyle) {
					document.createStyleSheet(url);
				}
			}
			else {
				var hasStyle = false;
				if (document.styleSheets) {
					$.each(document.styleSheets, function (index, value) {
						if (value.href != undefined && value.href.indexOf(theme) != -1) {
							hasStyle = true;
							return false;
						}
					});
				}
				if (!hasStyle) {
					var link = $('<link rel="stylesheet" href="' + url + '" media="screen" />');
					link[0].onload = function () {
						if ($.jqx && $.jqx.ready) {
							$.jqx.ready();
						};
					}
					$(document).find('head').append(link);
				}
			}
			$.jqx = $.jqx || {};
			$.jqx.theme = theme;
			return theme;
		};
		var theme = '';
		try
		{
			if (jQuery) {
				theme = getDemoTheme();
				if (window.location.toString().indexOf('file://') >= 0) {
					var loc = window.location.toString();
					var addMessage = false;
					if (loc.indexOf('grid') >= 0 || loc.indexOf('chart') >= 0 || loc.indexOf('scheduler') >= 0 || loc.indexOf('tree') >= 0 || loc.indexOf('list') >= 0 || loc.indexOf('combobox') >= 0 || loc.indexOf('php') >= 0 || loc.indexOf('adapter') >= 0 || loc.indexOf('datatable') >= 0 || loc.indexOf('ajax') >= 0) {
						addMessage = true;
					}

					if (addMessage) {
						$(document).ready(function () {
							setTimeout(function () {
								$(document.body).prepend($('<div style="font-size: 12px; font-family: Verdana;">Note: To run a sample that includes data binding, you must open it via "http://..." protocol since Ajax makes http requests.</div><br/>'));
							}
							, 50);
						});
					}
				}
			}
			else {
				$(document).ready(function () {
					theme = getDemoTheme();
				});
			}
		}
		catch (error) {
			var er = error;
		}
	</script>
	<script type="text/javascript">
        $(document).ready(function () {   

			$("#menu_keuangan").addClass("active");
			$("#menu_keuangan_master_sts_anggaran_tarif").addClass("active");
        
            var newRowID = null;
			
			$("#doExpand").click(function(){
				$.post( '<?php echo base_url()?>keuangan/master_sts/set_puskes', {puskes:'<?php echo $this->session->userdata('puskes');?>'},function( data ) {
					$("#treeGrid").jqxTreeGrid('expandAll');										
				});
            });
			
			$("#doCollapse").click(function(){
				$.post( '<?php echo base_url()?>keuangan/master_sts/set_puskes', {puskes:'<?php echo $this->session->userdata('puskes');?>'},function( data ) {
					$("#treeGrid").jqxTreeGrid('collapseAll');										
				});
            });
			
			$("select[name='pilih_type']").change(function(){
				$.post( '<?php echo base_url()?>keuangan/master_sts/set_puskes', {puskes:$(this).val()},function( data ) {
					
					$("#treeGrid").jqxTreeGrid('updateBoundData');

					
				});
            });
			
            // prepare the data
            var source =
            {
                dataType: "tab",
                dataFields: [
                    { name: "Id", type: "number" },
                    { name: "ParentID", type: "number" },
                    { name: "KodeRekening", type: "string" },
                    { name: "KodeAnggaran", type: "string" },
                    { name: "Uraian", type: "string" },
                    { name: "Type", type: "string" },
                    { name: "Id_tarif", type: "number" },
                    { name: "id_keu_anggaran", type: "number" },
                    { name: "Tarif", type: "number" },
                    { name: "Code_lc_phc", type: "string" }
                ],
                hierarchy:
                {
                    keyDataField: { name: 'Id' },
                    parentDataField: { name: 'ParentID' }
                },
                id: 'Id',
                url: '<?php echo base_url()?>keuangan/master_sts/api_data_tarif',
                 addRow: function (rowID, rowData, position, parentID, commit) {				
					// POST to server using $.post or $.ajax					
                     // synchronize with the server - send insert command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.
                     // you can pass additional argument to the commit callback which represents the new ID if it is generated from a DB.
                     commit(true);
                     newRowID = rowID;
                 },
                 updateRow: function (rowID, rowData, commit) {
                     // synchronize with the server - send update command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.					
					
					
					
                    commit(true);
					var arr = $.map(rowData, function(el) { return el });															
																				
					
					//0,7
					$.post( '<?php echo base_url()?>keuangan/master_sts/add_tarif', {id_anggaran:arr[0],tarif:arr[8]},function( data ) {
						if(data != 0){
							alert(data);									
						}else{
							//$("#treeGrid").jqxTreeGrid('updateBoundData');
						}
					});
                 },
                 deleteRow: function (rowID, commit) {
                     // synchronize with the server - send delete command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.
					
					 
                     commit(true);
                 }
             };

            var dataAdapter = new $.jqx.dataAdapter(source, {
                loadComplete: function () {
                    // data is loaded.
                }
            });

            $("#treeGrid").jqxTreeGrid(
            {
                width: '100%',
                source: dataAdapter,
                pageable: false,pageSize:999,
                editable: true,                
                altRows: true,
                ready: function()
                {
					
                    // called when the DatatreeGrid is loaded.         
                },
                pagerButtonsCount: 8,                
                columns: [
				/*
				{ name: "Id", type: "number" },
                    { name: "ParentID", type: "number" },
                    { name: "KodeRekening", type: "string" },
                    { name: "KodeAnggaran", type: "string" },
                    { name: "Uraian", type: "string" },
                    { name: "Type", type: "string" }
				*/                                   
                  { text: 'Kode Anggaran', editable:false, dataField: "KodeAnggaran", align: 'center', width: '25%' },
                  { text: 'Uraian', editable:false, dataField: "Uraian", align: 'center', width: '45%' },
				  { text: 'Tarif', dataField: "Tarif", align: 'center', cellsAlign: 'right', cellsFormat: "f", width: '15%' },
				  { text: 'Kode Rekening', editable:false, dataField: "KodeRekening", align: 'center', width: '15%' }
                ]
            });
			
			
        });
		
		function addParent(){
			var sub_id = 0;
			var kode_rekening = document.getElementById("kode_rekening").value;
			var kode_anggaran = document.getElementById("kode_anggaran").value;
			var uraian = document.getElementById("uraian").value;
			$.post( '<?php echo base_url()?>keuangan/master_sts/anggaran_add', {sub_id:sub_id, kode_rekening:kode_rekening, kode_anggaran:kode_anggaran, uraian:uraian},function( data ) {
				
					if(data != 0){
						alert(data);									
					}else{						
						$("#treeGrid").jqxTreeGrid('updateBoundData');
					
						document.getElementById("kode_rekening").value='';
						document.getElementById("kode_anggaran").value='';
						document.getElementById("uraian").value = '';
					}
					
				});
		}
    </script>
	
		