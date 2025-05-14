<?php
namespace RzSDK\Include\Import;
?>
<?php
class FindWorkingDirectory {
    public static function findTopLevelDirectory($dirPath, $targetName, $relevant = "../") {
        // Normalize the starting directory path
        $currentDir = realpath($dirPath);
        if ($currentDir === false) {
            return false;
        }

        // Initialize result array
        $topLevelDir = [
            "realpath" => "",
            "relativepath" => ""
        ];

        // Sanitize the target directory name
        $targetName = trim($targetName, "\\/");
        if (empty($targetName)) {
            return false;
        }

        // Initialize relative path accumulator
        $relativePath = "";

        while (true) {
            // Check if target directory exists in current path
            $potentialPath = $currentDir . DIRECTORY_SEPARATOR . $targetName;
            if (is_dir($potentialPath)) {
                $topLevelDir = [
                    "realpath" => realpath($potentialPath),
                    "relativepath" => ltrim($relativePath . $targetName, "/")
                ];
                return $topLevelDir;
            }

            // Move up one directory level
            $parentDir = dirname($currentDir);
            $relativePath .= $relevant;

            // Check if we've reached the filesystem root
            if ($parentDir === $currentDir) {
                break;
            }

            $currentDir = $parentDir;
        }

        return false;
    }

    public static function findBaseUrl($url, $target) {
        // Normalize URL slashes
        $url = str_replace("\\", "/", $url);

        // Loop through directories
        while ($url !== "/" && $url !== "" && $url !== "http:/") {
            if(basename($url) === $target) {
                return $url;
            }

            // Move up one directory
            $url = dirname($url);
            if(empty($url) || $url === "." || $url === "/") {
                return false;
            }
        }
        return false;
    }
}
?>
<?php
/*// Example Usage
$startPath = __DIR__; // Starting directory
$targetFolder = "global-library"; // Directory name to search
$relativePath = "./";
$result = FindWorkingDirectory::findTopLevelDirectory($startPath, $targetFolder, $relativePath);

if($result) {
    echo "Top-Level Directory Found:\n";
    echo "Real Path: " . $result["realpath"] . "\n";
    echo "Relative Path: " . $result["relativepath"] . "\n";
} else {
    echo "Directory not found.\n";
}*/
?>