<div class="row" style="margin: 0">
  <div class="col-md-12">
    <div class="box-footer" style="background: #FAFAFA;text-align: right">
      <button type="button" class="btn btn-primary" id="btn-fungsional-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
      <button type="button" class="btn btn-warning" id="btn-fungsional-tambah"><i class='fa fa-plus-circle'></i> &nbsp; Tambah Data Diklat Fungsional</button>
       </div>
        <div class="box-body">
          <div class="div-grid">
              <div id="jqxgridPendidikanFungsional"></div>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="popup_pendidikan_fungsional" style="display:none">
  <div id="popup_title">Data Diklat Fungsional</div>
  <div id="popup_pendidikan_fungsional_content">&nbsp;</div>
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
      { name: 'lama_diklat', type: 'number'},
      { name: 'tipe', type: 'string'},
      { name: 'instansi', type: 'string'},
      { name: 'penyelenggara', type: 'string'},
      { name: 'edit', type: 'number'},
      { name: 'delete', type: 'number'}
        ],
    url: "<?php echo site_url('kepegawaian/drh_pedidikan/json_pendidikan_fungsional/{id}'); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgridPendidikanFungsional").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgridPendidikanFungsional").jqxGrid('updatebounddata', 'sort');
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
     
    $('#btn-fungsional-refresh').click(function () {
      $("#jqxgridPendidikanFungsional").jqxGrid('clearfilters');
    });

    $("#jqxgridPendidikanFungsional").jqxGrid(
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
            var dataRecord = $("#jqxgridPendidikanFungsional").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail_fungsional (\""+dataRecord.id_pegawai+"\",\""+dataRecord.mst_peg_id_diklat+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif'></a></div>";
          }
                 }
                },
        { text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgridPendidikanFungsional").jqxGrid('getrowdata', row);
            if(dataRecord.delete==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_fungsional (\""+dataRecord.id_pegawai+"\",\""+dataRecord.mst_peg_id_diklat+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
        { text: 'Tipe', datafield: 'tipe', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'center', width: '8%' },
        { text: 'Instansi', datafield: 'instansi', columntype: 'textbox', filtertype: 'textbox', align:'center', cellsalign: 'center', width: '15%' },
        { text: 'Tanggal', datafield: 'tgl_diklat', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', align: 'center', cellsalign: 'center', width: '10%' },
        { text: 'Nama Diklat', datafield: 'nama_diklat', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '25%' },
        { text: 'Lama Diklat', datafield: 'lama_diklat', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center',width: '8%' },
        { text: 'Institusi Penyelenggara', datafield: 'penyelenggara', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '24%' }
            ]
    });

  function detail_fungsional(id,id_diklat){
      $("#popup_pendidikan_fungsional #popup_pendidikan_fungsional_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_fungsional_edit' ?>/" + id +"/"+id_diklat,  function(data) {
        $("#popup_pendidikan_fungsional_content").html(data);
      });
      $("#popup_pendidikan_fungsional").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 500,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_pendidikan_fungsional").jqxWindow('open');
  }

  function del_fungsional(id,id_diklat){
    var confirms = confirm("Hapus Data ?");
    if(confirms == true){
      $.post("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_fungsional_del' ?>/" + id +"/"+id_diklat,   function(){
        alert('data berhasil dihapus');

        $("#jqxgridPendidikanFungsional").jqxGrid('updatebounddata', 'cells');
      });
    }
  }

  $(function () { 
    $("#btn-fungsional-tambah").click(function(){
      $("#popup_pendidikan_fungsional #popup_pendidikan_fungsional_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_fungsional_add/'.$id;?>" , function(data) {
        $("#popup_pendidikan_fungsional_content").html(data);
      });
      $("#popup_pendidikan_fungsional").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 500,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_pendidikan_fungsional").jqxWindow('open');
    });
  });

</script>
