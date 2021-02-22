<?php

use App\Models\Tracing\HL\AudioPart;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Application;
use CFile;

class FillAudioPart20181204000957465305 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $path = realpath(__DIR__ . '/../..');
        $jsonData = file_get_contents($path . "/public/assets/build/json/tracing_audio.json");
        $data = json_decode($jsonData, true);

        foreach ($data as $audio) {
            $arFile = CFile::MakeFileArray($audio['src']);
            $index = (int) $audio['index'];
            $scenarioNumber = 1;

            $dataAudio = [
                'UF_AUDIO_EN' => $arFile,
                'UF_ACTIVE' => 1,
                'UF_INDEX' => $index,
                'UF_SCENARIO_NUMBER' => $scenarioNumber,
            ];

            foreach ($this->getParams($audio) as $key => $value) {
                if (empty($dataAudio['UF_CAUSE_PARAM_NAME'])) {
                    $dataAudio['UF_CAUSE_PARAM_NAME'] = $key;
                    $dataAudio['UF_CAUSE_PARAM_VALUE'] = $value;
                    continue;
                }
                if (empty($dataAudio['UF_ADD_C_PARAM_NAME'])) {
                    $dataAudio['UF_ADD_C_PARAM_NAME'] = $key;
                    $dataAudio['UF_ADD_C_PARAM_VALUE'] = $value;
                    continue;
                }
            }

            AudioPart::create($dataAudio);
        }
    }

    private function getParams(array $audio): array
    {
        $sysFields = ['src', 'index'];
        $params = [];
        foreach ($audio as $name => $value) {
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
        foreach (AudioPart::getList() as $audio) {
            $audio->delete();
        }
        $conn = Application::getConnection();
        $conn->query('truncate `tracing_audio_part`');
    }
}
