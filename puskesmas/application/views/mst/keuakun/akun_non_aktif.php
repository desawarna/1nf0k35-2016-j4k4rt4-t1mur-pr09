<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
           <h3 class="box-title">{title_form}</h3> 
      </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12 pull-left">
          <button id="doExpand_non_aktif" class="btn btn-warning " ><i class="icon fa fa-plus-square-o"></i> &nbsp;Expand</button>  
          <button id="doCollapse_non_aktif" class="btn btn-warning " ><i class="icon fa fa-minus-square-o"></i> &nbsp;Collapse</button> 
          <button id="doRefresh_non_aktif" class="btn btn-primary" ><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
        </div>
      </div>
    </div>
    <div class="box-body">
      <div class="default">
        <div id="treeGrid_akun_non_aktif"></div>
      </div>
      </div>
    </div>
  </div>
  </div>
</section>

<div id="popup_keuangan_akun_non_aktif_detail" style="display:none">
  <div id="popup_title">Detail Akun Non Aktif</div>
  <div id="popup_keuangan_akun_non_aktif_detail_content">&nbsp;</div>
</div>

<script type="text/javascript">
  $(document).ready(function () {

    $("#doExpand_non_aktif").click(function(){
        $("#treeGrid_akun_non_aktif").jqxTreeGrid('expandAll');                    
    });

    $("#doCollapse_non_aktif").click(function(){
        $("#treeGrid_akun_non_aktif").jqxTreeGrid('collapseAll');                    
    });

    $("#doRefresh_non_aktif").click(function(){
        $("#treeGrid_akun_non_aktif").jqxTreeGrid('updateBoundData');
    });

    $("[name='btn_akun_non_aktif_close']").click(function(){
        $("#popup_keuangan_akun_non_aktif_detail").jqxWindow('close');
    });
      
      var newRowID = null;
      var source = {
        dataType: "tab",
        dataFields: [
            { name: "id_mst_akun", type: "number" },
            { name: "id_mst_anggaran_parent", type: "number" },
            { name: "kode", type: "number" },
            { name: "uraian", type: "string" },
            { name: "saldo_normal", type: "string" },
            { name: "parent", type: "string" }
        ],
             hierarchy:
            {
                 keyDataField: { name: 'id_mst_akun' },
                 parentDataField: { name: 'id_mst_anggaran_parent' }
            },
            id: 'id_mst_akun',

            url: '<?php echo base_url()?>mst/keuangan_akun/api_data_akun_non_aktif',
            
             addRow: function (rowID, rowData, position, parentID, commit) {        
                commit(true);
                newRowID = rowID;
             },
             updateRow: function (rowID, rowData, commit) {
                commit(true);
                var arr = $.map(rowData, function(el) { return el });         
                if(typeof(arr[1]) === 'object'){
                  var arr2 = $.map(arr[1], function(el) { return el });
                  if(arr[4] + '' + arr[5] + '' + arr[6] + '' + arr[7]+ '' + arr[8]!='') {
                    $.post( '<?php echo base_url()?>mst/keuangan_akun/akun_add', {id_mst_akun:arr[2],id_mst_akun_parent:arr2[0], uraian:arr[4], kode:arr[5], saldo_normal:arr[6], saldo_awal : arr[7], mendukung_anggaran : arr[8]}, function( data ) {
                        if(data != 0){
                          alert(data);                  
                        }else{
                          alert("Data "+arr[4]+" berhasil disimpan");                  
                        }
                    });
                  }
                }else{      
                  $.post( '<?php echo base_url()?>mst/keuangan_akun/akun_update', 
                    {
                      row:rowID,
                      id_mst_akun:arr[0] ,
                      id_mst_akun_parent:arr[1], 
                      kode:arr[2], 
                      uraian : arr[3], 
                      saldo_normal : arr[4], 
                      saldo_awal:arr[5], 
                      mendukung_anggaran : arr[6]
                    },
                    function( data ) {
                      if(data != 0){
                        alert(data);
                      }
                  });
                }
             },
             deleteRow: function (rowID, commit) {
                if( Object.prototype.toString.call( rowID ) === '[object Array]' ) {
                  for(var i=0; i< rowID.length; i++){
                    $.post( '<?php echo base_url()?>mst/keuangan_akun/akun_delete', {id_mst_akun:rowID[i]},function( data ) {
                      $("#treeGrid").jqxTreeGrid('updateBoundData');
                    });
                  }
                }else{
                  $.post( '<?php echo base_url()?>mst/keuangan_akun/akun_delete', {id_mst_akun:rowID},function( data ) {
                    // $("#treeGrid").jqxTreeGrid('updateBoundData');
                  });
                }
                commit(true);
             }
         };
        var dataAdapter = new $.jqx.dataAdapter(source, {
            loadComplete: function () {
                // data is loaded.
            }
        });

        $("#treeGrid_akun_non_aktif").jqxTreeGrid({
            width: '100%',
            source: dataAdapter, 
            pageable: false,
            editable: true,
            showToolbar: true,
            altRows: true,
            ready: function(){
               $("#treeGrid_akun_non_aktif").jqxTreeGrid('expandAll');            
            },
            pagerButtonsCount: 8,
            toolbarHeight: 40,

            renderToolbar: function(toolBar)
            {
                var toTheme = function (className) {
                    if (theme == "") return className;
                    return className + " " + className + "-" + theme;
                }
                // appends buttons to the status bar.
                var container = $("<div style='overflow: hidden; position: relative; height: 100%; width: 100%;'></div>");
                var buttonTemplate = "<div style='float: left; padding: 3px; margin: 2px;'><div style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                var deleteButton = $(buttonTemplate);
                
                container.append(deleteButton);

                toolBar.append(container);

                deleteButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                deleteButton.find('div:first').addClass(toTheme('jqx-icon-delete'));
                deleteButton.jqxTooltip({ position: 'bottom', content: "Delete"});

                var updateButtons = function (action) {
                    switch (action) {
                        case "Select":
                            deleteButton.jqxButton({ disabled: false });
                            break;
                        case "Unselect":
                            deleteButton.jqxButton({ disabled: true });
                            break;
                        case "Edit":
                            deleteButton.jqxButton({ disabled: true });
                            break;
                        case "End Edit":
                            deleteButton.jqxButton({ disabled: false });
                            break;
                    }
                }
                var rowKey = null;
                $("#treeGrid_akun_non_aktif").on('rowSelect', function (event) {
                    var args = event.args;
                    rowKey = args.key;
                    updateButtons('Select');
                });

                deleteButton.click(function () {
                    if (!deleteButton.jqxButton('disabled')) {
                        var selection = $("#treeGrid_akun_non_aktif").jqxTreeGrid('getSelection');
                        if (selection.length > 1) {
                            var keys = new Array();
                            for (var i = 0; i < selection.length; i++) {
                                keys.push($("#treeGrid_akun_non_aktif").jqxTreeGrid('getKey', selection[i]));
                            }
                            if(confirm('Apakah anda yakin akan menghapus beberapa data sekaligus ? Data yang telah terhapus tidak dapat di kembalikan lagi')){
                              $("#treeGrid_akun_non_aktif").jqxTreeGrid('deleteRow', keys);
                            }
                        }
                        else {
                            if(confirm('Apakah anda yakin akan menghapus data ini ? Data yang telah terhapus tidak dapat di kembalikan lagi')){
                              $("#treeGrid_akun_non_aktif").jqxTreeGrid('deleteRow', rowKey);
                            }
                        }
                        updateButtons('delete');
                    }
                });
            },

          columns: [                             
            { text: 'Uraian ', datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox',align: 'center', width: '30%' },
            { text: 'Kode Akun', datafield: 'kode', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'left', width: '25%'},
            { text: 'Kelompok', datafield:'parent', columntype: 'textbox', filtertype: 'textbox',align: 'center', width: '35%' },
            {text: 'Detail', width: '10%', sortable: false, align:'center', editable: false, filterable: false, cellsrenderer: function (row, column, value) {
              if(row){
                return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail(" + row + ");'></a></div>";
              }
              },
            }
              ]
        });
    });

    function detail(id){
        $("#popup_keuangan_akun_non_aktif_detail #popup_keuangan_akun_non_aktif_detail_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
          $.get("<?php echo base_url().'mst/keuangan_akun/akun_non_aktif_detail' ?>/"+ id, function(data) {
            $("#popup_keuangan_akun_non_aktif_detail_content").html(data);
          });
          $("#popup_keuangan_akun_non_aktif_detail").jqxWindow({
            theme: theme, resizable: false,
            width: 600,
            height: 380,
            isModal: true, autoOpen: false, modalOpacity: 0.2
          });
          $("#popup_keuangan_akun_non_aktif_detail").jqxWindow('open');
      }

</script>

