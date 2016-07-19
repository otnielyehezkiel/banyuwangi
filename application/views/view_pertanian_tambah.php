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


                        </div>
                    </div>

                    <div class="row" style="margin-top: 50px">
                        <div class="col-lg-12">
                            <?php $val=$table_data->first_row();?>
                            <form method="post" action="<?php echo $link; ?>">
                                <div class="form-group">
                                    <label for="jenis_tanaman">Jenis Tanaman</label>
                                    <input type="text" class="form-control" id="jenis_tanaman" name="jenis_tanaman" value="<?php echo $val->jenis_tanaman;?>">
                                </div>
                                <div class="form-group">
                                    <label for="luas_panen">Luas Panen</label>
                                    <input type="text" class="form-control" id="luas_panen" name="luas_panen" value="<?php echo $val->luas_panen;?>">
                                </div>
                                <div class="form-group">
                                    <label for="produktivitas">Produktivitas</label>
                                    <input type="text" class="form-control" id="produktivitas" name="produktivitas" value="<?php echo $val->produktivitas;?>">
                                </div>
                                <div class="form-group">
                                    <label for="produksi">Produksi</label>
                                    <input type="text" class="form-control" id="produksi" name="produksi" value="<?php echo $val->produksi;?>">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_tanaman">Waktu</label>
                                    <input type="text" class="form-control" id="waktu" name="waktu" value="<?php echo $val->bulan.' '.$val->tahun;?>">
                                </div>
                                <button type="submit" name="submit" value="submit" class="btn btn-success">Submit</button>
                                <button type="reset" name="reset" class="btn btn-danger">Reset</button>
                            </form>
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

<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>

<script>
    $(document).ready(function () {
        $($('.has_sub')[0]).addClass('open');
    });
</script>

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