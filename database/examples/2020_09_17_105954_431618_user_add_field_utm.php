<?php

use App\Table\AuctionUtmLogsTable;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class UserAddFieldUtm20200917105954431618 extends BitrixMigration
{
    const CATEGORY_OPT = 'form';
    const NAME_OPT     = 'user_edit';

    protected $arOptions = [
        0 => [
            'edit1'           => 'Пользователь',
            'DATE_REGISTER'   => 'Дата регистрации',
            'LAST_UPDATE'     => 'Дата обновления',
            'LAST_LOGIN'      => 'Последняя авторизация',
            'ACTIVE'          => 'Активен',
            'TITLE'           => 'Обращение',
            'NAME'            => 'Имя',
            'LAST_NAME'       => 'Фамилия',
            'SECOND_NAME'     => 'Отчество',
            'EMAIL'           => '*E-Mail',
            'LOGIN'           => '*Логин (мин. 3 символа)',
            'PASSWORD'        => '*Новый пароль',
            'XML_ID'          => 'Внешний код',
            'LID'             => 'Сайт по умолчанию для уведомлений',
            'LANGUAGE_ID'     => 'Язык для уведомлений',
            'user_info_event' => 'Оповестить пользователя',
        ],
        1 => [
            'edit2'    => 'Группы',
            'GROUP_ID' => 'Группы пользователя',
        ],
        2 => [
            'edit3'               => 'Личные данные',
            'PERSONAL_PROFESSION' => 'Профессия',
            'PERSONAL_WWW'        => 'WWW-страница',
            'PERSONAL_ICQ'        => 'ICQ',
            'PERSONAL_GENDER'     => 'Пол',
            'PERSONAL_BIRTHDAY'   => 'Дата рождения',
            'PERSONAL_PHOTO'      => 'Фотография',
            'USER_PHONES'         => '--Телефоны',
            'PERSONAL_PHONE'      => 'Телефон',
            'PERSONAL_FAX'        => 'Факс',
            'PERSONAL_MOBILE'     => 'Мобильный',
            'PERSONAL_PAGER'      => 'Пейджер',
            'USER_POST_ADDRESS'   => '--Почтовый адрес',
            'PERSONAL_COUNTRY'    => 'Страна',
            'PERSONAL_STATE'      => 'Область / край',
            'PERSONAL_CITY'       => 'Город',
            'PERSONAL_ZIP'        => 'Почтовый индекс',
            'PERSONAL_STREET'     => 'Улица, дом',
            'PERSONAL_MAILBOX'    => 'Почтовый ящик',
            'PERSONAL_NOTES'      => 'Дополнительные заметки',
        ],

        3 => [
            'edit4'                  => 'Работа',
            'WORK_COMPANY'           => 'Наименование компании',
            'WORK_WWW'               => 'WWW-страница',
            'WORK_DEPARTMENT'        => 'Департамент / Отдел',
            'WORK_POSITION'          => 'Должность',
            'WORK_PROFILE'           => 'Направления деятельности',
            'WORK_LOGO'              => 'Логотип компании',
            'USER_WORK_PHONES'       => '--Телефоны',
            'WORK_PHONE'             => 'Телефон',
            'WORK_FAX'               => 'Факс',
            'WORK_PAGER'             => 'Пейджер',
            'USER_WORK_POST_ADDRESS' => '--Почтовый адрес',
            'WORK_COUNTRY'           => 'Страна',
            'WORK_STATE'             => 'Область / край',
            'WORK_CITY'              => 'Город',
            'WORK_ZIP'               => 'Почтовый индекс',
            'WORK_STREET'            => 'Улица, дом',
            'WORK_MAILBOX'           => 'Почтовый ящик',
            'WORK_NOTES'             => 'Дополнительные заметки',
        ],

        4 => [
            'edit_rating' => 'Рейтинг',
            'RATING_BOX'  => 'Рейтинг',
        ]
        ,
        5 => [
            'edit_blog'       => 'Блог',
            'MODULE_TAB_blog' => 'Блог',
        ],

        6 => [
            'edit_forum'       => 'Форум',
            'MODULE_TAB_forum' => 'Форум',
        ],

        7 => [
            'edit_learning'       => 'Обучение',
            'MODULE_TAB_learning' => 'Обучение',
        ],

        8 => [
            'edit_socialservices'       => 'Битрикс24',
            'MODULE_TAB_socialservices' => 'Битрикс24',
        ],

        9 => [
            'edit10'      => 'Заметки',
            'ADMIN_NOTES' => 'Заметки администратора',
        ],

        10 => [
            'user_fields_tab'      => 'Доп. поля',
            'USER_FIELDS_ADD'      => 'Добавить пользовательское поле',
            'UF_IM_SEARCH'         => 'IM: users can find',
            'UF_QUESTION'          => 'Вопрос при регистрации',
            'UF_USER_ENTITY_TYPE'  => '*Тип лица',
            'UF_COUNTRY'           => 'Страна',
            'UF_COMPANY_ID'        => 'Компания',
            'UF_HASH'              => 'Хэш пользователя',
            'UF_CRM_ID'            => 'CRM id пользователя',
            'UF_USER_NAME_RU'      => 'Имя (рус)',
            'UF_USER_NAME_EN'      => 'Имя (англ)',
            'UF_USER_NAME_CN'      => 'Имя (кит)',
            'UF_USER_SURNAME_RU'   => 'Фамилия (рус)',
            'UF_USER_SURNAME_EN'   => 'Фамилия (англ)',
            'UF_USER_SURNAME_CN'   => 'Фамилия (кит)',
            'UF_USER_MIDDLE_NAME'  => 'Отчество',
            'UF_TAX_NUMBER'        => 'ИНН',
            'UF_PASSPORT_ID'       => 'Идентификатор паспортных данных',
            'UF_REGISTRATION_ID'   => 'Идентификатор адреса регистрации',
            'UF_CAN_BUY'           => 'Возможность участия в аукционах',
            'UF_IS_APPROVING'      => 'Заявка в процессе согласования',
            'UF_CAN_AUCTION'       => 'Может участвовать в аукционах',
            'UF_PURCHASE_UP_100'   => 'Доступна покупка до 100 000',
            'UF_ADD_PHONES'        => 'Дополнительные телефоны',
            'UF_ADD_EMAILS'        => 'Дополнительные email',
            'UF_PERSONAL_MANAGER'  => 'Персональный менеджер',
            'UF_APPEAL'            => 'Обращение',
            'UF_PURCHASE_OVER_100' => 'Доступна покупка более 100 000',
            'UF_CLIENT_PB'         => 'Клиент PB',
            'UF_BANK'              => 'Банк',
        ],
        11 => [
            'cedit1'             => 'UTM',
            'UF_UTM_AUCTION_LOG' => 'Логи utm аукционов',
        ],
    ];

    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {

        AuctionUtmLogsTable::getEntity()->createDbTable();

        $this->addUF(
            [
                'ENTITY_ID'       => 'USER',
                'FIELD_NAME'      => 'UF_UTM_AUCTION_LOG',
                'XML_ID'          => 'UF_UTM_AUCTION_LOG',
                'USER_TYPE_ID'    => 'utm_log_show',
                'MANDATORY'       => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Логи utm аукционов',
                    'en' => 'utm log',
                    'cn' => 'utm log',
                ],
            ]
        );

        $this->addOptions();
    }

    protected function addOptions()
    {
        $result = [];
        foreach ($this->arOptions as $i => $option) {
            $tab = [];
            foreach ($option as $oId => $oTitle) {
                if (empty($tab)) {
                    $tab[] = $oId . '--#--' . $oTitle . '--';
                    continue;
                }
                $tab[] = '--' . $oId . '--#--' . $oTitle . '--';
            }
            $result[] = implode(',', $tab);
        }

        CUserOptions::SetOption(self::CATEGORY_OPT, self::NAME_OPT, ['tabs' => implode(';--', $result) . ';--'], true);
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {

    }
}
