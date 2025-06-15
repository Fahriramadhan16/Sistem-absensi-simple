<?php
$directory = 'uploads/';
$files = array_diff(scandir($directory), array('.', '..'));

$photos = [];

foreach ($files as $file) {
    $filePath = $directory . $file;

    if (is_file($filePath)) {
        $photos[] = [
            'name' => $file,
            'time' => date("Y-m-d H:i:s", filemtime($filePath))
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($photos);
?>
