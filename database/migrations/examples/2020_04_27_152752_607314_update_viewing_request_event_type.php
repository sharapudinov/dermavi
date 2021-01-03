<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;

/**
 * Класс, описывающий миграцию для изменения почтового события "Запросить показ", перемещая содержимое письма в компонент
 * Class UpdateViewingRequestEventType20200427152752607314
 */
class UpdateViewingRequestEventType20200427152752607314 extends BitrixMigration
{
    /**
     * Обновляет письмо
     *
     * @param string $message Тело письма
     *
     * @return void
     */
    private function update(string $message): void
    {
        $eventMessageData = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => 'VIEWING_REQUEST'],
        ])->fetch();

        EventMessageTable::update(
            $eventMessageData['ID'],
            [
                'MESSAGE' => $message,
            ]
        );
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update(
            '<?php EventMessageThemeCompiler::includeComponent(\'email.dispatch:viewing.request\', \'\', [
\'request_id\' => #REQUEST_ID#
])?>'
        );
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(
            'Название компании - #UF_COMPANY_NAME# <br>
Сфера деятельности компании - #UF_COMPANY_ACTIVITY# <br>
ИНН - #UF_TAX_ID# <br>
Страна - #UF_COUNTRY# <br>
<br>
Фамилия - #UF_SURNAME# <br>
Имя - #UF_NAME# <br>
Телефон - #UF_PHONE# <br>
Email - #UF_EMAIL# <br>
<br>
Дата показа - #UF_DATE_OF_VIEWING# <br>
Время показа - #UF_TIME_OF_VIEWING# <br>
Комментарий - #UF_COMMENT# <br>
Запрашиваемые позиции - #UF_URL_DIAMOND# <br>

'
        );
    }
}
