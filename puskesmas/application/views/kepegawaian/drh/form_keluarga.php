<script>
  $(function() {
        $('#jqxTabsKeluarga').jqxTabs({ width: '100%', height: '500'});

        var loadPage = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#keluargasub' + tabIndex).html(data);
            });
        }

        loadPage('<?php echo base_url()?>kepegawaian/drh_keluarga/biodata_keluarga/1/{id}', 1);
        $('#jqxTabsKeluarga').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage('<?php echo base_url()?>kepegawaian/drh_keluarga/biodata_keluarga/'+pageIndex+'/{id}', pageIndex);
        });

  });
</script>

<section class="content">
<div id='jqxWidgetKeluarga'>
    <div id='jqxTabsKeluarga'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Orang Tua</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Suami / Istri</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Anak</div>
              </div>
            </li>
        </ul>
        <div id="keluargasub1" style="background: #FAFAFA">
        </div>
        <div id="keluargasub2" style="background: #FAFAFA">
        </div>
        <div id="keluargasub3" style="background: #FAFAFA">
        </div>
    </div>
</div>

</section>

