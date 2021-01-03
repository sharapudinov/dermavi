<?php

namespace App\Helpers\Itg;
/*
require_once('classes/crest.php');
require_once('classes/DbQueries.php');
require_once('classes/InitDb.php');
require_once('classes/Actions.php');
*/

use Bitrix\Main\Type\DateTime;

class Actions
{
    protected $request;

    public function __construct()
    {
        $this->request = new DbQueries;
    }

    /*
     * Функция получает список пользователей из списка заказов
     * @array $orders - список заказов
     * @return array $users - функция возвращает массив с пользователями
     */
    public function getUserListFromOrders(array $orders): array
    {
        foreach ($orders as $order) {
            $users[$order['ID']] = $this->getUserById((int)$order['USER_ID']);
        }
        return (array)$users;
    }

    /*
    * Функция выбирает заказы из БД по дате
    * Функция возвращает массив заказов.
    */
    public function selectNewOrders(): array
    {
        $result = \CSaleOrder::GetList(
        //Order
            [
                'id' => 'DESC'
            ],
            //filter
            [
                '>DATE_UPDATE' => (new DateTime())->add('-1 day'),
            ],
            //Group
            false,
            //Params
            [],
            //Select Fields
            [
                '*',
            ],
            //Non Use
            []
        );
        while ($arSales = $result->Fetch()) {
            $orders[] = $arSales;
        }

        return (array)$orders;
    }

    /*
     * Функция выбирает пользователя по id
     * @int id - id пользователя
     * @return array $user - функция возвращает массив пользователя
     */
    public function getUserById(int $id): array
    {
        return (\CUser::GetByID($id))->Fetch();
    }

    /*
     * Функция проверяет пользвоателей на наличие связанных контактов с crm, при необходимости создаёт
     * @array $users - массив пользователй
     * Функция ничего не возвращает
     */
    public function addAliasesToAllUsers($users)
    {
        foreach ($users as $key => $user) {
            $this->createUserAliasIfNeed($user, $key);
        }
    }

    /*
     * Функция создаёт для аккаунта пользователя связь с контактом в crm
     * @array $user - массив с данными пользователя
     * @integer $orderId - id заказа
     * Функция ничего не возвращает
     */
    public function createUserAliasIfNeed(array $user, int $orderId)
    {
        if (!$this->request->isUserHasAlias($user['ID'])) {
            $this->request->createUserAlias($user['ID'],
                $this->createUserContact($user, $orderId)
            );
        }
    }

    /*
     * Функция создаёт контакт в crm из данных пользователя
     * @array $user - массив с данными пользователя
     * @integer $orderId - id заказа
     * @return int - функция возвращает id контакта, который приходит в ответе от crm
     */
    public function createUserContact(array $user, $orderId): int
    {
        sleep(1);
        $response = CRest::call('crm.contact.add', [
            'FIELDS' => [
                'NAME' => $user['UF_USER_NAME_RU'] ? $user['UF_USER_NAME_RU'] : 'Заказ с сайта',
                'SECOND_NAME' => $user['UF_USER_MIDDLE_NAME'] ? $user['UF_USER_MIDDLE_NAME'] : 'нет',
                'LAST_NAME' => $user['UF_USER_SURNAME_RU'] ? $user['UF_USER_SURNAME_RU'] : '№' . $orderId,
                'TYPE_ID' => 'CLIENT',
                'SOURCE_ID' => 'ALROSA',
                'EMAIL' => [
                    0 => [
                        'VALUE' => $user['EMAIL'],
                    ],
                ]
            ],
        ]);
        return $response['result'];
    }

}