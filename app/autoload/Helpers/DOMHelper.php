<?php

namespace App\Helpers;

use DOMDocument;
use Soundasleep\Html2Text;

/**
 * Класс-хелпер для работы с датой/временем
 * Class DOMHelper
 * @package App\Helpers
 */
class DOMHelper
{
    /**
     * Возвращает текст из HTML без тэгов
     *
     * @param DOMDocument $dom
     * @param string $tag
     * @return string
     * @throws
     */
    public static function getTextByTag(\DOMDocument $dom, string $tag): string
    {
        $string = $dom->saveHTML($dom->getElementsByTagName($tag)->item(0));

        return Html2Text::convert($string, ['drop_links'=>true]);
    }

    /**
     * Возвращает DOM страницы
     *
     * @param $url - URL
     * @param $httpBasicUser - логин для HTTP Basic Auth
     * @param $httpBasicPwd - пароль для HTTP Basic Auth
     *
     * @return DOMDocument|null
     */
    public static function loadDomFromUrl($url, $httpBasicUser = null, $httpBasicPwd = null): ?DOMDocument
    {

        $dom = (new DOMDocument());
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($httpBasicUser) && !empty($httpBasicPwd)) {
            curl_setopt($ch, CURLOPT_USERPWD, $httpBasicUser . ":" . $httpBasicPwd);
        }

        $content = curl_exec($ch);
        curl_close($ch);
        $dom->loadHTML($content);
        return $dom;
    }

    public static function getEmailTagLink(string $email){
        return "<a href='mailto:$email'>$email</a>";
    }
}
