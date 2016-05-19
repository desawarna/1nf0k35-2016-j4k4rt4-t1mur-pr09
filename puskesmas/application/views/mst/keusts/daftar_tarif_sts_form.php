<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<div id="popup_keuangan_sts" style="display:none">
  <div id="popup_title">Buat Versi Daftar Tarif STS Baru</div>
  <div id="popup_keuangan_sts_content">&nbsp;</div>
</div>

<div id="popup_keuangan_sts_induk" style="display:none">
  <div id="popup_title">Tambah Induk Baru</div>
  <div id="popup_keuangan_sts_induk_content">&nbsp;</div>
</div>

<div id="popup_keuangan_versi_sts" style="display:none">
  <div id="popup_title">Semua Versi Tarif STS</div>
  <div id="popup_keuangan_versi_sts_content">&nbsp;</div>
</div>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
           <h3 class="box-title">{title_form}</h3> 
      </div>

      <div class="box-body">
      <div class="row">
        <div class="col-md-2" style="padding-top:8px;"><label> Pilih Versi </label> </div>
        <div class="col-md-3">
          <select name="versi" class="form-control" id="versi">
            <option value="0">Pilih Versi</option>
          </select>
        </div>
        <div class="col-md-7">
          <button type="button" class="btn btn-success" onclick="lihat_versi()"><i class='fa fa-search'></i> &nbsp;Lihat Semua Versi</button>           
          <button type="button" class="btn btn-danger" onclick="add_versi()"><i class='fa fa-plus-square'></i> &nbsp;Buat Versi Baru</button> 
          <button type="button" id="btn-kembali" class="btn btn-primary pull-right"><i class='fa  fa-arrow-circle-o-left'></i> &nbsp;Kembali</button>
        </div>
      </div>
      </div>

    <div class="box-body">
      <div class="row">
        <div class="col-md-2"><label> Versi Daftar Tarif</label> </div>
        <div class="col-md-10"><label> <div id="nama_versi"> </div></label> </div>
      </div>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-md-2"><label> Status Versi </label> </div>
        <div class="col-md-2"><label> <div id="versistatusid"> </div></label> </div>
        <div class="col-md-6">
          <button id="status" type="button" class="btn btn-danger" name="aktifkan_status"> </button> 
        </div>
      </div>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-md-12 pull-left">
          <button id="doRefresh" class="btn btn-primary " ><i class="icon fa fa-refresh"></i> &nbsp;Refresh</button>  
          <button id="doExpand" class="btn btn-warning " ><i class="icon fa fa-plus-square-o"></i> &nbsp;Expand</button>  
          <button id="doCollapse" class="btn btn-warning " ><i class="icon fa fa-minus-square-o"></i> &nbsp;Collapse</button> 
          <button id="doInduk" onclick='add_induk()' class="btn btn-success"><i class="icon fa fa-plus-square"></i> &nbsp;Tambah Induk</button> 
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
    $("#btn-kembali").click(function(){
      $.get('<?php echo base_url()?>mst/keuangan_sts/kembali', function (data) {
        $('#content1').html(data);
      });
    });

    function getDemoTheme() {
      var theme = document.body ? $.data(document.body, 'theme') : null
      if (theme == null) {
        theme = '';
      } else {
        return theme;
      }
      var themestart = window.location.toString().indexOf('?');
      if (themestart == -1) {
        return '';
      }

      var theme = window.location.toString().substring(1 + themestart);
      if (theme.indexOf('(') >= 0){
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
      
      function statusversi(argument) {
        $.ajax({
        url: "<?php echo base_url().'mst/keuangan_sts/statusversi/'?>"+$("#versi").val(),
        dataType: "json",
        success:function(data){ 
          $.each(data,function(index,elemet){
            if (elemet.mst_keu_versi_status == $("#versi").val()) {
                $("#versistatusid").html("Aktif");
                $("[name='aktifkan_status']").hide();
            }else{
                $("#versistatusid").html("Non Aktif");
                $("#status").html("Aktifkan Versi Ini");
                $("[name='aktifkan_status']").show();
            }
          });
        }
        });
        return false;
      }

      function nama_versi(argument) {
        $.ajax({
          url: "<?php echo base_url().'mst/keuangan_sts/nama_versi/'?>" + $("#versi").val(),
           success:function(data){ 
          $("#nama_versi").html(data);
           }
        });
        return false;
      }

      $('#versi').change(function(){
        if (($(this).val()=='0')||$(this).val()==null) {
            var dataver = "{versi}";
        }else{
            var dataver =  $(this).val();
        }
        $.ajax({
            url : '<?php echo site_url('mst/keuangan_sts/get_versi_sts') ?>',
            type : 'POST',
            data : 'versi='+dataver,
            success : function(data) {
            $("select[name='versi']").html(data);

            statusversi();
            nama_versi();
          }
        });
        return false;
      }).change();

      var newRowID = null;
      $("#doExpand").click(function(){
          $("#treeGrid").jqxTreeGrid('expandAll');                    
      });
      
      $("#doCollapse").click(function(){
          $("#treeGrid").jqxTreeGrid('collapseAll');                    
      });

      $("select[name='versi']").change(function(){
        $.post( '<?php echo base_url()?>mst/keuangan_sts/set_versi', {versi:$(this).val()},function( data ) {
          $("#treeGrid").jqxTreeGrid('updateBoundData');
        });
      });

      $("#doRefresh").click(function(){
          $("#treeGrid").jqxTreeGrid('updateBoundData');
      });


      var source =
      {
          datatype: "json",
          datafields: [
              { name: 'id_mst_akun' },
              { name: 'rekening' }
          ],
          url: '<?php echo base_url()?>mst/keuangan_sts/json_kode_rekening',
          async: true
      };
      var kode_rekening_source = new $.jqx.dataAdapter(source);

            var source =
            {
                dataType: "tab",
                dataFields: [
                    { name: "id_mst_anggaran", type: "number" },
                    { name: "id_mst_anggaran_parent", type: "number" },
                    { name: "id_mst_akun", type: "number" },
                    { name: "kode_anggaran", type: "string" },
                    { name: "uraian", type: "string" },
                    { name: "tarif", type: "number" },
                    { name: "id_mst_anggaran_versi", type: "number" },
                    { name: "kode_rekening", type: "string" },
                    { name: "uraian_rekening", type: "string" }
                ],
                hierarchy:
                {
                    keyDataField: { name: 'id_mst_anggaran' },
                    parentDataField: { name: 'id_mst_anggaran_parent' }
                },
                id: 'id_mst_anggaran',
                url: '<?php echo base_url()?>mst/keuangan_sts/api_data',
                 addRow: function (rowID, rowData, position, parentID, commit) {        
                    commit(true);
                    newRowID = rowID;
                 },
                 updateRow: function (rowID, rowData, commit) {
                    commit(true);
                    var arr = $.map(rowData, function(el) { return el });         
                    if(typeof(arr[1]) === 'object'){
                      var arr2 = $.map(arr[1], function(el) { return el });
                      if(arr[4] + '' + arr[5] + '' + arr[6] + '' + arr[7]!='') {
                        $.post( '<?php echo base_url()?>mst/keuangan_sts/anggaran_add', {id_mst_anggaran:arr[2],id_mst_anggaran_parent:arr2[0], id_mst_akun:arr[7], kode_anggaran:arr[4], uraian : arr[5], tarif : arr[6], id_mst_anggaran_versi : arr[0]}, function( data ) {
                            if(data != 0){
                              alert(data);                  
                            }else{
                              alert("Data "+arr[5]+" berhasil disimpan");                  
                            }
                        });
                      }
                    }else{      
                      $.post( '<?php echo base_url()?>mst/keuangan_sts/anggaran_update', 
                        {
                          row:rowID,
                          id_mst_anggaran:arr[0] ,
                          id_mst_anggaran_parent:arr[1], 
                          kode_anggaran:arr[3], 
                          uraian : arr[4], 
                          tarif : arr[5], 
                          id_mst_akun:arr[7], 
                          id_mst_anggaran_versi : arr[1]
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
                        $.post( '<?php echo base_url()?>mst/keuangan_sts/anggaran_delete', {id_mst_anggaran:rowID[i]},function( data ) {
                          $("#treeGrid").jqxTreeGrid('updateBoundData');
                        });
                      }
                    }else{
                      $.post( '<?php echo base_url()?>mst/keuangan_sts/anggaran_delete', {id_mst_anggaran:rowID},function( data ) {
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

            $("#treeGrid").jqxTreeGrid({
                width: '100%',
                source: dataAdapter, 
                pageable: false,
                editable: true,
                showToolbar: true,
                altRows: true,
                ready: function(){
                   $("#treeGrid").jqxTreeGrid('expandAll');            
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
                                if(confirm('Apakah anda yakin akan menghapus beberapa data sekaligus ? Data yang telah terhapus tidak dapat di kembalikan lagi')){
                                  $("#treeGrid").jqxTreeGrid('deleteRow', keys);
                                }
                            }
                            else {
                                if(confirm('Apakah anda yakin akan menghapus data ini ? Data yang telah terhapus tidak dapat di kembalikan lagi')){
                                  $("#treeGrid").jqxTreeGrid('deleteRow', rowKey);
                                }
                            }
                            updateButtons('delete');
                        }
                    });
                },
              columns: [                             
               { text: 'Kode Anggaran', dataField: "kode_anggaran", align: 'center', width: '27%' },
               { text: 'Uraian', dataField: "uraian", align: 'center', width: '31%' }, 
               { text: 'Tarif', dataField: "tarif", align: 'center', width: '12%', cellsalign: 'right' },         
               { text: 'Kode Rekening', dataField: 'kode_rekening', width: "30%", align:'center',columnType: "template",
                   createEditor: function (row, cellvalue, editor, cellText, width, height) {
                       editor.jqxDropDownList({autoDropDownHeight: true, width: '100%', height: '100%' , source: kode_rekening_source, displayMember: "rekening", valueMember: "rekening"});

                   },
                   initEditor: function (row, cellvalue, editor, celltext, width, height) {
                       editor.jqxDropDownList('selectItem', cellvalue);
                   },
                   getEditorValue: function (row, cellvalue, editor) {
                       return editor.val();
                   }
                }
              ]
            });
        });
    
      function add_versi(){
      $("#popup_keuangan_sts #popup_keuangan_sts_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
        $.get("<?php echo base_url().'mst/keuangan_sts/versi_add' ?>/", function(data) {
          $("#popup_keuangan_sts_content").html(data);
        });
        $("#popup_keuangan_sts").jqxWindow({
          theme: theme, resizable: false,
          width: 600,
          height: 280,
          isModal: true, autoOpen: false, modalOpacity: 0.2
        });
        $("#popup_keuangan_sts").jqxWindow('open');
    }

    function add_induk(){
      $("#popup_keuangan_sts_induk #popup_keuangan_sts_induk_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
        $.get("<?php echo base_url().'mst/keuangan_sts/induk_add' ?>/", function(data) {
          $("#popup_keuangan_sts_induk_content").html(data);
        });
        $("#popup_keuangan_sts_induk").jqxWindow({
          theme: theme, resizable: false,
          width: 620,
          height: 300,
          isModal: true, autoOpen: false, modalOpacity: 0.2
        });
        $("#popup_keuangan_sts_induk").jqxWindow('open');
    }

    function lihat_versi(){
      $("#popup_keuangan_versi_sts #popup_keuangan_versi_sts_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
        $.get("<?php echo base_url().'mst/keuangan_sts/versi_view'?>/", function(data) {
          $("#popup_keuangan_versi_sts_content").html(data);
        });
        $("#popup_keuangan_versi_sts").jqxWindow({
          theme: theme, resizable: false,
          width: 620,
          height: 420,
          isModal: true, autoOpen: false, modalOpacity: 0.2
        });
        $("#popup_keuangan_versi_sts").jqxWindow('open');
    }

    $("[name='aktifkan_status']").click(function(){
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url: "<?php echo base_url().'mst/keuangan_sts/aktifkan_status/'?>"+ $("#versi").val(),
            success : function(response){
                 alert("Versi berhasil di aktifkan");
                 $("#versistatusid").html("Aktif");
                 $("[name='aktifkan_status']").hide();
            }
        });
        return false;
    });
 
</script>

