<?php

use App\Models\Tracing\HL\Subtitle;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Application;

class FillSubtitles20181204001019071961 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $path = realpath(__DIR__ . '/../..');
        $jsonData = file_get_contents($path . "/public/assets/build/json/tracing_subtitles.json");
        $data = json_decode($jsonData, true);

        foreach ($data as $subtitle) {
            $dataSubtitle = [
                'UF_TEXT_EN' => (string) $subtitle['text'],
                'UF_INDEX' => (int) $subtitle['index'],
                'UF_ACTIVE' => 1,
                'UF_SCENARIO_NUMBER' => 1,
                'UF_START_TIME' => (string) $subtitle['start'],
                'UF_END_TIME' => (string) $subtitle['end'],
            ];

            foreach ($this->getParams($subtitle) as $key => $value) {
                if (empty($dataSubtitle['UF_CAUSE_PARAM_NAME'])) {
                    $dataSubtitle['UF_CAUSE_PARAM_NAME'] = $key;
                    $dataSubtitle['UF_CAUSE_PARAM_VALUE'] = $value;
                    continue;
                }
                if (empty($dataSubtitle['UF_ADD_C_PARAM_NAME'])) {
                    $dataSubtitle['UF_ADD_C_PARAM_NAME'] = $key;
                    $dataSubtitle['UF_ADD_C_PARAM_VALUE'] = $value;
                    continue;
                }
            }

            Subtitle::create($dataSubtitle);
        }
    }

    private function getParams(array $subtitle): array
    {
        $sysFields = ['text', 'start', 'end', 'index'];
        $params = [];
        foreach ($subtitle as $name => $value) {
            if (!in_array($name, $sysFields)) {
                if ($value === true) {
                    $value = 'Y';
                }

                if ($value === false) {
                    $value = 'N';
                }
                $params[$name] = $value;
            }
        }

        return $params;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $conn = Application::getConnection();
        $conn->query('truncate `tracing_subtitle`');
    }
}
