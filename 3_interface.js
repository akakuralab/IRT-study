function KaitouSeisei(item){
　
 document.getElementById("question").innerHTML=item["question"];
 var choices = item["choices"]; //選択肢↓id=choicesのHTML要素を取得
 var choice_area = document.getElementById("choices");
 
 for(var c=0;c<choices.length;c++){ 
  choice_area.appendChild(Sentakushi(c, choices[c]));;
 }
}

function Sentakushi(c,choice){
  var input = document.createElement("input");
  input.type = "radio";
  input.name = "choices";
  input.id = "choice"+(c+1);
  input.value = c+1; /*↓*/
  input.classList.add("custom-control-input");
  input.required = true; /**/

  var label = document.createElement("label");
  label.setAttribute("for", "choice"+(c+1));
  label.innerHTML = choice; /*↓*/
  label.classList.add("custom-control-label");

  var div = document.createElement("div");
  div.classList.add("custom-control"); /**/
  div.classList.add("custom-radio"); /**/
  div.appendChild(input);
  div.appendChild(label);
  return div;
}