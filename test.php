<?php
$ItemBank = json_encode('[
 {
  "id" : 1,
  "question":"Globalと反対の意味をもつ語句",
  "choices":[
	"minded","unusual","unpredictable","headed","local"
  ],
  "correct":5, "a":1, "b":0
 },{
  "id" : 2,
  "question":"Localと反対の意味をもつ語句",
  "choices":[
	"signal","unusual","global","unpredictable","hot"
  ],
  "correct":3, "a":1, "b":0
 }
]',true);

var_dump($ItemBank);