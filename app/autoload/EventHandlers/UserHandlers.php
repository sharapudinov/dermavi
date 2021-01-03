<?php

namespace App\EventHandlers;

use App\Core\User\ClientPB;
use App\Models\User;

class UserHandlers
{
    /**
     * Проверка обязательности полей
     *
     * @param $arFields
     *
     * @return bool
     */
    public function checkRequiredFields(&$arFields)
    {
        if (!isset($arFields['ID'])) {
            return true;
        }

        $oldUser = User::getById($arFields['ID']);
        //Возможно это не самый надежный способ инициализации модели
        //Просто выбрать по ID мы не можем, т.к. изменений еще нет в БД
        $user = new User($arFields['ID'], $arFields);

        if (isset($arFields['UF_USER_ENTITY_TYPE']) && $oldUser['UF_USER_ENTITY_TYPE'] != $user['UF_USER_ENTITY_TYPE']) {
            app()->ThrowException('Нельзя сменить тип пользователя');
            return false;
        }

        if (isset($arFields['XML_ID'])) {
            if (!$user->country) {
                app()->ThrowException('Необходимо указать страну');
                return false;
            }
        }

        return true;
    }

    /**
     * @param $fields
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     * @throws \Bitrix\Main\SystemException
     */
    public function updateClientPB(&$fields)
    {
        /** @var User $user*/
        $user = User::getById($fields['USER_ID']);

        if ($fields['USER_ID'] > 0) {
            if (ClientPB::isClientPB() && !$user->isClientPB()) {
                $user->fields['UF_CLIENT_PB'] = true;
                $user->save(['UF_CLIENT_PB']);
            }
        }
    }
}
