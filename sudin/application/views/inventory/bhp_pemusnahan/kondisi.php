
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<script type="text/javascript">
     var sourceoengeluaran = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_mst_inv_barang_habispakai', type: 'string' },
      { name: 'uraian', type: 'string' },
      { name: 'nama_pilihan', type: 'string' },
      { name: 'tgl_update', type: 'date' },
      { name: 'jml', type: 'string' },
      { name: 'harga', type: 'double' },
        ],
    url: "<?php echo site_url('inventory/bhp_opname/jsonpengeluaran/'.$kode); ?>",
    cache: false,
      updateRow: function (rowID, rowData, commit) {
             
         },
    filter: function(){
      $("#jqxgridpengeluaran").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgridpengeluaran").jqxGrid('updatebounddata', 'sort');
    },
    root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){   
      if (data != null){
        sourceoengeluaran.totalrecords = data[0].TotalRows;          
      }
    }
    };    
    var dataadapterpengeluaran = new $.jqx.dataAdapter(sourceoengeluaran, {
      loadError: function(xhr, status, error){
        alert(error);
      }
    });
     
    $('#btn-refresh').click(function () {
      $("#jqxgridpengeluaran").jqxGrid('clearfilters');
    });

    $("#jqxgridpengeluaran").jqxGrid(
    {   
      width: '100%',
      selectionmode: 'singlerow',
      source: dataadapterpengeluaran, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100', '200'],
      showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
      rendergridrows: function(obj)
      {
        return obj.data;    
      },
      columns: [
      /*  { text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgridpengeluaran").jqxGrid('getrowdata', row);
            if(dataRecord.id_mst_inv_barang_habispakai!=null){
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='add(\""+dataRecord.id_mst_inv_barang_habispakai+"\");'></a></div>";
            }else{
              return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lockdo.gif'></a></div>";
            }
          }
        },*/
      //  { text: 'Kode Barang', editable:false ,datafield: 'id_mst_inv_barang_habispakai', columntype: 'textbox', filtertype: 'none', width: '20%' },
        { text: 'Tanggal Pengeluaran', align: 'center', cellsalign: 'center', columngroup: 'update',editable: false,datafield: 'tgl_update', columntype: 'date', filtertype: 'none', cellsformat: 'dd-MM-yyyy', width: '20%'},
        { text: 'Nama Barang', editable:false ,datafield: 'uraian', columntype: 'textbox', filtertype: 'textbox', width: '40%' },
       /* { text: 'Jumlah Awal',editable:false ,align: 'center', cellsalign: 'right', datafield: 'jmlawal', columntype: 'textbox', filtertype: 'none', width: '13%' },
        { text: 'Jumlah Akhir',editable:false ,align: 'center', cellsalign: 'right', datafield: 'jml_akhir', columntype: 'textbox', filtertype: 'none', width: '13%' },*/
        { text: 'Satuan', editable:false ,datafield: 'nama_pilihan', columntype: 'textbox', filtertype: 'none', width: '20%' },
        { text: 'Jumlah Keluar',editable:false ,datafield: 'jml', columntype: 'textbox', filtertype: 'none', width: '20%' ,align: 'center', cellsalign: 'right'}
            ]
    });

  $("#btn-export").click(function(){
    
    var post = "";
    var filter = $("#jqxgridpengeluaran").jqxGrid('getfilterinformation');
    for(i=0; i < filter.length; i++){
      var fltr  = filter[i];
      var value = fltr.filter.getfilters()[0].value;
      var condition = fltr.filter.getfilters()[0].condition;
      var filteroperation = fltr.filter.getfilters()[0].operation;
      var filterdatafield = fltr.filtercolumn;
      if(filterdatafield=="tgl"){
        var d = new Date(value);
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getYear();
        value = year+'-'+month+'-'+day;
        
      }
      post = post+'&filtervalue'+i+'='+value;
      post = post+'&filtercondition'+i+'='+condition;
      post = post+'&filteroperation'+i+'='+filteroperation;
      post = post+'&filterdatafield'+i+'='+filterdatafield;
      post = post+'&'+filterdatafield+'operator=and';
    }
    post = post+'&filterscount='+i;
    
    var sortdatafield = $("#jqxgridpengeluaran").jqxGrid('getsortcolumn');
    if(sortdatafield != "" && sortdatafield != null){
      post = post + '&sortdatafield='+sortdatafield;
    }
    if(sortdatafield != null){
      var sortorder = $("#jqxgridpengeluaran").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgridpengeluaran").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
      post = post+'&sortorder='+sortorder;
      
    }
    post = post+'&puskes='+$("#puskesmas option:selected").text();
    
    $.post("<?php echo base_url()?>inventory/bhp_opname/pengadaan_export",post,function(response ){
      window.location.href=response;
    });
  });
</script>

<div class="div-grid">
    <div id="jqxgridpengeluaran"></div>
</div>