<?php

namespace App\EventHandlers;

use App\Helpers\SiteHelper;
use App\Helpers\StringHelper;
use App\Models\HL\GetInTouchForm;
use Bitrix\Main\Entity\Event;
use CEvent;

/**
 * Класс-обработчик для обработки событий, связанных с формами обратной связи
 * Class CallbackFormHandlers
 * @package App\EventHandlers
 */
class CallbackFormHandlers
{
    /**
     * Посылаем письмо менеджеру
     *
     * @param Event $event - Событие
     * @return void
     */
    public static function sendEmailToManager(Event $event): void
    {
        /** @var GetInTouchForm $request - Модель формы "Напишите нам" */
        $request = GetInTouchForm::getById($event->getParameter('id'));
        /** @var string $theme - Выбранная тема. По ней определится идентификатор письма, которое надо отправить */
        $theme = StringHelper::changeSpacesToUnderscore(strtoupper($request->getTheme()));
        if (!$theme) {
            $theme = 'QUESTIONS_ABOUT_THE_ORDER';
        }

        CEvent::SendImmediate('CALLBACK_FORM_ADD_' . $theme, SiteHelper::getSiteIdByCurrentLanguage(), [
            'THEME' => $request->getTheme(),
            'COMPANY_NAME' => $request->getCompanyName(),
            'EMAIL' => $request->getEmail(),
            'NAME' => $request->getName(),
            'SURNAME' => $request->getSurname(),
            'QUESTION' => $request->getQuestion(),
            'PHONE' => $request->getPhone(),
            'REQUEST_URL' => get_external_url(false)
                . '/bitrix/admin/highloadblock_row_edit.php?ENTITY_ID=1&ID=' . $event->getParameter('id') . '&lang=ru'
        ], 'Y', '', [], 'ru');
    }
}
