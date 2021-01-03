<?php

use App\Models\Seo\SitemapUrl;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FillHlSitemapUrl20201202125723530678 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $data = [
            [
                'UF_NAME'       => 'Главная',
                'UF_URL'        => '/',
                'UF_PARENT_URL' => '',
                'UF_PRIORITY'   => '1',
                'UF_CHANGEFREQ' => 'daily',
            ],
            [
                'UF_NAME'       => 'О компании',
                'UF_URL'        => '/about/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Путь бриллианта',
                'UF_URL'        => '/about/diamond-story/',
                'UF_PARENT_URL' => '/about/',

            ],
            [
                'UF_NAME'       => 'Русская огранка',
                'UF_URL'        => '/about/russian-cut/',
                'UF_PARENT_URL' => '/about/',
            ],
            [
                'UF_NAME'       => 'Кимберлийский процесс',
                'UF_URL'        => '/about/kimberley-process/',
                'UF_PARENT_URL' => '/about/',
            ],
            [
                'UF_NAME'       => 'Социальная ответственность',
                'UF_URL'        => '/about/social-responsibility/',
                'UF_PARENT_URL' => '/about/',
            ],
            [
                'UF_NAME'       => 'кологическая ответственность',
                'UF_URL'        => '/about/environmental-responsibility/',
                'UF_PARENT_URL' => '/about/',
            ],
            [
                'UF_NAME'       => 'Совет по ответственной практике в ювелирном бизнесе',
                'UF_URL'        => '/about/responsible-jewellery-council/',
                'UF_PARENT_URL' => '/about/',
            ],
            [
                'UF_NAME'       => 'Гарантии',
                'UF_URL'        => '/customer-service/warranty/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Оплата и доставка',
                'UF_URL'        => '/customer-service/payment-and-shipping/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Примерка',
                'UF_URL'        => '/customer-service/try-on/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Сертификация',
                'UF_URL'        => '/customer-service/certification/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Персонализация',
                'UF_URL'        => '/customer-service/personalization/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Вопросы и ответы',
                'UF_URL'        => '/customer-service/questions-and-answers/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Партнерам',
                'UF_URL'        => '/customer-service/for-partners/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Блог',
                'UF_URL'        => '/customer-service/blog/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Задать вопрос',
                'UF_URL'        => '/customer-service/get-in-touch/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Контакты',
                'UF_URL'        => '/customer-service/contacts/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Аукционы',
                'UF_URL'        => '/auctions/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Создать украшение',
                'UF_URL'        => '/create-jewelry/',
                'UF_PARENT_URL' => '',
            ],
            [
                'UF_NAME'       => 'Ювелирные украшения с бриллиантами',
                'UF_URL'        => '/jewelry/',
                'UF_PARENT_URL' => '',
                'UF_PRIORITY'   => '0.9',
                'UF_CHANGEFREQ' => 'daily',
            ],
            [
                'UF_NAME'       => 'Все бриллианты',
                'UF_URL'        => '/diamonds/',
                'UF_PARENT_URL' => '',
                'UF_PRIORITY'   => '0.9',
                'UF_CHANGEFREQ' => 'daily',
            ],
        ];

        $sort = 1;
        foreach ($data as $i => $item) {
            $item['UF_SORT'] = $sort;

            if(!$item['UF_PRIORITY']) {
                $item['UF_PRIORITY'] = 0.7;
            }

            if(!$item['UF_CHANGEFREQ']) {
                $item['UF_CHANGEFREQ'] = 'monthly';
            }

            SitemapUrl::create($item);
            $sort += 10;
        }
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}
