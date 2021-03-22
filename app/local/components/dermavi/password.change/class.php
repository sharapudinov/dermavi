<?php

namespace App\Components\Local;

use App\Components\BaseComponent;
use App\Models\User;

/**
 * Класс-контроллер для работы с формой изменения пароля пользователя
 * Class UserPasswordChange
 * @package App\Components\Local
 */
class UserPasswordChange extends BaseComponent
{
    /** @var User $user - Текущий пользователь */
    private $user;

    /**
     * Определяем параметры компонента
     *
     * @param array $arParams - Параметры компонента
     * @return void
     */
    public function onPrepareComponentParams(array $arParams): void
    {
        $this->user = $arParams['user'];
    }

    /**
     * Реализует логику компонента
     *
     * @return void
     */
    public function executeComponent(): void
    {
        $this->arResult['user'] = $this->user;
        $this->includeComponentTemplate();
    }
}
