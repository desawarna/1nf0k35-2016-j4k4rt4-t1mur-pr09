<div class="row" style="margin: 0">
  <div class="col-md-12">
    <div class="box-footer" style="background: #FAFAFA;text-align: right">
      <button type="button" class="btn btn-primary" id="btn-ortu-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
      <button type="button" class="btn btn-warning" id="btn-ortu-tambah"><i class='fa fa-plus-circle'></i> &nbsp; Tambah Data Orang Tua</button>
       </div>
        <div class="box-body">
          <div class="div-grid">
              <div id="jqxgridKeluarga"></div>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="popup_keluarga_ortu" style="display:none">
  <div id="popup_title">Data Keluarga Orang Tua</div>
  <div id="popup_keluarga_ortu_content">&nbsp;</div>
</div>

<script type="text/javascript">
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_pegawai', type: 'string'},
      { name: 'urut', type: 'string'},
      { name: 'nama', type: 'string'},
      { name: 'nama_keluarga', type: 'string'},
      { name: 'jenis_kelamin', type: 'string'},
      { name: 'tgl_lahir', type: 'date'},
      { name: 'code_cl_district', type: 'string'},
      { name: 'usia', type: 'string'},
      { name: 'bpjs', type: 'string'},
      { name: 'hidup', type: 'string'},
      { name: 'status_pns', type: 'string'},
      { name: 'akta_menikah', type: 'string'},
      { name: 'edit', type: 'number'},
      { name: 'delete', type: 'number'}
        ],
    url: "<?php echo site_url('kepegawaian/drh_keluarga/json_ortu/{id}'); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgridKeluarga").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgridKeluarga").jqxGrid('updatebounddata', 'sort');
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
     
    $('#btn-ortu-refresh').click(function () {
      $("#jqxgridKeluarga").jqxGrid('clearfilters');
    });

    $("#jqxgridKeluarga").jqxGrid(
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
            var dataRecord = $("#jqxgridKeluarga").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail (\""+dataRecord.id_pegawai+"\",\""+dataRecord.urut+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif'></a></div>";
          }
                 }
                },
        { text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgridKeluarga").jqxGrid('getrowdata', row);
            if(dataRecord.delete==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del (\""+dataRecord.id_pegawai+"\",\""+dataRecord.urut+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
        { text: 'Hubungan', datafield: 'nama_keluarga', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center',width: '12%' },
        { text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '25%' },
        { text: 'Jenis Kelamin', datafield: 'jenis_kelamin', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '10%' },
        { text: 'Usia', datafield: 'usia', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '8%' },
        { text: 'Tanggal Lahir', datafield: 'tgl_lahir', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', align: 'center', cellsalign: 'center', width: '10%' },
        { text: 'BPJS', datafield: 'bpjs', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '15%' },
        { text: 'Status Hidup', datafield: 'hidup', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '10%' }
            ]
    });

  function detail(id,urut){
    $("#popup_keluarga_ortu #popup_keluarga_ortu_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_keluarga/biodata_keluarga_ortu_edit' ?>/"+id+"/"+urut, function(data) {
        $("#popup_keluarga_ortu_content").html(data);
      });
      $("#popup_keluarga_ortu").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 500,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_keluarga_ortu").jqxWindow('open');
  }
 
  function del(id,urut){
       var confirms = confirm("Hapus Data ?");
    if(confirms == true){
      $.post("<?php echo base_url().'kepegawaian/drh_keluarga/biodata_keluarga_ortu_del' ?>/" + id +"/"+urut,   function(){
        alert('data berhasil dihapus');

        $("#jqxgridKeluarga").jqxGrid('updatebounddata', 'cells');
      });
    }
  }

  $(function () { 
    $("#btn-ortu-tambah").click(function(){
      $("#popup_keluarga_ortu #popup_keluarga_ortu_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_keluarga/biodata_keluarga_ortu_add/'.$id;?>" , function(data) {
        $("#popup_keluarga_ortu_content").html(data);
      });
      $("#popup_keluarga_ortu").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 500,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_keluarga_ortu").jqxWindow('open');
    });
  });

</script>
