<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового события "Регистрация пользователя"
 * Class CreateUserSignupMailEvent20200901151928783562
 */
class CreateUserSignupMailEvent20200901151928783562 extends BitrixMigration
{
    /** @var string Символьный код события */
    private const EVENT_NAME = 'SIGN_UP_USER';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $languages = LanguageHelper::getAvailableLanguages();
        $subjects = [
            'ru' => 'Алроса: регистрация',
            'en' => 'Alrosa: sign up',
            'cn' => 'Alrosa: sign up'
        ];

        foreach ($languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => self::EVENT_NAME,
                    'NAME' => 'Регистрация пользователя',
                    'DESCRIPTION' => '#EMAIL# - Email пользователя\n' .
                        '#PASSWORD# - Пароль пользователя'
                ]
            ]);
            if (!$resultType->isSuccess()) {
                throw new MigrationException(
                    'Ошибка при добавлении типа шаблона: '
                    . implode(', ', $resultType->getErrorMessages())
                );
            }

            $resultMessage = EventMessageTable::add([
                'fields' => [
                    'EVENT_NAME' => self::EVENT_NAME,
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:user.signup', '', [
'email' => #EMAIL#,
'password' => #PASSWORD#
])?>"
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
        $res = EventTypeTable::getList(['filter' => ['EVENT_NAME' => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type['ID']);
        }

        $res = EventMessageTable::getList(['filter' => ['EVENT_NAME' => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(['filter' => ['EVENT_MESSAGE_ID' => $type['ID']]]);
            db()->query(
                'DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type['ID']
            );

            EventMessageTable::delete($type['ID']);
        }
    }
}
