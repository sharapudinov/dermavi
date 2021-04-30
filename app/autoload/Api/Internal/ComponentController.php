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
        'dermavi:order.history',
        'dermavi:cart',
        "dermavi:product.list"
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
