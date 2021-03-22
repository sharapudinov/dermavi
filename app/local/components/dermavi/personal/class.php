<?php

namespace App\Local\Component;

use App\Components\ExtendedComponent;
use App\Core\Geolocation\Country;
use App\Core\User\CompanyActivity;
use App\Helpers\TTL;
use App\Models\Auxiliary\CRM\Region;
use App\Models\User as UserModel;
use App\Core\User\User;

/**
 * Класс-контроллер для работы с персональной информацией пользователя/компании
 * Class Personal
 * @package App\Local\Component
 */
class Personal extends ExtendedComponent
{
    /** @var UserModel $user - Текущий пользователь */
    private $user;

    /**
     * Определяем параметры компонента
     *
     * @param array $arParams - Параметры
     * @return void
     */
    public function onPrepareComponentParams(array $arParams): void
    {
        $this->user = $arParams['user'];
    }

    /**
     * Запускаем компонент
     *
     * @return void
     */
    public function executeComponent(): void
    {
        $this->loadData();
        $this->includeComponentTemplate();
    }

    /**
     * Загружаем данные пользователя
     *
     * @return void
     */
    private function loadData(): void
    {
        $this->arResult = cache(
            get_default_cache_key(self::class) . '_' . $this->user->getId(),
            TTL::DAY,
            function () {
                return [
                    'name'       => $this->user->getName(),
                    'surname'    => $this->user->getSurname(),
                    'patronymic' => $this->user->getMiddleName(),
                    'email'      => $this->user->getEmail(),
                    'phone'      => $this->user->getPhone(),
                    'region'     => $this->user->getRegion(),
                    'city'       => $this->user->getCity(),
                    'street'     => $this->user->getStreet()
                ];
            },
            User::PERSONAL_INFO_PROFILE_CACHE_INIT_DIR . $this->user->getId()
        );

        $this->arResult['user'] = $this->user;
    }
}
