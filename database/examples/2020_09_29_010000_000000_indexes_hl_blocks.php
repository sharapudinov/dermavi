<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Application;

/**
 * Class IndexesHlBlocks20200929230000000000
 * Добавление индексов для таблиц hl-блоков
 */
class IndexesHlBlocks20200929010000000000 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $ixPrefix = 'IX_ALRS_';

        $connection = Application::getConnection();

        $table = 'diamond_packet';
        if (!$connection->isIndexExists($table, ['UF_PACKET_GUID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_PACKET_GUID',
                ['UF_PACKET_GUID']
            );
        }
        if (!$connection->isIndexExists($table, ['UF_PACKET_NUMBER'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_PACKET_NUMBER',
                ['UF_PACKET_NUMBER']
            );
        }

        $table = 'dict_color';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'dict_quality';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'stone_location';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'dict_polish';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'dict_persona';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'dict_symmetry';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'catalog_color';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'catalog_shape';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'catalog_clarity';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'catalog_fluorescence';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        $table = 'catalog_fluorescence_color';
        if (!$connection->isIndexExists($table, ['UF_XML_ID'])) {
            $connection->createIndex(
                $table,
                $ixPrefix . 'UF_XML_ID',
                ['UF_XML_ID']
            );
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        return true;
    }
}
