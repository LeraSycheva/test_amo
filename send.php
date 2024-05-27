<?php
$client_id = 'daf76846-87d0-4f84-9fc3-f4a6d3abe687';
$client_secret = 'Uuw0nmZOTxNKMKGEoHoAVFWK4eETGukLeLU7JcSSpByKDlJX3sidGX9zNY3yl7xW';
$redirect_uri = 'https://cx34086.tw1.ru/';
$auth_url = 'https://lerasycheva01.amocrm.ru/oauth/authorize';
$code = 'def502001c1e75b13627de56370ccf9ac8b5300b49c24fccbbb941b515a3c8babaf34d01c08b61a81ae22cd962ad5956920786ca6d462aef98a1999b10dd9f46d29e2a8a08a02a493e42cfd836f3ee99e095f731060a279d74a42b00356afc6cfe48bdcb80abe5438abc2151a4008aeadc0eb0ac00513a0ada91d39abd0283eb9cb388892fe088481f319678f9389458d6024e3c80a1442fe758e59ee0621fbfa507b649d99abaeca22bd0fc07aaba0dceedbc63fbf02e82e80cd66c0578583c7a4161aa8931570a25971817113f9e4aacfb68997df6e7a1a9a8665b7020fbb03996828375e959a7ba17e4f64991aa4c99f7838780ddc5b4978e22e822165db11a4bd26c1b99bf275f884a341c5cde2a759693aad8eb695f558e59aa2cf2203cfa6c35475e73325236c01f3d977eda138acabf5cee96686c415ad49454a4b3c037c9ddf10338f296b5811fc7d93cdc76ae0526c2c31c6a3d814059c95c199b3d283ebd5c3729226d2d56d7adf2ef5689dcbdab2127eafdfcd8d9cd92be746eeba883800a411e0343418eaaed43e784c48b559f0c3ead11a1026448de934e2d0921ee568ebbda8cc5f7fb4a0045a7a5f5802ac7afd9f787540a6b5101396ebffb88c96b3e8930e1b49f94faf8205495d8c14fcab1c930eb4313cdf06da397717a963bbb5c6b48b3a06b15f7d7a4'; // Код авторизации
$token_file = 'tokens.txt';
$error = ''; 

if (!file_exists($token_file)) {
    $error = "Нет файла токена";
}

$dataToken = file_get_contents($token_file);
$dataToken = json_decode($dataToken, true);

if ($dataToken["endTokenTime"] - 60 < time()) {

    $link = "https://lerasycheva01.amocrm.ru/oauth2/access_token";

    $data = [
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'grant_type'    => 'refresh_token',
        'refresh_token' => $dataToken["refresh_token"],
        'redirect_uri'  => $redirect_uri,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $code = (int)$code;
    $errors = [
        301 => 'Moved permanently.',
        400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
        401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
        403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
        404 => 'Not found.',
        500 => 'Internal server error.',
        502 => 'Bad gateway.',
        503 => 'Service unavailable.'
    ];

    if ($code < 200 || $code > 204) {
        $error = "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error');
    }

    $response = json_decode($out, true);

    $arrParamsAmo = [
        "access_token"  => $response['access_token'],
        "refresh_token" => $response['refresh_token'],
        "token_type"    => $response['token_type'],
        "expires_in"    => $response['expires_in'],
        "endTokenTime"  => $response['expires_in'] + time(),
    ];

    $arrParamsAmo = json_encode($arrParamsAmo);

    file_put_contents($token_file, $arrParamsAmo);

    $access_token = $response['access_token'];

} else {
    $access_token = $dataToken["access_token"];
}


$name = $_POST['NAME'];
$phone = $_POST['PHONE'];
$email = $_POST['EMAIL'];
$time = $_POST['TIME'];

$price = intval($_POST['PRICE']);
$pipeline_id = 8187098;
$user_amo = 11067154;
if (empty($name) || empty($phone) || empty($email) || empty($price)) {
    $error = "Не все поля заполнены";
} else {
    $data = [
        [
            "name" => "Новая заявка с тестового сайта",
            "price" => $price,
            "responsible_user_id" => $user_amo,
            "pipeline_id" => $pipeline_id,
            "_embedded" => [
                "contacts" => [
                    [
                        "first_name" => $name,
                        "custom_fields_values" => [
                            [
                                "field_code" => "PHONE",
                                "values" => [
                                    ["value" => $phone, "enum_code" => "WORK"]
                                ]
                            ],
                            [
                                "field_code" => "EMAIL",
                                "values" => [
                                    ["value" => $email, "enum_code" => "WORK"]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "custom_fields_values" => [
                [
                    "field_id" => 820603,
                    "values" => [
                        [
                            "value" => boolval($time)
                        ]
                    ]
                ],
            ]
        ]
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_URL, "https://lerasycheva01.amocrm.ru/api/v4/leads/complex");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $code = (int) $code;
    $errors = [
        301 => 'Moved permanently.',
        400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
        401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
        403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
        404 => 'Not found.',
        500 => 'Internal server error.',
        502 => 'Bad gateway.',
        503 => 'Service unavailable.'
    ];

    if ($code < 200 || $code > 204) {
        $error = "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error');
    }
}

if (!empty($error)) {
    echo json_encode(["error" => $error]);
}