<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для обновления тем писем аукционов
 * Class UpdateAuctionsEmailsSubjects20200325135619850326
 */
class UpdateAuctionsEmailsSubjects20200325135619850326 extends BitrixMigration
{
    /**
     * Обновляет тему письма
     *
     * @param string $eventName Символьный код письма
     * @param array|string[] $subjects Массив тем письма, разделенных по языкам ['ru' => 'Тема', 'en' => 'Subject']
     *
     * @return void
     */
    private function update(string $eventName, array $subjects): void
    {
        $query = EventMessageTable::getList(['filter' => ['EVENT_NAME' => $eventName]]);
        while ($event = $query->fetch()) {
            EventMessageTable::update(
                $event['ID'],
                [
                    'SUBJECT' => $subjects[$event['LANGUAGE_ID']]
                ]
            );
        }
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
            'NOTIFY_USERS_ABOUT_NEW_AUCTION',
            [
                'ru' => 'Аукцион бриллиантов #AUCTION_NAME#',
                'en' => 'Polished Diamonds Auction #AUCTION_NAME#',
                'cn' => 'Polished Diamonds Auction #AUCTION_NAME#'
            ]
        );

        $this->update(
            'AUCTION_24_HOURS_LAST',
            [
                'ru' => 'Напоминание по аукциону бриллиантов #AUCTION_NAME#',
                'en' => 'Polished Diamonds Auction #AUCTION_NAME# Reminder',
                'cn' => 'Polished Diamonds Auction #AUCTION_NAME# Reminder'
            ]
        );

        $this->update(
            'AUCTION_2_HOURS_LAST',
            [
                'ru' => 'Напоминание по аукциону бриллиантов #AUCTION_NAME#',
                'en' => 'Polished Diamonds Auction #AUCTION_NAME# Reminder',
                'cn' => 'Polished Diamonds Auction #AUCTION_NAME# Reminder'
            ]
        );

        $this->update(
            'AUCTION_RESULTS_USER_REBIDDING',
            [
                'ru' => 'Продолжение торгов на аукционе бриллиантов #AUCTION_NAME#',
                'en' => 'Rebidding in the Polished Diamonds Auction #AUCTION_NAME#',
                'cn' => 'Rebidding in the Polished Diamonds Auction #AUCTION_NAME#'
            ]
        );

        $this->update(
            'AUCTION_RESULTS_USER',
            [
                'ru' => 'Аукцион бриллиантов #AUCTION_NAME# завершен',
                'en' => 'Polished Diamonds Auction #AUCTION_NAME# has concluded',
                'cn' => 'Polished Diamonds Auction #AUCTION_NAME# has concluded'
            ]
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
            'NOTIFY_USERS_ABOUT_NEW_AUCTION',
            [
                'ru' => 'Алроса: старт аукциона',
                'en' => 'Alrosa: auction start',
                'cn' => 'Alrosa: auction start'
            ]
        );

        $this->update(
            'AUCTION_24_HOURS_LAST',
            [
                'ru' => 'Аукцион закончится через 24 часа',
                'en' => 'Auction ends in 24 hours',
                'cn' => 'Auction ends in 24 hours'
            ]
        );

        $this->update(
            'AUCTION_2_HOURS_LAST',
            [
                'ru' => 'Аукцион закончится через 2 часа',
                'en' => 'Auction ends in 2 hours',
                'cn' => 'Auction ends in 2 hours'
            ]
        );

        $this->update(
            'AUCTION_RESULTS_USER_REBIDDING',
            [
                'ru' => 'Алроса: Аукцион "#AUCTION_NAME#" закончен с переторжками',
                'en' => 'Alrosa: Auction "#AUCTION_NAME#" concluded with rebiddings',
                'cn' => 'Alrosa: Auction "#AUCTION_NAME#" concluded with rebiddings'
            ]
        );

        $this->update(
            'AUCTION_RESULTS_USER',
            [
                'ru' => 'Алроса: результаты аукциона',
                'en' => 'Alrosa: auction results',
                'cn' => 'Alrosa: auction results'
            ]
        );
    }
}
