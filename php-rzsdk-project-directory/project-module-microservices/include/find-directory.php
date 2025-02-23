<?php
if(!function_exists("findNamedDirectory")) {
    function findNamedDirectory($startDir, $targetName) {
        $currentDir = $startDir;
        $retVal = array("realpath" => "", "relativepath" => "",);
        $relativepath = "";
    
        while(true) {
            // Check if the target directory exists in the current path
            if(is_dir($currentDir . DIRECTORY_SEPARATOR . $targetName)) {
                //return realpath($currentDir . DIRECTORY_SEPARATOR . $targetName);
                $retVal = array(
                    "realpath" => realpath($currentDir . DIRECTORY_SEPARATOR . $targetName),
                    "relativepath" => $relativepath,
                );
                return $retVal;
            } else {
                //---$relativepath .= "../";
            }
    
            $parentDir = dirname($currentDir);
            $relativepath .= "../";
    
            // Stop if we have reached the root directory
            if ($parentDir === $currentDir) {
                // Target directory not found
                //return false;
                return $retVal;
            }
    
            // Move up one directory
            $currentDir = $parentDir;
            //$relativepath .= "../";
        }
    }
}
?>
<?php
/* function findNamedDirectory($startDir, $targetName) {
    $currentDir = $startDir;
    $retVal = array("realpath" => "", "relativepath" => "",);
    $relativepath = "";

    while(true) {
        // Check if the target directory exists in the current path
        if(is_dir($currentDir . DIRECTORY_SEPARATOR . $targetName)) {
            //return realpath($currentDir . DIRECTORY_SEPARATOR . $targetName);
            $retVal = array(
                "realpath" => realpath($currentDir . DIRECTORY_SEPARATOR . $targetName),
                "relativepath" => $relativepath,
            );
            return $retVal;
        } else {
            //---$relativepath .= "../";
        }

        $parentDir = dirname($currentDir);
        //$relativepath .= "../";

        // Stop if we have reached the root directory
        if ($parentDir === $currentDir) {
            // Target directory not found
            return false;
        }

        // Move up one directory
        $currentDir = $parentDir;
        $relativepath .= "../";
    }
} */
?>
<?php

/* function findExistingDirectory($startDir) {
    $currentDir = $startDir;

    while(!is_dir($currentDir)) {
        $parentDir = dirname($currentDir);

        // Stop if we have reached the root directory
        if($parentDir === $currentDir) {
            // Directory not found
            return false;
        }

        // Move up one directory
        $currentDir = $parentDir;
    }

    // Found directory
    return $currentDir;
}

// Example usage
$startDir = __DIR__ . "/global-library/nonexistent/directory";
$result = findExistingDirectory($startDir);

if($result) {
    echo "Directory found at: $result\n";
} else {
    echo "No directory exists in the hierarchy.\n";
} */
?>