<style>
.datepicker{
    z-index: 100000 !important;
}

</style>


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
		
	    <div class="box-body">
			<div class="">
				
				<div id="buttonTambah" style="" class="col-md-2 pull-left">
					<button class="btn btn-block btn-primary " data-toggle="modal" data-target="#myModal">Tambah STS</button>					
				</div>				
				
				
				<div class="col-md-3 pull-right">
				<select class="form-control" name="pilih_type">				
					<option value="0">Select Puskesmas</option>
					<?php
					foreach($data_puskesmas as $p){ 
						if($p['code'] == $this->session->userdata('puskes')){
						?>
							<option selected value="<?=$p['code'].'#'.$p['value']?>" ><?=$p['value']?></option>
						<?php					
						}else{
						?>	
							<option value="<?=$p['code'].'#'.$p['value']?>" ><?=$p['value']?></option>
						<?php
						}
						?>
						
					<?php } ?>
					
				</select>
				</div>
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
	
		function doDeleteSts(tgl){
			if(confirm('hapus ?')){
				$.post( '<?php echo base_url()?>keuangan/sts/delete_sts', {tgl:tgl},function( data ) {
					$("#treeGrid").jqxTreeGrid('updateBoundData');		
				});
			}
		}
        $(document).ready(function () {
            $("#menu_keuangan").addClass("active");
            $("#menu_keuangan_sts_general").addClass("active");

			<?php					
				if(empty($this->session->userdata('puskes')) and $this->session->userdata('puskes')=='0'){										
			?>				
				$("#buttonTambah").hide();
			<?php
				}
			?>

			
            var newRowID = null;			
			$("select[name='pilih_type']").change(function(){
				var datapuskes = $(this).val().split("#");
				$.post( '<?php echo base_url()?>keuangan/sts/set_puskes', {puskes:datapuskes[0]},function( data ) {
					$("#treeGrid").jqxTreeGrid('updateBoundData');					
					document.getElementById("puskesmas_id").value= datapuskes[0];
					document.getElementById("puskesmas_nama").value= datapuskes[1];
					if(datapuskes[0] == "0"){
						$("#buttonTambah").hide();
					}else{
						$("#buttonTambah").show();
					}
				});
            });
			
            // prepare the data
            var source =
            {
                dataType: "tab",
                dataFields: [
                    { name: "tgl", type: "string" },
                    { name: "nomor", type: "string" },
                    { name: "total", type: "number" },
                    { name: "status", type: "string" },                    
                    { name: "tombolShow", type: "string" },                    
                    { name: "tombolDelete", type: "string" },                    
                ],
                hierarchy:
                {
                    keyDataField: { name: 'Id' },
                    parentDataField: { name: 'ParentID' }
                },
                id: 'Id',
                url: '<?php echo base_url()?>keuangan/sts/api_data_sts_general',
                 addRow: function (rowID, rowData, position, parentID, commit) {				
					// POST to server using $.post or $.ajax					
                     // synchronize with the server - send insert command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.
                     // you can pass additional argument to the commit callback which represents the new ID if it is generated from a DB.
                     commit(true);
                     newRowID = rowID;
                 },
                                  
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
                pageable: true,
                editable: false,
                
                altRows: true,
                ready: function()
                {
                    // called when the DatatreeGrid is loaded.   
					$("#treeGrid").jqxTreeGrid('expandAll');						
                },
                pagerButtonsCount: 8,
                toolbarHeight: 35,
                renderToolbar: function(toolBar)
                {
                    var toTheme = function (className) {
                        if (theme == "") return className;
                        return className + " " + className + "-" + theme;
                    }

                    // appends buttons to the status bar.
                    var container = $("<div style='overflow: hidden; position: relative; height: 100%; width: 100%;'></div>");
                    var buttonTemplate = "<div style='float: left; padding: 3px; margin: 2px;'><div style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                    var addButton = $(buttonTemplate);
                    var editButton = $(buttonTemplate);
                    var deleteButton = $(buttonTemplate);
                    var cancelButton = $(buttonTemplate);
                    var updateButton = $(buttonTemplate);                    
                    
                    container.append(addButton);
                    container.append(editButton);
                    container.append(deleteButton);
                    container.append(cancelButton);
                    container.append(updateButton);

                    toolBar.append(container);
					
					
                    addButton.jqxButton({cursor: "pointer", enableDefault: false, disabled: true, height: 25, width: 25 });
                    addButton.find('div:first').addClass(toTheme('jqx-icon-plus'));
                    addButton.jqxTooltip({ position: 'bottom', content: "Tambah Cabang"});

                    editButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    editButton.find('div:first').addClass(toTheme('jqx-icon-edit'));
                    editButton.jqxTooltip({ position: 'bottom', content: "Edit"});

                    deleteButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    deleteButton.find('div:first').addClass(toTheme('jqx-icon-delete'));
                    deleteButton.jqxTooltip({ position: 'bottom', content: "Delete"});

                    updateButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    updateButton.find('div:first').addClass(toTheme('jqx-icon-save'));
                    updateButton.jqxTooltip({ position: 'bottom', content: "Save Changes"});

                    cancelButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    cancelButton.find('div:first').addClass(toTheme('jqx-icon-cancel'));
                    cancelButton.jqxTooltip({ position: 'bottom', content: "Cancel"});

                    var updateButtons = function (action) {
                        switch (action) {
                            case "Select":
                                addButton.jqxButton({ disabled: false });
                                deleteButton.jqxButton({ disabled: false });
                                editButton.jqxButton({ disabled: false });
                                cancelButton.jqxButton({ disabled: true });
                                updateButton.jqxButton({ disabled: true });
                                break;
                            case "Unselect":
                                addButton.jqxButton({ disabled: true });
                                deleteButton.jqxButton({ disabled: true });
                                editButton.jqxButton({ disabled: true });
                                cancelButton.jqxButton({ disabled: true });
                                updateButton.jqxButton({ disabled: true });
                                break;
                            case "Edit":
                                addButton.jqxButton({ disabled: true });
                                deleteButton.jqxButton({ disabled: true });
                                editButton.jqxButton({ disabled: true });
                                cancelButton.jqxButton({ disabled: false });
                                updateButton.jqxButton({ disabled: false });
                                break;
                            case "End Edit":
                                addButton.jqxButton({ disabled: false });
                                deleteButton.jqxButton({ disabled: false });
                                editButton.jqxButton({ disabled: false });
                                cancelButton.jqxButton({ disabled: true });
                                updateButton.jqxButton({ disabled: true });
                                break;

                        }
                    }

                    var rowKey = null;
                    $("#treeGrid").on('rowSelect', function (event) {
                        var args = event.args;
                        rowKey = args.key;
                        updateButtons('Select');
                    });
                    $("#treeGrid").on('rowUnselect', function (event) {
                        updateButtons('Unselect');
                    });
                    $("#treeGrid").on('rowEndEdit', function (event) {
                        updateButtons('End Edit');
                    });
                    $("#treeGrid").on('rowBeginEdit', function (event) {
                        updateButtons('Edit');
                    });
					
					
					
                    addButton.click(function (event) {
                        if (!addButton.jqxButton('disabled')) {							
                            $("#treeGrid").jqxTreeGrid('expandRow', rowKey);
                            // add new empty row.
                            $("#treeGrid").jqxTreeGrid('addRow', null, {}, 'first', rowKey);
                            // select the first row and clear the selection.
                            $("#treeGrid").jqxTreeGrid('clearSelection');
                            $("#treeGrid").jqxTreeGrid('selectRow', newRowID);
                            // edit the new row.
                            $("#treeGrid").jqxTreeGrid('beginRowEdit', newRowID);
                            updateButtons('add');
                        }
                    });

                    cancelButton.click(function (event) {
                        if (!cancelButton.jqxButton('disabled')) {
                            // cancel changes.
                            $("#treeGrid").jqxTreeGrid('endRowEdit', rowKey, true);
                        }
                    });

                    updateButton.click(function (event) {
                        if (!updateButton.jqxButton('disabled')) {
                            // save changes.
                            $("#treeGrid").jqxTreeGrid('endRowEdit', rowKey, false);
                        }
                    });

                    editButton.click(function () {
                        if (!editButton.jqxButton('disabled')) {
                            $("#treeGrid").jqxTreeGrid('beginRowEdit', rowKey);
                            updateButtons('edit');

                        }
                    });
                    deleteButton.click(function () {
                        if (!deleteButton.jqxButton('disabled')) {
                            var selection = $("#treeGrid").jqxTreeGrid('getSelection');
                            if (selection.length > 1) {
                                var keys = new Array();
                                for (var i = 0; i < selection.length; i++) {
                                    keys.push($("#treeGrid").jqxTreeGrid('getKey', selection[i]));
                                }
                                $("#treeGrid").jqxTreeGrid('deleteRow', keys);
                            }
                            else {
                                $("#treeGrid").jqxTreeGrid('deleteRow', rowKey);
                            }
                            updateButtons('delete');

                        }
                    });
                },
                columns: [
				/*
				{ name: "Id", type: "number" },
                    { name: "ParentID", type: "number" },
                    { name: "KodeRekening", type: "string" },
                    { name: "KodeAnggaran", type: "string" },
                    { name: "Uraian", type: "string" },
                    { name: "Type", type: "string" }
				*/                                                     
                  { text: 'Detail', cellsAlign: 'center', dataField: "tombolShow", align: 'center', width: '5%' },
                  { text: 'Delete', cellsAlign: 'center', dataField: "tombolDelete", align: 'center', width: '5%' },
                  { text: 'Tanggal', dataField: "tgl", align: 'center', width: '20%' },				  
                  { text: 'Nomor', dataField: "nomor", align: 'center', width: '25%' },				  
                  { text: 'Total', dataField: "total", cellsFormat: "f", cellsAlign: 'right', align: 'right', width: '25%' },				  
                  { text: 'Status', dataField: "status", align: 'center', width: '20%' },				  
				  			
                ]
            });
			
			 
        });
		
		function add_sts(){
			
			var nomor = document.getElementById("nomor").value;
			var tanggal = document.getElementById("tanggal").value;
			var code_cl_phc = document.getElementById("puskesmas_id").value;
			$.post( '<?php echo base_url()?>keuangan/sts/add_sts', {nomor:nomor, tgl:tanggal, code_cl_phc:code_cl_phc},function( data ) {
					if(data == 0){
						$("#treeGrid").jqxTreeGrid('updateBoundData');
						var no = nomor.split("/");
						document.getElementById("nomor").value=(parseInt(no[0])+1)+"/"+no[1]+"/"+no[2]+"/"+no[3];
					}else{
						alert(data);
					}
					
										
				});
		}
		
    </script>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah STS Baru</h4>
      </div>
      <div class="modal-body">
        
		<div class="form-group">
		  <label for="exampleInputEmail1">Nomor</label>
		  <input type="text" required id="nomor" class="form-control" name="nomor" id="exampleInputEmail1" placeholder="nomor" value="<?=$nomor?>">
		</div>
		<div class="form-group">
		  <label for="exampleInputEmail1">Tanggal</label>
		  <input type="text" required id="tanggal" value="<?=date("m/d/Y")?>" class="form-control" name="tanggal" id="exampleInputEmail1" placeholder="Tanggal" >		  
		</div>
		<div class="form-group">
		  <label for="exampleInputEmail1">Puskesmas</label>
		  <input type="text" readonly required id="puskesmas_nama" value="<?=$nama_puskes?>" class="form-control" name="puskesmas" id="exampleInputEmail1" placeholder="Puskesmas" >		  
		  <input type="hidden" required id="puskesmas_id"  class="form-control" value="<?=$this->session->userdata('puskes')?>" name="puskesmas" id="exampleInputEmail1" placeholder="Puskesmas" >		  
		</div>
		
		<script type="text/javascript">
            $(function() {
				$( "#tanggal" ).datepicker({
										
				});
			});
        </script>
		<input type="hidden" name="type" value="<?php echo $this->session->userdata('tipe')?>"/>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="add_sts()" data-dismiss="modal" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>