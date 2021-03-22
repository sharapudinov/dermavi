<?php

namespace App\Core\Traits\DiamondPacket;

use App\Models\Catalog\HL\DiamondPacket;
use App\Models\Catalog\HL\PacketAdditionalInfo;

/**
 * Trait ImportDiamondPacketCleanTrait
 * Трейт удаления записей из таблиц diamond_packet (HL: DiamondPacket) и packet_additional_info (HL: PacketAdditionalInfo).
 * Для импорта каталога бриллиантов.
 *
 * @package App\Core\Traits\DiamondPacket
 */
trait ImportDiamondPacketCleanTrait
{
    /**
     * @param array $stockIdList
     * @return array
     */
    protected function cleanDiamondPackets(array $stockIdList = []): array
    {
        $stoneGUIDs = [];

        $filter = [
            '=UF_IS_AUCTION' => 0,
        ];
        if ($stockIdList) {
            $filter['=UF_STOCK_ID'] = $stockIdList;
        }

        foreach (DiamondPacket::filter($filter)->getList() as $packet) {
            $stoneGuid = $packet->getStoneGUID();
            if ($stoneGuid !== '') {
                $stoneGUIDs[] = $stoneGuid;
            }
            $packet->delete();
        }

        if ($stoneGUIDs) {
            foreach (PacketAdditionalInfo::filter(['UF_IS_AUCTION' => 0])->getList() as $info) {
                if (in_array($info->getStoneGUID(), $stoneGUIDs, false)) {
                    $info->delete();
                }
            }
        }

        return $stoneGUIDs;
    }
}
