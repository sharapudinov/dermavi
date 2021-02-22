<?php

use App\Models\Tracing\HL\Chapter;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Application;

class FillChapters20181204001028711873 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $path = realpath(__DIR__ . '/../..');
        $jsonData = file_get_contents($path . "/public/assets/build/json/tracing_chapters.json");
        $data = json_decode($jsonData, true);

        foreach ($data as $i => $chapter) {
            $dataChapter = [
                'UF_CODE' => (string) $chapter['name'],
                'UF_NAME_EN' => ucfirst($chapter['name']),
                'UF_INDEX' => $i,
                'UF_ACTIVE' => 1,
                'UF_SCENARIO_NUMBER' => 1,
                'UF_CAUSE_PARAM_NAME' => '',
                'UF_CAUSE_PARAM_VALUE' => '',
                'UF_START_TIME' => (string) $chapter['start'],
                'UF_END_TIME' => (string) $chapter['end'],
                'UF_VIDEO_INDEX' => (int) $chapter['videoIndex'],
                'UF_VIDEO_START' => (string) $chapter['videoStart'],
                'UF_AUDIO_INDEX' => (int) $chapter['audioIndex'],
                'UF_AUDIO_START' => (string) $chapter['audioStart'],
            ];

            foreach ($this->getParams($chapter) as $key => $value) {
                if (empty($dataChapter['UF_CAUSE_PARAM_NAME'])) {
                    $dataChapter['UF_CAUSE_PARAM_NAME'] = $key;
                    $dataChapter['UF_CAUSE_PARAM_VALUE'] = $value;
                    continue;
                }
            }

            Chapter::create($dataChapter);
        }
    }

    private function getParams(array $chapter): array
    {
        $sysFields = [
            'name',
            'start',
            'end',
            'videoIndex',
            'videoStart',
            'audioIndex',
            'audioStart',
        ];
        $params = [];
        foreach ($chapter as $name => $value) {
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
        $conn->query('truncate `tracing_chapter`');
    }
}
