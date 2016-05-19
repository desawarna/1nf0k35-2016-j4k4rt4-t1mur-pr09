<div class="row" style="margin: 0">
  <div class="col-md-12">
    <div class="box-footer" style="background: #FAFAFA;text-align: right">
      <button type="button" class="btn btn-primary" id="btn-formal-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
      <button type="button" class="btn btn-warning" id="btn-formal-tambah"><i class='fa fa-plus-circle'></i> &nbsp; Tambah Data Pendidikan Formal</button>
       </div>
        <div class="box-body">
          <div class="div-grid">
              <div id="jqxgridPendidikan"></div>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="popup_pendidikan_formal" style="display:none">
  <div id="popup_title">Data Pendidikan Formal</div>
  <div id="popup_pendidikan_formal_content">&nbsp;</div>
</div>

<script type="text/javascript">
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_pegawai', type: 'string'},
      { name: 'id_mst_peg_jurusan', type: 'string'},
      { name: 'sekolah_nama', type: 'string'},
      { name: 'sekolah_lokasi', type: 'string'},
      { name: 'ijazah_tgl', type: 'date'},
      { name: 'nama_jurusan', type: 'string'},
      { name: 'deskripsi', type: 'string'},
      { name: 'cpns', type: 'cpns'},
      { name: 'edit', type: 'number'},
      { name: 'delete', type: 'number'}
        ],
    url: "<?php echo site_url('kepegawaian/drh_pedidikan/json_pendidikan_formal/{id}'); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgridPendidikan").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgridPendidikan").jqxGrid('updatebounddata', 'sort');
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
     
    $('#btn-formal-refresh').click(function () {
      $("#jqxgridPendidikan").jqxGrid('clearfilters');
    });

    $("#jqxgridPendidikan").jqxGrid(
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
            var dataRecord = $("#jqxgridPendidikan").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail_formal (\""+dataRecord.id_pegawai+"\",\""+dataRecord.id_mst_peg_jurusan+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif'></a></div>";
          }
                 }
                },
        { text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgridPendidikan").jqxGrid('getrowdata', row);
            if(dataRecord.delete==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_formal (\""+dataRecord.id_pegawai+"\",\""+dataRecord.id_mst_peg_jurusan+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
        { text: 'Tingkat', datafield: 'deskripsi', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '9%' },
        { text: 'Bidang Studi', datafield: 'nama_jurusan', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '21%' },
        { text: 'Nama Sekolah', datafield: 'sekolah_nama', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center',width: '25%' },
        { text: 'Lokasi', datafield: 'sekolah_lokasi', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '12%' },
        { text: 'Tanggal Lulus', datafield: 'ijazah_tgl', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', align: 'center', cellsalign: 'center', width: '10%' },
        { text: 'Pendidikan Pertama', datafield: 'cpns', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '13%' }
            ]
    });

  function detail_formal(id,id_jurusan){
      $("#popup_pendidikan_formal #popup_pendidikan_formal_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_formal_edit' ?>/" + id +"/"+id_jurusan,  function(data) {
        $("#popup_pendidikan_formal_content").html(data);
      });
      $("#popup_pendidikan_formal").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 500,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_pendidikan_formal").jqxWindow('open');
  }

  function del_formal(id,id_jurusan){
    var confirms = confirm("Hapus Data ?");
    if(confirms == true){
      $.post("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_formal_del' ?>/" + id +"/"+id_jurusan,   function(){
        alert('data berhasil dihapus');

        $("#jqxgridPendidikan").jqxGrid('updatebounddata', 'cells');
      });
    }
  }

  $(function () { 
    $("#btn-formal-tambah").click(function(){
      $("#popup_pendidikan_formal #popup_pendidikan_formal_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
      $.get("<?php echo base_url().'kepegawaian/drh_pedidikan/biodata_pendidikan_formal_add/'.$id;?>" , function(data) {
        $("#popup_pendidikan_formal_content").html(data);
      });
      $("#popup_pendidikan_formal").jqxWindow({
        theme: theme, resizable: false,
        width: 600,
        height: 500,
        isModal: true, autoOpen: false, modalOpacity: 0.2
      });
      $("#popup_pendidikan_formal").jqxWindow('open');
    });
  });

</script>
