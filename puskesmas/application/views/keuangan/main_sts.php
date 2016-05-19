<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h4>    <i class="icon fa fa-check"></i> Information!</h4>
    <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<section class="content">
<form action="<?php echo base_url()?>keuangan/sts/dodel_multi" method="POST" name="">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick='add_sts()'><i class='fa fa-plus-square'></i> &nbsp;Tambah STS</button> 
            <button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp;Refresh</button> 
                 <div class="col-md-3 pull-right">
                    <div class="row">
                        <div class="col-md-4" style="padding-top:5px;"><label> Tahun </label> </div>
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
                 <div class="col-md-3 pull-right">
                    <div class="row">
                        <div class="col-md-4" style="padding-top:5px;"><label> Bulan </label> </div>
                        <div class="col-md-8">
                            <select name="bulan" id="bulan" class="form-control">
                                <?php foreach ($bulan as $val=>$key ) { ;?>
                                <?php $select = $val == date("m") ? 'selected=selected' : '' ?>
                                    <option value="<?php echo $val; ?>" <?php echo $select ?>><?php echo $key; ?></option>
                                <?php   } ;?>
                            </select>
                         </div> 
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

<div id="popup_keuangan_sts" style="display:none">
  <div id="popup_title">Tambah STS Baru</div>
  <div id="popup_keuangan_sts_content">&nbsp;</div>
</div>

<script type="text/javascript">

    $(function () { 
        $("#menu_keuangan").addClass("active");
        $("#menu_keuangan_sts_general").addClass("active");

        $("select[name='bulan']").change(function(){
            $.post("<?php echo base_url().'keuangan/sts/filter_bulan' ?>", 'bulan='+$(this).val(),  function(){
                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
            });
        });

        $("select[name='tahun']").change(function(){
            $.post("<?php echo base_url().'keuangan/sts/filter_tahun' ?>", 'tahun='+$(this).val(),  function(){
                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');

            });
        });
    });

       var source = {
            datatype: "json",
            type    : "POST",
            datafields: [
            { name: 'id_sts', type: 'string'},
            { name: 'tgl', type: 'date'},
            { name: 'nomor', type: 'string'},
            { name: 'total', type: 'string'},
            { name: 'status',type: 'string'},   
            { name: 'edit', type: 'number'},
            { name: 'delete', type: 'number'}
        ],
        url: "<?php echo site_url('keuangan/sts/json_sts'); ?>",
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
                { text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
                    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
                    if(dataRecord.edit==1){
                        return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='detail(\""+dataRecord.id_sts+"\");'></a></div>";
                    }else{
                        return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif'></a></div>";
                    }
                  }
                },
                
                { text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
                    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
                    if(dataRecord.delete==1){
                        return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id_sts+"\");'></a></div>";
                    }else{
                        return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
                    }
                 }
                },
                { text: 'Tanggal', align: 'center', cellsalign: 'center', datafield: 'tgl', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '20%' },
                { text: 'Nomor STS', datafield: 'nomor', columntype: 'textbox', filtertype: 'textbox',align: 'center', cellsalign: 'center', width: '21%',cellsalign: 'center'},
                { text: 'Total Uang', datafield: 'total', columntype: 'textbox', filtertype: 'textbox', align: 'center',  width: '29%',cellsalign: 'center' },
                { text: 'Status', datafield: 'status', columntype: 'textbox', filtertype: 'textbox', align: 'center', cellsalign: 'center', width: '20%' }
            ]
        });

    function detail(id){
        document.location.href="<?php echo base_url().'keuangan/sts/detail';?>/" + id ;
    }

    function del(id){
        var confirms = confirm("Hapus Data ?");
        if(confirms == true){
            $.post("<?php echo base_url().'keuangan/sts/delete_sts' ?>/" + id,  function(){
                alert('Data berhasil dihapus');

                $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
            });
        }
    }

    function add_sts(){
      $("#popup_keuangan_sts #popup_keuangan_sts_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
        $.get("<?php echo base_url().'keuangan/sts/add_sts' ?>/", function(data) {
          $("#popup_keuangan_sts_content").html(data);
        });
        $("#popup_keuangan_sts").jqxWindow({
          theme: theme, resizable: false,
          width: 600,
          height: 300,
          isModal: true, autoOpen: false, modalOpacity: 0.2
        });
        $("#popup_keuangan_sts").jqxWindow('open');
    }

</script>

