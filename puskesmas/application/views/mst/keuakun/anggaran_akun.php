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
          <button id="doInduk" onclick='add_induk()' class="btn btn-success"><i class="icon fa fa-edit"></i> &nbsp;Ubah Anggaran</button> 
            <div class="col-md-3 pull-right">
              <div class="row">
                <div class="col-md-4" style="padding-top:5px;"><label> Periode </label> </div>
                  <div class="col-md-8">
                    <select name="tahun" id="tahun" class="form-control">
                      <?php for ($i=date("Y");$i>=date("Y")-10;$i--) { ;?>
                      <?php $select = $i == date("Y") ? 'selected=selected' : '' ?>
                     <option value="<?php echo $i; ?>" <?php echo $select ?>><?php echo $i; ?></option>
                      <?php   } ;?>
                    </select>
                  </div> 
              </div>
            </div>
        </div>
      </div>
    </div>
    <div class="box-body">
      <div class="default">
        <div id="treeGrid_anggaran_akun"></div>
      </div>
      </div>
    </div>
  </div>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function () {

    $("select[name='tahun']").change(function(){
      $.post("<?php echo base_url().'mst/keuangan_akun/filter_tahun' ?>", 'tahun='+$(this).val(),  function(){
        $("#treeGrid_anggaran_akun").jqxTreeGrid('updateBoundData','filter');
        });
    });
      
      var newRowID = null;

      var source =
      {
          datatype: "json",
          datafields: [
              { name: 'id_mst_akun' },
              { name: 'saldo_normal' }
          ],
          url: '<?php echo base_url()?>mst/keuangan_sts/json_kode_rekening',
          async: true
      };
      var saldo_norma_source = new $.jqx.dataAdapter(source);

           var source = {
            dataType: "tab",
            dataFields: [
                { name: "id_mst_akun", type: "number" },
                { name: "id_mst_anggaran_parent", type: "number" },
                { name: "kode", type: "number" },
                { name: "uraian", type: "string" },
                { name: "saldo_normal", type: "string" },
                { name: "saldo_awal", type: "number" },
                { name: "mendukung_transaksi", type: "number"}
            ],
                hierarchy:
                {
                     keyDataField: { name: 'id_mst_akun' },
                     parentDataField: { name: 'id_mst_anggaran_parent' }
                },
                id: 'id_mst_akun',

                url: '<?php echo base_url()?>mst/keuangan_akun/api_data',

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
                          $("#treeGrid_anggaran_akun").jqxTreeGrid('updateBoundData');
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

            $("#treeGrid_anggaran_akun").jqxTreeGrid({
                width: '100%',
                source: dataAdapter, 
                pageable: false,
                editable: true,
                showToolbar: true,
                altRows: true,
                ready: function(){
                   $("#treeGrid_anggaran_akun").jqxTreeGrid('expandAll');            
                },
                pagerButtonsCount: 8,
                toolbarHeight: 40,

                renderToolbar: function(toolBar){
                    var toTheme = function (className) {
                        if (theme == "") return className;
                        return className + " " + className + "-" + theme;
                    }


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
                    $("#treeGrid_anggaran_akun").on('rowSelect', function (event) {
                        var args = event.args;
                        rowKey = args.key;
                        updateButtons('Select');
                    });
                    $("#treeGrid_anggaran_akun").on('rowUnselect', function (event) {
                        updateButtons('Unselect');
                    });
                    $("#treeGrid_anggaran_akun").on('rowEndEdit', function (event) {
                        updateButtons('End Edit');
                    });
                    $("#treeGrid_anggaran_akun").on('rowBeginEdit', function (event) {
                        updateButtons('Edit');
                    });

                },
               
              columns: [                             
                { text: 'Uraian ', datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox',align: 'center', width: '45%' },
                { text: 'Kode Akun', datafield: 'kode', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'center', width: '20%'},
                { text: 'Anggaran', datafield: 'saldo_normal', columntype: 'textbox', filtertype: 'textbox', align: 'center',  width: '35%', cellsalign: 'center' }
              ]
            });
        });

    function detail(id){
        $("#popup_keuangan_akun_detail #popup_keuangan_akun_detail_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
          $.get("<?php echo base_url().'mst/keuangan_akun/induk_detail' ?>/"+ id, function(data) {
            $("#popup_keuangan_akun_detail_content").html(data);
          });
          $("#popup_keuangan_akun_detail").jqxWindow({
            theme: theme, resizable: false,
            width: 600,
            height: 450,
            isModal: true, autoOpen: false, modalOpacity: 0.2
          });
          $("#popup_keuangan_akun_detail").jqxWindow('open');
      }
    
    function add_induk(){
      $("#popup_keuangan_akun #popup_keuangan_akun_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
        $.get("<?php echo base_url().'mst/keuangan_akun/induk_add' ?>/", function(data) {
          $("#popup_keuangan_akun_content").html(data);
        });
        $("#popup_keuangan_akun").jqxWindow({
          theme: theme, resizable: false,
          width: 600,
          height: 280,
          isModal: true, autoOpen: false, modalOpacity: 0.2
        });
        $("#popup_keuangan_akun").jqxWindow('open');
    }

</script>

