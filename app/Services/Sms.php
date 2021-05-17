<?php


namespace App\Services;


use App\Models\SmsHistory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Sms
{
    private $api_key;

    private $api;

    public function __construct(array $phones, $msg)
    {
        $this->api_key = Config::get('app.sms_ru_api');
        $this->api = "https://sms.ru/sms/send?api_id=$this->api_key&to=" . implode(',', $phones) . "&msg=$msg&json=1";
    }

    public function send()
    {
        $client = new Client();

        $response = $client->get($this->api);
        $result = [];
        $body = $response->getBody()->getContents();
        Log::info('SMS - ' . $body);
        $json = json_decode($body);

        if ($json->status == "OK") { // Запрос выполнился
            foreach ($json->sms as $phone => $data) { // Перебираем массив СМС сообщений
                if ($data->status == "OK") { // Сообщение отправлено
                    SmsHistory::create([
                        'phone' => $phone,
                        'sms_id' => $data->sms_id
                    ]);
                    return  ['success' => true, 'message' => "Сообщение на номер $phone успешно отправлено."];
                }
                return ['success' => false, 'message' => 'Превышен лимит отправки повторных сообщений'];
            }

        }
        return  ['success' => false, 'message' =>'Запрос не выполнился. Не удалось установить связь с сервером.'];
    }

}
