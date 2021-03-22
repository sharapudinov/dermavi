<?php

namespace App\Core\Traits\Auction;

use App\Models\Auctions\AuctionRuleFile;
use Illuminate\Support\Collection;

/**
 * Трейт для работы с правилами аукциона
 * Trait AuctionsRulesTrait
 * @package App\Core\Traits
 */
trait AuctionsRulesTrait
{
    /**
     * Получает коллекцию файлов правил проведения аукционов
     *
     * @param Collection &$files - Коллекция файлов с правилами
     * @return void
     */
    protected function setAuctionRulesFiles(Collection &$files): void
    {
        foreach ($files as $file) {
            /** @var AuctionRuleFile $file - Файл правила аукционов */
            $file->setFile();
        }
    }
}
