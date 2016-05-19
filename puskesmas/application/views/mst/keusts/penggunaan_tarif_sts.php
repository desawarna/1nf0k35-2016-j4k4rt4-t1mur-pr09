<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div id="popup_barang_bhp" style="display:none">
  <div id="popup_title">Detail Opname Barang</div>
  <div id="popup_content_bhp">&nbsp;</div>
</div>
<section class="content">
<form action="<?php echo base_url()?>inventory/bhp_opname/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
          <div class="box-footer">
            <div class="row"> 
              <div class="col-md-3 pull-right">
              </div>
            </div>
    </div>
         <div class="box-body">
        <div class="div-grid">
            <div id="jqxgrid"></div>
      </div>
      </div>
    </div>
  </div>
  </div>
</form>
</section>

<script type="text/javascript">

  function detail(id_pegawai){
      document.location.href="<?php echo base_url()?>kepegawaian/drh/detail/" + id_pegawai;
  }

     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'code', type: 'string'},
      { name: 'value', type: 'string'},
      { name: 'nama', type: 'string'}
        ],
    url: "<?php echo site_url('mst/keuangan_sts/json_puskesmas'); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgrid").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
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
      $("#jqxgrid").jqxGrid('clearfilters');
    });

    $("#jqxgrid").jqxGrid(
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
        { text: 'Kode Puskesmas', datafield: 'code', columntype: 'textbox', filtertype: 'textbox', align: 'center' , cellsalign: 'center', width: '20%'},
        { text: 'Nama Puskesmas', datafield: 'value', columntype: 'textbox', filtertype: 'textbox', align: 'center', width: '40%' },
        { text: 'Versi STS Aktif', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', align: 'center', width: '40%' }
          ]
    });

  
</script>