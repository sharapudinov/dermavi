<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\Auction;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания свойства "Интерфейс прикрепления пользователей для отправки приглашений на email"
 * Class AddUsersToNotificateInterfacePropertyToAuctionsIblock20190905131053461335
 */
class AddUsersToNotificateInterfacePropertyToAuctionsIblock20190905131053461335 extends BitrixMigration
{
    /** @var string $interfacePropertyCode - Символьный код нового свойства */
    private $interfacePropertyCode = 'USERS_TO_NOTIFICATE_INTERFACE';

    /** @var array|string[] $propertiesToUpdate - Массив свойств на обновление */
    private $propertiesToUpdate = [
        'USERS_TO_NOTIFICATE' => ['sort' => '515'],
        'USERS_EMAILS_TO_NOTIFICATE' => ['sort' => '516'],
        'VIEWING_TIME_SLOTS_INTERFACE_LINK' => ['sort' => '517'],
        'VIEWING_TIME_SLOTS' => ['sort' => '518'],
        'AUCTION_PREVIEW_LINK' => ['sort' => '519'],
        'AUCTION_NOTIFICATION_MAIL_PREVIEW_LINK' => ['sort' => '520'],
        'NOTIFICATIONS_ABOUT_AUCTION_START_SENT' => ['sort' => '521'],
        'AUCTION_WINNERS' => ['sort' => '522'],
        'VIEWING_REQUESTS' => ['sort' => '523'],
        'REDEFINE_WINNERS' => ['sort' => '524'],
        'AUCTION_WINNERS_NOTIFY_DATE' => ['sort' => '525'],
        'AUCTION_WITH_REBIDDINGS' => ['sort' => '526'],
        'EXCEL_EXPORT' => ['sort' => '527'],
        'NOTIFY_AND_REQUEST_VIEWING_EXCEL_EXPORT' => ['sort' => '528']
    ];

    /** @var Property $property - Экземпляр класса для работы со свойствами */
    private $property;

    /**
     * AddUsersToNotificateInterfacePropertyToAuctionsIblock20190905131053461335 constructor.
     */
    public function __construct()
    {
        $this->property = new Property(Auction::iblockId());
        foreach ($this->propertiesToUpdate as $propertyCode => $propertyInfo) {
            $this->property->addPropertyToQuery($propertyCode);
        }

        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add([
            'CODE' => $this->interfacePropertyCode,
            'NAME' => 'Интерфейс прикрепления пользователей для отправки приглашений на email',
            'SORT' => '515',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'IBLOCK_ID' => Auction::iblockId()
        ]);

        foreach ($this->property->getPropertiesInfo() as $property) {
            (new CIBlockProperty())->Update($property['PROPERTY_ID'], [
                'SORT' => ++$this->propertiesToUpdate[$property['CODE']]['sort']
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
        $this->property->addPropertyToQuery($this->interfacePropertyCode);
        CIBlockProperty::Delete($this->property->getPropertiesInfo()['USERS_TO_NOTIFICATE_INTERFACE']['PROPERTY_ID']);

        foreach ($this->property->getPropertiesInfo() as $property) {
            (new CIBlockProperty())->Update($property['PROPERTY_ID'], [
                'SORT' => $this->propertiesToUpdate[$property['CODE']]['sort']
            ]);
        }
    }
}
