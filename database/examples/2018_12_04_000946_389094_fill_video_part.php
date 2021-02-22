<?php

use App\Models\Tracing\HL\VideoPart;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Application;
use CFile;

class FillVideoPart20181204000946389094 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $path = realpath(__DIR__ . '/../..');
        $jsonData = file_get_contents($path . "/public/assets/build/json/tracing_video.json");
        $data = json_decode($jsonData, true);

        foreach ($data as $video) {
            $arFile = CFile::MakeFileArray($video['src']);
            $index = (int) $video['index'];
            $scenarioNumber = 1;

            $dataVideo = [
                'UF_VIDEO_EN' => $arFile,
                'UF_ACTIVE' => 1,
                'UF_INDEX' => $index,
                'UF_SCENARIO_NUMBER' => $scenarioNumber,
            ];

            foreach ($this->getParams($video) as $key => $value) {
                if (empty($dataVideo['UF_CAUSE_PARAM_NAME'])) {
                    $dataVideo['UF_CAUSE_PARAM_NAME'] = $key;
                    $dataVideo['UF_CAUSE_PARAM_VALUE'] = $value;
                    continue;
                }
                if (empty($dataVideo['UF_ADD_C_PARAM_NAME'])) {
                    $dataVideo['UF_ADD_C_PARAM_NAME'] = $key;
                    $dataVideo['UF_ADD_C_PARAM_VALUE'] = $value;
                    continue;
                }
            }

            VideoPart::create($dataVideo);
        }
    }

    private function getParams(array $video): array
    {
        $sysFields = ['src', 'index'];
        $params = [];
        foreach ($video as $name => $value) {
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
        foreach (VideoPart::getList() as $audio) {
            $audio->delete();
        }
        $conn = Application::getConnection();
        $conn->query('truncate `tracing_video_part`');
    }
}
