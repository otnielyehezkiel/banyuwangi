<?php
$this->load->view('page/header');
?>
<div class="container">
    <div style="margin-top:10px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading" style="height: 50px; padding: 0px" >
                <div class="panel-title col-md-6 text-center" style="height: 100%; margin: 0px; background-color: white; padding-top: 12px">Upload Excel</div>
                <div class="panel-title col-md-6 text-center" style="height: 100%; margin: 0px; padding-top: 12px"><a href="<?= site_url()?>/upload_excel/hasilupload">Hasil Upload</a></div>
            </div>     
            <div style="padding-top:30px" class="panel-body">
                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                <form id="uploadForm" class="form-horizontal" role="form" enctype="multipart/form-data" action="<?= site_url() ?>/upload_excel/hasilupload" method="post" >
                <input type="hidden" name="namajenis" id="namajenis" value=""/>
                <input type="hidden" name="table" id="table" value="konsumsi"/>
                    <div style="margin-bottom: 25px" class="input-group">
                        <select name="jenisdata" class="form-control" onchange="changelinkname()" id="jenisdata">
                                <option value="1">Konsumsi Padi Sawah</option>
                                <option value="2">Konsumsi Padi Ladang</option>
                                <option value="3">Konsumsi Jagung</option>
                                <option value="4">Konsumsi Kedelai</option>
                                <option value="5">Konsumsi Kacang Tanah</option>
                                <option value="6">Konsumsi Kacang Hijau</option>
                                <option value="7">Konsumsi Ubi Kayu</option>
                                <option value="8">Konsumsi Ubi Jalar</option>
                            </select>                        
                    </div>

                    
                    <div style="margin-bottom: 25px" class="input-group">
                        <div style="display:none">
                            
                            <input type="file" name="fileExcel[]" id="pilihFile" multiple="" onchange="pilihFileChange()"/>
                        </div>
                        <input type="button" onclick="chooseFile()" class="btn btn-default" value="Pilih File"/>
                    </div>
                    
                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-12 controls" style="border-bottom: 1px solid#888; padding-bottom:15px; font-size:85%">
                            <input type="button" onclick="submitForm()" class="btn btn-primary" value="Upload Excel" id="buttonSubmit" style="display:none"/>
                        </div>
                    </div>
                </form>     
                <a href='javascript:void(gantilink())'  id="linkdownload">Download Template Excel</a>
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
    var txt = 'Konsumsi Padi Sawah';
    document.getElementById('namajenis').value = txt;
    
    function changelinkname()
    {
        txt= $("#jenisdata option:selected").text();
        document.getElementById("linkdownload").innerHTML="Download Template "+txt;
        document.getElementById('namajenis').value = txt;
    }

    function gantilink() {
        var id=document.getElementById("jenisdata").value;
        switch (id)
        {
            case '1':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/beras_padi_sawah.xls";
                break;
            case '2':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/beras_padi_ladang.xls";
                break;
            case '3':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/jagung.xls";
                break;
            case '4':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/kedelai.xls";
                break;
            case '5':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/kacang_tanah.xls";
                break;
            case '6':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/kacang_hijau.xls";
                break;  
            case '7':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/ubi_kayu.xls";
                break;
            case '8':
                window.location = "<?php echo base_url();?>/formatexcel/ketersediaan/ubi_jalar.xls";
                break;
           
        };

    }
</script>
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
            for (var i = 0, n = files.length; i < n; i++) {
                var file = files[i];
            }
            $('#buttonSubmit').show('fast');
        }
        $('#divListFile').show('slow');
    }

    $(document).ready(function () {
        $('#pilihFile').on('change', function () {

        });
        $($('.has_sub')[0]).addClass('open');
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
