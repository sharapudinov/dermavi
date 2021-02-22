<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

/** @noinspection PhpUnused */

class ChangeSitesEnToEnRuToRoot20200729140022862822 extends BitrixMigration
{
    /**
     * @var CSite
     */
    private $obSite;

    public function __construct()
    {
        parent::__construct();

        $this->obSite = new CSite();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $list = $this->getSites();

        while ($item = $list->Fetch()) {
            if ($item['LID'] === 's1') {
                $this->updateSite(
                    $item['LID'],
                    [
                        'DIR' => '/en/',
                        'DEF' => 'N',
                        'SORT' => '2',
                        'TEMPLATE' => [
                            [
                                'CONDITION' => "CSite::InDir('/en/pdf/')",
                                'SORT' => 1,
                                'TEMPLATE' => 'pdf',
                            ],
                            [
                                'CONDITION' => "CSite::InDir('/en/')",
                                'SORT' => 2,
                                'TEMPLATE' => 'main',
                            ],
                        ],
                    ]
                );
            }

            if ($item['LID'] === 's2') {
                $this->updateSite(
                    $item['LID'],
                    [
                        'DIR' => '/',
                        'DEF' => 'Y',
                        'SORT' => '1',
                        'TEMPLATE' => [
                            [
                                'CONDITION' => '',
                                'SORT' => 1,
                                'TEMPLATE' => 'main',
                            ],
                            [
                                'CONDITION' => "CSite::InDir('/pdf/')",
                                'SORT' => 2,
                                'TEMPLATE' => 'pdf',
                            ],
                        ],
                    ]
                );
            }
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function down()
    {
        $list = $this->getSites();

        while ($item = $list->Fetch()) {
            if ($item['LID'] === 's1') {
                $this->updateSite(
                    $item['LID'],
                    [
                        'DIR' => '/',
                        'DEF' => 'Y',
                        'SORT' => '1',
                        'TEMPLATE' => [
                            [
                                'CONDITION' => '',
                                'SORT' => 1,
                                'TEMPLATE' => 'main',
                            ],
                            [
                                'CONDITION' => "CSite::InDir('/pdf/')",
                                'SORT' => 2,
                                'TEMPLATE' => 'pdf',
                            ],
                        ],
                    ]
                );
            }

            if ($item['LID'] === 's2') {
                $this->updateSite(
                    $item['LID'],
                    [
                        'DIR' => '/ru/',
                        'DEF' => 'N',
                        'SORT' => '2',
                        'TEMPLATE' => [
                            [
                                'CONDITION' => "CSite::InDir('/ru/pdf/')",
                                'SORT' => 1,
                                'TEMPLATE' => 'pdf',
                            ],
                            [
                                'CONDITION' => "CSite::InDir('/ru/')",
                                'SORT' => 2,
                                'TEMPLATE' => 'main',
                            ],
                        ],
                    ]
                );
            }
        }

        return true;
    }

    /** @noinspection PhpUndefinedClassInspection */
    private function getSites(): CDBResult
    {
        $by = '';
        $order = '';
        return CSite::GetList($by, $order);
    }

    /**
     * @param string $lid
     * @param array $arFields
     *
     * @throws MigrationException
     */
    private function updateSite(string $lid, array $arFields): void
    {
        $result = $this->obSite->Update(
            $lid,
            $arFields
        );

        if (!$result) {
            throw new MigrationException(
                sprintf('Unable to update site %s. Error: %s', $lid, $this->obSite->LAST_ERROR)
            );
        }
    }
}
