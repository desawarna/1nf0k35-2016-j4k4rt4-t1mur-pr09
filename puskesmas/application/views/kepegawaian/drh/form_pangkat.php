<script>
  $(function() {
        $('#jqxTabsPangkat').jqxTabs({ width: '100%', height: '500'});
        $('#btn-skpangkat-tambah').click(function(){
            $.get('<?php echo base_url()?>kepegawaian/drh_pangkat/add/{id}', function (data) {
                $('#content4').html('data');
            });
        });

        var loadPage1 = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#pangkatnsub' + tabIndex).html(data);
            });
        }

        loadPage1('<?php echo base_url()?>kepegawaian/drh_pangkat/biodata_pangkat/1/{id}', 1);
        $('#jqxTabsPangkat').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage1('<?php echo base_url()?>kepegawaian/drh_pangkat/biodata_pangkat/'+pageIndex+'/{id}', pageIndex);
        });

  });
</script>

<section class="content">
  <div class="row" style="margin-bottom:15px">
    <div class="col-md-12" style="text-align: right">
      <button type="button" class="btn btn-success" id="btn-skpangkat-tambah"><i class='fa fa-plus-circle'></i> &nbsp; Tambah SK Pangkat</button>
    </div>
  </div>
<div id='jqxWidgetPangkat'>
    <div id='jqxTabsPangkat'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Non PNS / Honorer</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Calon Pegawai Negeri Sipil / CPNS</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Pegawai Negeri Sipil / PNS</div>
              </div>
            </li>
        </ul>
        <div id="pangkatnsub1" style="background: #FAFAFA">
        </div>
        <div id="pangkatnsub2" style="background: #FAFAFA">
        </div>
        <div id="pangkatnsub3" style="background: #FAFAFA">
        </div>
    </div>
</div>

</section>

