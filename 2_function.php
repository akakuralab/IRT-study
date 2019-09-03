<?php
session_start();
function main(){ //メイン関数
 if(isset($_SESSION["IR"]) && isset($_POST["choices"]))
  $item = Keizoku($_POST["choices"]);//IRセッションがあれば試験中
 else $item = Kaishi(); //IRセッションがなければ試験開始
 if($item===false||$_SESSION["IR"]["N"]>3) Owari();
 return $item; //項目の連想配列を返却
}
function Kaishi(){ //テスト開始時の処理
 $_SESSION["IR"]=array("N"=>0,"I"=>[0],"X"=>[],"T"=>0);
 return Questions(0); //問題を出力
}
function Keizoku($choice){ //テスト中の処理
 $i=$_SESSION["IR"]["I"][$_SESSION["IR"]["N"]];
 $item = Questions($i); //問題を取り出す
 $_SESSION["IR"]["X"][]=($choice==$item["correct"])?1:0;
 $_SESSION["IR"]["N"]++; //解答数を繰り上げ ↑正誤判定
 $_SESSION["IR"]["T"]=array_sum($_SESSION["IR"]["X"])/$_SESSION["IR"]["N"];
 $item = Questions($i+1); //次の問題を取り出す
 $_SESSION["IR"]["I"][]=$item["id"]; //問題の履歴
 return $item; //問題IDを出力
}
function Owari(){ //テスト終了時の処理
 echo "<H1>試験は終了です．</H1>";
 echo "あなたの能力値は".$_SESSION["IR"]["T"]."です";
 unset($_SESSION["IR"]); //セッション変数を消去
 exit; //以降の処理を実行しない
}
function Questions($i){ //項目の取り出し
 $ItemBank = json_decode(file_get_contents("ItemBank.json"),true);
 if(isset($ItemBank[$i])) return $ItemBank[$i];
 else return false; //項目がない場合はfalseを返却
}

//var_dump(main()); // 演習で書いたコードはコメントアウトをする