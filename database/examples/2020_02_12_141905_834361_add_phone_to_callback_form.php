<?php

use App\Models\HL\GetInTouchForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\Constructor;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Helpers;

class AddPhoneToCallbackForm20200212141905834361 extends BitrixMigration
{
    protected $events
        = [
            'CALLBACK_FORM_ADD_PARTNERSHIP',
            'CALLBACK_FORM_ADD_QUESTIONS_ABOUT_THE_ORDER',
            'CALLBACK_FORM_ADD_ADVERTISING_AND_PR',
        ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = Constructor::objHLBlock(Helpers::getHlId(GetInTouchForm::TABLE_CODE));

        (new UserField())->constructDefault($entityId, 'UF_PHONE')
            ->setLangDefault('ru', 'Телефон')
            ->setLangDefault('en', 'Phone')
            ->setLangDefault('cn', '电话号码')
            ->add();

        /** @var string $messageText Текст шаблона почтового */
        $messageText = "<h2>Добавлена заявка через форму обратной связи</h2>
<hr />
<p>Тема: #THEME#</p>
<p>Название компании: #COMPANY_NAME#</p>
<p>Email: #EMAIL#</p>
<p>Имя: #NAME#</p>
<p>Фамилия: #SURNAME#</p>
<p>Вопрос: #QUESTION#</p>
<p>Телефон: #PHONE#</p>
<p><a href='#REQUEST_URL#' target='_blank'>Заявка в административной части сайта</a></p>
";

        $this->updateText($messageText);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $entityId = Constructor::objHLBlock(Helpers::getHlId(GetInTouchForm::TABLE_CODE));

        if (!$entityId) {
            throw new Exception('Cant find entity');
        }
        /** @var array|mixed[] $property Массив, описывающий пользовательское свойство */
        $property = CUserTypeEntity::GetList([], ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_PHONE'])->Fetch();
        if (!$property) {
            throw new Exception('Cant find property');
        }
        UserField::delete($property['ID']);

        /** @var string $messageText Текст шаблона почтового */
        $messageText = "<h2>Добавлена заявка через форму обратной связи</h2>
<hr />
<p>Тема: #THEME#</p>
<p>Название компании: #COMPANY_NAME#</p>
<p>Email: #EMAIL#</p>
<p>Имя: #NAME#</p>
<p>Фамилия: #SURNAME#</p>
<p><a href='#REQUEST_URL#' target='_blank'>Заявка в административной части сайта</a></p>
";

        $this->updateText($messageText);
    }

    /**
     * @param string $messageText
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function updateText(string $messageText): void
    {
        foreach ($this->events as $event) {
            $result = \Bitrix\Main\Mail\Internal\EventMessageTable::getList(['filter' => ['EVENT_NAME' => $event],]);

            while ($eventMessageData = $result->fetch()) {
                \Bitrix\Main\Mail\Internal\EventMessageTable::update($eventMessageData['ID'],
                    [
                        'MESSAGE' => $messageText,
                    ]
                );
            }
        }
    }
}
