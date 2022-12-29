//　選択肢をすべて取得
const answersList = document.querySelectorAll('ol.answers li');
//選択肢を並べて、クリックされたらイベントを起こす
answersList.forEach(li => li.addEventListener('click', checkClickedAnswer));


//選択肢がクリックされたときにおこること
function checkClickedAnswer(event) {
    //クリックされたものを取得
    const clickedAnswerElement = event.currentTarget;
    //データ属性がanswerを取得
    const selectedAnswer = clickedAnswerElement.dataset.answer;
    //問題文のid取得
    const questionId = clickedAnswerElement.closest('ol.answers').dataset.id;

    //送られてくるデータの入れ物を作る
    const formData = new FormData();

    //送信したい値を追加
    formData.append('id', questionId);
    formData.append('selectedAnswer', selectedAnswer);

    //リクエストを送る
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'answer.php');
    //データを送る
    xhr.send(formData);
    //リクエストが完了したときにイベント発生
    xhr.addEventListener('loadend', function (event) {
        /**@type{XMLHttpRequest}**/
        const xhr = event.currentTarget;

        //リクエストが成功したかステータスコードで確認
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.response);
            //答えが正しいか判定
            const result = response.result;
            const correctAnswer = response.correctAnswer;
            const explanation = response.explanation;

            displayResult(result, correctAnswer, explanation);

        } else {
            alert('Error:回答データの取得に失敗しました');
        }
    });


}

function displayResult(result, correctAnswer, explanation) {
    let message;
    let answerColorCode;

    if (result) {
        message = '正解です！';
        answerColorCode = 'blue';
    } else {
        message = 'ざんねん！不正解です';
        answerColorCode = 'red';
    }


    alert(message);
    //正解の内容をHTMLに組み込む
    document.querySelector('span#correct-answer').innerHTML = correctAnswer;
    document.querySelector('span#explanation').innerHTML = explanation;

    document.querySelector('span#correct-answer').style.color = answerColorCode;
    //答えを表示
    document.querySelector('div#section-correct-answer').style.display = 'block';
}
