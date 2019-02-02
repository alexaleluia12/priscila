

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            curta = <?= $ang_mcurta ?> , <?= $tend_mcurta ?> <br>
            longa = <?= $ang_mlonga ?> , <?= $tend_mlonga ?>
        </div>
    </div>
     
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div id="chart_rsi" style="width: <?= $width ?>; height: <?= $height ?>;"></div>
        </div>
    </div>
     <div class="row">
        <div class="col-sm-12">
            <div id="chart_price" style="width: <?= $width ?>; height: <?= $height ?>;"></div>
        </div>
    </div>
</div>


<script>
$(function() {
    var render_chart = function() {
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawVisualization);
        
        function drawVisualization() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?= $xtitle ?>');
            data.addColumn('number', '<?= $ytitle ?>');
            data.addColumn('number', '<?= $nome1 ?>');
            data.addColumn('number', '<?= $nome2 ?>');
            data.addRows(<?= $data1 ?>);
            var options = {
              title : '<?= $title1 ?>',
              vAxis: {title: '<?= $ytitle ?>', format: 'decimal'},
              hAxis: {title: '<?= $xtitle ?>'},
            };
            
            var data2 = google.visualization.arrayToDataTable(<?= $data2 ?>);
            var options2 = {
              title : '<?= $title2 ?>',
              vAxis: {title: '<?= $ytitle ?>'},
              hAxis: {title: '<?= $xtitle ?>'},
              seriesType: 'bars'
            };
            
            var chart2 = new google.charts.Line(document.getElementById('chart_rsi'));
            chart2.draw(data2, options2);
            
            var chart = new google.charts.Line(document.getElementById('chart_price'));
            chart.draw(data, options);
        }
    };
  
    if (typeof google == 'undefined') {
        $.getScript("https://www.gstatic.com/charts/loader.js", render_chart);
    }
    else {
        render_chart();
    }
});

</script>
