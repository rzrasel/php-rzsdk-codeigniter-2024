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
                $topLevelDir = array(
                    "realpath" => realpath($currentDir . DIRECTORY_SEPARATOR . $targetName),
                    "relativepath" => $relativePath,
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