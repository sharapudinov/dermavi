<?php

namespace App\Core;

use stdClass;
use Exception;

/**
 * Класс для curl запросов
 * Class CurlQuery
 * @package App\Core
 */
final class CurlQuery
{
    /** @var mixed - Результат, полученный через curl */
    private $result;

    /**
     * Альтернатива file_get_contents, чтобы можно было задавать таймаут
     *
     * @param string $url - ссылка, с которой надо получить информацию
     * @param int $timeout - количество секунд для попытки присоединения к ресурсу
     * @return CurlQuery
     */
    public function fileGetContents(string $url, int $timeout): self
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($curl);
        curl_close($curl);

        $this->result = $data;
        if ($data === false) {
            logger('import')->error(__CLASS__ . ': ' . 'cURL failed to connect to ' . $url);
        }

        return $this;
    }

    /**
     * Получаем объект
     *
     * @return stdClass|null
     */
    public function getJSON()
    {
        return $this->result !== false ? json_decode($this->result, true) : false;
    }

    /**
     * Получаем строку
     *
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->result !== false ? (string) $this->result : false;
    }

    /**
     * Отправляет HEAD запрос и возвращает истину, если статус ответа 200.
     * @param string $url
     * @param int $timeout
     * @return bool
     */
    public static function checkPageExists(string $url, int $timeout = 1): bool
    {
        // Если сайт проверяет тип агента и не поддерживает IE, то текущие заголовки перенаправлять нельзя.
        $userAgent = 'Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36';

        $curl = curl_init();
        try {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
            curl_exec($curl);
        } catch (Exception $exception) {
            logger('api')->error(self::class . ': '. $exception->getMessage());
        } finally {
            $result = curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200;
            curl_close($curl);

            return $result;
        }
    }
}
