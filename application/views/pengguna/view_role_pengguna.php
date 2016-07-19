<?php
$this->load->view('page/header');
?>
<div class="container">
        <div style="margin-top:10px;" class="mainbox col-lg-12">                    
            <div class="panel panel-info" >
                <div style="padding-top:10px" class="panel-body">
                    <div class="page-tables" id="div_edit_penduduk_1" style="">
						<form action="<?=site_url()?>/pengguna/update_role_pengguna" method="POST">
						<input type="hidden" name="id_pengguna" value="<?= $id_pengguna ?>"/>
                        <table>
							<thead>
								<tr>
									<th>Pilih</th>
									<th>Nama Role</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach($role as $r){
								echo '<tr>'
								.'<td><input type="checkbox" name="check_role[]" id="role_'.$r["ROLE_ID"].'" value="'.$r["ROLE_ID"].'"/></td>'
								.'<td>'.$r["ROLE_NAME"].'</td>'
								.'</tr>';
							}
							?>
							</tbody>
						</table>
						<div class="clearfix"><br/></div>
						<button type="submit" class="btn btn-success">Simpan</button>
						</form>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>  
        </div>
    </div>
<?php
$this->load->view('page/footer');
?>
<script>
var site_url='<?=site_url()?>';
var base_url='<?=base_url()?>';
var role_pengguna = <?= json_encode($role_pengguna); ?>;
 $(document).ready(function () {
    $($('.has_sub')[3]).addClass('open');
	init_role_pengguna();
 });
 
 function init_role_pengguna(){
	 for(var i=0, i2=role_pengguna.length; i<i2; i++){
		 var role = role_pengguna[i];
		 $("#role_" + role["ROLE_ID"]).prop("checked",true);
	 }
 }
 
</script>