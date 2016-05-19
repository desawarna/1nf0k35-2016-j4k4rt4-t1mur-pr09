<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>permohonan" method="POST" name="frmUsers">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
       <div class="box-body">
          <div class="box-footer">
            <!-- <button type="button" class="btn btn-info" onClick="document.location.href='<?php  echo base_url()?>lap_komoditi/excel/<?php  echo $tahun?>'">Excel Export</button> -->

            <label>Tahun 
              <select name="tahun" id="tahun">
                {tahun_option}
              </select> 
            </label>
        </div>
	</div>
  </div>
    <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <?php $active = true;
                    foreach ($tab as $row) { ?>
                    <li  <?php if ($active) {echo 'class="active"'; $active = false;} ?>><a href="<?php echo $row['label_id']; ?>" data-toggle="tab">Komoditas <?php echo $row['label']; ?></a></li>
                  <?php } ?>
                </ul>
                <div class="tab-content no-padding">

                  <?php $active = true;
                    foreach ($tab_content as $contentrow) { ?>
                    <div class="tab-pane <?php if ($active) {echo "active"; $active = false;} ?>" id="<?php echo $contentrow['tab_id']; ?>" style="position: relative;">
                      <div class="box">
                      <div class="box-body">
                            <!-- <div class="box-footer"> -->
                                    <button type="button" class="btn btn-info" onClick="document.location.href='<?php  echo base_url()?>lap_komoditi/excel/<?php  echo $contentrow['kode_kelompok'];?>/<?php  echo $tahun;?>'">Excel Export</button>
                                    <div style="clear:both;"></div><br>
                                <!-- </div> -->
                        <table id="dataTable_<?php echo $contentrow['tab_id']; ?>" class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Komoditi</th>
                              <th>SMB</th>
                              <th>SKMB</th>
                              <th>SKHPP</th>
                              <th>SMKP</th>
                              <th>SMSB</th>
                              <th>SMKE</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php 
                          $start=0;
                          foreach($contentrow['tab_content'] as $row):?>
                            <tr>
                              <td><?php $start++; echo ($start<10 ? "0":"").$start; ?>&nbsp;</td>
                              <td><a href="<?php echo base_url()?>komoditi/detail/<?php echo $row->kode_komoditi?>"><?php echo $row->nama?></a>&nbsp;</td>
                              <td><?php echo (isset($sert[$row->kode_komoditi]['SMB']) ? $sert[$row->kode_komoditi]['SMB'] : 0)?>&nbsp;</td>
                              <td><?php echo (isset($sert[$row->kode_komoditi]['SKMB']) ? $sert[$row->kode_komoditi]['SKMB'] : 0)?>&nbsp;</td>
                              <td><?php echo (isset($sert[$row->kode_komoditi]['SKHPP']) ? $sert[$row->kode_komoditi]['SKHPP'] : 0)?>&nbsp;</td>
                              <td><?php echo (isset($sert[$row->kode_komoditi]['SMKP']) ? $sert[$row->kode_komoditi]['SMKP'] : 0)?>&nbsp;</td>
                              <td><?php echo (isset($sert[$row->kode_komoditi]['SMSB']) ? $sert[$row->kode_komoditi]['SMSB'] : 0)?>&nbsp;</td>
                              <td><?php echo (isset($sert[$row->kode_komoditi]['SMKE']) ? $sert[$row->kode_komoditi]['SMKE'] : 0)?>&nbsp;</td>
                            </tr>
                          <?php endforeach; ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th>No</th>
                              <th>Komoditi</th>
                              <th>SMB</th>
                              <th>SKMB</th>
                              <th>SKHPP</th>
                              <th>SMKP</th>
                              <th>SMSB</th>
                              <th>SMKE</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    </div>
                  <?php } ?>
                </div>
              </div>
  </div>
</form>
</section>

<script>
	$(function () {	
    <?php foreach ($tab_content as $row) { ?>
        $("#dataTable_<?php echo $row['tab_id']; ?>").dataTable();
      <?php } ?>
        $("#dataTable").dataTable();
    $("select[name='tahun']").change(function(){
      document.location.href="<?php echo base_url().'lap_komoditi/';?>"+$("select[name='tahun']").val();
    });
		$("#menu_laporan_komoditi").addClass("active");
		$("#menu_laporan").addClass("active");

	});
</script>
