<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового события для письма "Поделиться оправой"
 * Class AddShareFrameMailEvent20200615155917910449
 */
class AddShareFrameMailEvent20200615155917910449 extends BitrixMigration
{
    /** @var string $eventName Символьный код почтового события */
    private $eventName = 'SHARE_FRAME';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|string[] $languages - Массив идентификаторов языковых версий сайта */
        $languages = LanguageHelper::getAvailableLanguages();

        /** @var array|string[] $subjects - Темы писем под разные языки */
        $subjects = [
            'en' => 'Alrosa: Shared frame',
            'ru' => 'Алроса: Предложенная оправа',
            'cn' => 'Alrosa: Shared frame'
        ];

        $sites = [
            'en' => 's1',
            'ru' => 's2',
            'cn' => 's3'
        ];

        foreach ($languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => $this->eventName,
                    'NAME' => 'Поделиться украшением',
                    'DESCRIPTION' => "#EMAIL_FROM# - От кого (email)\n
                                        #NAME_FROM# - От кого (имя)\n
                                        #EMAIL_TO# - Кому (email)\n
                                        #NAME_TO# - Кому (имя)\n
                                        #MESSAGE# - Сообщение\n
                                        #FRAME_ID# - Идентификатор бриллианта"
                ]
            ]);
            if (!$resultType->isSuccess()) {
                throw new MigrationException(
                    'Ошибка при добавлении типа шаблона: '
                    . implode(', ', $resultType->getErrorMessages())
                );
            };

            $resultMessage = EventMessageTable::add([
                'fields' => [
                    'EVENT_NAME' => $this->eventName,
                    'LID' => $sites[$language],
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:share.frame', '', [
                    'name_from' => #NAME_FROM#,
                    'name_to' => #NAME_TO#,
                    'message' => #MESSAGE#,
                    'frame_sku_id' => #FRAME_ID#
                    ])?>"
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => $this->eventName]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => $this->eventName]]);
        while ($type = $res->fetch()) {
            EventMessageTable::delete($type["ID"]);
        }
    }
}
