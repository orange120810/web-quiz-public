<?php

function loadTemplate($filename,array $assignData = [])
{
    extract($assignData);
    include __DIR__ . '/../template/'.$filename.'.tpl.php';
}
function error404()
{
    header('HTTP/1.1 404 NOt Found');

    header('Content-Type:text/html;charset=UTF-8');

    loadTemplate('404');

    exit(0);
}

function fetchAll()
{
    //ファイルを開く
    $handler = fopen(__DIR__ . '/data.csv', 'r');

    //データを取得
    $questions = [];
    while ($row = fgetcsv($handler)) {
        if (isDataRow($row)) {
             $questions[] = $row;
        }
    }


    //ファイルを閉じる
    fclose($handler);

    //データを返す
    return $questions;
}
;

function fetchById($id)
{
    //ファイルを開く
    $handler = fopen(__DIR__ . '/data.csv', 'r');

    //データを取得
    $question = [];



    while ($row = fgetcsv($handler)) {
        if (isDataRow($row)) {
            if ($row[0] === $id) {
                $question = $row;
                break;
            }
        }
    }


    //ファイルを閉じる
    fclose($handler);

    //データを返す
    return $question;
}
;

//取ってきたデータが使えるか判別
function isDataRow(array $row)
{
    if (count($row) !== 8) {
        return false;
    }

    foreach ($row as $value) {
        if (empty($value)) {
            return false;
        }
    }

    if (!is_numeric($row[0])) {
        return false;
    }

    return true;
}

function genarateFormattedData($data)
{

    $formattedData = [
        'id' => escape($data[0]),
        'question' => escape($data[1], true),
        'answers' => [
            'A' => escape($data[2]),
            'B' => escape($data[3]),
            'C' => escape($data[4]),
            'D' => escape($data[5]),
        ],
        'correctAnswer' => escape(($data[6])),
        'explanation' => escape($data[7], true),
    ];

    return $formattedData;
}

//改行をする
function escape($data, $nl2br = false)
{
    $convertedData = htmlspecialchars($data, ENT_HTML5);

    if ($nl2br) {
        return nl2br($convertedData);
    }
    return $convertedData;
}