<?php

namespace App\Services\TelegramServices\Telegram;

use App\Message;
use CURLFile;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Class Api
 * @package App\Services\TelegramServices\Telegram
 */
class Api
{

    /** @var mixed */
    private $token;

    /** @var mixed|string */
    private $url = "https://api.telegram.org/bot{token}/{method}";

    /** @var string */
    private $bot_type;

    /**
     * Api constructor.
     * @param string $token
     * @param string $bot_type
     */
    public function __construct(string $token = '', string $bot_type = "user")
    {

        if ($token) {
            $this->token = $token;
        } else {
            $this->token = env('BOT_TOKEN');
        }
        $this->url = str_replace('{token}', $this->token, $this->url);
        $this->bot_type = $bot_type;
    }

    /**
     * @param string $url
     * @return array
     */
    public function setWebhook(string $url): array
    {
        $response = Http::get(str_replace('{method}', 'setWebhook', $this->url), ['url' => $url]);
        if ($response->ok()) {
            return $response->json();
        }
        return $response->json();
    }

    /**
     * @return WebhookUpdates
     */
    public function getWebhookUpdates(): WebhookUpdates
    {
        return new WebhookUpdates(json_decode(file_get_contents('php://input'), true), $this->bot_type);
    }

    /**
     * @param string $method
     * @param array $params
     * @return Response|array
     * @throws RequestException
     */
    public function send(string $method, array $params = [], bool $with_errors = false)
    {
        if ($method && !empty($params)) {
            $url = str_replace('{method}', $method, $this->url);
            $response = Http::get($url, $params);
            /** @var object $response */
            if ($response->ok() || $with_errors) {
                return $response->json();
            }
            $this->send("sendMessage", [
                'chat_id' => 287956415,
                'text' => json_encode(["method" => $method, "params" => $params, "error" => $response->json()])
            ]);
            die();
        }
    }

    /**
     * @param array $array
     * @return false|string
     */
    public function replyMarkup(array $array)
    {
        return json_encode($array);
    }


    /**
     * @param string $method
     * @param array $params
     * @param array $file
     * @param bool $is_query_file
     * @return mixed
     */
    public function sendFile(string $method, array $params, array $file = [], bool $is_query_file = false)
    {

        if (!isset($file["type"]) || !isset($file["path"])) {
            die();
        }

        $params[$file['type']] = $is_query_file
            ? new CURLFile($file["path"], '', $file['file_name'] ?? "")
            : $params[$file['type']] = new CURLFile(realpath($file["path"]), '', $file['file_name'] ?? "");

        $url = str_replace('{method}', $method . "?chat_id=" . $params['chat_id'], $this->url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

}
