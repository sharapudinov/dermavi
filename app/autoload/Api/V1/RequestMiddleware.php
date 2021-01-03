<?php

namespace App\Api\V1;

use App\Helpers\UserHelper;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;
use Slim\Route;

/**
 * Класс для работы с запросами, идущими из внешних систем в ИМ
 * Class RequestMiddleware
 * @package App\Api\V1
 */
class RequestMiddleware
{
    /**
     * Производит операции над параметрами
     *
     * @param Request $request
     * @param Response $response
     * @param Route $next
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, Route $next): ResponseInterface
    {
        /** @var string $login - Логин */
        $login = e($request->getQueryParams()['login']);

        /** @var string $password - Пароль */
        $password = e($request->getQueryParams()['password']);

        /** @var User|null $user - Модель пользователя */
        $user = User::getByLogin($login);

        if ($user && UserHelper::compareHashedPasswords($password, $user)) {
            logger('api')->addInfo(
                self::class . ': Был произведен вход через внешнее api пользователем ' . $user->getEmail()
            );
            return $next($request, $response);
        } else {
            logger('api')->error(
                self::class . ': Ошибка авторизации через внешнее api пользователя ' . $login . ';' . $password
            );
            return $response->withStatus(StatusCode::HTTP_NOT_FOUND)->withJson('User not found');
        }
    }
}
