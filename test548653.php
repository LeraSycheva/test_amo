<?php
$subdomain = 'lerasycheva01';
$link = 'https://lerasycheva01.amocrm.ru/oauth2/access_token';

$data = [
    'client_id' => '11c26bde-72bd-49b7-9dde-e42f4802081a',
    'client_secret' => 'OZlOm443HK0NCPaFpy6kulQJHtrUpcTqxAIesHCTB5GMCtDAj3JHPN3xpmGDfvd1',
    'grant_type' => 'authorization_code',
    'code' => 'def502001bc294a461ff4c7b53b04dc41c895fada87f4d9e29f84d94d01769c8cb1daec0474368f3377ac013c122b952f808a894e9c22008d09b0964179252e3f06b50bb02f860f2a3861aa03d1f13052cdc769391eb18053cb96b3217beb661eefd666848e7315f7b4cedc6dea4134bc67e57b06af5edd09abebf33b03bd46d2daf93d0ddd7cd3ed172bd34a745173d1d0fd440bd9ec4c28afb4c6e4b1313db9e22505dfef1f453b1b75cb9d1c1de136dc491875f182ba6c464dc21c39e56c4a2959d8e52ad62c8e3da68f308667464d94663cb567802f4a4a54a43071a2236a76dcb1072838fd77149dbf2de15dd2231016a1d244674f63d7e3f673b2cfff43a740eb8531056d23a25df57f7feadd0bbbe686b73bed4f1db40263dc77d209cbd52a58f7dd4ee23d17940983f9f6d2331f9c78e9183629aaddca664636e5966e1106289aa61ef10f58c2fab3f5e7263c629a48687312080d725fcbc3ab83c74804ca2eb40c968f814085fd8fe86f914c6a6b49f48f66da2be8269e1484b1a5fee1dacd63d671044df8590d0f4ae73e8319117e6dd8d3e0578c5f49aa19599975b9a664d0ec08b29b7d4d80ab4c5043d84b6cffd8894956d45c490e7f546d72303d52447cdec915c0a4edaed75ce041720cfd195cec8417225ba0db89a8346c6de116d91489f004dc8',
    'redirect_uri' => 'https://cx34086.tw1.ru/',
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