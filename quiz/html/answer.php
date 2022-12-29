<?php
//function.phpを読みこむ
require __DIR__ . '/../lib/functions.php';

//送られてきたidを取得
$id = $_POST['id'] ?? '';
//送られてきた答えを取得
$selectedAnswer = $_POST['selectedAnswer'] ?? '';

//idから問題文等のデータを取得
$data = fetchById($id);

//データが使えなかった場合、jsonでエラー表示
if (!$data) {
    header('HTTP/1.1 404 NOt Found');

    header('Content-Type:text application/json;charset=UTF-8');

    $response = [
        'messege' => 'The specified id could not be found',
    ];

    echo json_encode($response);

    exit(0);
}

//データをフォーマットに直す
$formattedData = genarateFormattedData($data);

//正しい答えと解説を取得
$correctAnswer = $formattedData['correctAnswer'];
$explanation = $formattedData['explanation'];

//送られてきたものと正解を比較
$result = $selectedAnswer === $correctAnswer;

//正答を取得
$response = [
    'result' => $result,
    'correctAnswer' => $correctAnswer,
    'explanation' => $explanation,
];

//jsonでエンコード
echo json_encode($response);