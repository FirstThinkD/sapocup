<?php
$dir = __DIR__;
echo "dir=[". $dir. "]";

$zip = new ZipArchive;

// $zip->open(__DIR__.'/pdf/sample.zip', ZipArchive::CREATE|ZipArchive::OVERWRITE);
// $zip->addFile(__DIR__.'/pdf/20200224175213/20200224175213.pdf');
// $zip->addFile(__DIR__.'/pdf/20200224175213/20200224175214.pdf');
// $zip->close();

$fileName = 'myfile.zip';
$dir = '/usr/home/haw1008ufet9/html/manage/estimates/new/pdf';
$command =  'cd ' . $dir . ';' . 'zip -r '. $fileName . ' ./20200224175213/';
exec($command);
$zipPath = $dir . '/' . $fileName;
header( "Content-Type: application/zip" );
header( "Content-Disposition: attachment; filename=" . basename( $zipPath ) );
header( "Content-Length: " . filesize( $zipPath ) );
ob_clean();
flush();
?>
