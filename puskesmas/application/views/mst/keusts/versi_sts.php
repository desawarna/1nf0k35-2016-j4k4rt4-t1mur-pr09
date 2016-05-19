<section class="content">
<div class="box box-primary">
    <div class="box-footer" style="text-align: right">
        <button type="button" name="btn_versi_sts_close" class="btn btn-warning"><i class='fa fa-close'></i> &nbsp; Tutup</button>
    </div>
          
      <div class="div-grid">
         <div id="jqxgridVersi"></div>
      </div>
</div>
</section>

<script type="text/javascript">
  $(function () { 
      $("[name='btn_versi_sts_close']").click(function(){
        $("#popup_keuangan_versi_sts").jqxWindow('close');
      });

    });

      var source = {
        datatype: "json",
        type  : "POST",
        datafields: [
        { name: 'id_mst_anggaran_versi', type: 'string'},
        { name: 'nama', type: 'string'},
        { name: 'deskripsi', type: 'string'},
        { name: 'tanggal_dibuat', type: 'string'},
        { name: 'status', type: 'string'},
        { name: 'edit', type: 'number'},
        { name: 'delete', type: 'number'}
          ],
      url: "<?php echo site_url('mst/keuangan_sts/json_anggaran_versi'); ?>",
      cache: false,
      updaterow: function (rowid, rowdata, commit) {
        },
      filter: function(){
        $("#jqxgridVersi").jqxGrid('updatebounddata', 'filter');
      },
      sort: function(){
        $("#jqxgridVersi").jqxGrid('updatebounddata', 'sort');
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
       
      $("#jqxgridVersi").jqxGrid(
      {   
        width: '98%',
        selectionmode: 'singlerow',
        source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
        showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
        rendergridrows: function(obj)
        {
          return obj.data;    
        },
        columns: [
          { text: 'Lihat', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
              var dataRecord = $("#jqxgridVersi").jqxGrid('getrowdata', row);
              if(dataRecord.edit==1){
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='view(\""+dataRecord.id_mst_anggaran_versi+"\");'></a></div>";
            }else{
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
            }
                   }
                  },
          { text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
              var dataRecord = $("#jqxgridVersi").jqxGrid('getrowdata', row);
              if(dataRecord.delete==1 && dataRecord.status!="Aktif"){
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id_mst_anggaran_versi+"\");'></a></div>";
            }else{
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
            }
           }
          },
          { text: 'Tgl Dibuat', datafield: 'tanggal_dibuat', columntype: 'textbox', filtertype: 'none', align:'center',  cellsalign: 'center', width: '20%' },
          { text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox',align: 'center', width: '23%' },
          { text: 'Deskripsi', datafield: 'deskripsi', columntype: 'textbox', filtertype: 'textbox', align: 'center', width: '29%' },
          { text: 'Status', datafield: 'status', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'center', width: '15%'} 
        ]
      });
 
      function view(versi){
        $("#popup_keuangan_versi_sts").jqxWindow('close');
        $("select[name='versi']").val(versi).change();
      }

      function del(id){
        var confirms = confirm("Hapus Data ?");
        if(confirms == true){
          $.post("<?php echo base_url().'mst/keuangan_sts/versi_del';?>/" + id,  function(){
            alert('Data berhasil dihapus');
            $.ajax({
              url: "<?php echo base_url().'mst/keuangan_sts/get_versi';?>",
               success : function(data) {
                $("select[name='versi']").html(data);
              }
            });

            $("#jqxgridVersi").jqxGrid('updatebounddata', 'cells');
          });
        }
      }

</script>

