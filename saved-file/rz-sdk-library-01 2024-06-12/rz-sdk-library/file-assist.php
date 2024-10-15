<?php
namespace RzSDK\File;
?>
<?php
class FileAssist {
    public function write($filePath, $fileData) {
        $filePointer = fopen($filePath, "w");
        fwrite($filePointer, $fileData);
        fclose($filePointer);
    }

    public function read($filePath) {
        return file_get_contents($filePath);
    }
}
?>