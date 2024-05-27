<?php
$client_id = 'c81c3946-afbb-47ad-976b-176dcdcc8108';
$client_secret = 'xUQKuxDWw6bS4AgCdxoWDkClTuiZ3s1pocB9xLcGgIULXANz6B2M9v37RwEzF5Cd';
$redirect_uri = 'http://lerasy9n.beget.tech/';
$auth_url = 'https://lerasycheva01.amocrm.ru/oauth/authorize';

$token_file = 'amocrm_token.json';
if (!file_exists($token_file)) {
    echo "Нет файла токена";
    exit;
}

$tokens = json_decode(file_get_contents($token_file), true);


function refreshAccessToken($tokens, $client_id, $client_secret) {

    $link = 'https://lerasycheva01.amocrm.ru/oauth2/access_token';
    $data = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'refresh_token',
        'refresh_token' => $tokens['refresh_token'],
        'redirect_uri' => $redirect_uri
    ];
    $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
    /** Устанавливаем необходимые опции для сеанса cURL  */
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
    curl_setopt($curl,CURLOPT_URL, $link);
    curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
    curl_setopt($curl,CURLOPT_HEADER, false);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
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
   
   
    if ($code < 200 || $code > 204) die( "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error') );

    $response = json_decode($out, true);

    $arrParamsAmo = [
        "access_token"  => $response['access_token'],
        "refresh_token" => $response['refresh_token'],
        "token_type"    => $response['token_type'],
        "expires_in"    => $response['expires_in'],
        "endTokenTime"  => $response['expires_in'] + time(),
    ];


    file_put_contents($token_file, json_encode($arrParamsAmo));
    return $response;
}

if ($tokens['expires_in'] < time()) {
    $tokens = refreshAccessToken($tokens, $client_id, $client_secret);
}
$name = $_POST['NAME'];
$email = $_POST['EMAIL'];
$phone = $_POST['PHONE'];
$price = $_POST['PRICE'];
if ($name == '' || $email == '' || $phone == '') {
    echo 'Необходимо заполнить все поля: Имя, Email и Телефон.';
}

$url = 'https://lerasycheva01.amocrm.ru/api/v4/leads/complex';

$name = 'Имя клиента';
$phone = '+380123456789';
$email = 'email@gmail.com';
$target = 'Цель';
$company = 'Название компании';

$data = [
    {
      "name": "Пример названия сделки",
      "price": 10000,
      "_embedded": {
        "contacts": [
          {
            "first_name": "Иван",
          "custom_fields_values": [
              {
                "field_id": 66186,
                "values": [
                  {
                    "enum_id": 193200,
                    "value": "ivan@example.com"
                  }
                ]
              },
              {
                "field_id": 66192,
                "values": [
                  {
                    "enum_id": 193226,
                    "value": "+79876543210"
                  }
                ]
              }
            ]
          }
        ],
        "tags": [
          {
            "name": "важная"
          }
        ]
      }
    }
];


$headers = [
    'Authorization: Bearer ' . $tokens['access_token'],
    'Content-Type: application/json'
];

$options = [
    'http' => [
        'header' => implode("\r\n", $headers),
        'method' => 'POST',
        'content' => json_encode($data),
    ]
];
$context = stream_context_create($options);
$context_options = stream_context_get_options($context);


$result = file_get_contents($url, false, $context);
if (!$result) {
    echo 'Ошибка при создании заявки';
} else {
    echo 'Заявка успешно создана';
}