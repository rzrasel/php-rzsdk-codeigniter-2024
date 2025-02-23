<?php
namespace RzSDK\Include\Import;
?>
<?php
class FindWorkingDirectory {
    public static function findTopLevelDirectory($dirPath, $targetName, $relevant = "../") {
        $currentDir = realpath($dirPath);
        $topLevelDir = array("realpath" => "", "relativepath" => "");

        // Relative path accumulator
        $relativePath = "";

        while ($currentDir !== false) {
            // Check if the target directory exists in the current path
            if (is_dir($currentDir . DIRECTORY_SEPARATOR . $targetName)) {
                // Update the top-level directory found
                $tempTargetName = trim(trim($targetName, "\\"), "/");
                $tempRelativePath = trim(trim($relativePath, "\\"), "/");
                $topLevelDir = array(
                    "realpath" => realpath($currentDir . DIRECTORY_SEPARATOR . $tempTargetName),
                    "relativepath" => $tempRelativePath . "/" . $tempTargetName,
                );
            }

            // Move up one directory
            $parentDir = dirname($currentDir);
            $relativePath .= $relevant;

            // Stop if we have reached the root directory
            if ($parentDir === $currentDir) {
                break;
            }

            $currentDir = $parentDir;
        }

        return !empty($topLevelDir["realpath"]) ? $topLevelDir : false;
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