<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddNewCallbackEventTypes20190131123505522557 extends BitrixMigration
{
	private $array = [
		[
			'theme_en' => 'PARTNERSHIP',
			'theme_ru' => 'Сотрудничество',
			'email_to' => 'diamond@alrosa.ru'
		],
		[
			'theme_en' => 'QUESTIONS_ABOUT_THE_ORDER',
			'theme_ru' => 'Вопросы по заказу',
			'email_to' => 'diamond@alrosa.ru'
		],
		[
			'theme_en' => 'ADVERTISING_AND_PR',
			'theme_ru' => 'Реклама и PR',
			'email_to' => 'smi@alrosa.ru'
		]
	];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
    	foreach ($this->array as $item) {
    		$resultType = EventTypeTable::add([
	            'fields' => [
	                'LID' => 'ru',
	                'EVENT_NAME' => 'CALLBACK_FORM_ADD_' . $item['theme_en'],
	                'NAME' => 'Добавлена заявка через форму обратной связи (' . $item['theme_ru'] . ')',
	                'DESCRIPTION' => "#REQUEST_URL# - Ссылка на заявку  в административной части сайта\n" .
	                "#THEME# - Тема\n" .
	                "#COMPANY_NAME# - Название компании\n" .
	                "#EMAIL# - Email\n" .
	                "#NAME# - Имя\n" .
	                "#SURNAME# - Фамилия\n" .
	                "#QUESTION# - Вопрос\n"
	            ]
	        ]);
	        if (!$resultType->isSuccess()) {
	            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
	        }

	        $resultMessage = EventMessageTable::add([
	            'fields' => [
	                'EVENT_NAME' => 'CALLBACK_FORM_ADD_' . $item['theme_en'],
	                'LID' => 's1',
	                'LANGUAGE_ID' => 'ru',
	                'SITE_TEMPLATE_ID' => '',
	                'ACTIVE' => 'Y',
	                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
	                'EMAIL_TO' => $item['email_to'],
	                'CC' => 'TkachevaAM@alrosa.ru',
	                'SUBJECT' => 'Добавлена заявка через форму обратной связи (' . $item['theme_ru'] . ')',
	                'BODY_TYPE' => 'html',
	                'MESSAGE' =>
	                "<h2>Добавлена заявка через форму обратной связи</h2>\n" .
	                "<hr />\n" .
	                "<p>Тема: #THEME#</p>\n" .
	                "<p>Название компании: #COMPANY_NAME#</p>\n" .
	                "<p>Email: #EMAIL#</p>\n" .
	                "<p>Имя: #NAME#</p>\n" .
	                "<p>Фамилия: #SURNAME#</p>\n" .
	                "<p>Вопрос: #QUESTION#</p>\n" .
	                "<p><a href='#REQUEST_URL#' target='_blank'>Заявка в административной части сайта</a></p>\n"
	            ],
	        ]);

	        if (!$resultMessage->isSuccess()) {
	            return;
	        }

	        $listSite = ['s1', 's2', 's3'];
	        $id = $resultMessage->getId();
	        foreach ($listSite as $siteID) {
	            EventMessageSiteTable::add([
	                'fields' => [
	                    'EVENT_MESSAGE_ID' => $id,
	                    'SITE_ID' => $siteID,
	                ],
	            ]);
	        }
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
    	foreach ($this->array as $item) {
	        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'CALLBACK_FORM_ADD_' . $item['theme_en']]]);
	        while ($type = $res->fetch()) {
	            EventTypeTable::delete($type["ID"]);
	        }

	        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'CALLBACK_FORM_ADD_' . $item['theme_en']]]);
	        while ($type = $res->fetch()) {
	            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
	            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

	            EventMessageTable::delete($type["ID"]);
	        }
	    }
    }
}
