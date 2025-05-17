<?php
$input = [
    [
        'label' => 'root',
        'count' => 3,
        'files' => [
            'Jtlps4MZ-2025-05-17-193830-3521.png',
        ],
    ],
    [
        'label' => 'profile-image',
        'count' => 18,
        'files' => [
            'profile-image/496923263_122101484240867325_8319934615767361976_n.jpg',
        ],
    ],
    [
        'label' => 'user-image',
        'count' => 9,
        'files' => [
            'user-image/Jtlps4MZ-2025-05-17-193830-2138.png',
        ],
    ],
];
?>

<?php
class DirectoryFileGallery {
    public static function traverseAndGenerateHtmlGallery($basePath, $files, $extensions = []) {
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }
    }
}
?>