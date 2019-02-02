<div class="container-fluid" id="mygrafic">
    
     <div class="row">
        <div class="col-sm-12">
            <div id="chart_rsi" style="width: <?= $width ?>; height: <?= $heightRSI ?>"> </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div id="chart_price" style="width: <?= $width ?>; height: <?= $height ?>"> </div>
        </div>
    </div>
</div>




<script type='text/javascript'>
g = new Dygraph(
    document.getElementById("chart_price"),

    <?= $csv ?>,
    {
        legend: "always",
        strokeWidth: 1.5
        
    } 
  );

grsi = new Dygraph(
    document.getElementById("chart_rsi"),

    <?= $rsi ?>, 
    {
        legend: "always",
        strokeWidth: 1.5
    }
  );

var sync = Dygraph.synchronize(g, grsi, {
      selection: true,
      zoom: false
});

</script>

