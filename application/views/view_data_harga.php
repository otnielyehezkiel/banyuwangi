<?php
$this->load->view('page/header');
?>
<style>
th { font-size: 12px; }
td { 
    font-size: 11px;
    font-weight: normal; 
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">
                <div class="panel-body">
                  <div class="row">
                            <div class="col-lg-12">
                                <h2>Harga Bahan Pokok</h2>
                                <br>
                            </div>
                </div>
                     <form class="form-inline" id="form1" method="post" action="<?php echo site_url()?>/data/hargapasar" >
                        <div class="form-group">
                          <div class="row">
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="tanggal">Tanggal:</label>
                                <div class="input-group date">
                                <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?php echo $tanggal ?>" >
                                 <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="pasar">Pasar:</label>
                                    <select class="form-control" name="pasar_id" id="pasar_id">
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
                            <br>
                            <button type="submit" class="btn btn-primary" style="margin-top:4px"> Filter</button>
                          </div>  
                        </div>
                    </form>
                    

                    <div class="row" style="margin-top: 50px">
                        <div class="col-lg-12">
                            <table id="example" class="display" cellspacing="0" width="100%">

                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bahan Pokok</th>
                                    <th>Satuan</th>
                                    <th>Harga Kemarin</th>
                                    <th>Harga Sekarang</th>
                                    <th>Perubahan (Rp)</th>
                                    <th>Perubahan (%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(!empty($harga))
                                    {
                                        $no=0;
                                        foreach ($harga as $val)
                                        {
                                            $perubahan = $val['price'] - $val['price_yesterday'];
                                            $persen = ($perubahan/$val['price_yesterday'])*100;
                                            $no++;
                                            echo "<tr>";
                                            echo "<td>$no</td>";
                                            echo "<td>".$val['commodity_title']."</td>";
                                            echo "<td>". $val['commodity_unit']."</td>";
                                            echo "<td>". $val['price_yesterday']."</td>";
                                            echo "<td>". $val['price']."</td>";
                                            echo "<td>". $perubahan ."</td>";
                                            echo "<td>". number_format((float)$persen, 2, '.', '')  ."</td>";
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('page/footer');
?>

<script>
    $(document).ready(function () {
        $($('.has_sub')[2]).addClass('open');
        $('#example').DataTable({
            "pageLength": 20

        });
    });


</script>
