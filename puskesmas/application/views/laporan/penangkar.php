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
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
          <div class="box-footer">
             <button type="button" class="btn btn-info" onClick="document.location.href='<?php  echo base_url()?>lap_penangkar/excel'">Excel Export</button>
          </div>
        </div>

        <div class="box-body">
            <table id="dataTable" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Jenis Usaha</th>
                  <th>Provinsi</th>
                  <th>Kota</th>
                  <th>Kecamatan</th>
                  <th>Desa</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              $start=0;
              foreach($penangkar as $row):?>
                <tr>
                  <td><?php $start++; echo ($start<10 ? "0":"").$start; ?>&nbsp;</td>
                  <td><?php echo $row->nama?>&nbsp;</td>
                  <td><?php echo $row->perusahaan?>&nbsp;</td>
                  <td><?php echo $row->propinsi?>&nbsp;</td>
                  <td><?php echo $row->kota?>&nbsp;</td>
                  <td><?php echo $row->kecamatan?>&nbsp;</td>
                  <td><?php echo $row->desa?>&nbsp;</td>
                </tr>
              <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Provinsi</th>
                  <th>Kota</th>
                  <th>Alamat</th>
                  <th>Terdaftar</th>
                </tr>
              </tfoot>
            </table>

	    </div>

	  </div>
	</div>
  </div>
</form>
</section>

<script>
	$(function () {	
        $("#dataTable").dataTable();
		$("#menu_laporan_penangkar").addClass("active");
		$("#menu_laporan").addClass("active");
	});
</script>
