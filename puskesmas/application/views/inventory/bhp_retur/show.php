
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div id="popup_barang" style="display:none">
	<div id="popup_title">Data Barang</div>
	<div id="popup_content">&nbsp;</div>
</div>
<section class="content">
<div class="row">
    <div id='jqxTabs'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-list-alt" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Retur Barang</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-list" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Daftar Barang Retur</div>
              </div>
            </li>

        </ul>
        <div id="content1" style="background: #FAFAFA"></div>
        <div id="content2" style="background: #FAFAFA"></div>
    </div>
</div>
</section>

<script type="text/javascript">
  $(function() {
	    $("#menu_bahan_habis_pakai").addClass("active");
	    $("#menu_inventory_bhp_retur").addClass("active");

        $('#jqxTabs').jqxTabs({ width: '100%', height: '700'});

        var loadPage = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#content' + tabIndex).html(data);
            });
        }

        loadPage('<?php echo base_url()?>inventory/bhp_retur/tab/1', 1);
        $('#jqxTabs').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage('<?php echo base_url()?>inventory/bhp_retur/tab/'+pageIndex, pageIndex);
        });
    });	

	function close_popup(){
		$("#popup_barang").jqxWindow('close');
	}
</script>