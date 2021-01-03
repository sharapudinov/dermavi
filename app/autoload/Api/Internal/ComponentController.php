<?php

namespace App\Api\Internal;

use App\Api\BaseController;
use Bitrix\Main\Diag\Debug;

class ComponentController extends BaseController
{
    /**
     * Список компонентов которые разрешено запрашивать через данный контроллер.
     *
     * @var array
     */
    protected $whiteList = [
        'admin:article.create',
        'admin:auction.lot.diamonds.choose',
        'auction:auction.detail',
        'auction_pb:auction.detail',
        'auction:auction.lot.quickview',
        'auction_pb:auction.lot.quickview',
        'blog:list',
        'catalog:cart',
        'catalog:detail.filter',
        'catalog:main.filter',
        'catalog:product.list',
        'catalog:product.top',
        'jewelry:assistant.result',
        'jewelry:product.detail',
        'jewelry:product.list',
        'jewelry.constructor:assistant.result',
        'jewelry.constructor:diamonds.complects.list',
        'jewelry.constructor:diamonds.list',
        'jewelry.constructor:frames.list',
        'jewelry.constructor:frame.detail',
        'jewelry.constructor:ready.products.list',
        'main:pagination',
        'user:diamond.order',
        'popup:auction.bid.popup',
        'popup:auction_pb.bid.popup',
        'popup:cart.add.popup',
        'popup:request.list',
        'popup:request_pb.list',
        'popup:request.viewing.auctions',
        'popup:request_pb.viewing.auctions',
        'sale:cart',
        'search:diamonds.result',
        'search:articles.result',
        'search:jewelry.result',
        'search:static.result',
        'user:order.history',
        'user:personal.info.form'
    ];

    /**
     * Динамическое подключение битриксовых компонентов.
     *
     * @param string $name
     * @param string $template
     * @return mixed
     */
    public function show($name, $template = '')
    {
        global $APPLICATION;

        $template = $template ?: '.default';

        $params = !empty($_REQUEST['params']) ? $_REQUEST['params'] : [];

        if(empty($params)){
            $content=file_get_contents('php://input');

            $params=json_decode($content,true)['params'];
            if($params) $json_content=true;
        }

        if (!in_array($name, $this->whiteList)) {
            return $this->errorForbidden("Component '{$name}' is not whitelisted");
        }
        ob_start();
        $APPLICATION->IncludeComponent($name, $template, $params);
        $content = ob_get_clean();

        return !empty($_GET['content_type']) && $_GET['content_type'] === 'json' || $json_content
        ? $this->response->write($content)->withHeader('Content-type', 'application/json;charset=utf-8')
        : $this->response->write($content);
    }
}
