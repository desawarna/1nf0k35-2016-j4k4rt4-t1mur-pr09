	<style>        
        .min {
            color: black\9;
            background-color: #FFFCCA\9;
        }        
        .min:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .min:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
            color: black;
            background-color: #FFFCCA	;
        }
    </style>
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">

  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
		<?php foreach($data_sts as $ds) { ?>
		<div class="box">
		
                <div class="box-header">
                  <h3 class="box-title">Data Penanggung Jawab <?php if($ds['status']!='buka') echo "<b style=\"color:red\"> [STS TUTUP BUKU]</b>"  ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
				
                  <table class="table table-condensed">
					
					<tr>
						<td></td>
						<td>
							<b>Nomor</b>
						</td>
						<td>
							<?=$ds['nomor']?>
						</td>
						<td>
						</td>
						<td></td>
                    </tr>
					<tr>
						<td></td>
						<td>
							<b>Tanggal</b>
						</td>
						<td>
							<?=$tgl2?>
						</td>
						<td>
						</td>
						<td></td>
                    </tr>
					<tr>
						<td></td>
						<td>
							<b>Puskesmas</b>
						</td>
						<td>
							<?=$ds['code_cl_phc']?> - <?=$ds['value']?>
						</td>
						<td>
						</td>
						<td></td>
                    </tr>
					<tr>
						<td></td><td></td><td></td><td></td><td></td>
					</tr>
                    <tr>
						<th></th>
						<th>Pimpinan</th>
						<th>Penerima</th>
						<th>Penyetor</th>
						<th></th>
                    </tr>
					<form method="post" action="<?=base_url()?>keuangan/sts/update_ttd">
					
					<input type="hidden" name="tgl" value="<?=$ds['tgl']?>" >
					<input type="hidden" name="puskes" value="<?=$ds['code_cl_phc']?>" >
                    <tr>
						<td></td>
						<td>
							<div class="form-group">
								<input <?php echo $ds['status']!='buka' ? "readonly" : "" ?> type="text" name="ttd_pimpinan_nama" value="<?=$ds['ttd_pimpinan_nama']?>" class="form-control" id="pimpinan_nama" placeholder="Nama Pimpinan">
							</div>
						</td>
						<td>
							<div class="form-group">
								<input <?php echo $ds['status']!='buka' ? "readonly" : "" ?> type="text" name="ttd_penerima_nama" value="<?=$ds['ttd_penerima_nama']?>" class="form-control" id="penerima_nama" placeholder="Nama Penerima">
							</div>
						</td>
						<td>
							<div class="form-group">
							  <input <?php echo $ds['status']!='buka' ? "readonly" : "" ?> type="text" name="ttd_penyetor_nama" value="<?=$ds['ttd_penyetor_nama']?>" class="form-control" id="penyetor_nama" placeholder="Nama Penyetor">
							</div>
						</td>
						<td></td>
                    </tr>
					<tr>
						<td></td>
						<td>
							<div class="form-group">
								<input <?php echo $ds['status']!='buka' ? "readonly" : "" ?> type="text" name="ttd_pimpinan_nip" value="<?=$ds['ttd_pimpinan_nip']?>" class="form-control" id="pimpinan_nip" placeholder="NIP Pimpinan">
							</div>
						</td>
						<td>
							<div class="form-group">
								<input <?php echo $ds['status']!='buka' ? "readonly" : "" ?> type="text" name="ttd_penerima_nip" value="<?=$ds['ttd_penerima_nip']?>" class="form-control" id="penerima_nip" placeholder="NIP Penerima">
							</div>
						</td>
						<td>
							<div class="form-group">
							  <input <?php echo $ds['status']!='buka' ? "readonly" : "" ?> type="text" name="ttd_penyetor_nip" value="<?=$ds['ttd_penyetor_nip']?>" class="form-control" id="penyetor_nip" placeholder="NIP Penyetor">
							</div>
						</td>
						<td></td>
                    </tr>
					
					<tr>
						<td></td><td></td><td></td><td></td><td></td>
					</tr>
					<tr>
						<td></td>
						<td><b>Uang Total</b></td><td colspan="3" id="angkaTotal">
						
						
					</tr>
					<tr>
						<td></td>
						<td><b>Terbilang</b></td><td id="terbilangTotal" colspan="3"></td>
					</tr>
					<?php } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
        		             		
		<div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>
	    <div class="box-body">
			<?php
				if($this->session->flashdata('notif_type') == 'error'){
			?>
				<div class="alert alert-warning alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Ups!</strong> <?=$this->session->flashdata('notif_content')?>
				</div>
			<?php	
				}elseif($this->session->flashdata('notif_type') == 'saved'){
			?>
				<div class="alert alert-success alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Data Telah Tersimpan !</strong> data telah tersimpan dan Anda masih bisa mengganti data.
				</div>
			<?php	
				}elseif($this->session->flashdata('notif_type') == 'closed'){
			?>
				<div class="alert alert-info alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Data Telah Tersimpan & Ditutup !</strong> Data Anda telah terkunci, hanya bisa dilihat dan tidak bisa di udah kembali.
				</div>
			<?php				
				}
			?>
			
		
			<div class="col-md-4 pull-left">
				<p id="doExpand" class="btn btn-warning" >Expand All</p>	
				<p id="doCollapse" onclick="" class="btn btn-warning" >Collapse All</p>	
			</div>
			<?php if($ds['status']=='tutup'){ ?>
				<div class="col-md-6 pull-right" style="text-align:right">
					<input disabled type="submit" class="btn btn-warning" value="STS Telah Ditutup" >								
					
					
						<input type="hidden" name="tgl" value="<?=$ds['tgl']?>" >
						<input type="hidden" name="puskes" value="<?=$ds['code_cl_phc']?>" >
						<?php 
							$kodepuskesmas = $this->session->userdata('puskesmas');							
							if(substr($kodepuskesmas, -2)=="01"){											
						?>
							<input type="button" onclick="reopen('<?=$ds['tgl']?>','<?=$ds['code_cl_phc']?>')" class="btn btn-success" name="openlagi" value="Buka STS" >
						<?php
								//kecamatan
							}else{								
								//kelurahan
							}
						?>
						
						
					
				</form>
					<a href="<?=base_url()?>keuangan/sts/general" class="btn btn-primary" >Kembali<a>					
				</div>
			<?php }elseif($ds['status']=='setor'){ ?>
				<div class="col-md-6 pull-right" style="text-align:right">
					<input disabled type="submit" class="btn btn-warning" value="STS Telah Disetor" >								
						
					
				</form>
					<a href="<?=base_url()?>keuangan/sts/general" class="btn btn-primary" >Kembali<a>					
				</div>
			<?php }else { ?>
			<div class="">		
				<div class="col-md-6 pull-right" style="text-align:right">
					<input type="hidden" name="tgl" value="<?=$ds['tgl']?>" >
					<input type="hidden" name="puskes" value="<?=$ds['code_cl_phc']?>" >
					<input type="submit" name="save" class="btn btn-success" value="Simpan Sementara" >								
					<input type="submit" name="delete" class="btn btn-danger" onclick="return confirm('apakah Anda yakin telah selesai mengisi form STS ? form yang telah ditutup tidak dapat diedit kembali')" value="Simpan & Tutup STS">
					<a href="<?=base_url()?>keuangan/sts/general" name="save" class="btn btn-primary" value="" >Kembali</a>						
				</div>
				</form>
			</div>
			<?php } ?>
	    </div>
        <div class="box-body">
			<div class="default">
				<div id="treeGrid"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>

