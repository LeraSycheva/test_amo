<?php
$subdomain = 'lerasycheva01';
$link = 'https://lerasycheva01.amocrm.ru/oauth2/access_token';

$data = [
    'client_id' => 'c81c3946-afbb-47ad-976b-176dcdcc8108',
    'client_secret' => 'xUQKuxDWw6bS4AgCdxoWDkClTuiZ3s1pocB9xLcGgIULXANz6B2M9v37RwEzF5Cd',
    'grant_type' => 'authorization_code',
    'code' => 'def50200c2471337758af9f8e9fad15adbc52112207f38db952774698af1866a3a394e0a9fb3060c755fa2a767c6d5e4b88d3ee522fc2d725aa8f9770cc4604495c05e225f015ce1b602c68134fe5213a21ea46c35d477e408384f3c6756994b01c053118c3f8635688cc839437393efe08a2b36d73d0b018fd77b3480abab60862d655e1ed89d4dd0be1d92969cad4b39e2c16e53bb01da9878541262f591e1efbef3428153117070e756dfa63940a424fab2a95bc4b2ecac489c0bf51272105945f16a89d97e14343fdbce11acd077e5983cc443abc15a6c8a09920052636edb0dde0c665d4fdb72e23edf9f1c486ae8f4973cb34c5376c45250041dda9d4f07fb09d22b51b4c734e2db557db5dc8a652158596993c7d83736bd3029a7cf4d0a1e643be76af15b4eda88423f7bf9bf658038bf20fff9b43c7308dc67c48d4a6d851bd851b086b2fe1ca7b844195976892c8d14e3f8151a6aa79d5231dfd962437dd950dec95574f48fc486564df2cc8d940b3472b2b0456f0298f103766794eb873671bb6acd6f9952ed31e88c1b0f79233766ecc0da0e1310f9d77f80f59251e1e6adf794e940fb1aeb478406ba1c48916f189785ff244ee8687a2ab401886e208aa3be8a3d5d62d9e160d813499f3731fc9795d14c971fbd8cb826e4b37712f7142de6000fc5cae3c65e62',
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