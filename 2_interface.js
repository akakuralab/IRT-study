function KaitouSeisei(item){
  console.log(item);
 document.getElementById("question").innerHTML=item["question"];
 var choices = item["choices"]; //選択肢↓id=choicesのHTML要素を取得
 var choice_area = document.getElementById("choices");
 
 for(var c=0;c<choices.length;c++){ 
  choice_area.appendChild(Sentakushi(c, choices[c]));;
 }
}

function Sentakushi(c,choice){
  var input = document.createElement("input");
  input.type = "radio"; //ラジオボタン要素
  input.name = "choices"; //$_POST["choices"]に対応
  input.value = c+1; //正誤判定の際に用いる値

  var label = document.createElement("label");
  label.innerHTML = choice; //選択肢文要素

  var div = document.createElement("div");
  div.appendChild(input); //選択肢エリアにラジオボタン追加
  div.appendChild(label); //選択肢文を追加
  return div;
}