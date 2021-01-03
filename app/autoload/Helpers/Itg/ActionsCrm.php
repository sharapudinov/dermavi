<?php

namespace App\Helpers\Itg;
/*
require_once('classes/crest.php');
require_once('classes/DbQueries.php');
require_once('classes/InitDb.php');
require_once('classes/Actions.php');
*/
class ActionsCrm extends Actions
{
    //
    // Работа с заказами
    //
    /*
       * Функция проверяет заказ на сайте на наличие связанных сделок в crm, при необходимости создаёт
       * @array $orders - массив заказов
       * Функция ничего не возвращает
       */
    public function addAliasesToAllOrders($orders)
    {
        foreach ($orders as $key => $order) {
            $this->createOrderAliasIfNeed($order);
        }
    }
    /*
    * Функция создаёт для аккаунта пользователя связь с контактом в crm
    * @array $order - массив с данными заказа
    * Функция ничего не возвращает
    */
    public function createOrderAliasIfNeed(array $order)
    {
        if (!$this->request->isOrderHasAlias($order['ID'])) {
            $this->request->createOrderAlias($order['ID'],
                $this->createOrderDeal($order)
            );
        }
    }

    /**
     * Функция создаёт сделку в crm
     * @param array $order - массив с заказом
     * @return int функция возвращает номер созданной сделки
     */
    public function createOrderDeal(array $order): int
    {
        sleep(1);
        $response = CRest::call('crm.deal.add', [
                'FIELDS' => [
                    'TITLE' => 'Заказ с сайта №'. $order['ID'],
                    'TYPE_ID' => 3,
                    'CONTACT_ID' => (int) $this->request->isUserHasAlias($order['USER_ID'])['contact_id'],
                ]
            ]
        );
        return $response['result'];
    }

    /**
     * Функция возвращает корзины из заказов
     * @param array $orders - массив заказов
     * @return array - функция возвращает массив корзин, где ключ - номер заказа
     */
    public function getBaskets(array $orders): array
    {
        foreach ($orders as $order) {
            $baskets[$order['ID']] = $this->getProductsFromOrder($order);
        }
        return (array)$baskets;
    }

    /**
     *
     * @param array $order
     * @return mixed
     */
    public function getProductsFromOrder(array $order)
    {
        $basket = \CSaleBasket::GetList(array(), array("ORDER_ID" => $order['ID']));
        while ($arItem = $basket->Fetch()) {
            $bs[] = $arItem;
            //Список товаров в корзине
            $json[] = array(
                'name' => $arItem['NAME'],
                'id' => $arItem['PRODUCT_ID'],
                'price' => $arItem['PRICE'],
                'quantity' => $arItem['QUANTITY']
            );
        }
        return $json;
    }

    /**
     * Функция возращает номер бирки товара в crm по его id на сайте
     * @param int $id - id товара на сайте
     * @return string - код товара в crm
     */
    public function getProductCrm(int $id): string
    {
        $prop_common = \CIBlockElement::GetByID($id);
        $prop = null;
        if ($ar  = $prop_common->Fetch()){
            $result[] = $ar;
        }

        return $result[0]['XML_ID'];
        //return $result[0]['CODE']. ' ' .$result[0]['XML_ID'];
        //return $result;
        //return (isset($x['CML2_LINK']) ? $x['CML2_LINK'][1] : 0);
    }

    /**
     * Функция находит товар в crm по его составному коду
     * @param string $complex_code - составной код
     * @return int - функция возвращает id товара
     */
    public function findProductInCrm(string $complex_code): int
    {
            $result = CRest::call('crm.product.list', [
                    'order' => [
                        'id' => 'ASC',
                    ],
                    'filter' => [
                        'catalog_id' => 27,
                        '%NAME' => $complex_code,
                    ],
                    'select' => [
                        '*',
                    ],
                ]
            )['result'][0]['ID'];

        return (int)$result;
    }

    /**
     * Функция подготавливает массив для добавления товаров в заказы в crm
     * @param array $baskets - массив корзин
     * @return array - функция возвращает массив для замен, где ключ - номер заказа на сайте, значение - список бирок для добавления
     */
    public function makeArrayToAdd(array $baskets): array
    {
        foreach ($baskets as $key => $basket) {
            foreach ($basket as $products) {
                $toAdd[$key][
                    $this->findProductInCrm(
                    $this->getProductCrm($products['id']))
                ] = null;
            }
        }
        return $toAdd;
    }
}