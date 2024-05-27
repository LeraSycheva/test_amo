<?php
$subdomain = 'lerasycheva01';
$link = 'https://lerasycheva01.amocrm.ru/oauth2/access_token';

$data = [
    'client_id' => 'c81c3946-afbb-47ad-976b-176dcdcc8108',
    'client_secret' => 'xUQKuxDWw6bS4AgCdxoWDkClTuiZ3s1pocB9xLcGgIULXANz6B2M9v37RwEzF5Cd',
    'grant_type' => 'authorization_code',
    'code' => 'def50200a8d29daae6b1f7236d54a1f3313e0940f847f07e509a05a012f1e77ee178d03b85cb307fcb5ea3665a501619a16d3d41aeda0e472a25a5d4c211807bd619ded86ca0cccd5565c9dc9b2673ffc895f858f8915637129df326d29f84d75fefd6db94e43f15036d96f9e2e7e5b846224f5e6f6d7dc9c786eb89e66300930ec65d0002561b8d797274ae3522641ef9ed5070756c88a1e83f2cd079ae132de01c49cf276feb0862495f0bf7e72cae64537775f9f54089dbd6f0f97f37fe7e5178563d78580bbf4f136e7916fc54dc1312eb68712bc4522241e38151918bc9a84ce0de6735a050122b34448107732a3b619294149f15b608a0f7062a366d268a3a0e02915d921eee7de941e855dc2f4676337187a1008e2ae8791c7f063a67e478fab6f79f1d338b008b6fd7a8e7c49a1dc01edc435f5a2466d19f325421dc94f349b33a8c3cb92bcd9f9d969a575215150ffb1e2b985e1c5cc4acdecbce3f42c6bc2d3273d11c8f2df752455790e53bddcfd4116bcdf4e7cfc3360e3ef0be564e5a01f09e43543cb2a315b6fa678e1c474aafbefe423e93851923d0b1fcabce9b0b894eb7a4b6207ad97efc92ef66ad0f60b80793191cce74abf146ae885a7389e28ea297743fdfc8865a17d24312a98d9d6b47dd7c4cf2bac634e3429c637c99605497ef698c234f0b8b38',
    'redirect_uri' => 'http://lerasy9n.beget.tech/',
];

$curl = curl_init();

curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$code = (int)$code;
$errors = [
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
];

try
{
   if ($code < 200 || $code > 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
}
catch(\Exception $e)
{
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}


$response = json_decode($out, true);


$tokens = [
    'access_token' => $response['access_token'],
    'refresh_token' => $response['refresh_token'],
    'expires_in' => 86400, 
    'token_type' => 'Bearer'
];

file_put_contents('amocrm_token.json', json_encode($tokens));