<?php
$this->load->view('page/header');
?>
<div class="container">
        <div style="margin-top:10px;" class="mainbox col-lg-12">                    
            <div class="panel panel-info" >
                <div style="padding-top:10px" class="panel-body">
					<button type="button" class="btn btn-success" onclick="tampilkan_form_tambah_pengguna()" id="button_tampilkan_form_tambah_pengguna">Tambah Pengguna</button>
                    <div class="page-tables" id="div_tambah_pengguna" style="">
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">Nama</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="nama" value=""/>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">username</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="username" value=""/>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">Password</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" id="password" value=""/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-lg-12">
                            <div class="col-lg-8 col-lg-offset-3">
                                <button type="button" class="btn btn-success" onclick="tambah_pengguna()" id="button_tambah_pengguna">Simpan</button>
								<button type="button" class="btn btn-info" onclick="batal_tambah_pengguna()">Batal</button>
                            </div>
                        </div>
                        <!-- END FIELD EDIT-->
                    </div>
                    <div class="clearfix"><br/></div>
                    <div class="page-tables">
                        <div class="table-responsive" style="; min-height: 200px;">
                            <table id="tabel_list_pengguna" cellpadding="0" cellspacing="1" border="0" width="100%" style="white-space: nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel_list_pengguna_body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
<div id="modal_hapus" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="judulModal">Hapus Pengguna</h4>
            </div>
            <div class="modal-body" id="modal_hapus_body">
            </div>
            <input type="hidden" id="id_pengguna_hapus" value='0'/>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="hapus_pengguna()" id="">Hapus</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Batal</button>
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
 $(document).ready(function () {
    $($('.has_sub')[3]).addClass('open');
	batal_tambah_pengguna();
	init_tabel_list_pengguna();
 });
 function batal_tambah_pengguna(){
	 $("#button_tampilkan_form_tambah_pengguna").show("fast");
	 $("#div_tambah_pengguna").hide("fast");
 }
 var tabel_list_pengguna = null;
 function init_tabel_list_pengguna(){
	 if(tabel_list_pengguna!=null){
		 tabel_list_pengguna.fnDestroy();
	 }
	 tabel_list_pengguna = $('#tabel_list_pengguna').dataTable({
		"processing": true,
		"serverSide": true,
		order: [[0, "asc"]],
		"columnDefs": [
			{"orderable": false, "targets": 2},
			{"searchable": false, "targets": 2}
		],
		"ajax": {
			'method': 'post',
			'data': {
			},
			"url": site_url + "/pengguna/get_list_pengguna",
			"dataSrc": function (json) {
				jsonData = json.data;
				return jsonData;
			}
		},
		"createdRow": function (row, data, index) {
			var id = data[0];
			$('td', row).eq(0).html(index + 1);
			var html = '<div class="btn-group">' +
					'<button class="btn btn-default btn-xs dropdown-toggle btn-info" data-toggle="dropdown">Aksi <span class="caret"></span></button>' +
					'<ul class="dropdown-menu">';
			html += '<li><a href="'+site_url+'/pengguna/mapping_role?id_pengguna='+id+'" target="_blank"><i class="fa fa-sitemap fa-fw"></i> Mapping Role</a></li>';
			html += '<li><a href="javascript:showEditForm('+id+','+index+')"><i class="fa fa-refresh fa-fw"></i> Ubah</a></li>';
			html += '<li><a href="javascript:showDeleteDialog(' + id + ','+index+')"><i class="fa fa-trash-o fa-fw"></i> Hapus</a></li>';
			
			html += '</ul>' +
					'</div>';
			$('td', row).eq(3).html(html);
			$(row).attr({'id': 'kabupaten_' + id});
		}
	});
 }
 function reqDataDropdown(d) {
    console.log(d);
    var targetEl = $('#' + d.target_element);
    targetEl.parent().parent().append('<img class="snake_loader" src="' + base_url + 'static/img/snake_loader.gif" width="20">');
    targetEl.html('');
    if(d.initial_text!=undefined)
        targetEl.html($('<option></option>').attr('selected', 'true').val('x').html(d.initial_text));
    $.ajax({
        type: "get",
        url: site_url + '/' + d.url,
        data: d.param,
        success: function (data) {
            var val = JSON.parse(data);
            if (val.length > 0) {
                var j2 = d.ret_array.length;
                for (var i = 0, l = val.length; i < l; i++) {
                    //console.log('proses data ke ' + i);
                    var v = val[i];
                    var id = v[d.ret_array[0]];
                    var teks = '';
                    for (var j = 1; j < j2; j++) {
                        teks += v[d.ret_array[j]] + ' ';
                    }
                    var opt = $('<option></option>').val(id).html(teks);
                    if (id == d.selected_value) {
                        opt.attr({'selected': 'true'});
                    }
                    targetEl.append(opt);
                }
            }
            if (d.end_text != undefined && d.end_text.length > 0)
                targetEl.append($('<option></option>').val('y').html(d.end_text));
            $('.snake_loader').remove();
        }
        , error: function (xhr, ajaxOptions, thrownError) {
            $('.snake_loader').remove();
        }
    });
	if(d.element_reset==undefined)
		d.element_reset=[];
	for (var i = 0, n_element_reset = d.element_reset.length; i < n_element_reset; i++) {
		var elmn = document.getElementById(d.element_reset[i]);
		while (elmn.children.length > 1) {
			elmn.children[1].remove();
		}
	}
}
function showDeleteDialog(id, indexData){
	$("#modal_hapus").modal();
}
function showEditForm(id, indexData){
	 var data = tabel_list_pengguna.fnGetData();
	 var pengguna = data[indexData];
	 console.log(pengguna);
	 tampilkan_form_tambah_pengguna();
	 $("#nama").val(pengguna[1]);
	 $("#username").val(pengguna[2]);
	 $("#button_tambah_pengguna").attr({onclick:"update_pengguna("+id+")"});
 }
 function tambah_pengguna(){
	 $.ajax({
        type: "post",
        url: site_url + '/pengguna/tambah_pengguna_json',
        data: {
			"nama": $("#nama").val(),
			"username": $("#username").val(),
			"password": $("#password").val()
		},
        success: function (data) {
            var resp = JSON.parse(data);
			if(resp["status"]=="ok"){
				tabel_list_pengguna.fnDraw();
				batal_tambah_pengguna();
			}else{
				alert(resp["reason"]);
			}
        }, 
		error: function (xhr, ajaxOptions, thrownError) {
        }
    });
 }
 function tampilkan_form_tambah_pengguna(){
	 $("#button_tampilkan_form_tambah_pengguna").hide("fast");
	 $("#div_tambah_pengguna").show("fast");
	 $("#nama").val("");
	 $("#username").val("");
	 $("#password").val("");
	 $("#button_tambah_pengguna").attr({onclick:"tambah_pengguna()"});
 }
 function update_pengguna(idPengguna){
	 $.ajax({
        type: "post",
        url: site_url + '/pengguna/update_pengguna_json',
        data: {
			"nama": $("#nama").val(),
			"username": $("#username").val(),
			"password": $("#password").val(),
			"id_pengguna": idPengguna
		},
        success: function (data) {
            var resp = JSON.parse(data);
			if(resp["status"]=="ok"){
				tabel_list_pengguna.fnDraw();
				batal_tambah_pengguna();
			}else{
				alert(resp["reason"]);
			}
        }, 
		error: function (xhr, ajaxOptions, thrownError) {
        }
    });
 }
</script>