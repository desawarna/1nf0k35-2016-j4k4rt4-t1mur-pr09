<div class="row" style="margin: 0">
  <div class="col-md-12">
    <div class="box-footer" style="background: #FAFAFA;text-align: right">
      <button type="button" class="btn btn-primary" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
      <button type="button" class="btn btn-warning" id="btn-struktural-tambah"><i class='fa fa-plus-circle'></i> &nbsp; Tambah Data Diklat Strukutral</button>
       </div>
        <div class="box-body">
          <div class="div-grid">
              <div id="jqxgridPendidikanStruktural"></div>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="popup_pendidikan_struktural" style="display:none">
  <div id="popup_title">Data Diklat Strukutral</div>
  <div id="popup_pendidikan_struktural_content">&nbsp;</div>
</div>

<script type="text/javascript">
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_pegawai', type: 'string'},
      { name: 'mst_peg_id_diklat', type: 'number'},
      { name: 'jenis_diklat', type: 'string'},
      { name: 'tipe', type: 'string'},
      { name: 'nama_diklat', type: 'string'},
      { name: 'tgl_diklat', type: 'date'},
      { name: 'nomor_sertifikat', type: 'string'},
      { name: 'edit', type: 'number'},
      { name: 'delete', type: 'number'}
        ],
    url: "<?php echo site_url('kepegawaian/drh_pedidikan/json_pendidikan_struktural/{id}'); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgridPendidikanStruktural").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgridPendidikanStruktural").jqxGrid('updatebounddata', 'sort');
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
      $("#jqxgridPendidikanStruktural").jqxGrid('clearfilters');
    });

    $("#jqxgridPendidikanStruktural").jqxGrid(
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
            var dataRecord = $("#jqxgridPendidikanStruktural").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail_struktural (\""+dataRecord.id_pegawai+"\",\""+dataRecord.mst_peg_id_diklat+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif'></a></div>";
          }
                 }
                },
        { text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgridPendidikanStruktural").jqxGrid('getrowdata', row);
            if(dataRecord.delete==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_struktural (\""+dataRecord.id_pegawai+"\",\""+dataRecord.mst_peg_id_diklat+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
        { text: 'Nomor', datafield: 'nomor_sertifikat', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center',width: '20%' },
        { text: 'Tanggal', datafield: 'tgl_diklat', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', align: 'center', cellsalign: 'center', width: '10%' },
        { text: 'Nama Diklat', datafield: 'nama_diklat', columntype: 'textbox', filtertype: 'textbox', align: 'center' , cellsalign: 'center', width: '38%' },
        { text: 'Jenis', datafield: 'jenis_diklat', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '22%' }
            ]
    });

  function detail_struktural(id,id_diklat){
      $("#popup_pendidikan_struktural #popup_pendidikan_struktural_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_struktural_edit' ?>/" + id +"/"+id_diklat,  function(data) {
        $("#popup_pendidikan_struktural_content").html(data);
      });
      $("#popup_pendidikan_struktural").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 400,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_pendidikan_struktural").jqxWindow('open');
  }

  function del_struktural(id,id_diklat){
    var confirms = confirm("Hapus Data ?");
    if(confirms == true){
      $.post("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_struktural_del' ?>/" + id +"/"+id_diklat,   function(){
        alert('data berhasil dihapus');

        $("#jqxgridPendidikanStruktural").jqxGrid('updatebounddata', 'cells');
      });
    }
  }

  $(function () { 
    $("#btn-struktural-tambah").click(function(){
      $("#popup_pendidikan_struktural #popup_pendidikan_struktural_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_struktural_add/'.$id;?>" , function(data) {
        $("#popup_pendidikan_struktural_content").html(data);
      });
      $("#popup_pendidikan_struktural").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 400,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_pendidikan_struktural").jqxWindow('open');
    });
  });

</script>
