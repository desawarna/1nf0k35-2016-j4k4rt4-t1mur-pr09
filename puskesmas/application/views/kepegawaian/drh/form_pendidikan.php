<script>
  $(function() {
        $('#jqxTabsPendidikan').jqxTabs({ width: '100%', height: '500'});

        var loadPage = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#pendidikansub' + tabIndex).html(data);
            });
        }

        loadPage('<?php echo base_url()?>kepegawaian/drh_pedidikan/biodata_pendidikan/1/{id}', 1);
        $('#jqxTabsPendidikan').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage('<?php echo base_url()?>kepegawaian/drh_pedidikan/biodata_pendidikan/'+pageIndex+'/{id}', pageIndex);
        });

  });
</script>

<section class="content">
<div id='jqxWidgetPendidikan'>
    <div id='jqxTabsPendidikan'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Pendidikan Formal</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Diklat Struktural</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Diklat Fungsional</div>
              </div>
            </li>
        </ul>
        <div id="pendidikansub1" style="background: #FAFAFA">
        </div>
        <div id="pendidikansub2" style="background: #FAFAFA">
        </div>
        <div id="pendidikansub3" style="background: #FAFAFA">
        </div>
    </div>
</div>

</section>

