<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockType;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class ForCustomersIb20190714172227662688 extends BitrixMigration
{

    const IBLOCK_TYPE_ID = 'for_customers';

    private $sliderElements = [
        [
            'CODE' => 'slide_1',
            'TITLE_RU' => 'Уникальность',
            'DESCRIPTION_RU' => 'Природа создавала алмазы сотни миллионов лет и вряд ли когда-то еще повторит этот опыт. При этом каждый из них уникален, двух одинаковых камней просто не существует в природе. Запасы алмазов на земле постепенно тают, ведь их добыча идет каждый день. Спрос же растет вместе с численностью среднего класса в развивающихся странах. Неповторимость природных бриллиантов является гарантией роста цен на них.',
            'TITLE_EN' => 'Uniqueness',
            'DESCRIPTION_EN' => 'Nature has been creating diamonds for hundreds of millions of years and is unlikely to ever repeat this experience. Moreover, each diamond is unique; two identical gemstones simply do not exist in nature. Diamond stockpiles on the Earth are gradually waning, because they are mined every day. While the demand on them is growing alongside with the size of the middle class in developing countries. The uniqueness of natural diamonds is a guarantee of the rise in their prices.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_1.jpg',
        ],
        [
            'CODE' => 'slide_2',
            'TITLE_RU' => 'История',
            'DESCRIPTION_RU' => 'Компания АЛРОСА — мировой лидер по объему добычи алмазов, которая самостоятельно производит из них готовую продукцию — бриллианты. Благодаря этому мы можем рассказать Вам о них все и гарантировать подлинность происхождения: ведь в процессе производства бриллиант не попадал в чужие руки, его не могли подменить на синтетический или добытый с причинением вреда природе или людям. Вы сможете продолжить историю своего бриллианта после покупки.',
            'TITLE_EN' => 'History',
            'DESCRIPTION_EN' => 'ALROSA is the world leader in diamond mining by volume, independently manufacturing finished products from rough diamonds – cut and polished diamonds. Due to this, we can tell you everything about diamonds and guarantee the authenticity of the origin: the diamond does not fall into the wrong hands during the production process, and it cannot be replaced by a synthetic one or the one mined with harm to nature or people. You can continue the story of your diamond after purchase.',

            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_2.jpg',
        ],
        [
            'CODE' => 'slide_3',
            'TITLE_RU' => 'Стабильность',
            'DESCRIPTION_RU' => 'Как физический актив, ценный во все времена, бриллиант
позволяет избежать валютных рисков, которые присутствуют при вложениях в большинство
финансовых активов. Он может быть куплен в одной валюте, а впоследствии продан в
другой. А меньше места по сравнению со всеми другими альтернативными активами —
предметами искусства, золотом или недвижимостью, занимает только банковская
карта.',
            'TITLE_EN' => 'Stability',
            'DESCRIPTION_EN' => 'As a physical asset valuable at all times, a diamond allows you to avoid currency risks that are present when investing in most financial assets. It can be bought in one currency, and subsequently sold in another. And only a bank card occupies less space than a polished diamond, in comparison with any other alternative assets – art, gold or real estate.
',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_3.jpg',
        ],
        [
            'CODE' => 'slide_4',
            'TITLE_RU' => 'Независимость',
            'DESCRIPTION_RU' => 'Бриллианты слабо коррелируют с другими активами, их цены совершенно не зависят, например, от цен на нефть или золото, от котировок акций компаний или движения фондовых индексов. Это делает их привлекательным инструментом диверсификации инвестиционных рисков. ',
            'TITLE_EN' => 'Independence',
            'DESCRIPTION_EN' => 'Polished diamonds weakly correlate with other assets and are completely independent of, for example, oil or gold prices, stock prices or the movement of stock indices. This makes them an attractive tool for investment risk diversification.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_4.jpg',
        ],
        [
            'CODE' => 'slide_5',
            'TITLE_RU' => 'Наследие',
            'DESCRIPTION_RU' => 'Во всем мире бриллиант — это семейная реликвия и фамильная ценность, передающаяся из поколения в поколение. Во-первых, потому что это удобный инструмент для сохранения семейного капитала и его последующего наследования. Во-вторых, потому что это память, которую люди оставляют своим потомкам.',
            'TITLE_EN' => 'Heritage',
            'DESCRIPTION_EN' => 'All over the world, a polished diamond is a family relic and heirloom handed down from generation to generation. Firstly, because it is a convenient tool for preservation of family capital and its subsequent inheritance. Secondly, because it is a memory that people leave to their descendants.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_5.jpg',
        ],
    ];

    private $infoElements = [
        [
            'CODE' => 'personal_passport',
            'TITLE_RU' => 'Персональный<br>паспорт',
            'DESCRIPTION_RU' => 'История Вашего бриллианта, собранная в уникальный паспорт, может быть дополнена именем бриллианта и личным обращением к близкому человеку, которому предназначен подарок.',
            'TITLE_EN' => 'Personal<br>passport',
            'DESCRIPTION_EN' => 'The history of your diamond, compiled in a unique passport, can be supplemented by the name of the diamond and a personal message to a loved one to whom the gift is intended.',
            'IMAGE' => false,
        ],
        [
            'CODE' => 'gravirovka',
            'TITLE_RU' => 'Гравировка',
            'DESCRIPTION_RU' => 'По Вашему желанию на рундист — боковую часть бриллианта, к которой сходятся грани камня — может быть сделано нанесение инициалов, имени или важной даты. Надпись выполняется с помощью лазера и будет видна под микроскопом. В процессе нанесения гравировки ни цвет, ни чистота бриллианта не изменяются.',
            'TITLE_EN' => 'Engraving',
            'DESCRIPTION_EN' => 'At your request, initials, a name or an important date can be applied on the girdle – the side of the diamond, to which the facets converge. The inscription is done with a laser and is visible under the microscope. In the process of engraving, neither the color nor the clarity of the diamond changes.',
            'IMAGE' => false,
        ],
        [
            'CODE' => 'individual_upakovka',
            'TITLE_RU' => 'Индивидуальная упаковка',
            'DESCRIPTION_RU' => 'Бриллиант будет передан Вам в подарочной упаковке вместе со всеми необходимыми документами: сертификатом и паспортом.',
            'TITLE_EN' => 'Individual packing',
            'DESCRIPTION_EN' => 'The diamond will be given to you in a gift box along with all the necessary documents: a certificate and a passport.',
            'IMAGE' => false,
        ],
        [
            'CODE' => 'certification',
            'TITLE_RU' => 'СЕРТИФИКАЦИЯ',
            'DESCRIPTION_RU' => '<p class="page-for-customers__text">Согласно требованиям российского законодательства, сертификация бриллианта в российской геммологической лаборатории обязательна для осуществления продажи его&nbsp;физическому&nbsp;лицу.</p>
            <p class="page-for-customers__text">Сертификат на бриллиант содержит информацию о его основных параметрах, таких как:</p>
            <ul class="ul-list page-for-customers__ul">
                <li>форма огранки</li>
                <li>масса</li>
                <li>цвет</li>
                <li>чистота</li>
                <li>флуоресценция</li>
            </ul>
            <p class="page-for-customers__text">Кроме того, подлежат определению основные размеры бриллиантов, которыми являются:</p>
            <ul class="ul-list page-for-customers__ul">
                <li>минимальный диаметр;</li>
                <li>максимальный диаметр;</li>
                <li>длина и ширина (для бриллиантов фантазийных форм огранки);</li>
                <li>общая высота;</li>
                <li>размер площадки;</li>
                <li>угол короны и глубина павильона;</li>
                <li>толщина рундиста;</li>
                <li>размер калетты;</li>
                <li>качество симметрии и полировки.</li>
            </ul>',
            'TITLE_EN' => 'CERTIFICATION',
            'DESCRIPTION_EN' => '<p class="page-for-customers__text">According to the requirements of the Russian legislation, the certification of a polished diamond in a Russian gemological laboratory is mandatory for selling it to an individual. </p>
            <p class="page-for-customers__text">The diamond certificate contains information on its main parameters, such as: </p>
            <ul class="ul-list page-for-customers__ul">
                <li>cut</li>
                <li>carat weight</li>
                <li>color</li>
                <li>clarity</li>
                <li>fluorescence</li>
            </ul>
            <p class="page-for-customers__text">In addition, some measurements of a diamond are to be done, which are:</p>
            <ul class="ul-list page-for-customers__ul">
                <li>minimum diameter;</li>
                <li>maximum diameter;</li>
                <li>length and width (for fancy shaped diamonds);</li>
                <li>total height;</li>
                <li>table size;</li>
                <li>crown angle and pavilion depth;</li>
                <li>girdle thickness;</li>
                <li>culet size;</li>
                <li>symmetry and polish grading.</li>
            </ul>',
            'IMAGE' => false,
        ],
        [
            'CODE' => 'upakovka_1',
            'TITLE_RU' => 'Упаковка',
            'DESCRIPTION_RU' => 'Сертифицированные в российской лаборатории бриллианты помещаются в прозрачную пластиковую упаковку, которая запаивается таким образом, что при вскрытии упаковки она разрушается.',
            'TITLE_EN' => 'Packaging',
            'DESCRIPTION_EN' => 'Diamonds certified in a Russian laboratory are placed in a transparent plastic package, which is sealed in such a way that it damages at the moment of opening.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_6.jpg',
        ],
        [
            'CODE' => 'upakovka_2',
            'TITLE_RU' => 'ПРОЗРАЧНЫЙ ПЛАСТИКОВЫЙ БЛИСТЕР',
            'DESCRIPTION_RU' => 'На оттиске после запайки изображен знак соответствия Системы сертификации ограненных драгоценных камней. Внутри упаковки вставлена этикетка с названием геммологической лаборатории, знаком соответствия Системы, основными характеристиками бриллианта и номером сертификата.',
            'TITLE_EN' => 'Transparent plastic blister',
            'DESCRIPTION_EN' => 'The mark of conformance with the Gemstone Certification System is depicted above sealing. A label with the name of the gemological laboratory, the System\'s conformance mark, the main parameters of the diamond and the certificate number is inserted inside the package. ',
            'IMAGE' => false,
        ],
        [
            'CODE' => 'russian_certificate',
            'TITLE_RU' => 'РОССИЙСКИЙ СЕРТИФИКАТ',
            'DESCRIPTION_RU' => 'Бланк сертификата напечатан на гербовой бумаге формата А4 водяными знаками и другими дополнительными элементами защиты. Заполненный бланк сертификата ламинируется. В сертификате также приведен детальный эскиз бриллианта с указанием всех его внутренних и внешних дефектов.',
            'TITLE_EN' => 'Russian certificate',
            'DESCRIPTION_EN' => 'Diamond conformity certificate
The certificate form is printed on A4 stamped paper with watermarks and other additional security features. The filled in certificate form is laminated.
The certificate also contains a detailed schematic of a polished diamond with all its internal and external inclusions/blemishes.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_7.jpg',
        ],
        [
            'CODE' => 'gia_certificate',
            'TITLE_RU' => 'СЕРТИФИКАТ GIA',
            'DESCRIPTION_RU' => 'По Вашему желанию сертификация также может проводится в международной лаборатории GIA. Сертификаты, выданные на один и тот же бриллиант разными сертификационными центрами мира, могут отличаться. Это связано с особенностью восприятия российской системой западных критериев оценки бриллиантов.',
            'TITLE_EN' => 'GIA certificate',
            'DESCRIPTION_EN' => 'At your request, the diamond can also be certified in GIA international laboratory. Certificates issued for the same diamond by different certification centers of the world may differ. This occurs due to specific comprehension of the Western criteria for polished diamond evaluation in the Russian grading system.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_8.jpg',
        ],
        [
            'CODE' => 'registration',
            'TITLE_RU' => 'Регистрация клиента',
            'DESCRIPTION_RU' => '<div class="page-for-customers__registration-col">
                <h5 class="page-for-customers__registration-title">Простая регистрация</h5>
                <ul class="ul-list">
                    <li>онлайн-оформление заказа до&nbsp;100&nbsp;000&nbsp;рублей;</li>
                    <li>сохранение параметров выбора и истории заказов бриллиантов;</li>
                    <li>консультации с персональным менеджером в режиме онлайн.</li>
                </ul>
            </div>
            <div class="page-for-customers__registration-col">
                <h5 class="page-for-customers__registration-title">Полная регистрация</h5>
                <ul class="ul-list">
                    <li>оперативное оформление покупки на сумму свыше 100 000 рублей;</li>
                    <li>эксклюзивные предложения ассортимента бриллиантов;</li>
                    <li>доступ к участию в электронных аукционах.</li>
                </ul>
            </div>',
            'TITLE_EN' => 'CUSTOMER REGISTRATION',
            'DESCRIPTION_EN' => '<div class="page-for-customers__registration-col">
                <h5 class="page-for-customers__registration-title">Simple registration:</h5>
                <ul class="ul-list">
                    <li>online checkout up to 100,000 rubles;</li>
                    <li>saving selection parameters and diamond order history;</li>
                    <li>consultations with a personal account manager online.</li>
                </ul>
            </div>
            <div class="page-for-customers__registration-col">
                <h5 class="page-for-customers__registration-title">Full registration:</h5>
                <ul class="ul-list">
                    <li>prompt order processing for an amount exceeding 100,000 rubles;</li>
                    <li>exclusive offers of a range of polished diamonds;</li>
                    <li>access to participation in electronic auctions.</li>
                </ul>
            </div>',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_8.jpg',
        ],
        [
            'CODE' => 'dopolnenia',
            'TITLE_RU' => 'ДОПОЛНЕНИЯ',
            'DESCRIPTION_RU' => 'Бриллианты крупных размеров и бриллианты «фантазийных» цветов реализуются на внутренних аукционах в Москве и за рубежом. Права на участие в аукционе предоставляются только при условии подтверждения личности. Процедура регистрации потребует предоставления паспортных данных.',
            'TITLE_EN' => 'Notes:',
            'DESCRIPTION_EN' => 'Large diamonds and fancy-colored diamonds are sold at domestic auctions in Moscow and abroad. The rights to participate in an auction are provided only on condition of the identity authentication. The registration procedure will require the provision of passport data.',
            'IMAGE' => __DIR__ . '/../../public/html/img/page_for_customers/pic_8.jpg',
        ],
    ];

    private $faqElements = [
        [
            'CODE' => 'faq-1',
            'QUESTION_RU' => 'Кто может приобретать бриллианты?',
            'QUESTION_EN' => 'Who can buy polished diamonds?',
            'ANSWER_RU' => 'Покупателем сертифицированных бриллиантов может быть любое физическое лицо, зарегистрированное на территории Российской Федерации или зарубежного государства. ',
            'ANSWER_EN' => 'Any individual registered in the territory of the Russian Federation or a foreign state can become a retail buyer of certified polished diamonds.
',
        ],
        [
            'CODE' => 'faq-2',
            'QUESTION_RU' => 'Какие документы необходимо предоставить для заключения договора на покупку бриллианта?',
            'QUESTION_EN' => 'What documents need to be provided to conclude a contract for the purchase of a diamond?',
            'ANSWER_RU' => ' В случае если стоимость Вашего бриллианта не превышает сумму 100 000 рублей, достаточно предъявить паспорт при оплате его в офисе компании.  Если стоимость превышает 100 000 рублей, пожалуйста, пройдите полную регистрацию в вашем личном кабинете для подготовки договора.
Мы соблюдаем требования Федерального закона Российской Федерации «О противодействии легализации (отмыванию) доходов, полученных преступным путем, и финансированию терроризма» №115-ФЗ.
',
            'ANSWER_EN' => 'In case the value of your diamond does not exceed the amount of 100,000 rubles, it is enough to present a passport when paying at the company\'s office. If the cost exceeds 100,000 rubles, please complete the registration form in your customer account for the contract preparation.
We comply with The Russian Federation Federal Law No. 115-FZ of 7 August 2001 on Countering the Legalisation (Laundering) of Criminally Obtained Incomes and the Financing of Terrorism.',
        ],
        [
            'CODE' => 'faq-3',
            'QUESTION_RU' => 'Как часто обновляется ассортимент на сайте?',
            'QUESTION_EN' => 'How often is the assortment updated on the website?',
            'ANSWER_RU' => 'Ассортимент на сайте обновляется в режиме реального времени.
',
            'ANSWER_EN' => 'The range of products on the website is updated in real time.',
        ],
        [
            'CODE' => 'faq-4',
            'QUESTION_RU' => 'Как осуществляется оплата приобретаемых бриллиантов?',
            'QUESTION_EN' => 'How to pay for purchased diamonds?',
            'ANSWER_RU' => 'Оплата за приобретаемые бриллианты осуществляется банковским переводом по выставленному счету или банковской картой в офисе компании.',
            'ANSWER_EN' => 'Payment for the purchased diamonds can be made by bank transfer against the invoice, or by credit card at the company\'s office.',
        ],
        [
            'CODE' => 'faq-5',
            'QUESTION_RU' => 'Возможен ли обмен или возврат приобретённого бриллианта?',
            'QUESTION_EN' => 'Is it possible to exchange or return the purchased diamond?',
            'ANSWER_RU' => 'Нет, по законодательству Российской Федерации бриллианты не подлежат возврату и обмену.',
            'ANSWER_EN' => 'No, according to the legislation of the Russian Federation, all diamonds are final sale.',
        ],
        [
            'CODE' => 'faq-6',
            'QUESTION_RU' => 'Что делать, если в каталоге не оказалось нужного бриллианта?',
            'QUESTION_EN' => 'What to do if there is no right diamond in the catalog?',
            'ANSWER_RU' => 'Компания АЛРОСА является ведущим производителем бриллиантов в России и реализует широкий спектр бриллиантов всех форм, размерно-весовых групп, цветовых и качественных характеристик. В случае отсутствия желаемого варианта в каталоге Вы можете оставить заявку на помощь в подборе бриллианта менеджером компании или изготовления бриллианта на заказ с дальнейшей его сертификацией в одной из российских или международных лабораторий.',
            'ANSWER_EN' => 'ALROSA is a leading manufacturer of diamonds in Russia selling a wide selection of diamonds of all shapes, size and weight groups, color and quality characteristics. In the absence of the desired option in the catalog, you can submit a request for the company manager assistance in diamond selection or for manufacturing a custom diamond with its further certification in one of Russian or international laboratories.',
        ]
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        try {
            db()->startTransaction();

            $this->addIBType();

            $this->addIB();

            $this->addElements();

            db()->commitTransaction();

        } catch (Exception $e) {

            db()->rollbackTransaction();
            throw $e;

        }
    }

    private function addIBType()
    {
        (new IBlockType())
            ->setId(self::IBLOCK_TYPE_ID)
            ->setSections(true)
            ->setLang('ru', 'Для покупателей')
            ->setLang('en', 'For customers')
            ->setLang('cn', 'For customers')
            ->add();
    }

    private function addIB()
    {

        $this->addIBSlider();

        $this->addIBInfo();

        $this->addIBFAQ();
    }

    private function addIBSlider()
    {
        $iblock = (new IBlock())
            ->constructDefault('Слайдер', \App\Models\ForCustomers\Slider::IBLOCK_CODE, self::IBLOCK_TYPE_ID)
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(500);

        $iblock->fields['LID'] = ['s1', 's2', 's3'];
        $iblock->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $iblock->add();

        $props = [
            [
                "NAME" => "Заголовок (рус)",
                "SORT" => "100",
                "CODE" => "TITLE_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (анг)",
                "SORT" => "100",
                "CODE" => "TITLE_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (кит)",
                "SORT" => "100",
                "CODE" => "TITLE_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (рус)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (англ)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (кит)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение",
                "CODE" => "IMAGE",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    private function addIBInfo()
    {
        $iblock = (new IBlock())
            ->constructDefault('Информация', \App\Models\ForCustomers\Info::IBLOCK_CODE, self::IBLOCK_TYPE_ID)
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(500);

        $iblock->fields['LID'] = ['s1', 's2', 's3'];
        $iblock->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $iblock->add();

        $props = [
            [
                "NAME" => "Заголовок (рус)",
                "SORT" => "100",
                "CODE" => "TITLE_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (анг)",
                "SORT" => "100",
                "CODE" => "TITLE_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (кит)",
                "SORT" => "100",
                "CODE" => "TITLE_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (рус)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (англ)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (кит)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение",
                "CODE" => "IMAGE",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    private function addIBFAQ()
    {
        $iblock = (new IBlock())
            ->constructDefault('Вопрос-Ответ', \App\Models\ForCustomers\FAQ::IBLOCK_CODE, self::IBLOCK_TYPE_ID)
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(500);

        $iblock->fields['LID'] = ['s1', 's2', 's3'];
        $iblock->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $iblock->add();

        $props = [
            [
                "NAME" => "Вопрос (рус)",
                "SORT" => "100",
                "CODE" => "QUESTION_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Вопрос(анг)",
                "SORT" => "100",
                "CODE" => "QUESTION_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Вопрос(кит)",
                "SORT" => "100",
                "CODE" => "QUESTION_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Ответ (рус)",
                "SORT" => "100",
                "CODE" => "ANSWER_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Ответ (англ)",
                "SORT" => "100",
                "CODE" => "ANSWER_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Ответ (кит)",
                "SORT" => "100",
                "CODE" => "ANSWER_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    /**
     * Добавляет элементы всех 3ех ИБ
     */
    private function addElements()
    {
        $this->addSliderElements();
        $this->addInfoElements();
        $this->addFAQElements();
    }

    /**
     * Добавляем элементы слайдера.
     */
    private function addSliderElements()
    {
        $sort = 100;
        foreach ($this->sliderElements as $element) {
            $sort += 100;

            \App\Models\ForCustomers\Slider::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' =>  $element['TITLE_EN'],
                    'TITLE_CN' => '&nbsp;',
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => '&nbsp;', 'TYPE' => 'HTML',]],
                    'IMAGE' => $element['IMAGE'] ? Array(
                        "n0" => Array(
                            "VALUE" => CFile::MakeFileArray($element['IMAGE'])
                        )
                    ) : null,
                ],
            ]);
        }
    }

    private function addInfoElements()
    {
        $sort = 100;
        foreach ($this->infoElements as $element) {
            $sort += 100;

            \App\Models\ForCustomers\Info::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' => $element['TITLE_EN'],
                    'TITLE_CN' => '&nbsp;',
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => '&nbsp;', 'TYPE' => 'HTML',]],
                    'IMAGE' => $element['IMAGE'] ? Array(
                        "n0" => Array(
                            "VALUE" => CFile::MakeFileArray($element['IMAGE'])
                        ),
                    ) : null,
                ],
            ]);
        }
    }

    /**
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     */
    private function addFAQElements()
    {
        $sort = 100;
        foreach ($this->faqElements as $element) {
            $sort += 100;
            \App\Models\ForCustomers\FAQ::create([
                'NAME' => $element['QUESTION_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'QUESTION_RU' => $element['QUESTION_RU'],
                    'QUESTION_EN' => $element['QUESTION_EN'],
                    'ANSWER_RU' => ['VALUE' => ['TEXT' => $element['ANSWER_RU'], 'TYPE' => 'HTML',]],
                    'ANSWER_EN' => ['VALUE' => ['TEXT' => $element['ANSWER_EN'], 'TYPE' => 'HTML',]],
                ],
            ]);

        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //$this->deleteIblockByCode(\App\Models\ForCustomers\FAQ::IBLOCK_CODE);
        //$this->deleteIblockByCode(\App\Models\ForCustomers\Slider::IBLOCK_CODE);
        //$this->deleteIblockByCode(\App\Models\ForCustomers\Info::IBLOCK_CODE);

        (new IBlockType())->delete(self::IBLOCK_TYPE_ID);
    }
}
