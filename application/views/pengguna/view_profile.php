<?php
$this->load->view('page/header');
?>
<div class="container">
        <div style="margin-top:10px;" class="mainbox col-lg-12">                    
            <div class="panel panel-info" >
                <div style="padding-top:10px" class="panel-body">
                    <div class="page-tables" id="div_edit_penduduk_1" style="">
                        <form action="<?= site_url() ?>/pengguna/edit" method="post">
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">Nama</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="nik" name="nama" value="<?= $profile['PENGGUNA_NAMA'] ?>"/>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">username</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="kk" name="username" value="<?= $profile['PENGGUNA_USERNAME'] ?>"/>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">Password lama</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" id="nama" name="password0" value=""/>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">Password Baru</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" id="nama" name="password1" value=""/>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="col-lg-3 control-label">Password Baru Lagi</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" id="nama" name="password2" value=""
                            </div>
                        </div>
                         <div class="clearfix"></div>
                        <div class="form-group col-lg-12">
                            <div class="col-lg-8 col-lg-offset-3">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>
                        <!-- END FIELD EDIT-->
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
 $(document).ready(function () {
    $($('.has_sub')[3]).addClass('open');
    $('#tanggal_lahir').datepicker({ dateFormat: 'yy-mm-dd' });
   
 });
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
</script>