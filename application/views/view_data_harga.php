<?php
$this->load->view('page/header');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">
                <div class="panel-body">
                     <form class="form-inline" id="form1">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="tanggal">Tanggal:</label>
                                <div class="input-group date" id='datetimepicker1'>
                                <input type="text" class="form-control" id="tanggal" value="2016-08-04" >
                                 <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>
                            </div>
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
                               <!--  <?php
                                    if($table_data->num_rows>0)
                                    {
                                        $no=0;
                                        foreach ($table_data->result() as $val)
                                        {
                                            $no++;
                                            echo "<tr>";
                                            echo "<td>$no</td>";
                                            echo "<td>$val->nama_kabupaten</td>";
                                            echo "<td>$val->nama_kecamatan</td>";
                                            echo "<td>$val->nama_tanaman</td>";
                                            echo "<td>$val->luas_panen</td>";
                                            echo "<td>$val->produktivitas</td>";
                                            echo "<td>$val->produksi</td>";
                                            echo "<td>$val->bulan</td>";
                                            echo "<td>$val->tahun</td>";
                                            echo "<td>
                                                    <a href=$link_edit$val->id_bahan_makanan><button class='btn btn-info'>Edit</button> </a>
                                                    <a href=$link$val->id_bahan_makanan><button class='btn btn-danger'>Delete</button> </a>
                                                        
                                                    </td>";
                                            echo "</tr>";
                                        }
                                    }
                                ?> -->
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



<script type="text/javascript">
    $(document).ready(function () {
        $($('.has_sub')[2]).addClass('open');
        $('#example').DataTable();
    });

    $(function(){
        $("#datetimepicker1").datetimepicker({
            format: "DD-MM-YYYY"
        });
    });
</script>
