<?php

use App\Models\Tracing\HL\Caption;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Application;

class FillCaption20181204001053047410 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $path = realpath(__DIR__ . '/../..');
        $jsonData = file_get_contents($path . "/public/assets/build/json/tracing_captions.json");
        $data = json_decode($jsonData, true);

        foreach ($data as $i => $caption) {
            Caption::create([
                'UF_TEXT_EN' => '',
                'UF_NAME' => (string) $caption['name'],
                'UF_INDEX' => $i,
                'UF_ACTIVE' => 1,
                'UF_SCENARIO_NUMBER' => 1,
                'UF_START_TIME' => (string) $caption['start'],
                'UF_END_TIME' => (string) $caption['end'],
            ]);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $conn = Application::getConnection();
        $conn->query('truncate `tracing_caption`');
    }
}