</section>


	<script type="text/javascript">
		
		function getDemoTheme() {
			var theme = document.body ? $.data(document.body, 'theme') : null
			if (theme == null) {
				theme = '';
			}
			else {
				return theme;
			}
			var themestart = window.location.toString().indexOf('?');
			if (themestart == -1) {
				return '';
			}

			var theme = window.location.toString().substring(1 + themestart);
			if (theme.indexOf('(') >= 0)
			{
				theme = theme.substring(1);
			}
			if (theme.indexOf(')') >= 0) {
				theme = theme.substring(0, theme.indexOf(')'));
			}

			var url = "<?=base_url()?>jqwidgets/styles/jqx." + theme + '.css';

			if (document.createStyleSheet != undefined) {
				var hasStyle = false;
				$.each(document.styleSheets, function (index, value) {
					if (value.href != undefined && value.href.indexOf(theme) != -1) {
						hasStyle = true;
						return false;
					}
				});
				if (!hasStyle) {
					document.createStyleSheet(url);
				}
			}
			else {
				var hasStyle = false;
				if (document.styleSheets) {
					$.each(document.styleSheets, function (index, value) {
						if (value.href != undefined && value.href.indexOf(theme) != -1) {
							hasStyle = true;
							return false;
						}
					});
				}
				if (!hasStyle) {
					var link = $('<link rel="stylesheet" href="' + url + '" media="screen" />');
					link[0].onload = function () {
						if ($.jqx && $.jqx.ready) {
							$.jqx.ready();
						};
					}
					$(document).find('head').append(link);
				}
			}
			$.jqx = $.jqx || {};
			$.jqx.theme = theme;
			return theme;
		};
		var theme = '';
		try
		{
			if (jQuery) {
				theme = getDemoTheme();
				if (window.location.toString().indexOf('file://') >= 0) {
					var loc = window.location.toString();
					var addMessage = false;
					if (loc.indexOf('grid') >= 0 || loc.indexOf('chart') >= 0 || loc.indexOf('scheduler') >= 0 || loc.indexOf('tree') >= 0 || loc.indexOf('list') >= 0 || loc.indexOf('combobox') >= 0 || loc.indexOf('php') >= 0 || loc.indexOf('adapter') >= 0 || loc.indexOf('datatable') >= 0 || loc.indexOf('ajax') >= 0) {
						addMessage = true;
					}

					if (addMessage) {
						$(document).ready(function () {
							setTimeout(function () {
								$(document.body).prepend($('<div style="font-size: 12px; font-family: Verdana;">Note: To run a sample that includes data binding, you must open it via "http://..." protocol since Ajax makes http requests.</div><br/>'));
							}
							, 50);
						});
					}
				}
			}
			else {
				$(document).ready(function () {
					theme = getDemoTheme();
				});
			}
		}
		catch (error) {
			var er = error;
		}
	</script>
	<script type="text/javascript">
		function formatMoney(n, currency) { 
			n = parseFloat(n);
			return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
		}
        $(document).ready(function () {
            $("#menu_keuangan").addClass("active");
            $("#menu_keuangan_sts_general").addClass("active");
			
			//terbilang
			document.getElementById("terbilangTotal").innerHTML = terbilang(<?php echo intval($data_sts_total); ?>);
			document.getElementById("angkaTotal").innerHTML = formatMoney(<?php echo intval($data_sts_total); ?>,'Rp');
			
            var newRowID = null;
			
			
			$("#doExpand").click(function(){
				$.post( '<?php echo base_url()?>keuangan/master_sts/set_puskes', {puskes:'<?php echo $this->session->userdata('puskes');?>'},function( data ) {
					$("#treeGrid").jqxTreeGrid('expandAll');										
				});
            });
			
			$("#doCollapse").click(function(){
				$.post( '<?php echo base_url()?>keuangan/master_sts/set_puskes', {puskes:'<?php echo $this->session->userdata('puskes');?>'},function( data ) {
					$("#treeGrid").jqxTreeGrid('collapseAll');										
				});
            });
			
			$("select[name='pilih_type']").change(function(){
				$.post( '<?php echo base_url()?>keuangan/master_sts/set_puskes', {puskes:$(this).val()},function( data ) {
					
					$("#treeGrid").jqxTreeGrid('updateBoundData');

					
				});
            });
			
            // prepare the data
            var source =
            {
                dataType: "tab",
                dataFields: [
                    { name: "Id", type: "number" },
                    { name: "ParentID", type: "number" },
                    { name: "KodeRekening", type: "string" },
                    { name: "KodeAnggaran", type: "string" },
                    { name: "Uraian", type: "string" },                    
                    { name: "Tarif", type: "number" },
                    { name: "Volume", type: "number" },
                    { name: "Subtotal", type: "number" }                    
                ],
                hierarchy:
                {
                    keyDataField: { name: 'Id' },
                    parentDataField: { name: 'ParentID' }
                },
                id: 'Id',
                url: '<?php echo base_url()?>keuangan/sts/api_data_sts_detail/<?php echo $tgl ?>',
                 addRow: function (rowID, rowData, position, parentID, commit) {				
					// POST to server using $.post or $.ajax					
                     // synchronize with the server - send insert command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.
                     // you can pass additional argument to the commit callback which represents the new ID if it is generated from a DB.
                     commit(true);
                     newRowID = rowID;
                 },
                 updateRow: function (rowID, rowData, commit) {
                     // synchronize with the server - send update command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.					
					
					
					
                    commit(true);
					var arr = $.map(rowData, function(el) { return el });																				
					
					//0,6
					//update volume data
					$.post( '<?php echo base_url()?>keuangan/sts/update_volume', {tgl:'<?=$tgl?>',id_keu_anggaran: arr[0], tarif:arr[5], vol:arr[6], code_cl_phc:'<?=$this->session->userdata['puskes']?>', },function( data ) {
						$("#treeGrid").jqxTreeGrid('updateBoundData');	
						//alert(data);
						document.getElementById("terbilangTotal").innerHTML = terbilang(data.split('.')[0]);
						document.getElementById("angkaTotal").innerHTML = formatMoney(data, "Rp");						
					});
                 },
                 deleteRow: function (rowID, commit) {
                     // synchronize with the server - send delete command
                     // call commit with parameter true if the synchronization with the server is successful 
                     // and with parameter false if the synchronization failed.
					
					 
                     commit(true);
                 }
             };

            var dataAdapter = new $.jqx.dataAdapter(source, {
                loadComplete: function () {
                    // data is loaded.
					$("#treeGrid").jqxTreeGrid('expandAll');
                }
            });

            $("#treeGrid").jqxTreeGrid(
            {
                width: '100%',				
                source: dataAdapter, 
                //pageable: true,
                editable: <?php echo $ds['status']!='buka' ? "false" : "true" ?>,                
                altRows: true,
                ready: function()
                {
					
                    // called when the DatatreeGrid is loaded.         
                },
                //pagerButtonsCount: 8,                
                columns: [				                                 
                  { text: 'Kode Anggaran', editable:false, dataField: "KodeAnggaran", align: 'center', width: '25%' },
                  { text: 'Uraian', editable:false, dataField: "Uraian", align: 'center', width: '43%' },
				  { text: 'Volume', dataField: "Volume",cellClassName: "min", editable:<?php echo $ds['status']!='buka' ? "false" : "true" ?>, align: 'center', cellsAlign: 'right',  cellsFormat: "f", width: '8%' },
				  { text: 'Tarif', dataField: "Tarif", editable:false, align: 'center', cellsAlign: 'right', cellsFormat: "f", width: '12%' },                                    
				  { text: 'Sub Total', dataField: "Subtotal", editable:false, align: 'center', cellsAlign: 'right', cellsFormat: "f", width: '12%' }      
                ]
            });
			
			
        });
		
		function addParent(){
			var sub_id = 0;
			var kode_rekening = document.getElementById("kode_rekening").value;
			var kode_anggaran = document.getElementById("kode_anggaran").value;
			var uraian = document.getElementById("uraian").value;
			$.post( '<?php echo base_url()?>keuangan/master_sts/anggaran_add', {sub_id:sub_id, kode_rekening:kode_rekening, kode_anggaran:kode_anggaran, uraian:uraian},function( data ) {
					$("#treeGrid").jqxTreeGrid('updateBoundData');
					
					document.getElementById("kode_rekening").value='';
					document.getElementById("kode_anggaran").value='';
					document.getElementById("uraian").value = '';
				});
		}
		
		function reopen(tgl, code_cl_phc){
			
			$.post( '<?php echo base_url()?>keuangan/sts/reopen', {tgl:tgl, code_cl_phc:code_cl_phc},function( data ) {
					location.reload();
				});
		}
		
		/*
Fungsi terbilang dalam JavaScript
dibuat oleh Budi Adiono (iKode.net)
*/

