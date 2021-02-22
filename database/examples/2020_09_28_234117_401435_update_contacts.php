<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateContacts20200928234117401435 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $office = Office::filter(['NAME' => 'Jewelry Smolensk'])->first();
        (new CIBlockProperty)->Add([
            'NAME' => 'Email для юр лиц',
            'CODE' => 'EMAIL_FOR_LEGAL',
            'SORT' => '517',
            'IBLOCK_ID' => Office::iblockId()
        ]);
        (new CIBlockProperty)->Add([
            'NAME' => 'Email для связи',
            'CODE' => 'EMAIL_FOR_LINE',
            'SORT' => '518',
            'IBLOCK_ID' => Office::iblockId()
        ]);
        (new CIBlockProperty)->Add([
            'NAME' => 'Телефон для связи',
            'CODE' => 'PHONE_FOR_LINE',
            'SORT' => '519',
            'IBLOCK_ID' => Office::iblockId()
        ]);
        $office->update(
            [
                'PROPERTY_EMAIL_FOR_CLIENTS_VALUE' => 'DIAMOND@ALROSA.RU',
                'PROPERTY_EMAIL_FOR_LEGAL_VALUE' => 'polished-sales@alrosa.ru',
                'PROPERTY_PHONE_FOR_COMMUNICATIONS_VALUE' => '+7 (495) 620-92-50',
                'PROPERTY_CITY_RU_VALUE' => 'Москва',
                'PROPERTY_CITY_EN_VALUE' => 'Moscow',
                'PROPERTY_CITY_CN_VALUE' => 'Moscow',
                'PROPERTY_NAME_RU_VALUE' => '«БРИЛЛИАНТЫ АЛРОСА», Главный офис',
                'PROPERTY_NAME_EN_VALUE' => 'DIAMONDS ALROSA, Head office',
                'PROPERTY_NAME_CN_VALUE' => 'DIAMONDS ALROSA, Head office',
                'PROPERTY_EMAIL_FOR_LINE_VALUE' => 'brilliance@alrosa.ru',
                'PROPERTY_PHONE_FOR_LINE_VALUE' => '+7 (495) 777-09-41',
                'PROPERTY_FAX_VALUE' => '+7 (495) 777-10-80',
                'PROPERTY_ADDRESS_RU_VALUE' => 'ул. Смольная, 12, Москва, 125493',
                'PROPERTY_ADDRESS_EN_VALUE' => 'Smolnaya St., 125493, Moscow',
                'PROPERTY_ADDRESS_CN_VALUE' => 'Smolnaya St., 125493, Moscow',
            ]
        );

        $officeMoscow = Office::filter(['NAME' => 'Магазин в Москве'])->first();
        $officeMoscow->update(
            [
                'PROPERTY_NAME_RU_VALUE' => '«БРИЛЛИАНТЫ АЛРОСА», Отдел прямых продаж (Москва)',
                'PROPERTY_NAME_EN_VALUE' => 'DIAMONDS ALROSA, Sales department (Moscow)',
                'PROPERTY_NAME_CN_VALUE' => 'DIAMONDS ALROSA, Sales department (Moscow)',
                'PROPERTY_PHONE_FOR_CLIENTS_VALUE' => ['+7 (495) 777-10-87'],
                'PROPERTY_FAX_VALUE' => '+7 (495) 777-10-80',
                'PROPERTY_EMAIL_FOR_LINE_VALUE' => 'polished-sales@alrosa.ru',
                'PROPERTY_ADDRESS_RU_VALUE' => 'ул. Смольная, 12, Москва, 125493',
                'PROPERTY_ADDRESS_EN_VALUE' => 'Smolnaya St., 125493, Moscow',
                'PROPERTY_ADDRESS_CN_VALUE' => 'Smolnaya St., 125493, Moscow',
            ]
        );

        $officePiter = Office::filter(['NAME' => 'Магазин в Санкт-Петербурге'])->first();
        $officePiter->update(
            [
                'PROPERTY_NAME_RU_VALUE' => '«БРИЛЛИАНТЫ АЛРОСА», Отдел тендерных продаж',
                'PROPERTY_NAME_EN_VALUE' => 'DIAMONDS ALROSA, Auction department',
                'PROPERTY_NAME_CN_VALUE' => 'DIAMONDS ALROSA, Auction department',
                'PROPERTY_PHONE_FOR_CLIENTS_VALUE' => ['+7 (495) 777-29-69','+7 (495) 777-09-46'],
                'PROPERTY_FAX_VALUE' => '+7 (495) 777-10-80',
                'PROPERTY_EMAIL_FOR_LINE_VALUE' => 'polished-sales@alrosa.ru',
                'PROPERTY_ADDRESS_RU_VALUE' => 'ул. Смольная, 12, Москва, 125493',
                'PROPERTY_ADDRESS_EN_VALUE' => 'Smolnaya St., 125493, Moscow',
                'PROPERTY_ADDRESS_CN_VALUE' => 'Smolnaya St., 125493, Moscow',
                'PROPERTY_CITY_RU_VALUE' => 'Москва',
                'PROPERTY_CITY_EN_VALUE' => 'Moscow',
                'PROPERTY_CITY_CN_VALUE' => 'Moscow',
            ]
        );

        $officePiter = Office::filter(['NAME' => 'Магазин в Смоленске'])->first();
        $officePiter->update(
            [
                'PROPERTY_NAME_RU_VALUE' => '«БРИЛЛИАНТЫ АЛРОСА», Отдел прямых продаж (Смоленск)',
                'PROPERTY_NAME_EN_VALUE' => 'DIAMONDS ALROSA, Sales department (Smolensk)',
                'PROPERTY_NAME_CN_VALUE' => 'DIAMONDS ALROSA, Sales department (Smolensk)',
                'PROPERTY_PHONE_FOR_CLIENTS_VALUE' => ['+7 (4812) 200-139'],
                'PROPERTY_FAX_VALUE' => '+7 (495) 777-10-80',
                'PROPERTY_EMAIL_FOR_LINE_VALUE' => 'polished-sales@alrosa.ru',
                'PROPERTY_ADDRESS_RU_VALUE' => 'ул. Шкадова, 2, Смоленск, 214031',
                'PROPERTY_ADDRESS_EN_VALUE' => '2 Shkadov St., 214031, Smolensk',
                'PROPERTY_ADDRESS_CN_VALUE' => '2 Shkadov St., 214031, Smolensk',
                'PROPERTY_CITY_RU_VALUE' => 'Смоленск',
                'PROPERTY_CITY_EN_VALUE' => 'Smolensk',
                'PROPERTY_CITY_CN_VALUE' => 'Smolensk',
            ]
        );

        $officeSmolenskTwo = Office::filter(['NAME' => 'Магазин в Смоленске_2'])->first();
        $officeSmolenskTwo->update(['ACTIVE' => 'N']);

        $officeVoronej = Office::filter(['NAME' => 'Магазин в Воронеже'])->first();
        $officeVoronej->update(['ACTIVE' => 'N']);

        $officeKursk = Office::filter(['NAME' => 'Магазин в Курске'])->first();
        $officeKursk->update(['ACTIVE' => 'N']);

        $officeMoscowTwo = Office::filter(['NAME' => 'Moscow Office'])->first();
        $officeMoscowTwo->update(['ACTIVE' => 'N']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}
