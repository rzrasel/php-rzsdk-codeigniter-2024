<?php
namespace App\Microservice\Utils\Directory\File\Rename;
?>
<?php
class DirectoryFileRename {
    public static function traverseFileRename($basePath, $files, $prefixName = '', $extensions = []) {
        //echo "<pre>" . print_r($files, true) . "</pre>";
        // Normalize extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        // If prefixName empty, generate once and use for all files
        if (empty($prefixName)) {
            $prefixName = self::getRandomString(8);
        }

        foreach ($files as $file) {
            $dateSuffix = date('Y-m-d-His') . "-" . rand(1000, 9999);
            $fullPath = $file;
            if(!empty($basePath)) {
                $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            }
            /*echo "<br />File Name<br />";
            echo $fullPath;
            echo "<br />";*/
            if (!file_exists($fullPath)) {
                echo "File does not exist: $fullPath\n";
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!empty($extensions) && !in_array($ext, $extensions)) {
                // Skip file if extension filtering is active and this file's ext is not included
                continue;
            }

            $dirname = pathinfo($fullPath, PATHINFO_DIRNAME);
            $filenameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
            $filenameWithoutExt = "";

            // Construct new filename: prefix + original filename + date + extension
            $newFilename = $prefixName . '-' . $filenameWithoutExt . $dateSuffix . '.' . $ext;
            $newFullPath = $dirname . DIRECTORY_SEPARATOR . $newFilename;

            if (rename($fullPath, $newFullPath)) {
                //echo "Renamed '$fullPath' => '$newFullPath'\n";
            } else {
                //echo "Failed to rename '$fullPath'\n";
            }
            usleep(500);
        }
    }

    public static function getRandomString($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
?>