<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Helpers\TTL;
use App\Models\Catalog\Diamond;
use Slim\Http\Response;

/**
 * Класс-контроллер для работы с бриллиантом
 * Class DiamondController
 * @package App\Api\Internal\Sale
 */
class DiamondController extends BaseController
{
    /**
     * Выполняем поиск пакета бриллиантов
     *
     * @param string $packetId - Идентификатор пакета бриллиантов
     * @return Response
     */
    public function searchDiamond(string $packetId): Response
    {
        /** @var Diamond $packet - Пакет бриллиантов */
        $packet = Diamond::query()->cache(TTL::DAY)->filter(['NAME' => e($packetId)])->first();
        if ($packet) {
            return $this->response->withJson([
                'prefix' => get_language_version_href_prefix(),
                'packetId' => $packet->getPacketNumber(),
            ]);
        } else {
            return $this->respondWithError();
        }
    }
}
