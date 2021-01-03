<?php

namespace App\Helpers\Itg;

use App\Models\Itg\OrderAlias;
use App\Models\Itg\UserAlias;

class DbQueries
{
    /*
     * Функция записывает в БД время последнего запуска скрипта
     */
    /*
    public function writeTimer()
    {
        global $DB;
        $DB->Query("INSERT INTO `my_timer` (id, last_time) VALUES (1, NOW()) " .
            "ON DUPLICATE KEY UPDATE last_time = NOW()");
    }
    */

    /*
     * Функция проверяет наличие алиаса для пользователя по id, потом проверяет этот алиас в crm, если такого нет, то
     * удаляет запись из БД
     */
    public function isUserHasAlias($id)
    {
        //$contactId =  ($DB->Query(" SELECT * FROM `my_user_aliases` WHERE `user_id` = " . (int)$id . ' LIMIT 1'))->Fetch();
        $contactId = UserAlias::whereUserId((int)$id)->first();
        $contactCrm = CRest::call('crm.contact.get', [
            'id' => $contactId->contact_id,
        ]);
        if (isset($contactCrm['result'])) {
            return $contactId;
        } elseif ($contactId) {
            $contactId->delete();
            //UserAlias::whereContactId($contactId['contact_id'])->first()->delete();
           // $DB->Query("DELETE FROM `my_user_aliases` WHERE `contact_id` = " . $contactId['contact_id']);
        }
        return false;
    }

    /**
     * Функция создаёт алиас между id пользователя на сайте и id контакта в crm
     * @param int $userId - id пользователя
     * @param int $contactId - id контакта
     * @return mixed - Функция возвращает результат запроса в БД или false, если ничего не найдено
     */
    public function createUserAlias(int $userId, int $contactId)
    {
        return UserAlias::create([
            'user_id' => (int)$userId,
            'contact_id' => (int)$contactId,
        ]);
        //$DB->Query(" INSERT INTO `my_user_aliases` SET `user_id` = " . (int)$userId . ", contact_id =  " . (int)$contactId)
        //return ($DB->Query(" INSERT INTO `my_user_aliases` SET `user_id` = " . (int)$userId . ", contact_id =  " . (int)$contactId));
    }

    /**
     * Функция проверяет наличие алиаса для заказа по id, если алиас найден, но не существует в crm, то такая запись удаляется
     * @param int $id - id заказа
     * @return array|false - Функция возвращает массив, результат запроса в БД или false, если не найдено.
     */

    public function isOrderHasAlias(int $id)
    {
        //$dealId =  ($DB->Query(" SELECT * FROM `my_order_aliases` WHERE `order_id` = " . (int)$id . ' LIMIT 1'))->Fetch();
        $dealId = OrderAlias::whereOrderId((int)$id)->first();
        $dealCrm = CRest::call('crm.deal.get', [
            'id' => $dealId->deal_id,
        ]);
        if (isset($dealCrm['result'])) {
            return $dealId;
        } elseif ($dealId) {
            $dealId->delete();
           // OrderAlias::whereDealId($dealId['deal_id'])->first()->delete();
           // $DB->Query("DELETE FROM `my_order_aliases` WHERE `deal_id` = " .(int) $dealId['deal_id'] .";");
        }
        return false;
    }

    /**
     * Функция создаёт алиас для заказа и сделки в crm
     * @param int $orderId - id заказа
     * @param int $dealId - id сделки
     * @return mixed
     */
    public function createOrderAlias(int $orderId, int $dealId)
    {
      return OrderAlias::create([
            'order_id' => (int)$orderId,
            'deal_id' => (int)$dealId,
        ]);
        // global $DB;
        // return ($DB->Query(" INSERT INTO `my_order_aliases` SET `order_id` = " . (int)$orderId . ", deal_id =  " . (int)$dealId));
    }
}