<?php
$this->load->view('page/header');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Grafik Harga</h2>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Filter</div>
        <div class="panel-body">
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="pasar">Pasar:</label>
                        <select class="form-control" name="pasar_id" id="pasar_id" onchange="pasar();">
                            <?php
                                foreach ($pasar as $row) {
                                    if($row['id'] == $defpasar){
                                        echo "<option value='". $row['id']. "' selected='selected' />".$row['nama']."</option>";
                                    }
                                    else echo "<option value='". $row['id']. "'/>".$row['nama']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
              </div>  
            </div>
        </div>
    </div>
    <div class="panel panel-default">
    <div class="panel-heading">Graphic</div>
        <div class="panel-body">
            <h2>Grafik Harga</h2>
            <div>
                <div id="legendDiv"> </div>
                <canvas id="chart"></canvas>
                <!-- <?php echo '<pre>' . var_export($grafik, true) . '</pre>'; ?> -->
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('page/footer');
?>


<script>

$(document).ready(function(){
    $($('.has_sub')[2]).addClass('open');
    pasar();

})

var dynamicColors = function() {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return "rgb(" + r + "," + g + "," + b  + ")";
}

var site_url = "<?php echo site_url()?>";

var chart = document.getElementById("chart");
var linegraph = new Chart(chart, {
    type: 'line',
    responsive: true,
    maintainAspectRatio: true,
    data: {
        labels: [],
        datasets: []
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

function pasar()
{
    var select = $('#pasar_id');
    var url = site_url + '/data/grafikHarga';
    console.log(select.val());
    $.ajax({
        type: 'POST',
        url: url,
        data:{ pasar_id: select.val()},
        success: function(data){
            console.log(data);
            arr = $.parseJSON(data);
            reloadchart(arr);
        },
        failure: function(err){
            console.log(err);
        }
    });
}


function reloadchart(res)
{
    if(res.length == 0){
        linegraph.clear();
        linegraph['data']['labels'] = "";
        linegraph['data']['datasets'] = "";
        linegraph.update();
    }
    var data = Array();
    var col = $.map(res, function(n, i){
        return n.commodity_name;
    });
    var col2 = $.map(res, function(n, i ){
        return n.date;
    })
    /* Kolom komoditas/label*/
    var label = col.filter(function(itm, i, col){
        return i == col.indexOf(itm);
    });
    /* Labels Tanggal */
    var labels = col2.filter(function(itm, i, col2){
        return i == col2.indexOf(itm);
    });
    /* Ambil dataset dari tiap label/kolom komoditas*/
    var temp = [];
    for(i=0;i<label.length-1;i++){
        temp[i] = $.map(res, function(n, j){
            if(n.commodity_name == label[i])
                return n.price;
        });
        var arr = Array();
        color = dynamicColors();
        arr['borderColor'] = color;
        arr['backgroundColor']=color;
        arr['fill'] = false;
        arr['tension'] = 0;
        arr['label'] = label[i];
        arr['data'] = temp[i];
        data.push(arr);
    }
    // console.log(data);
    linegraph['data']['labels']=labels;
    linegraph['data']['datasets']=data;

    linegraph.update();
}


</script>

