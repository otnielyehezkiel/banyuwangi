<?php
$this->load->view('page/header');
?>

<div class="container">
    <div style="margin-top:10px;" class="mainbox col-md-12">
        <div class="panel panel-info" >
            <div class="panel-heading" style="height: 50px; padding: 0px" >
                <div class="panel-title col-md-6 text-center" style="height: 100%; margin: 0px; padding-top: 12px"><a href="<?= site_url() ?>/upload_excel/get">Upload Excel</a></div>
                <div class="panel-title col-md-6 text-center" style="height: 100%; margin: 0px; background-color: white; padding-top: 12px">Hasil Upload <?php echo $nama; ?></div>
            </div>
            <div style="padding-top:30px" class="panel-body">
                <div class="clearfix"></div>
                <div class="page-tables" id="div___" style="">

                </div>
                <div class="clearfix"></div>
                <div class="page-tables">
                    <div class="table-responsive" style="; min-height: 200px;overflow-x: auto">
                        <form method="post" action="<?php echo site_url('/data/savefromexceltoproduksi/') ?>">
                            <input type="hidden" name="filepath" value="<?php echo $filepath; ?>">
                            <input type='hidden' name='jenis_data' value="<?php echo $jenis_data; ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="waktu">Bulan dan Tahun Data:</label>
                                        <input type="text" id="waktu" name="waktu" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="kecamatan">Kecamatan:</label>
                                        <select class="form-control" id="kecamatan" name="kecamatan" onchange="">

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <table id="" cellpadding="0" cellspacing="1" border="0" width="100%" style="white-space: nowrap" class="table table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox"  id="checkbox_check_semua" onclick="checkall(this)"/> </th>
                                    <?php
                                    foreach($header as $row){
                                        echo "<th>$row</th>";
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no_id=0;
                                foreach ($data_val as $val)
                                {
                                    $no_id++;
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='row[]' id='row' value='$no_id'/></td>";
                                    echo "<td>$val[1]</td>";
                                    echo "<td>$val[2]</td>";
                                    echo "<td>$val[3]</td>";
                                    echo "<td>$val[4]</td>";
                                    echo "<td>$val[5]</td>";
                                    echo "<td>$val[6]</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success" name="submit" value="submit">Submit</button>
                    </div>
                    </form>
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
    function checkall(source)
    {
        checkboxes=document.getElementsByName("row[]");
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
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

<script>

    function kecamatan() {
        var $selKecamatan = $('#kecamatan');
        $.getJSON("<?php echo site_url('data/getkecamatan/1')?>", function(data){

            //clear the current content of the select
            $selKecamatan.html('');
            //iterate over the data and append a select option
            $.each(data, function(key, val){
                $selKecamatan.append('<option value="' + val.id_kecamatan + '">' + val.nama_kecamatan +'</option>')
            });
        });
    }
</script>

<script>
    $(document).ready(function() {
        kecamatan();
    } );
</script>