<?php


namespace App\RequestHooks\OnPageStart;


use App\Core\User\ClientPB;
use App\Models\User;

class ClientPBHook
{
    /**
     * устанавливаем клиента PB, если пользователь сначала авторизовался, а потом только перешел по ссылке
     *
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     * @throws \Bitrix\Main\SystemException
     */
    public static function handle()
    {
        if ((ClientPB::isPagePb() || ClientPB::isUtmLink()) && !is_ajax() && !is_api()) {
            /** @var User $user*/
            $user = User::current();
            if (self::needSetPB($user)) {
                $user->fields['UF_CLIENT_PB'] = true;
                $user->save(['UF_CLIENT_PB']);
            }
        }
    }

    /**
     * проверка, что пользователь
     * 1. авторизован,
     * 2. сейчас он не клиент PB
     * 3. нужно ему установить клиент PB
     *
     * @param User $user
     * @return bool
     * @throws \Bitrix\Main\SystemException
     */
    private static function needSetPB(User $user)
    {
        $auth = $user->isAuthorized();
        $currentClientPb = $user->isClientPB();
        $needSetPb = ClientPB::isClientPB();
        return ($auth && !$currentClientPb && $needSetPb);
    }


}