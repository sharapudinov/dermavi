<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ChangeRobotTxt20200902133720599662 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $fileName = $_SERVER['DOCUMENT_ROOT'] . '/robots.txt';

        if (in_production()) {
            $content = <<<CONFIG
User-agent: *
Disallow: /*index.php$
Disallow: /bitrix/
Disallow: /auth/
Disallow: /personal/
Disallow: /upload/
Disallow: /search/
Disallow: /*/search/
Disallow: /*/slide_show/
Disallow: /*/gallery/*order=*
Disallow: /*?*
Disallow: /*&print=
Disallow: /*register=
Disallow: /*forgot_password=
Disallow: /*change_password=
Disallow: /*login=
Disallow: /*logout=
Disallow: /*auth=
Disallow: /*action=*
Disallow: /*bitrix_*=
Disallow: /*backurl=*
Disallow: /*BACKURL=*
Disallow: /*back_url=*
Disallow: /*BACK_URL=*
Disallow: /*back_url_admin=*
Disallow: /*print_course=Y
Disallow: /*COURSE_ID=
Disallow: /*PAGEN_*
Disallow: /*PAGE_*
Disallow: /*SHOWALL
Disallow: /*show_all=
Disallow: /*/cart/
Disallow: /cart/
Disallow: /*/wishlist/
Disallow: /wishlist/
Disallow: /*clear_cache=
Disallow: /*utm_
Disallow: /*privacy-policy/
Disallow: /*terms-and-conditions/
Allow: /*.js
Allow: /*.css
Allow: /bitrix/*.js
Allow: /bitrix/*.css
Sitemap: https://diamonds.alrosa.ru/sitemap.xml
CONFIG;
        } else {
            $content = <<<CONFIG
User-agent: *
Disallow: /
Disallow: /en/
Disallow: /cn/
CONFIG;
        }

        if (!file_put_contents($fileName, $content))
            throw new MigrationException("Can't create 'robots.txt' file");

        if (!chmod($fileName, 0666))
            throw new MigrationException("Can't set permisions for 'robots.txt' file");

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}
