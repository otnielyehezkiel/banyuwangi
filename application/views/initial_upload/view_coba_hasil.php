<?php
$this->load->view('page/header');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table border="1">
                <tr>
                    <th>No</th>
                    <th>Id Kabupaten</th>
                    <th>Id Kecamatan</th>
                    <th>Nama Kecamatan</th>
                    <th>Luas wilayah</th>
                    <th>Jumlah Penduduk</th>
                </tr>
                <?php 
                    foreach ($data_val as $val)
                    {
                        echo "<tr>";
                            echo "<td>$val[1]</td>";
                            echo "<td>$val[2]</td>";
                            echo "<td>$val[3]</td>";
                            echo "<td>$val[4]</td>";
                            echo "<td>$val[5]</td>";
                            echo "<td>$val[6]</td>";
                        echo "</tr>";
                    }
                
                ?>
            </table>
        </div>
    </div>
</div>

<?php
$this->load->view('page/footer');
?>