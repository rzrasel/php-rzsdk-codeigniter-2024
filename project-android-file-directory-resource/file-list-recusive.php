<?php
require_once("include.php");
?>
<?php
use RzSDK\Directory\File\Scan\DirectoryFileScan;
use RzSDK\Libs\File\Rename\Helper\FileRenameHelper;
?>
<?php
$directory = "resource";
define("ROOT", dirname(__FILE__) . "/{$directory}");
$fileName = "";
$tree = DirectoryFileScan::dirToArray(ROOT,$fileName);
?>
<?php
echo "<pre>" . print_r($tree,1)  . "</pre>";
?>
<?php
$fileList = array();
for($i = 1; $i < 22; $i++) {
    $fileList[] = "alpha_en_consonant_{$i}_type_1.mp3";
}
//$fileList = $tree["rename"];
echo "<pre>" . print_r($fileList,1)  . "</pre>";
$directory = "{$directory}/rename";
$fileRenameHelper = new FileRenameHelper();
//$fileRenameHelper->renameTopLevel($fileList, "letter_en_consonant_", "00", $directory);
?>
<?php
class ResourceFile {
    public $brief = "brief";
    public $drawable = "drawable";
    public $raw = "raw";
}
?>
<?php
enum ResourceType: string {
    case BRIEF = "brief";
    case DRAWABLE = "drawable";
    case DRAWABLE_1 = "drawable-1";
    case RAW  = "raw";
    case EMPTY  = "empty";
    public static function getByValue($value): self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return self::EMPTY;
    }

    public function getDescription(): string {
        return match($this) {
            self::BRIEF         => "drawable",
            self::DRAWABLE      => "drawable",
            self::DRAWABLE_1    => "drawable",
            self::RAW           => "raw",
        };
    }

    public static function getAliases(string $status): array {
        return match ($status) {
            self::BRIEF->value      => ["started", "available"],
            self::DRAWABLE->value   => ["finished", "completed"],
            default                 => [],
        };
    }
}
// Example usage
//print_r(ResourceType::getAliases('open'));
// Output: ['started', 'available']
?>
<?php
function dirFileListToArray($dirList): array {
    $dirFileList = array();
    foreach($dirList as $keyOne => $valueOne) {
        if(is_array($valueOne)) {
            //$fileList = new FileList();
            foreach($valueOne as $keyTwo => $valueTwo) {
                $fileList = new ResourceFile();
                $fileList->brief = null;
                $fileList->drawable = null;
                $fileList->raw = null;
                if(!array_key_exists($keyTwo, $dirFileList)) {
                    $dirFileList[$keyTwo] = $fileList;
                }
                if(!is_string($keyTwo)) {
                    $resourceType = ResourceType::getByValue($keyOne);
                    if($resourceType == ResourceType::DRAWABLE) {
                        //$fileList->drawable = $valueTwo;
                        $dirFileList[$keyTwo]->drawable = $valueTwo;
                        /* echo $resourceType->getDescription();
                        echo "<br />"; */
                    } else if($resourceType == ResourceType::DRAWABLE_1) {
                        //$fileList->drawable = $valueTwo;
                        $dirFileList[$keyTwo]->drawable = $valueTwo;
                        /* echo $resourceType->getDescription();
                        echo "<br />"; */
                    } else if($resourceType == ResourceType::RAW) {
                        //$fileList->raw = $valueTwo;
                        $dirFileList[$keyTwo]->raw = $valueTwo;
                    }
                }
                //$dirFileList[] = $fileList;
            }
            //$dirFileList[] = $fileList;
        }
    }
    //echo "<pre>" . print_r($dirFileList,1)  . "</pre>";
    return $dirFileList;
}
dirFileListToArray($tree);
?>
<?php
/* $directory = "scan";
define("ROOT", dirname(__FILE__) . "/{$directory}");
$file_name = 'template.html';
$tree = dirToArray(ROOT,$file_name);
echo "<pre>".print_r($tree,1)."</pre>";
//echo json_encode($tree);
$filePreFix = "ad-";
$preZeros = "00";
foreach($tree as $key => $value) {
    if(!is_string($key)) {
        $count = $key + 1;
        $oldFile = $directory . "/" . $value;
        //
        $pathinfo = pathinfo($oldFile);
        $extension = $pathinfo["extension"];
        //
        $strLen = strlen($count);
        $strPreZeros = substr($preZeros, $strLen);
        $fileZero = "{$strPreZeros}{$count}";
        //
        $newFile = $directory . "/{$filePreFix}{$fileZero}.{$extension}";
        /-* echo "newFile: - {$newFile}";
        echo "<br />"; *-/
        //
    }
}
echo "End"; */
?>
<?php
//Get data from array in recursion and make ul li list in php

