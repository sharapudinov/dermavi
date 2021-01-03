<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use App\Core\Logs\LoggerReader;

if (!(new LoggerReader())->canBeDownloaded(e($_POST['link']))) {
    echo 'Ошибка получения лога';
    exit();
}

$file = LoggerReader::getDirToLog(e($_POST['link']));

$fileExt = ($file && preg_match('/^.+\.csv$/i', $file)) ? 'csv' : 'txt';

header('Content-type: text/' . $fileExt);
header(
    'Content-Disposition: attachment;filename="'
    . strtolower(str_replace(' ', '_', e($_POST['name']))) . '.'. $fileExt .'"'
);

echo file_get_contents($file);
exit();
