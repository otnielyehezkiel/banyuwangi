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
                            <h2>Pertanian | Luas Lahan Panen</h2>
                            <br>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalTambah" style="margin-bottom: 10px;">Tambah Data</button>

                        </div>
                    </div>

                    <div class="row" style="margin-top: 50px">
                        <div class="col-lg-12">
                            <table id="example" class="display" cellspacing="0" width="100%">

                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kabupaten</th>
                                    <th>Nama Kecamatan</th>
                                    <th>Jenis Tanaman</th>
                                    <th>Luas Panen</th>
                                    <th>Produktivitas</th>
                                    <th>Produksi</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kabupaten</th>
                                    <th>Nama Kecamatan</th>
                                    <th>Jenis Tanaman</th>
                                    <th>Luas Panen</th>
                                    <th>Produktivitas</th>
                                    <th>Produksi</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                <?php
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
                                            echo "<td>$val->jenis_tanaman</td>";
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

<!-- Modal -->
<div id="modalTambah" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Data</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo site_url()?>/data/tambah/<?php echo $table; ?>">
                    <div class="form-group">
                        <label for="kabupaten">Kabupaten:</label>
                        <select id="kabupaten" class="form-control" name="kabupaten">
                            <?php
                                foreach ($kabupaten->result() as $val)
                                {
                                    echo "<option values='$val->id_kabupaten'>$val->nama_kabupaten</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan:</label>
                        <select id="kecamatan" class="form-control" name="kecamatan">
                            <?php
                            foreach ($kecamatan->result() as $val)
                            {
                                echo "<option values='$val->id_kecamatan'>$val->nama_kecamatan</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_tanaman">Jenis Tanaman</label>
                        <input type="text" class="form-control" id="jenis_tanaman" name="jenis_tanaman" value="">
                    </div>
                    <div class="form-group">
                        <label for="luas_panen">Luas Panen</label>
                        <input type="text" class="form-control" id="luas_panen" name="luas_panen" value="">
                    </div>
                    <div class="form-group">
                        <label for="produktivitas">Produktivitas</label>
                        <input type="text" class="form-control" id="produktivitas" name="produktivitas" value="">
                    </div>
                    <div class="form-group">
                        <label for="produksi">Produksi</label>
                        <input type="text" class="form-control" id="produksi" name="produksi" value="">
                    </div>
                    <div class="form-group">
                        <label for="jenis_tanaman">Waktu</label>
                        <input type="text" class="form-control" id="waktu" name="waktu" value="">
                    </div>
                    <button type="submit" name="submit" value="submit" class="btn btn-success">Submit</button>
                    <button type="reset" name="reset" class="btn btn-danger">Reset</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>

<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<script>
    $(function() {
        $( "#waktu" ).datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            yearRange: "-50:+0",
            onClose: function(dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $($('.has_sub')[2]).addClass('open');
        $('#example').DataTable();
    });
</script>