$data = [
    [
        'id' => 1,
        'name' => 'Electronics',
        'children' => [
            [
                'id' => 2,
                'name' => 'Phones',
                'children' => [
                    ['id' => 4, 'name' => 'Accessories', 'children' => []],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Laptops',
                'children' => [],
            ],
        ],
    ],
    [
        'id' => 5,
        'name' => 'Furniture',
        'children' => [
            ['id' => 6, 'name' => 'Chairs', 'children' => []],
        ],
    ],
];

function generateList($items) {
    if (empty($items)) {
        return '';
    }

    $html = '<ul>';
    foreach ($items as $item) {
        $html .= '<li>' . htmlspecialchars($item['name']);

        // Recursively generate the list for children
        if (!empty($item['children'])) {
            $html .= generateList($item['children']);
        }

        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}

// Generate the HTML list
$htmlList = generateList($data);

// Output the list
echo $htmlList;
?>
<?php
// Get data from array in recursion and generate ul li list in callback function in php

$data = [
    [
        'id' => 1,
        'name' => 'Electronics',
        'children' => [
            [
                'id' => 2,
                'name' => 'Phones',
                'children' => [
                    ['id' => 4, 'name' => 'Accessories', 'children' => []],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Laptops',
                'children' => [],
            ],
        ],
    ],
    [
        'id' => 5,
        'name' => 'Furniture',
        'children' => [
            ['id' => 6, 'name' => 'Chairs', 'children' => []],
        ],
    ],
];

function generateListWithCallback($items, $callback) {
    if (empty($items)) {
        return '';
    }

    $html = '<ul>';
    foreach ($items as $item) {
        // Use the callback to generate content for each <li>
        $html .= '<li>' . $callback($item);

        // Recursively generate the list for children
        if (!empty($item['children'])) {
            $html .= generateListWithCallback($item['children'], $callback);
        }

        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}

// Define a callback function
$callback = function($item) {
    return htmlspecialchars($item['name']); // Customize this as needed
};

// Generate the HTML list
$htmlList = generateListWithCallback($data, $callback);

// Output the list
echo $htmlList;

$callback = function($item) {
    return '<span data-id="' . htmlspecialchars($item['id']) . '">' . htmlspecialchars($item['name']) . '</span>';
};
?>
<?php
// Callback class function in php

class ListRenderer {
    public function generateList($items) {
        return $this->buildList($items, [$this, 'renderItem']);
    }

    private function buildList($items, $callback) {
        if (empty($items)) {
            return '';
        }

        $html = '<ul>';
        foreach ($items as $item) {
            $html .= '<li>' . call_user_func($callback, $item);
            if (!empty($item['children'])) {
                $html .= $this->buildList($item['children'], $callback);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function renderItem($item) {
        return htmlspecialchars($item['name']);
    }
}

$data = [
    [
        'name' => 'Electronics',
        'children' => [
            ['name' => 'Phones', 'children' => []],
            ['name' => 'Laptops', 'children' => []],
        ],
    ],
    ['name' => 'Furniture', 'children' => []],
];

$renderer = new ListRenderer();
echo $renderer->generateList($data);

/* $callback = function($item) use ($renderer) {
    return $renderer->renderItem($item);
}; */
?>