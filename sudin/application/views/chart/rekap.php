          <div class="row">

            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-body">
                  <div class="box-footer">
                    <label>Tahun 
                      <select name="tahun" id="tahun">
                        {tahun_option}
                      </select> 
                    </label>
                </div>
              </div>
              </div>
            </div>

            <div class="col-md-4">
              <!-- DONUT CHART -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Rekapitulasi Jenis Sertifikat</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                    <canvas id="pieChart" height="150"></canvas>
                    <BR><BR>
                    <div class="col-md-12">
                      <ul class="chart-legend clearfix" style="font-size:85%;">
                        <li class="text-black"><i class="fa fa-circle-o text-red"></i> SMB - Sertifikat Mutu Benih</li>
                        <li class="text-black"><i class="fa fa-circle-o text-green"></i> SKMB - Surat Keterangan Mutu Benih</li>
                        <li class="text-black"><i class="fa fa-circle-o text-yellow"></i> SKHPP - Surat Keterangan Hasil Pengawasan Peredaran</li>
                        <li class="text-black"><i class="fa fa-circle-o text-aqua"></i> SMK - Sertifikat Mutu Kebun Perbanyakan</li>
                        <li class="text-black"><i class="fa fa-circle-o text-light-blue"></i> SMSB - Sertifikat Mutu Sumber Benih</li>
                        <li class="text-black"><i class="fa fa-circle-o text-gray"></i> SMKE - Sertifikat Mutu Kebun Entres</li>
                      </ul>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <!-- BAR CHART -->
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Statistik Jumlah Permohonan & Sertifikasi</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="barChart" height="290"></canvas>
                    <br><br>
                    <div class="col-md-6">
                      <ul class="chart-legend clearfix">
                        <li class="text-black"><i class="fa fa-circle-o text-gray"></i> Permohonan Baru</li>
                        <li class="text-black"><i class="fa fa-circle-o text-orange"></i> Sertifikat Selesai</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


<script src="<?php echo base_url()?>public/themes/disbun/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<script>
	$(function () {	

      $("select[name='tahun']").change(function(){
        document.location.href="<?php echo base_url().'chart_sert/';?>"+$("select[name='tahun']").val();
      });

        var areaChartData = {
          labels: [{label}],
          datasets: [
            {
              label: "Permohonan",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [{value_permohonan}]
            },
            {
              label: "Sertifikat",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [{value_sertifikat}]
            }
          ]
        };

        var areaChartOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };


        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
          {
            value: {SMB},
            color: "#f56954",
            highlight: "#f56954",
            label: "SMB"
          },
          {
            value: {SKMB},
            color: "#00a65a",
            highlight: "#00a65a",
            label: "SKMB"
          },
          {
            value: {SKHP},
            color: "#f39c12",
            highlight: "#f39c12",
            label: "SKHP"
          },
          {
            value: {SMKP},
            color: "#00c0ef",
            highlight: "#00c0ef",
            label: "SMKP"
          },
          {
            value: {SMSB},
            color: "#3c8dbc",
            highlight: "#3c8dbc",
            label: "SMSB"
          },
          {
            value: {SMKE},
            color: "#d2d6de",
            highlight: "#d2d6de",
            label: "SMKE"
          }
        ];
        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        barChartData.datasets[1].fillColor = "#ff8a00";
        barChartData.datasets[1].strokeColor = "#ff8a00";
        barChartData.datasets[1].pointColor = "#ff8a00";
        var barChartOptions = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - If there is a stroke on each bar
          barShowStroke: true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth: 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing: 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing: 1,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to make the chart responsive
          responsive: true,
          maintainAspectRatio: false
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);

		$("#menu_chart_sertifikat").addClass("active");
		$("#menu_chart").addClass("active");
	});
</script>
