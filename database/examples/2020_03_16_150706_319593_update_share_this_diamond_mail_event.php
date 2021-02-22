<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для добавления языковых версий письму "Поделиться бриллиантом (SHARE_DIAMOND)"
 * Class UpdateShareThisDiamondMailEvent20200316150706319593
 */
class UpdateShareThisDiamondMailEvent20200316150706319593 extends BitrixMigration
{
    /** @var string Символьный код типа почтового события */
    private const EVENT_NAME = 'SHARE_DIAMOND';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $res = EventMessageTable::getList(['filter' => ['EVENT_NAME' => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(['filter' => ['EVENT_MESSAGE_ID' => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }

        foreach (LanguageHelper::getAvailableLanguages() as $siteId => $language) {
            $resultMessage = EventMessageTable::add([
                'fields' => [
                    'EVENT_NAME' => self::EVENT_NAME,
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => 'Alrosa: share diamond',
                    'BODY_TYPE' => 'html',
                    'MESSAGE' =>
                        '<?php EventMessageThemeCompiler::includeComponent(\'email.dispatch:share.diamond\', \'\', [
\'name_from\' => #NAME_FROM#,
\'name_to\' => #NAME_TO#,
\'message\' => #MESSAGE#,
\'diamond_id\' => #DIAMOND_ID#
])?>'
                ],
            ]);

            EventMessageSiteTable::add([
                'fields' => [
                    'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                    'SITE_ID' => $siteId,
                ],
            ]);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
    }
}
