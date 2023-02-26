<?php
session_start();

if(isset($_POST["change"])){
    
    if(!empty($_POST["before"])){
        
        /* $before = htmlspecialchars($_POST["before"],ENT_QUOTES); */ //タグが認識されなかった。
        $before = $_POST["before"];
        $beforeString = (string)$before; //文字列に変換

        $target = array('&','<', '>',' ',); //変換したい変換前の文字列を配列に入れる。
        $replace = array('&amp;','&amp;lt;', '&amp;gt;','&amp;nbsp;'); //変換したい変換後の文字列を配列に入れる。
        $escape = str_replace($target, $replace, $beforeString); //変換処理が複数あるので、引数には変数（配列）を設定。
        $escapeN2 = nl2br($escape); //nl2brは改行をbrタグで出力してくれる関数。

        $_SESSION['success'] = $escapeN2; //下のheader関数でデータが消えるの防ぐためにsessionを使う
        
    }
    header("Location:./"); //リダイレクト時のPOSTの2重送信を防ぐ
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Chat HTML escape</title>
</head>
<body>
  <div class="container">
    <h1 class="escapeh1">HTMLエスケープ処理変換</h1>
    <form action="" method="post">
        <textarea name="before" placeholder="ここにHTMLを入力"></textarea>
        <!-- <input class="arrow" type="submit" name="change" value="Escape!"> -->
 
           <button class="arrow" type="submit" name="change">Escape!</button>

        <textarea id="foo" value="https://github.com/zenorocha/clipboard.js.git" name="after" placeholder="エスケープ処理後(コピーして使う)">
<?php //formにスペースができるためphpタグのインデントを消す。
           if(!empty($_SESSION['success'])){
               echo  $_SESSION['success'];

           //sessionの中身を削除
           unset($_SESSION['success']);
           }
?>
        </textarea>
<?php ?>
        <div class="auth-submit-button">
            <button type="button" class="button" data-clipboard-target="#foo">Copy</button>
        </div>
    </form>
  </div>   
    <script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js">//clipboard.jsのCDN</script>
    <script>
        const clipboard = new ClipboardJS('.button'); //clipboard.jsを使うので、インスタンス化
        const btn = document.getElementsByClassName('button');

        //ページの表示時、リロード時にフォームを空にする。
        window.addEventListener('pageshow',()=>{ 
            if(window.performance.navigation.type==1) 
            document.getElementById('foo').value ='';;
            });

        //マウスが外れた時にとtooltip（copied!の吹き出し）を消す
        btn[0].addEventListener('mouseleave',clearTooltip);

        //イベントが起きた要素に、buttonというクラスつける。（クラス名からtooltipをとる）
        function clearTooltip(e){
            e.currentTarget.setAttribute('class','button');
        }
        //要素に、button tooltipというクラスつける。（buttonのクラス名にtooltipをつける）
        function showTooltip(elem){
            elem.setAttribute('class','button tooltip');
        }
        //clipboardが発火成功したら、関数showTooltipを実行し、フォームの値を空にする
        clipboard.on('success', function(e) {
            showTooltip(e.trigger);
            document.getElementById('foo').value ='';
        });

        
    </script>
</html>