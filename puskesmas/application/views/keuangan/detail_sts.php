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
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">ﾗ</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<form method="post" action="<?=base_url()?>keuangan/sts/update_ttd">
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
		<?php foreach($data_sts as $ds) { ?>
            <div class="box-header">
              <h4>
              	<div class="row">
              		<div class="col-md-6">Data Penanggung Jawab </div>
              		<?php if($ds['status']!='draft') echo "<div  class='col-md-6 pull-right' style='color:red;text-align:right'> [STS TUTUP BUKU]</div>"  ?>
              	</div>
              </h4>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-condensed">
				<tr>
					<td></td>
					<td>
						<b>Nomor STS </b>
					</td>
					<td>
						<?php echo $ds['nomor']?>
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
						<?php echo date("d-m-Y",strtotime($ds['tgl'])); ?>
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
						<?php echo $ds['code_pl_phc']?> - <?=$ds['value']?>
					</td>
					<td>
					</td>
					<td></td>
                </tr>
				<input type="hidden" name="id_sts" value="<?=$ds['id_sts']?>" >
				<input type="hidden" name="puskes" value="<?=$ds['code_pl_phc']?>" >
                
                <tr>
					<th></th>
					<th>Pimpinan</th>
					<td><input <?php echo $ds['status']!='draft' ? "readonly" : "" ?> type="text" name="ttd_pimpinan_nama" value="<?=$ds['ttd_pimpinan_nama']?>" class="form-control" id="pimpinan_nama" placeholder="Nama Pimpinan"></td>
					<td><input <?php echo $ds['status']!='draft' ? "readonly" : "" ?> type="text" name="ttd_pimpinan_nip" value="<?=$ds['ttd_pimpinan_nip']?>" class="form-control" id="pimpinan_nip" placeholder="NIP Pimpinan"></td>
					<th></th>
                </tr>
                <tr>
					<th></th>
					<th>Penerima</th>
					<td><input <?php echo $ds['status']!='draft' ? "readonly" : "" ?> type="text" name="ttd_penerima_nama" value="<?=$ds['ttd_penerima_nama']?>" class="form-control" id="penerima_nama" placeholder="Nama Penerima"></td>
					<td><input <?php echo $ds['status']!='draft' ? "readonly" : "" ?> type="text" name="ttd_penerima_nip" value="<?=$ds['ttd_penerima_nip']?>" class="form-control" id="penerima_nip" placeholder="NIP Penerima"></td>
					<th></th>
                </tr>
                <tr>
					<th></th>
					<th>Penyetor</th>
					<td><input <?php echo $ds['status']!='draft' ? "readonly" : "" ?> type="text" name="ttd_penyetor_nama" value="<?=$ds['ttd_penyetor_nama']?>" class="form-control" id="penyetor_nama" placeholder="Nama Penyetor"></td>
					<td><input <?php echo $ds['status']!='draft' ? "readonly" : "" ?> type="text" name="ttd_penyetor_nip" value="<?=$ds['ttd_penyetor_nip']?>" class="form-control" id="penyetor_nip" placeholder="NIP Penyetor"></td>
					<th></th>
                </tr>
				<tr>
					<td></td>
					<td><b>Total Uang</b></td><td colspan="3" id="angkaTotal"></td>
				</tr>
				<tr>
					<td></td>
					<td><b>Terbilang</b></td><td id="terbilangTotal" colspan="3"></td>
				</tr>
				<?php } ?>
              </table>
            </div><!-- /.box-body -->
      	</div><!-- /.box -->
        		             		
      	<div class="box box-primary">
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
					<p id="doExpand" class="btn btn-warning"><i class="icon fa fa-plus-square-o"></i> &nbsp;Expand All</p>	
					<p id="doCollapse" onclick="" class="btn btn-warning"><i class="icon fa fa-minus-square-o"></i> &nbsp;Collapse All</p>	
				</div>
				<?php if($ds['status']=='disetor'){ ?>
					<div class="col-md-6 pull-right" style="text-align:right">
						<input type="hidden" name="id" value="<?=$ds['id_sts']?>" >
						<?php 
							if($ds['tgl'] == date("Y-m-d")){											
						?>
							<input type="button" onclick="reopen('<?=$ds['id_sts']?>','<?=$ds['code_pl_phc']?>')" class="btn btn-success" name="openlagi" value="Buka STS" >
						<?php
							}
						?>
						<a href="<?=base_url()?>keuangan/sts/general" class="btn btn-primary" >Kembali</a>					
					</div>
				<?php } else { ?>
				<div>
					<div class="col-md-8 pull-right" style="text-align:right">
						<input type="hidden" name="tgl" value="<?=$ds['tgl']?>" >
						<input type="hidden" name="puskes" value="<?=$ds['code_pl_phc']?>" >
		   	            <button type="button" id="btn-export" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Export</button>
						<input type="submit" name="save" class="btn btn-success" value="Simpan Sementara" >								
						<input type="submit" name="delete" class="btn btn-danger" onclick="return confirm('apakah Anda yakin telah selesai mengisi form STS ? form yang telah ditutup tidak dapat diedit kembali')" value="Simpan & Tutup STS">
						<a href="<?=base_url()?>keuangan/sts/general" name="save" class="btn btn-primary" value="" ><i class='fa  fa-arrow-circle-o-left'></i> &nbsp;Kembali</a>						
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
  </div>
</section>
</form>

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
            url: '<?php echo base_url()?>keuangan/sts/api_data_sts_detail/<?php echo $id ?>',
             addRow: function (rowID, rowData, position, parentID, commit) {				
                 commit(true);
                 newRowID = rowID;
             },
             updateRow: function (rowID, rowData, commit) {
                commit(true);
				var arr = $.map(rowData, function(el) { return el });
				//0,6
				//update volume data
				$.post( '<?php echo base_url()?>keuangan/sts/update_volume', {id_sts:'<?=$id?>',id_mst_anggaran: arr[0], tarif:arr[5], vol:arr[6]},function( data ) {
					$("#terbilangTotal").html(terbilang(data.split('.')[0]));
					$("#angkaTotal").html(formatMoney(data, "Rp"));	
					//$("#treeGrid").jqxTreeGrid('updateBoundData');
				});
             },
             deleteRow: function (rowID, commit) {
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
            editable: <?php echo $ds['status']!='draft' ? "false" : "true" ?>,                
            altRows: true,
            ready: function()
            {
				
            // called when the DatatreeGrid is loaded.         
            },
            //pagerButtonsCount: 8,                
            columns: [				                                 
              { text: 'Kode Anggaran', editable:false, dataField: "KodeAnggaran", align: 'center', width: '25%',cellsalign: 'left' },
              { text: 'Uraian', editable:false, dataField: "Uraian", align: 'center', width: '43%',cellsalign: 'left' },
			  { text: 'Volume', dataField: "Volume",cellClassName: "min", editable:<?php echo $ds['status']!='draft' ? "false" : "true" ?>, align: 'center', cellsFormat: "f", width: '8%',cellsalign: 'center' },
			  { text: 'Tarif', dataField: "Tarif", editable:false, align: 'center', cellsFormat: "f", width: '12%',cellsalign: 'center' },                                    
			  { text: 'Sub Total', dataField: "Subtotal", editable:false, align: 'center', cellsFormat: "f", width: '12%',cellsalign: 'center' }      
            ]
        });
    });
	
	function reopen(id_sts, code_pl_phc){
		
		$.post( '<?php echo base_url()?>keuangan/sts/reopen', {id_sts:id_sts, code_pl_phc:code_pl_phc},function( data ) {
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

		$("#btn-export").click(function(){

			var post 		= "";
			// var filter 		= $("#treeGrid").jqxTreeGrid('getfilterinformation');
			// for(i=0; i < filter.length; i++){
			// 	var fltr 	= filter[i];
			// 	var value	= fltr.filter.getfilters()[0].value;
			// 	var condition		= fltr.filter.getfilters()[0].condition;
			// 	var filteroperation	= fltr.filter.getfilters()[0].operation;
			// 	var filterdatafield	= fltr.filtercolumn;
			// 	if(filterdatafield=="tgl"){
			// 		var d 		= new Date(value);
			// 		var day 	= d.getDate();
			// 		var month 	= d.getMonth();
			// 		var year 	= d.getYear();
			// 		value 		= year+'-'+month+'-'+day;
			// 	}
			// 	post = post+'&filtervalue'+i+'='+value;
			// 	post = post+'&filtercondition'+i+'='+condition;
			// 	post = post+'&filteroperation'+i+'='+filteroperation;
			// 	post = post+'&filterdatafield'+i+'='+filterdatafield;
			// 	post = post+'&'+filterdatafield+'operator=and';
			// }
			// post = post+'&filterscount='+i;
			
			// var sortdatafield = $("#treeGrid").jqxTreeGrid('getsortcolumn');
			// if(sortdatafield != "" && sortdatafield != null){
			// 	post = post + '&sortdatafield='+sortdatafield;
			// }
			// if(sortdatafield != null){
			// 	var sortorder = $("#treeGrid").jqxTreeGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#treeGrid").jqxTreeGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			// 	post = post+'&sortorder='+sortorder;
				
			// }
			// post = post+'&puskes='+$("#puskesmas option:selected").text();
			
			$.post("<?php echo base_url()?>keuangan/sts/detail_sts_export",post,function(response	){
				window.location.href=response;
			});
		});
</script>