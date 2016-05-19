<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/ajaxupload.3.5.js"></script>
<script>
  $(function() {
        $('#jqxTabs').jqxTabs({ width: '100%', height: '1000'});

        $('#btn-return').click(function(){
            document.location.href="<?php echo base_url()?>kepegawaian/drh";
        });

         var loadPage = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#content' + tabIndex).html(data);
            });
        }

        loadPage('<?php echo base_url()?>mst/keuangan_sts/sts_sts/1', 1);
        $('#jqxTabs').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage('<?php echo base_url()?>mst/keuangan_sts/sts_sts/'+pageIndex , pageIndex);
        });
  });
</script>

<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
      </div>

      <div class="box-footer" >

        <div class="row" id="linkimages_alert" style="display: none">
          <div class="col-sm-12 col-md-6" id="msg_alert">
          </div>
        </div>

<div id='jqxWidget'>
    <div id='jqxTabs'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-list-alt" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Daftar Tarif STS</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-list-alt" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Pengaturan STS</div>
              </div>
            </li>
            
        </ul>
        <div id="content1" style="background: #FAFAFA"></div>
        <div id="content2" style="background: #FAFAFA"></div>

    </div>
</div>

</section>

<script>
  $(function () { 
    $("#menu_master_data").addClass("active");
    $("#menu_mst_keuangan_sts").addClass("active");
  });
</script>
