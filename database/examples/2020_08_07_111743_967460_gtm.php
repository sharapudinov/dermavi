<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;
use Sprint\Options\Module;

/** @noinspection PhpUnused */

class Gtm20200807111743967460 extends BitrixMigration
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
            'GOOGLE_TAG_MANAGER_HEAD' => "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N5DCX54');</script>
<!-- End Google Tag Manager -->
",
            'GOOGLE_TAG_MANAGER_BODY' => '<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N5DCX54"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
',
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
