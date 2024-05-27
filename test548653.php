<?php
$subdomain = 'lerasycheva01';
$link = 'https://lerasycheva01.amocrm.ru/oauth2/access_token';

$data = [
    'client_id' => 'c81c3946-afbb-47ad-976b-176dcdcc8108',
    'client_secret' => 'xUQKuxDWw6bS4AgCdxoWDkClTuiZ3s1pocB9xLcGgIULXANz6B2M9v37RwEzF5Cd',
    'grant_type' => 'authorization_code',
    'code' => 'def5020094c61b2437b527af7f13bf154cd4b72db2d5a6e5f840d79ecc6936fe6b53b25a45103de79cc025b22a4c37675eaa21863768c0d811ec225def9b5583614f8eef462a57ec805cc94a42d2e348378b0576240e4dbd3cd4c53972a8073ce2cfa3cffee6af79af1082e78b3e7a9f7bcecb27bfaf170fdb8ffca5ffba961441e32d35583339906f0c8debdbf8216107aa636751ca115658a3c2eccb60723e9e6cfbe5f0d9cf65038dba2f8f7775879a188af1300420cf025b314bae303495e8411939ef665989c1d16971b18d36423ce1363a5b2ad44175e7ae03f3e54d3b2c2f5dd1987f681e13b3a8068edd56af133e8a0d49198f74cef69c0d947212bbce57bc38509eae496fe5ebef43f77340bb1dd5f647f4d3974176be03bca7fc12d1569aa1cba20cedf5a3e46f449114e18947bcf6eafab52efe03856887d6e6d1ee12728029b03f5d5df44e9f4e0211d38dc991505659bf1c69dfabba2b202bf60d685ac19030cdb2b278ba208ebb6cc36de203b514a0a421f9c1a2f2f35c2e142187274540ca34825b00ca439dbd3e10efabb33e83e7146b0b2dea0a7446bc613b210213036d8ecdedd4ea28aad91e4105d8fa9ddfed9c708fae3dbfee2beb33f6b81ecd0d8591a15a60f41ae6e22dc020c09ebc8b3d94d8779a6826cfba9a6f2d492b9a8bfab1e859bf5bd43d',
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

file_put_contents('tokens.txt', json_encode($tokens));