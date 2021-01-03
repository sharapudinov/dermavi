<?php

namespace App\Helpers\Itg;

class initDb
{
    public function __construct()
    {
        $this->initTimerTable();
        $this->initOrderAliasesTable();
        $this->initUserAliasesTable();
    }

    /*
     * Функция является аналогом миграции, создаёт таблицу для записей последнего времени работы скрипта
     */
    protected function initTimerTable()
    {
        global $DB;
        $tableName = 'my_timer';
        $DB->Query("CREATE TABLE IF NOT EXISTS " . $tableName . "(" .
            "id INT, " .
            "last_time DATETIME" .
            ")"
        );
        // $DB->Query("ALTER TABLE $tableName ADD PRIMARY KEY (id)");
    }

    /*
     * Функция является аналогом миграции, создаёт таблицу для записи соответствий между заказами на сайте и сделками в crm
     * +
     */
    protected function initOrderAliasesTable()
    {
        global $DB;
        $tableName = 'my_order_aliases';
        $DB->Query("CREATE TABLE IF NOT EXISTS $tableName(" .
            "id INT AUTO_INCREMENT PRIMARY KEY, " .
            "order_id INT, " .
            "deal_id INT" .
            ")"
        );
        //  $DB->Query("ALTER TABLE $tableName ADD PRIMARY KEY (id)");
        $DB->Query("ALTER TABLE $tableName ADD UNIQUE KEY (order_id, deal_id)");
    }

    /*
    * Функция является аналогом миграции, создаёт таблицу для записи соответствий между заказами на сайте и сделками в crm
    */
    protected function initUserAliasesTable()
    {
        global $DB;
        $tableName = 'my_user_aliases';
        $DB->Query("CREATE TABLE IF NOT EXISTS $tableName(" .
            "id INT AUTO_INCREMENT PRIMARY KEY, " .
            "user_id INT, " .
            "contact_id INT" .
            ")"
        );
        //$DB->Query("ALTER TABLE $tableName ADD PRIMARY KEY (id) AUTO_INCREMENT");
        //$DB->Query("ALTER TABLE $tableName ADD UNIQUE KEY (user_id, contact_id)");
    }
}