<?php

// Check if data is received using POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // XSS clean the received data
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $htmlContent = isset($_POST['htmlCode']) ? $_POST['htmlCode'] : '';
    $cssContent = isset($_POST['cssCode']) ? clean_input($_POST['cssCode']) : '';
    $jsContent = isset($_POST['jsCode']) ? clean_input($_POST['jsCode']) : '';

    // Create the \generated\ directory if it doesn't exist and is writable
    $directory = __DIR__ . '/generated/';
    if (!is_dir($directory) && is_writable(dirname($directory))) {
        mkdir($directory, 0755, true);
    }

    // Save code to files in the \generated\ directory
    file_put_contents($directory . 'index.html', $htmlContent);
    file_put_contents($directory . 'style.css', $cssContent);
    file_put_contents($directory . 'app.js', $jsContent);

    // Generate a zip file with the name "app-builder-YYYY-MM-DD.zip"
    $zipFileName = 'app-builder-' . date('Y-m-d') . '.zip';
    $zipFilePath = $directory . $zipFileName;

    $zip = new ZipArchive();
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $zip->addFile($directory . 'index.html', 'index.html');
        $zip->addFile($directory . 'style.css', 'style.css');
        $zip->addFile($directory . 'app.js', 'app.js');
        $zip->close();
        echo json_encode(['zipFilePath' => $zipFilePath]);
    } else {
        echo json_encode(['error' => 'Failed to create zip file']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
