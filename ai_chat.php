<?php
header('Content-Type: application/json; charset=utf-8');

$OPENAI_KEY = "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";

$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";
if(!$userMessage){
  echo json_encode(["ok"=>false,"error"=>"missing_message"]);
  exit;
}

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "Authorization: Bearer $OPENAI_KEY"
]);
$data = [
  "model" => "gpt-4o-mini",
  "messages" => [
    ["role"=>"system","content"=>"You are a helpful Persian-speaking legal assistant."],
    ["role"=>"user","content"=>$userMessage]
  ],
  "temperature" => 0.7,
  "max_tokens" => 600
];
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
$response = curl_exec($ch);
if($response===false){
  echo json_encode(["ok"=>false,"error"=>curl_error($ch)]);
  exit;
}
$out = json_decode($response, true);
$reply = $out["choices"][0]["message"]["content"] ?? "";
echo json_encode(["ok"=>true, "reply"=>$reply], JSON_UNESCAPED_UNICODE);
