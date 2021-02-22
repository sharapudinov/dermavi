<?php

namespace App\Api;

use Arrilot\SlimApiController\ApiController;
use Psr\Http\Message\ResponseInterface;

/**
 * Абстрактный класс-контроллер приложения
 * Class BaseController
 * @package App\Api
 */
abstract class BaseController extends ApiController
{
    /** @var string Имя для логгера */
    public const LOGGER_NAME = 'api';

    /**
     * Пишем лог при ошибке проверки csrf
     *
     * @param string $class - Класс, где произошла ошибка
     * @param string|null $loggerName - Идентификатор логгера
     */
    protected function writeCsrfLog(string $class, string $loggerName = null): void
    {
        logger($loggerName ?? self::LOGGER_NAME)->error(get_class_name_without_namespace($class) . ': '
            . 'Failed to check csrf token');
    }

    /**
     * Пишем обычный лог
     *
     * @param string $class - Класс, где произошла ошибка
     * @param string $message - Сообщение об ошибке
     */
    protected function writeErrorLog(string $class, string $message): void
    {
        logger(self::LOGGER_NAME)->error(get_class_name_without_namespace($class) . ': ' . $message);
    }

    /**
     * Отправить сообщение с успехом
     *
     * @param string $message - текст сообщения
     * @return ResponseInterface
     */
    protected function respondWithSuccess($message = 'No specific success message was specified', $json = null): ResponseInterface
    {
        $json = [
            'success' => [
                'message' => $message,
                'google' => $json,
            ]
        ];

        return $this->response->withJson($json);
    }

    /**
     * Отправляет сообщение о том, что элемент существует в базе
     *
     * @return ResponseInterface
     */
    protected function errorAlreadyExists(): ResponseInterface
    {

        return $this->respondWithError("Логин занят другим пользователем", 409);
    }

    /**
     * Получить значение из запроса
     * @param string $key - ключ значения
     * @return mixed
     */
    protected function getParam(string $key)
    {
        return $this->request->getParsedBody()[$key];
    }

    /**
     * Check Csrf Token
     *
     * @return bool
     */
    protected function checkCsrfToken()
    {
        if (!check_bitrix_sessid('csrf_token')) {
            if (in_production()) {
                return false;
            }
        }

        return true;
    }
}
