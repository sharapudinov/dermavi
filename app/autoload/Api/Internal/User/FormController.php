<?php

namespace App\Api\Internal\User;

use App\Api\BaseController;
use App\Core\User\User;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс-контроллер для работы с анкетой пользователя
 * Class FormController
 *
 * @package App\Api\Internal\User
 */
class FormController extends BaseController
{
    /**
     * Записывает в бд данные анкеты пользователя
     *
     * @return ResponseInterface
     */
    public function setInfo(): ResponseInterface
    {
        /** @var array|mixed[] $fields Поля формы */
        $fields = $this->request->getParsedBody();

        /** @var ResponseInterface $response Ответ сервера */
        $response = null;

        try {
            (new User())->setUserAndDefineUserPersonType(user())->getPersonType()->setProfileFormData(user(), $fields);
            logger('personal')->info('Добавлена/Обновлена анкета для пользователя #' . user()->getId());
            $response = $this->respondWithSuccess();
        } catch (Throwable $exception) {
            logger('personal')->error(
                'Не удалось добавить/обновить данные личной анкеты пользователя #' . user()->getId()
                . '. Причина: ' . $exception->getMessage()
            );
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }
}
