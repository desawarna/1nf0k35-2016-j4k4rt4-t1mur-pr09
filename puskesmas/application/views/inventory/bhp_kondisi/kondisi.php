<table width="80%" align="center" class="table table-striped table-bordered" cellspacing="0" id="tblkondisi">
  <tr>
    <th>Tanggal</th>
    <th>Baik</th>
    <th>Rusak</th>
    <th>Tidak diPakai</th>
  </tr>
  <?php
    foreach ($data_kondisi as $key) {
  ?>
   <tr>
    <td><?php echo date("d-m-Y",strtotime($key->tgl_update)); ?></td>
    <td><?php echo ($key->jml+$key->totaljumlah) - ($key->jml_rusak + $key->jml_tdkdipakai+$key->jmlpengeluaran); ?></td>
    <td><?php echo $key->jml_rusak; ?></td>
    <td><?php echo $key->jml_tdkdipakai; ?></td>
   </tr>
  <?php
    }
  ?>
</table>