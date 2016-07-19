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
                                <h2><?php echo $title ?></h2>
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
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="kabupaten">Kabupaten:</label>
                            <select class="form-control" id="kabupaten" onchange="kecamatan();reloaddata()">

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan:</label>
                            <select class="form-control" id="kecamatan" onchange="reloaddata()">

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tahun_data">Tahun Data:</label>
                            <select class="form-control" id="tahun_data" onchange="reloaddata()">

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <?php
                            if(isset($jenis_data))
                            {
                                echo "<select class='form-control' id='jenis_data' onchange='jenis_data_change()'>";
                                    foreach ($jenis_data as $val)
                                    {
                                        $text=$val['jenis_data'];
                                        echo "<option value='$text'>$text</option>";
                                    }
                                echo "</select>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

<!--        <div class="panel panel-default">-->
<!--            <div class="panel-heading">Chart</div>-->
<!--            <div class="panel-body">-->
<!--                <div style="overflow-x:scroll">-->
<!--                    <div id="bar"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <div class="panel panel-default">
            <div class="panel-heading">Line Graphic (Data 5  tahun terakhir) <button data-toggle="collapse" data-target="#panel">Buka</button></div>
            <div id="panel" class="panel-body collapse">
                <div>
                    <canvas id="line_chart"></canvas>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Graphic</div>
            <div class="panel-body">
                <h2>Grafik <?php echo $title ?></h2>
                        <div>
                            <canvas id="chart"></canvas>
                        </div>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Data</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="data" class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <?php
                                if(!empty($head))
                                {
                                    foreach ($head as $val)
                                    {
                                        echo "<th>$val</th>";
                                    }
                                }
                                ?>
                                <!--                            <th>Aksi</th>-->
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <?php
                                if(!empty($head))
                                {
                                    foreach ($head as $val)
                                    {
                                        echo "<th>$val</th>";
                                    }
                                }
                                ?>
                                <!--                        <th>Aksi</th>-->
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            </div>




<?php
$this->load->view('page/footer');
?>

<script>
    var aktif=0;
    var colum_name=Array();
    var dynamicColors = function() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b  + ")";
    }

    var ctx = document.getElementById("chart");
    var graph = new Chart(ctx, {
        type: 'bar',

        maintainAspectRatio: false,
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

    var table = $('#data').DataTable( {
        "fnDrawCallback": function( oSettings ) {
            if(aktif==1)
            {
                getchart();
//                aktif=0;
            }
            else
            {
                aktif=1;
            }

        },
        "ajax": {
            "url":"<?php echo site_url()?>/data/<?php echo $table?>/0/0/2015",
            "type":"POST",
            "datatype":"json"
        }
    } );

    function getchart()
    {
        var data=table.rows().data();
        var kecamatan=Array();
        var kabupaten=Array();
        var kolom= Array();
        var ykey=Array();
        var xkey=Array();
        colum_name=new Array();
        console.log(data.length);
        if(data.length==0)
        {
            graph.clear();
            graph['data']['labels']="";
            graph['data']['datasets']="";
            graph.update();
            return;
        }
        //kecamatan
        for(i=0;i<data.length;i++)
        {
            kecamatan.push(data[i][1]);
        }
        //kabupaten
        for(i=0;i<data.length;i++)
        {
            kabupaten.push(data[i][0]);
        }

        //kolom
        for(i=0;i<data[0].length-1;i++)
        {
            var arr=Array();
            if(!isNaN(Number(data[0][i])))
            {
                var tmp=table.column(i).header();
                var color=dynamicColors();
                colum_name.push($(tmp).html());
                arr['label']=$(tmp).html();
                arr['data']=table.column(i).data();
                arr['backgroundColor']=color;
                arr['borderColor']=color;
                kolom.push(arr);
            }
        }

        if($('#kabupaten').val()==0)
        {
            graph['data']['labels']=kabupaten;
        }
        else {
            graph['data']['labels']=kecamatan;
        }

        graph['data']['datasets']=kolom;
        graph.update();
    }

    function kabupaten() {
        var $select = $('#kabupaten');

        $.getJSON("<?php echo site_url('data/getkabupaten')?>", function(data){

            //clear the current content of the select
            $select.html('');
            $select.append('<option value="' + 0 + '">' + 'Semua Kabupaten' +'</option>')
            //iterate over the data and append a select option
            $.each(data, function(key, val){
                $select.append('<option value="' + val.id_kabupaten + '">' + val.nama_kabupaten +'</option>')
            });
        });
    }

    function kecamatan() {
        var $select = $('#kabupaten');
        var $selKecamatan = $('#kecamatan');
        $.getJSON("<?php echo site_url('data/getkecamatan/')?>"+'/'+$select.val(), function(data){

            //clear the current content of the select
            $selKecamatan.html('');
            $selKecamatan.append('<option value="' + 0 + '">' + 'Semua Kecamatan' +'</option>');
            //iterate over the data and append a select option
            $.each(data, function(key, val){
                $selKecamatan.append('<option value="' + val.id_kecamatan + '">' + val.nama_kecamatan +'</option>')
            });
        });
    }

//    function initialkecamatan() {
//        var $select = $('#kabupaten');
//        var $selKecamatan = $('#kecamatan');
//        $.getJSON("<?php //echo site_url('data/getkecamatan/')?>//"+'/'+1, function(data){
//
//            //clear the current content of the select
//            $selKecamatan.html('');
//            $selKecamatan.append('<option value="' + 0 + '">' + 'Semua Kecamatan' +'</option>')
//            //iterate over the data and append a select option
//            $.each(data, function(key, val){
//                $selKecamatan.append('<option value="' + val.id_kecamatan + '">' + val.nama_kecamatan +'</option>')
//            });
//        });
//    }

    function tahundata() {
        var $select = $('#tahun_data');
        $.getJSON("<?php echo site_url('data/gettahun')?>"+"/"+'<?php echo $table?>', function(data){

            //clear the current content of the select
            $select.html('');

            //iterate over the data and append a select option
            $.each(data, function(key, val){
                $select.append('<option value="' + val.tahun_data + '">' + val.tahun_data +'</option>')
            });
        });
    }
</script>

<script>
    var label=Array();
    var xkey=Array();
    var ykeys=Array();
//    var data;




    $(document).ready(function() {

        kabupaten();
        kecamatan();
        tahundata();
        console.log($('#tahun_data').val());
        table.ajax.url("<?php echo site_url()?>/data/<?php echo $table?>/0/0").load();
        //initialkecamatan();
        var url="<?php echo site_url()?>/data/<?php echo $table?>/0/0/-1";
        line_chart(url);
    } );

    function reloaddata()
    {
        var $select1 = $('#kabupaten');
        var $select2 = $('#kecamatan');
        var $select3 = $('#tahun_data');

        table.ajax.url("<?php echo site_url()?>/data/<?php echo $table?>"+"/"+$select1.val()+"/"+$select2.val()+"/"+$select3.val()).load();
        var url="<?php echo site_url()?>/data/<?php echo $table?>"+"/"+$select1.val()+"/"+$select2.val()+"/"+"-1";
        line_chart(url);
    }


    function jenis_data_change()
    {
        var $select=$('#jenis_data');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('data/set_jenis_session')?>",
            data: { value: $select.val() }
        }).done(function( msg ) {
            reloaddata()
        });
    }

    var ltx = document.getElementById("line_chart");
    var linegraph = new Chart(ltx, {
        type: 'line',

        maintainAspectRatio: false,
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

    function line_chart(url)
    {

        $.getJSON(url, function(result){
            if(result.length==0)
            {
                linegraph.clear();
                linegraph['data']['labels']="";
                linegraph['data']['datasets']="";
                linegraph.update();
            }
            var label=Array();
            var data2=Array();

            var tmp=Array();
            var tmp2=Array();
            //console.log(colum_name);

            for(i=0;i<result.length;i++)
            {
                label.push(result[i][0]);
            }

            for(i=1;i<=result[0].length;i++)
            {
                tmp2= new Array();
                for(j=0;j<result.length;j++)
                {
                    tmp2.push(result[j][i]);
                }
                tmp.push(tmp2);
            }
            //console.log(tmp[0]);
            for(i=0;i<result[0].length-1;i++)
            {
                var arr2=Array();
                    arr2['fill']=false;
                    arr2['label']=colum_name[i];
                    arr2['data']=tmp[i];
                    arr2['backgroundColor']=dynamicColors();
                    data2.push(arr2);

            }
            console.log(data);
            linegraph['data']['labels']=label;
            linegraph['data']['datasets']=data2;
            linegraph.update();
        });
    }
</script>
