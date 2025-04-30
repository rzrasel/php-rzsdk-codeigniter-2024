<?php
class FileContentWriter {
    public static function aggregateFiles(array $allFiles, string $baseDir = ''): string {
        $fileContents = [];
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR);

        foreach ($allFiles as $filePath) {
            $fullPath = !empty($baseDir) ? $baseDir . DIRECTORY_SEPARATOR . $filePath : $filePath;

            if (file_exists($fullPath)) {
                $content = file_get_contents($fullPath);
                $fileContents[] = [
                    'file' => str_replace("\\", "/", $filePath),
                    'content' => $content
                ];
            } else {
                $fileContents[] = [
                    'file' => $filePath,
                    'content' => 'File not found.'
                ];
            }
        }

        $finalOutput = "";
        foreach ($fileContents as $fileData) {
            $finalOutput .= "//----- File: " . $fileData['file'] . " -----\n\n";
            $finalOutput .= $fileData['content'] . "\n\n";
        }

        return $finalOutput;
    }

    public static function saveToFile(string $outputFile, array $allFiles, string $baseDir = ''): bool {
        $data = self::aggregateFiles($allFiles, $baseDir);
        return file_put_contents($outputFile, $data) !== false;
    }
}
?>