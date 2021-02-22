<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;
use Sprint\Options\Module;

class Gtag20201016125200064266 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function up()
    {
        if (!in_production()) {
            return true;
        }

        Loader::includeModule('sprint.options');

        /** @noinspection UnknownInspectionInspection */
        /** @noinspection ES6ConvertVarToLetConst */
        /** @noinspection EqualityComparisonWithCoercionJS */
        $data = [
            'GLOBAL_SITE_TAG_ID' => 'AW-575956687',
            'GLOBAL_SITE_TAG' => "<!-- Global site tag (gtag.js) - Google Ads: 575956687 -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-575956687\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-575956687');
</script>
",
        ];

        foreach ($data as $key => $value) {
            Module::setDbOption($key, $value);
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function down()
    {
        return true;
    }
}
