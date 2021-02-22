<?php

namespace App\Api\Internal\User;

use App\Api\BaseController;
use App\Core\BitrixEvent\EventMessage;
use App\Helpers\UserCartHelper;
use App\Helpers\UserHelper;
use App\Models\User as UserModel;
use App\Core\User\User;
use CEvent;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Класс-контроллер для операций, связанных с аутентификацией
 * Class AuthController
 * @package App\Api\Internal\User
 */
class AuthController extends BaseController
{
    /**
     * Регистрируем пользователя
     *
     * @return ResponseInterface
     *
     * @throws \Exception
     */

    const USER_PASSWORD_RESTORE_LINK_EVENT_NAME ='USER_PASSWORD_RESTORE_LINK';

    public function signUp(): ResponseInterface
    {
        /** @var array $request - Данные из формы регистрации */
        $request = $this->request->getParsedBody();

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;

        /** @var UserModel|null $user - Пользователь, найденный по логину */
        $user = UserModel::filter(['LOGIN' => $request['signup_email']])->first();

        if ($user) {
            $response = $this->errorAlreadyExists();
            logger(User::LOGGER_NAME_AUTH_SUCCESS)->info(
                'Не удалось зарегистрировать пользователя ' . $request['signup_email']
                . '. Причина: Пользователь с таким email уже зарегистрирован.'
            );
        } else {
            if ((new User)->defineUserPersonType($request['signup_person_type'])->signUp($request)) {
                $response = $this->respondWithSuccess();
                logger(User::LOGGER_NAME_AUTH_SUCCESS)
                    ->info('Пользователь ' . $request['signup_email'] . ' успешно зарегистрирован.');
            } else {
                $response = $this->respondWithError();
            }
        }

        return $response;
    }

    /**
     * Авторизуем пользователя
     *
     * @return ResponseInterface
     */
    public function signIn(): ResponseInterface
    {
        /** @var array $request - Данные из формы авторизации */
        $request = $this->request->getParsedBody();

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        $notAuthedFUserId = $_COOKIE['BITRIX_SM_SALE_UID'];
        if (User::signIn($request['signin_email'], $request['signin_password'])) {
            if(!user()->isLegalEntity()){
            }
            $response = $this->respondWithSuccess();
            logger(User::LOGGER_NAME_AUTH_SUCCESS)
                ->info('Пользователь ' . $request['signin_email'] . ' успешно авторизован.');
        } else {
            $response = $this->respondWithError();
            logger(User::LOGGER_NAME_AUTH_SUCCESS)
                ->info(
                    'Пользователю ' . $request['signin_email']
                    . ' не удалось авторизоваться. Неверный логин или пароль.'
                );
        }

        return $response;
    }

    /**
     * Посылает на почту ссылку для восстановления пароля
     *
     * @return ResponseInterface
     */
    public function sendRestoreLink(): ResponseInterface
    {
        /** @var array $request - Данные из формы запроса */
        $request = $this->request->getParsedBody();

        /** @var \App\Models\User $user - Пользователь, который восстанавливает пароль */
        $user = UserModel::getByEmail(e($request['email']));


        $userLanguageInfo =[
            'language_id' => 'ru',
            'site_id' => 's1'
            ];


      $eventMessage = EventMessage::getEventMessagesByCode(
          self::USER_PASSWORD_RESTORE_LINK_EVENT_NAME,
/*            $userLanguageInfo['language_id']*/
        )->first();

        /** @var Response $response - Ответ сервера */
        $response = null;


        if ($user) {
            CEvent::SendImmediate($eventMessage->getEventName(), $userLanguageInfo['site_id'], [
                'EMAIL_TO' => $user->getEmail(),
                'REQUEST_URL' => get_external_url(false, true) . '?hash=' . $user->getHash()
            ], 'Y', $eventMessage->getMessageId(), [], $userLanguageInfo['language_id']);


            $response = $this->respondWithSuccess();
            logger(User::LOGGER_NAME_AUTH_SUCCESS)->info(
                'Пользователь ' . $user->getEmail()
                . ' забыл пароль и запросил его смену'
            );
        } else {
            $response = $this->respondWithError();
            logger(User::LOGGER_NAME_AUTH_SUCCESS)->info(
                'Пользователь ' . $_REQUEST['email']
                . ' запросил смену пароля, но пользователь с таким email не был найден в базе'
            );
        }

        return $response;
    }

    /**
     * Задает пользователю новый пароль
     *
     * @return ResponseInterface
     */
    public function setNewPassword(): ResponseInterface
    {
        /** @var string $hash - Хэш пользователя */
        $hash = e($_REQUEST['new_password_user_hash']);

        /** @var string $password - Новый пароль */
        $password = e($_REQUEST['new_password_password']);

        /** @var \App\Models\User $user - Пользователь */
        $user = UserModel::filter(['UF_HASH' => $hash])->first();

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        if ($user) {
            try {
                $user->update([
                    'PASSWORD' => $password,
                    'CONFIRM_PASSWORD' => $password
                ]);
                $response = $this->respondWithSuccess();

                logger(User::LOGGER_NAME_AUTH_SUCCESS)
                    ->info('Пользователь ' . $user->getEmail() . ' изменил свой пароль');
            } catch (Exception $exception) {
                $this->writeErrorLog(self::class, $exception->getMessage());
                $response = $this->respondWithError();

                logger(User::LOGGER_NAME_AUTH_ERROR)->error(
                    'Не удалось обновить пароль для пользователя ' . ($user ? $user->getId() : 'unknown')
                    . '. Причина: ' . $exception->getMessage()
                );
            }
        } else {
            $response = $this->respondWithError();
            logger(User::LOGGER_NAME_AUTH_SUCCESS)
                ->info(
                    'Пользователю не удалось изменить свой пароль. Причина: пользователь с хешем ' . $hash
                    . ' не найден'
                );
        }

        return $response;
    }

    /**
     * Изменяет пароль пользователя
     *
     * @return ResponseInterface
     */
    public function changePassword(): ResponseInterface
    {
        /** @var UserModel $user - Текущий пользователь */
        $user = user();

        /** @var bool $passwordIsCorrect - Флаг правильности пароля */
        $passwordIsCorrect = UserHelper::compareHashedPasswords($this->getParam('oldPassword'), $user);

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            if ($passwordIsCorrect) {
                /** @var string $newPassword - Новый пароль пользователя */
                $newPassword = e($this->getParam('newPassword'));
                $user->update([
                    'PASSWORD' => $newPassword,
                    'CONFIRM_PASSWORD' => $newPassword
                ]);
                $response = $this->respondWithSuccess();
                logger(User::LOGGER_NAME_AUTH_SUCCESS)
                    ->info('Пользователь ' . $user->getEmail() . ' изменил свой пароль');
            } else {
                $response = $this->errorUnauthorized();
                logger(User::LOGGER_NAME_AUTH_SUCCESS)->info(
                    'Пользователь ' . $user->getEmail()
                    . ' неудачно попытался изменить свой пароль. Причина неудачи: Неверно введен текущий пароль.'
                );
            }
        } catch (Exception $exception) {
            logger(User::LOGGER_NAME_AUTH_ERROR)->error(
                'Не удалось обновить пароль для пользователя ' . ($user ? $user->getId() : 'unknown')
                . '. Причина: ' . $exception->getMessage()
            );
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Разлогиниваем пользователя
     *
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        global $USER;
        $USER->Logout();
        return $this->respondWithSuccess(['languagePrefix' => get_language_version_href_prefix()]);
    }
}