function terbilang(bilangan) {

	  bilangan    = String(bilangan);
	  var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
	  var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
	  var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

	  var panjang_bilangan = bilangan.length;

	  /* pengujian panjang bilangan */
	  if (panjang_bilangan > 15) {
		kaLimat = "Diluar Batas";
		return kaLimat;
	  }

	  /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
	  for (i = 1; i <= panjang_bilangan; i++) {
		angka[i] = bilangan.substr(-(i),1);
	  }

	  i = 1;
	  j = 0;
	  kaLimat = "";


	  /* mulai proses iterasi terhadap array angka */
	  while (i <= panjang_bilangan) {

		subkaLimat = "";
		kata1 = "";
		kata2 = "";
		kata3 = "";

		/* untuk Ratusan */
		if (angka[i+2] != "0") {
		  if (angka[i+2] == "1") {
			kata1 = "Seratus";
		  } else {
			kata1 = kata[angka[i+2]] + " Ratus";
		  }
		}

		/* untuk Puluhan atau Belasan */
		if (angka[i+1] != "0") {
		  if (angka[i+1] == "1") {
			if (angka[i] == "0") {
			  kata2 = "Sepuluh";
			} else if (angka[i] == "1") {
			  kata2 = "Sebelas";
			} else {
			  kata2 = kata[angka[i]] + " Belas";
			}
		  } else {
			kata2 = kata[angka[i+1]] + " Puluh";
		  }
		}

		/* untuk Satuan */
		if (angka[i] != "0") {
		  if (angka[i+1] != "1") {
			kata3 = kata[angka[i]];
		  }
		}

		/* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
		if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
		  subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
		}

		/* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
		kaLimat = subkaLimat + kaLimat;
		i = i + 3;
		j = j + 1;

	  }

	  /* mengganti Satu Ribu jadi Seribu jika diperlukan */
	  if ((angka[5] == "0") && (angka[6] == "0")) {
		kaLimat = kaLimat.replace("Satu Ribu","Seribu");
	  }

	  return kaLimat + "Rupiah";
	}
    </script>
	
		