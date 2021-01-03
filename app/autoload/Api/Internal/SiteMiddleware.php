<?php
namespace App\Api\Internal;

use App\Helpers\SiteHelper;
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс для определения сайта для текущего языка при запросах api.
 * Class SiteMiddleware
 * @package App\Api\Internal
 */
class SiteMiddleware
{
    /**
     * Заменяет сайт по умолчанию, если для текущего языка настроен другой.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $next): ResponseInterface
    {
        if (!check_csrf_token($request->getMethod() == 'PUT' ? $request->getParsedBody()['csrf_token'] : null)) {
            logger('api')->error($next->getCallable() . ': Failed to check csrf token');
            return $response->withStatus(401)->withJson(['code' => 401, 'message' => 'Unauthorized']);
        }

        try {
            $siteId = SiteHelper::getSiteIdByCurrentLanguage();
            if ($siteId != SITE_ID) {
                SiteHelper::setSiteId($siteId);
            }
        } catch (\Exception $e) {
            //Nothing
        }
        
        return $next($request, $response);
    }
}
