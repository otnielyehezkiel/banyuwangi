<?php
$this->load->view('page/header');
?>
<div class="container">
    <div style="margin-top:10px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading" style="height: 50px; padding: 0px" >
                <div class="panel-title col-md-6 text-center" style="height: 100%; margin: 0px; background-color: white; padding-top: 12px">Upload Excel</div>
                <div class="panel-title col-md-6 text-center" style="height: 100%; margin: 0px; padding-top: 12px"><a href="<?= site_url()?>/initial_upload/hasil_upload_page">Hasil Upload</a></div>
            </div>     
            <div style="padding-top:30px" class="panel-body">
                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                <form id="uploadForm" class="form-horizontal" role="form" enctype="multipart/form-data" action="<?= site_url() ?>/initial_upload/proses_upload" method="post" target="frame_upload_response">
                    <div style="margin-bottom: 25px" class="input-group">
                        <div style="display:none">
                            <input type="file" name="fileExcel[]" id="pilihFile" multiple="" onchange="pilihFileChange()"/>
                        </div>
                        <input type="button" onclick="chooseFile()" class="btn btn-default" value="Pilih File"/>
                    </div>
                    <div class="input-grup" style="display:none" id="divListFile">
                        <table class="table table-condensed" id="tableListFile">
                        </table>
                    </div>
                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-12 controls" style="border-bottom: 1px solid#888; padding-bottom:15px; font-size:85%">
                            <input type="button" onclick="submitForm()" class="btn btn-primary" value="Upload Excel" id="buttonSubmit" style="display:none"/>
                        </div>
                    </div>
                </form>     
            </div>
            <div class="panel-body">
                <iframe id="frame_upload_response" name="frame_upload_response" style="width: 100%; border: none"></iframe>
            </div>
        </div>  
    </div>

</div>
<style>
</style>
<?php
$this->load->view('page/footer');
?>
<script>
    function chooseFile() {
        $('#pilihFile').trigger('click');
    }
    function submitForm() {
        $('#uploadForm').submit();
    }
    function pilihFileChange() {
        $('#divListFile').hide('fast');
        console.log('input file berubah');
        var inputFile = document.getElementById('pilihFile');
        var files = inputFile.files;
        if (files.length > 0) {
            $('#tableListFile').html('');
            //console.log(files);
            for (var i = 0, n = files.length; i < n; i++) {
                var file = files[i];
                $('#tableListFile').append('<tr><td>' + file.name + '</td><td>Sheet <input type="text" name="sheetProcess[]" placeholder="1 atau 1,2,3" class="form-control" value="1" title="nomor sheet yang berisi data yang akan diproses"/></td></tr>');
            }
            $('#buttonSubmit').show('fast');
        }
        $('#divListFile').show('slow');
    }
    $(document).ready(function () {
        $('#pilihFile').on('change', function () {

        });
        $($('.has_sub')[2]).addClass('open');
    });
    function resetFormUpload1() {
        var inputButton = $('#pilihFile');
        var parent = inputButton.parent();
        var isi = parent.html();
        inputButton.remove();
        parent.append(isi);
        $('#tableListFile').parent().hide('fast');
        $('#buttonSubmit').hide('fast');
    }
</script>
