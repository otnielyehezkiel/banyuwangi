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

<?php
$this->load->view('page/footer');
?>
