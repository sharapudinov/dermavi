<?php

namespace App\Models\HL;

use App\Helpers\LanguageHelper;
use App\Helpers\SiteHelper;
use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionPB;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPBLot;
use App\Models\Auctions\BaseAuction;
use App\Models\Catalog\Diamond;
use App\Models\User;
use Arrilot\BitrixModels\Models\D7Model;
use CEvent;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Форма "Запросить показ""
 * Class ViewingRequestForm
 * @package App\Models\HL
 */
class ViewingRequestForm extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_viewing_request_form';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Обработчик события модели
     *
     * @return bool|mixed
     */
    protected function onAfterCreate()
    {
        try {
            if (empty($this['UF_URL_DIAMOND'])
                && preg_match('/\/diamonds\/(\d+)/', $_SERVER["HTTP_REFERER"], $match)) {
                global $packet;

                $this['UF_URL_DIAMOND'] = $_SERVER["HTTP_REFERER"];
                $packet = Diamond::getById($match[1]);
            }

            $isPb = strpos($_SERVER["HTTP_REFERER"], 'auctions_pb') !== false;
            $defaultAuctions = strpos($_SERVER["HTTP_REFERER"], 'auctions/') !== false;

            $subject = "";

            switch ($this['UF_TYPE']) {
                case 'auction':
                    $subject .= 'Запрос на просмотр бриллиантов аукциона ';
                    $auction = $isPb ? AuctionPB::getById($this['UF_AUCTION_ID']) : Auction::getById($this['UF_AUCTION_ID']);
                    $subject .= " " . $auction->getName();
                    break;
                case 'auction_lot':
                    $subject .= 'Запрос на просмотр бриллиантов лота ';
                    $auction = $isPb ? AuctionPB::getById($this['UF_AUCTION_ID']) : Auction::getById($this['UF_AUCTION_ID']);
                    $auctionLots = $isPb ? AuctionPBLot::filter(['ID' => $this['UF_AUCTION_LOT_IDS']])->getList() : AuctionLot::filter(['ID' => $this['UF_AUCTION_LOT_IDS']])->getList();
                    $subject .= " " . $auctionLots->pluck('CODE')->implode(', ');
                    break;
                default:
                    $subject .= 'Запрос на просмотр бриллианта ' . $this['UF_DIAMONDS'];
                    break;
            }

            if ($this->isAuction()) {
                $manager = $this->getManager($auction);
                $emailTo = $isPb || $defaultAuctions ? $manager->getEmail() : get_sprint_option('EMAIL_POLISHED_AUCTION');
                $userLanguageInfo = $manager->country ? $manager->country->getCountryLanguageInfo() : ['site_id' => 's1', 'language_id' => 'en'];
            } else {
                $emailTo = get_sprint_option('EMAIL_DIAMONDS');
            }

            if ($isPb || $defaultAuctions) {
                $arFields = [
                    'EMAIL_TO'   => $emailTo,
                    'UF_SUBJECT' => $subject,
                    'REQUEST_ID' => $this->getId(),
                    'UF_NAME'    => $this->getName(),
                    'UF_EMAIL'   => $this->getEmail(),
                    'UF_SURNAME' => $this->getSurname(),
                    'UF_PHONE'   => $this->getPhone(),
                    'UF_COMPANY_NAME' => $this->getCompanyName(),
                    'UF_COUNTRY' => $this->getCountry(),
                    'UF_COMMENT' => $this->getComment(),
                    'UF_URL_DIAMOND' => $_SERVER["HTTP_REFERER"],
                    'LOTS'       => $this['UF_AUCTION_LOT_IDS'] ? 'Коды: ' . implode(',', $this['UF_AUCTION_LOT_IDS']) : ''
                ];
            } else {
                $arFields = [
                    'EMAIL_TO'   => $emailTo,
                    'UF_SUBJECT' => $subject,
                    'REQUEST_ID' => $this->getId(),
                ];
            }

            logger('common')->info('send VIEWING_REQUEST', $arFields);

            CEvent::SendImmediate(
                $isPb || $defaultAuctions ? 'VIEWING_REQUEST_PB' : 'VIEWING_REQUEST',
                $isPb || $defaultAuctions ? $userLanguageInfo['site_id'] : SiteHelper::getSiteIdByLanguageId( LanguageHelper::RUSSIAN_LANGUAGE),
                $arFields,
                'Y',
                '',
                [],
                $isPb || $defaultAuctions ? $userLanguageInfo['language_id']: LanguageHelper::RUSSIAN_LANGUAGE
            );
        } catch (\Exception $e) {
            logger('common')->error($e->getMessage(), $arFields);
            return false;
        }

        return true;
    }

    /**
     * @param BaseAuction $auction
     *
     * @return User
     */
    private function getManager(BaseAuction $auction): User
    {
        $manager = User::getById(LANGUAGE_ID == 'en' ? $auction->getManagerIdEn() : $auction->getManagerIdRu());

        return $manager;
    }

    /**
     * Получает идентификатор заявки
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает имя
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['UF_NAME'];
    }

    /**
     * Возвращает фамилию
     *
     * @return string
     */
    public function getSurname(): string
    {
        return $this['UF_SURNAME'];
    }

    /**
     * Возвращает email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this['UF_EMAIL'];
    }

    /**
     * Возвращает телефон
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this['UF_PHONE'];
    }

    /**
     * Возвращает название компании
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this['UF_COMPANY_NAME'];
    }

    /**
     * Возвращает название страны
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this['UF_COUNTRY'];
    }

    /**
     * Возвращает массив деятельностей компании
     *
     * @return array|null
     */
    public function getCompanyActivity(): ?array
    {
        return $this['UF_COMPANY_ACTIVITY'];
    }

    /**
     * Возвращает ИНН
     *
     * @return string|null
     */
    public function getTaxId(): ?string
    {
        return $this['UF_TAX_ID'];
    }

    /**
     * Возвращает дату просмотра
     *
     * @return string
     */
    public function getDateOfViewing(): string
    {
        return $this['UF_DATE_OF_VIEWING'];
    }

    /**
     * Возвращает время просмотра
     *
     * @return string
     */
    public function getTimeOfViewing(): string
    {
        return $this['UF_TIME_OF_VIEWING'];
    }

    /**
     * Возвращает комментарий пользователя
     *
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this['UF_COMMENT'];
    }

    /**
     * Возвращает массив идентификаторов запрашиваемых бриллиантов
     *
     * @return string
     */
    public function getDiamonds(): string
    {
        return $this['UF_DIAMONDS'];
    }

    /**
     * Возвращает флаг, указывающий на запрос аукционных бриллиантов
     *
     * @return bool
     */
    public function isAuction(): bool
    {
        return $this['UF_IS_AUCTION'];
    }
}
