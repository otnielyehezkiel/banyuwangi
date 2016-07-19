<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MyUploadLib {

    private $uploadedFiles = array();
    private $uploadError = array();
    private $uploadDirectory = 'upload/';

    public function getUploadedFiles() {
        return $this->uploadedFiles;
    }

    public function getErrors() {
        return $this->uploadError;
    }

    public function deleteUploadedFiles() {
        foreach ($this->uploadedFiles as $f){
            unlink($f['filePath']);
        }
    }

    private function processUploadDir($userDir = '') {
        if ($userDir == '') {
            $this->uploadDirectory = 'upload/' . date('Y') . '/' . date('m') . '/';
        } else {
            $l = strlen($userDir);
            if ($userDir[$l - 1] == '/')
                $this->uploadDirectory = 'upload/' . $userDir;
            else
                $this->uploadDirectory = 'upload/' . $userDir . '/';
        }
    }

    public function prosesUpload($fileName = 'myFile', $userUploadDirectory = '') {
        $this->processUploadDir($userUploadDirectory);
        if (isset($_FILES[$fileName])) {
            $fileUpload = $_FILES[$fileName];
            //print_r($fileUpload);
            $suksesSemua = true;
            /*
             * jika mengupload file secara multiple, maka file[name] akan berupa array
             * tapi jikan bukan, maka tipe upload nya adalah single
             */
            if (is_array($fileUpload['name'])) {
                $nbData = count($fileUpload['name']);
                for ($i = 0; $i < $nbData; $i++) {
                    if ($this->prosesUploadSingle(array(
                                'name' => $fileUpload['name'][$i],
                                'type' => $fileUpload['type'][$i],
                                'tmp_name' => $fileUpload['tmp_name'][$i],
                                'error' => $fileUpload['error'][$i],
                                'size' => $fileUpload['size'][$i]
                            )) == false) {
                        $suksesSemua = false;
                    }
                }
            } else {
                if ($this->prosesUploadSingle(array(
                            'name' => $fileUpload['name'],
                            'type' => $fileUpload['type'],
                            'tmp_name' => $fileUpload['tmp_name'],
                            'error' => $fileUpload['error'],
                            'size' => $fileUpload['size']
                        )) == false) {
                    $suksesSemua = false;
                }
            }
            return $suksesSemua;
        } else {
            return false;
        }
    }

    private function prosesUploadSingle($fileUpload) {
        switch ($fileUpload['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->uploadError[] = array_merge($fileUpload, array('errorName' => 'Tidak ada file yang dikirim'));
                return false;
                break;
            case UPLOAD_ERR_INI_SIZE:
                $this->uploadError[] = array_merge($fileUpload, array('errorName' => 'INI size error'));
                return false;
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->uploadError[] = array_merge($fileUpload, array('errorName' => 'Ukuran file melebihi batas'));
                return false;
                break;
            default :
                $this->uploadError[] = array_merge($fileUpload, array('errorName' => 'Error tidak dikenal '));
                return false;
                break;
        }
        //check apakah sudah ada atau belum tujuan tempat upload
        if (file_exists($this->uploadDirectory) == false) {
            //tujuan folder upload belum ada, buat folder upload
            mkdir($this->uploadDirectory, 0777, true);
        } else {
            //ada file dengan path seperti folder tujuan upload, perlu dicek apakah itu adalah folder apakah file,
            //jika file, maka dibuat folder baru, karena yg dibtuuhkan adalah folder
            if (is_file($this->uploadDirectory)) {
                mkdir($this->uploadDirectory, 0777, true);
            }
        }
        $newFileName = $this->uploadDirectory . $fileUpload['name'];
        $uploadedName = $fileUpload['name'];
        $counterFile = 0;
        //selama pada folder tujuan telah ada file dengan nama yg sama dengan file yang akan dipindah, 
        //maka file baru diganti namanya dengan ditambah angka pada depannya 
        //hingga nama file itu berbeda dari yang sudah ada
        while (file_exists($newFileName)) {
            $newFileName = $this->uploadDirectory . $counterFile . $fileUpload['name'];
            $counterFile++;
        }
        if (move_uploaded_file($fileUpload['tmp_name'], $newFileName)) {
            $this->uploadedFiles[] = array_merge($fileUpload, array('filePath' => $newFileName));
        } else {
            $this->uploadError[] = array_merge($fileUpload, array('errorName' => 'Gagal memindahkan file ke folder upload'));
            return false;
        }
        return true;
    }

}
