<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddChangeStatusUserEmail20200129175939356262 extends BitrixMigration
{
    const EVENT_NAME = 'USER_CHANGE_STATUS';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|string[] $languages - Массив идентификаторов языковых версий сайта */
        $languages = \App\Helpers\LanguageHelper::getAvailableLanguages();

        /** @var array|string[] $subjects - Темы писем под разные языки */
        $subjects = [
            'en' => 'Alrosa: Profile status',
            'ru' => 'Алроса: Cтатус профиля',
            'cn' => 'Alrosa: Profile status',
        ];

        foreach ($languages as $siteId => $language) {
            $resultMessage = \Bitrix\Main\Mail\Internal\EventMessageTable::add([
                'fields' => [
                    'EVENT_NAME'       => self::EVENT_NAME,
                    'LID'              => $siteId,
                    'LANGUAGE_ID'      => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE'           => 'Y',
                    'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO'         => '#EMAIL_TO#',
                    'CC'               => '',
                    'SUBJECT'          => $subjects[$language],
                    'BODY_TYPE'        => 'html',
                    'MESSAGE'          => "<?php 
EventMessageThemeCompiler::includeComponent(
    'email.dispatch:user.change.status', 
    '', 
    [
        'user_id' => #USER_ID#,
    ]);
?>",
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
        $CDBResult = CEventMessage::GetList($by, $order, ["EVENT_NAME" => self::EVENT_NAME]);
        while ($item = $CDBResult->Fetch()) {
            CEventMessage::Delete($item["ID"]);
        }
    }
}
