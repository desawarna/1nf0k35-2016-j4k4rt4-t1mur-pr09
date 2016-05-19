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
            <button type="button" class="btn btn-info" onClick="document.location.href='<?php  echo base_url()?>lap_rekap/excel/<?php  echo $tahun?>'">Excel Export</button>

            <label> Tahun 
              <select name="tahun" id="tahun">
                {tahun_option}
              </select> 
            </label>

          </div>

        <div class="box-body" style="width:100%;overflow:scroll">
            <table id="dataTable" class="table table-bordered table-hover" style="width:1600px">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
                  <th rowspan="2">Nama Pemohon /<br>Jenis Usaha</th>
                  <th rowspan="2">Alamat</th>
                  <th rowspan="2">Lokasi<br>Pembibitan</th>
                  <th rowspan="2">Tanggal<br>Pemeriksaan /<br>Pengujian </th>
                  <th colspan="2">Komoditi</th>
                  <th colspan="3">Jumlah Tanaman </th>
                  <th rowspan="2">Tanggal /<br>No Sertifikat /<br>No. SERI </th>
                  <th rowspan="2">Ket. </th>
                </tr>
                 <tr>
                  <th>Jenis</th>
                  <th>Varietas/<br>Klon</th>
                  <th>Dianjukan/<br>diperiksa </th>
                  <th>Lulus</th>
                  <th>Tidak<br>Lulus</th>
                </tr>
              </thead>
              <tbody>
              <?php 
			  $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
              $start=0;
              foreach($sertifikat as $row):
				$tgl_tmp = explode("-", $row->tgl_periksa);
				$row->tgl_periksa = (int)$tgl_tmp[2]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[0];
				$tgl_tmp = explode("-", $row->tgl_berlaku);
				$row->tgl_berlaku = (int)$tgl_tmp[2]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[0];

              	?>
                <tr>
                  <td><?php $start++; echo ($start<10 ? "0":"").$start; ?>&nbsp;</td>
                  <td><?php echo $row->nama?> /<br><?php echo $row->perusahaan?>&nbsp;</td>
                  <td><?php echo $row->address.'<br>Desa '.ucwords(strtolower($row->nama_desa)).' Kecamatan '.ucwords(strtolower($row->nama_kecamatan)).'<br>'.ucwords(strtolower($row->nama_kota))?>&nbsp;</td>
                  <td><?php echo 'Desa '.ucwords(strtolower($row->nama_desa)).' Kecamatan '.ucwords(strtolower($row->nama_kecamatan)).'<br>'.ucwords(strtolower($row->nama_kota))?>&nbsp;</td>
                  <td><?php echo $row->tgl_periksa?>&nbsp;</td>
                  <td><?php echo $row->komoditi?>&nbsp;</td>
                  <td><?php echo $row->varietas?>&nbsp;</td>
                  <td><?php echo number_format($row->jml)?>&nbsp;<?php echo $row->kode_satuan?></td>
                  <td><?php echo number_format($row->jml_ok)?>&nbsp;<?php echo $row->kode_satuan?></td>
                  <td><?php echo number_format($row->jml-$row->jml_ok)?>&nbsp;<?php echo $row->kode_satuan?></td>
                  <td><?php echo $row->tgl_berlaku?><br><?php echo $row->nomor_sertifikat?>&nbsp;</td>
                  <td><?php echo $row->kode_sertifikat?>&nbsp;</td>
                </tr>
              <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th rowspan="2">No</th>
                  <th rowspan="2">Nama Pemohon /<br>Jenis Usaha</th>
                  <th rowspan="2">Alamat</th>
                  <th rowspan="2">Lokasi<br>Pembibitan</th>
                  <th rowspan="2">Tanggal<br>Pemeriksaan /<br>Pengujian </th>
                  <th colspan="2">Komoditi</th>
                  <th colspan="3">Jumlah Tanaman </th>
                  <th rowspan="2">Tanggal /<br>No Sertifikat /<br>No. SERI </th>
                  <th rowspan="2">Ket. </th>
                </tr>
                 <tr>
                  <th>Jenis</th>
                  <th>Varietas/<br>Klon</th>
                  <th>Dianjukan/<br>diperiksa </th>
                  <th>Lulus</th>
                  <th>Tidak<br>Lulus</th>
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
		$("#menu_laporan_rekap").addClass("active");
		$("#menu_laporan").addClass("active");

    $("select[name='tahun']").change(function(){
      document.location.href="<?php echo base_url().'lap_rekap/';?>"+$("select[name='tahun']").val();
    });
	});
</script>
