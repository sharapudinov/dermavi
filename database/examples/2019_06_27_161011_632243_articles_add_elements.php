<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\Blog\Article;

class ArticlesAddElements20190627161011632243 extends BitrixMigration
{
    private $gridElements = [
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_TEXT,
            'TITLE' => '',
            'TEXT' => ' <p>
            In the 1960s, computer technology began outpacing the speed of software programming. Computers became faster
            and cheaper, but software development remained slow, difficult to maintain, and prone to errors. This gap,
            and the dilemma of what to do about it, became known as the “software crisis.”
        </p>
        <p>
            In 1968, at the NATO conference on software engineering, Douglas McIlroy presented component-based
            development as a possible solution to the dilemma. Component-based development provided a way to speed up
            programming’s potential by making code reusable, thus making it more efficient and easier to scale. This
            lowered the effort and increased the speed of software development, allowing software to better utilize the
            power of modern computers.
        </p>
        <p>
            Now, 50 years later, we’re experiencing a similar challenge, but this time in design. Design is struggling
            to scale with the applications it supports because design is still bespoke—tailor-made solutions for
            individual problems.
        </p>
        <p>
            Have you ever performed a UI audit and found you’re using a few dozen similar hues of blue, or permutations
            of the same button? Multiply this by every piece of UI in your app, and you begin to realize how
            inconsistent, incomplete, and difficult to maintain your design has become.
        </p>',
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_RIGHT,
            'TITLE' => 'Invisible Features',
            'TEXT' => 'The scale of diamond clarity grades (GIA scale—other labs have added some other interim grades) goes like this (from best to worst)',
            'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_1.jpg',
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_LEFT,
            'TITLE' => 'Invisible Features',
            'TEXT' => 'The scale of diamond clarity grades (GIA scale—other labs have added some other interim grades) goes like this (from best to worst)',
            'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_2.jpg',
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_VIDEO,
            'TITLE' => 'The scale of diamond clarity grades (GIA scale—other labs have added some other interim grades) goes like this (from best to worst)',
            'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/video_preview.jpg',
            'VIDEO_URL' => 'https://www.youtube.com/embed/g8HRqxA0an4',
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_TEXT,
            'TEXT' => '<h3>Shooting Stars</h3>
        <p>
            In the 1960s, computer technology began outpacing the speed of software programming. Computers became faster and cheaper, but software development remained slow, difficult to maintain, and prone to errors. This gap, and the dilemma of what to do about it, became known as the “software crisis.”
        </p>
        <p>
            In 1968, at the NATO conference on software engineering, Douglas McIlroy presented component-based development as a possible solution to the dilemma. Component-based development provided a way to speed up programming’s potential by making code reusable, thus making it more efficient and easier to scale. This lowered the effort and increased the speed of software development, allowing software to better utilize the power of modern computers.
        </p>
        <div>
            For design in this state to keep up with the speed of development, companies could do 1 of 3 things:
        </div>
        <ol class="ol-list">
            <li>Hire more people</li>
            <li>Design faster</li>
            <li>Create solutions that work for multiple problems</li>
        </ol>
        <p>
            Even with more hands working faster, the reality is bespoke design simply doesn’t scale. Bespoke design is slow, inconsistent, and increasingly difficult to maintain over time.
        </p>
        <p>
            Design systems enable teams to build better products faster by making design reusable—reusability makes scale possible. This is the heart and primary value of design systems. <b>A design system is a collection of reusable components, guided by clear standards, that can be assembled together to build any number of applications.</b>
        </p>
        <p>
            For more than 50 years, engineers have operationalized their work. Now it’s time for design to realize its full potential and join them.
        </p>'
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_RIGHT,
            'TITLE' => 'Invisible Features',
            'TEXT' => 'The scale of diamond clarity grades (GIA scale—other labs have added some other interim grades) goes like this (from best to worst)',
            'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_3.jpg',
            'BUTTON_TEXT' => 'MIDDLE BUTTON',
            'BUTTON_URL' => 'https://yandex.ru'
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_LEFT,
            'TITLE' => 'Invisible Features',
            'TEXT' => 'The scale of diamond clarity grades (GIA scale—other labs have added some other interim grades) goes like this (from best to worst)',
            'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_4.jpg',
            'BUTTON_TEXT' => 'MIDDLE BUTTON',
            'BUTTON_URL' => 'https://google.com'
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_VIDEO,
            'TITLE' => 'The scale of diamond clarity grades (GIA scale—other labs have added some other interim grades) goes like this (from best to worst)',
            'BUTTON_TEXT' => 'MIDDLE BUTTON',
            'BUTTON_URL' => 'https://vk.com',
            'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/video_preview.jpg',
            'VIDEO_URL' => 'https://www.youtube.com/embed/g8HRqxA0an4',
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_TIP,
            'TITLE' => 'Buying tip',
            'TEXT' => '
        <p class="page-article__tip-text">
            Your visual language can transcend platforms to create continuity across web, iOS, Android, and email. Document and display your visual language in a prominent place within your design system’s site. This will help inform system contributors about how components should look and behave.
        </p>
        <p class="page-article__tip-text">
            For instance, Google’s Material Design dives deep into every aspect of their visual language. <a href="#">Check out their page on color.</a>
        </p>'
        ],
        [
            'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_TEXT,
            'TEXT' => ' <blockquote>
            In the 1960s, computer technology began outpacing the speed of software programming. Computers became faster and cheaper, but software development remained slow, difficult to maintain, and prone to errors. This gap, and the dilemma of what to do about it, became known as the “software crisis.”
        </blockquote>'
        ]

    ];

    private $source = [
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_1.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'TOP-5 САМЫХ ЗНАМЕНИТЫХ БРИЛЛИАНТОВ МИРА',
            'TEXT' => 'Бриллианты всегда были воплощением богатства и роскоши, служили путеводными талисманами и оберегами. С ними переживали лучшие моменты жизни и ради них шли на смерть. Из-за них начинались войны и с их же помощью заключался мир… Истории самых знаменитых алмазов мира – в нашем обзоре.',
            'gridElements' => [
                [
                    'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_RIGHT,
                    'TITLE' => '1. Великая звезда Африки ',
                    'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_1.jpg',
                    'TEXT' => '<b>Форма огранки: груша</b><br>
<b>Вес: 530,2 карата</b><br>
Самый большой из всех бриллиантов, изготовленных из легендарного алмаза Куллинан, весившего более 3 тысяч карат. До 1990 года являлся самым большим бриллиантом в мире. Несмотря на то, что сегодня он занимает лишь второе место, все же это самый крупный бесцветный бриллиант высочайшей цветовой категории D и крупнейший бриллиант с грушевидной огранкой. Сейчас бриллиант можно увидеть в лондонском Тауэре – он украшает королевский скипетр.',
                ],
                [
                    'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_LEFT,
                    'TITLE' => '2. Золотой юбилей ',
                    'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_1.jpg',
                    'TXT' => '<b>Форма огранки: кушон с элементами огненной розы</b><br>
<b>Вес: 545,67 карата</b><br>
Тот самый фантазийный желто-коричневый бриллиант, сместивший «Великую звезду Африки» с лидирующих позиций. Сегодня «Золотой юбилей» является самым крупным ограненным драгоценным камнем. За счет элементов огранки «огненной розы» бриллиант получил более интенсивный свет и лучшую игру света. Так как камень сравнительно молодой (огранен в 1990 году), то и владельцев у него было совсем немного, а назван он в честь короля Таиланда и сегодня находится в Королевском дворце в Бангкоке как часть сокровищ таиландской короны.',
                ],
                [
                    'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_RIGHT,
                    'TITLE' => '3. Тейлор-Бартон, также известный как Cartier Diamond ',
                    'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_1.jpg',
                    'TEXT' => '<b>Форма огранки: груша </b><br>
<b>Вес: 241 карат</b><br>
Одной из самых знаменитых пар в истории Голливуда были Элизабет Тейлор и Ричард Бертон. За историей их любви пристально следили миллионы людей по всему миру. В Голливуде ходила поговорка, что у Ричарда есть две мании – сходиться и расходиться с Лиз, а также осыпать ее бриллиантами. Среди подаренных Ричардом камней - прекрасный бриллиант грушевидной формы стоимостью более $1 млн. В 1969 году ювелирный дом Cartier выставил его на аукцион. Актер выкупил бриллиант и назвал его «Тейлор-Бертон». После окончательного расставания Элизабет Тейлор продала камень за $3 млн, пожертвовав деньги на строительство больницы для детей-сирот в Ботсване.',
                ],
                [
                    'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_LEFT,
                    'TITLE' => '4. Хоуп ',
                    'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_1.jpg',
                    'TEXT' => '<b>Форма огранки: кушон</b><br>
<b>Вес: 45,52 карата</b><br>
Камень известен своей трагической репутацией, так как приносил своим владельцам одни несчастья. Камень имеет сапфирово-синий цвет и назван в честь своего первого владельца – британца Генри Филиппа Хоупа. В наше время его можно увидеть в Смитсоновском национальном музее естественной истории (США). ',
                ],
                [
                    'SHOW_TYPE' => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_RIGHT,
                    'TITLE' => '5. Орлов',
                    'IMAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/pic_1.jpg',
                    'TEXT' => '<b>Форма огранки: роза</b><br>
<b>Вес: 190 карат</b><br>
Изначально бриллиант находился в Индии и принадлежал династии Великих Моголов, правившей на тот момент страной. В середине 18 века в Индию вторгся Персидский Шах Надир, и камень достался ему. После смерти шаха след бриллианта потерялся. Только спустя годы бриллиант появился в Амстердаме, но уже с другой формой огранки. Граф Орлов выкупил его для Екатерины II, и сегодня камень хранится в Алмазном фонде Московского Кремля.',
                ],
            ],
            'CATEGORY' => 'guides',
            //'SHOW_TYPE' => 'WIDE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_1.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'DIAMOND CUT: SHAPES AND HISTORY',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'guides',
            //'SHOW_TYPE' => 'WIDE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_2.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'SPARKS & FIRE: DIAMONDS CLARITY',
            'TEXT' => 'A diamond’s brilliance is largely determined by its cut. An expertly-cut diamond with perfectly symmetrical and aligned facets will expertly-cut diamond reflect light beautifully, leading to unrivalled brilliance.',
            'CATEGORY' => 'care',
            //'SHOW_TYPE' => 'MEDIUM',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_3.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'HOW TO CHOOSE DIAMOND COLOUR',
            'TEXT' => 'White or colourless diamonds exist on a scale of many different shades, ranging from brilliant white to pale yellow. These subtle differences are graded on a colour scale from D (colourless) to Z (light yellow)',
            'CATEGORY' => 'diamonds',
            //'SHOW_TYPE' => 'MEDIUM',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_4.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'DIAMOND SHAPES',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'guides',
            //  'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_5.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'THE 4CS OF DIAMONDS',
            'TEXT' => 'A diamond’s brilliance is largely determined by its cut. An expertly-cut diamond with perfectly symmetrical and aligned facets will expertly-cut diamond reflect light beautifully, leading to unrivalled brilliance.',
            'CATEGORY' => 'guides',
            // 'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_6.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'ANATOMY OF A DIAMOND: STRUCTURE, INCLUSIONS, POLISH, FLUORESCENCE.',
            'TEXT' => 'White or colourless diamonds exist on a scale of many different shades, ranging from brilliant white to pale yellow. These subtle differences are graded on a colour scale from D (colourless) to Z (light yellow)',
            'CATEGORY' => 'care',
            // 'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_7.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'ADVERTISING ON A BUDGET PART 3 FREQUENCY FREQUENCY',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'education',
            // 'SHOW_TYPE' => 'WIDE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_8.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'WHERE CAN YOU FIND UNIQUE MYSPACE LAYOUTS NOWADAYS',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'guides',
            // 'SHOW_TYPE' => 'WIDE-REVERSE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_9.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'TODAY S ORTHODONTIC TREATMENT COMFORTABLE CONVENIENT',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'education',
            //  'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_10.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'FOREHEAD LIFT BROW LIFT TEMPORAL LIFT FORHEAD REJUVENATION',
            'TEXT' => 'A diamond’s brilliance is largely determined by its cut. An expertly-cut diamond with perfectly symmetrical and aligned facets will expertly-cut diamond reflect light beautifully, leading to unrivalled brilliance.',
            'CATEGORY' => 'education',
            //  'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_11.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'TO KEEP MAKEUP LOOKING FRESH TAKE A POWDER',
            'TEXT' => 'White or colourless diamonds exist on a scale of many different shades, ranging from brilliant white to pale yellow. These subtle differences are graded on a colour scale from D (colourless) to Z (light yellow)',
            'CATEGORY' => 'education',
            // 'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_1.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'DIAMOND CUT: SHAPES AND HISTORY',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'guides',
            //'SHOW_TYPE' => 'WIDE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_2.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'SPARKS & FIRE: DIAMONDS CLARITY',
            'TEXT' => 'A diamond’s brilliance is largely determined by its cut. An expertly-cut diamond with perfectly symmetrical and aligned facets will expertly-cut diamond reflect light beautifully, leading to unrivalled brilliance.',
            'CATEGORY' => 'care',
            //'SHOW_TYPE' => 'MEDIUM',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_3.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'HOW TO CHOOSE DIAMOND COLOUR',
            'TEXT' => 'White or colourless diamonds exist on a scale of many different shades, ranging from brilliant white to pale yellow. These subtle differences are graded on a colour scale from D (colourless) to Z (light yellow)',
            'CATEGORY' => 'diamonds',
            //'SHOW_TYPE' => 'MEDIUM',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_4.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'DIAMOND SHAPES',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'guides',
            //  'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_5.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'THE 4CS OF DIAMONDS',
            'TEXT' => 'A diamond’s brilliance is largely determined by its cut. An expertly-cut diamond with perfectly symmetrical and aligned facets will expertly-cut diamond reflect light beautifully, leading to unrivalled brilliance.',
            'CATEGORY' => 'guides',
            // 'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_6.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'ANATOMY OF A DIAMOND: STRUCTURE, INCLUSIONS, POLISH, FLUORESCENCE.',
            'TEXT' => 'White or colourless diamonds exist on a scale of many different shades, ranging from brilliant white to pale yellow. These subtle differences are graded on a colour scale from D (colourless) to Z (light yellow)',
            'CATEGORY' => 'care',
            // 'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_7.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'ADVERTISING ON A BUDGET PART 3 FREQUENCY FREQUENCY',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'education',
            // 'SHOW_TYPE' => 'WIDE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_8.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'WHERE CAN YOU FIND UNIQUE MYSPACE LAYOUTS NOWADAYS',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'guides',
            // 'SHOW_TYPE' => 'WIDE-REVERSE',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_9.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'TODAY S ORTHODONTIC TREATMENT COMFORTABLE CONVENIENT',
            'TEXT' => 'True expertly-cut diamond expertise is required to create facets with perfect expertly-cut diamond proportions that maximise light and increase a diamond’s symmetrical and aligned signature sparkle.',
            'CATEGORY' => 'education',
            //  'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_10.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'FOREHEAD LIFT BROW LIFT TEMPORAL LIFT FORHEAD REJUVENATION',
            'TEXT' => 'A diamond’s brilliance is largely determined by its cut. An expertly-cut diamond with perfectly symmetrical and aligned facets will expertly-cut diamond reflect light beautifully, leading to unrivalled brilliance.',
            'CATEGORY' => 'education',
            //  'SHOW_TYPE' => 'SMALL',
        ],
        [
            'IMAGE_LIST_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/articles/article_11.jpg',
            'IMAGE_DETAIL_PAGE' => '/var/www/predprod/larionov.alrosa.greensight.ru/public/html/img/page_article/bg.jpg',
            'TITLE' => 'TO KEEP MAKEUP LOOKING FRESH TAKE A POWDER',
            'TEXT' => 'White or colourless diamonds exist on a scale of many different shades, ranging from brilliant white to pale yellow. These subtle differences are graded on a colour scale from D (colourless) to Z (light yellow)',
            'CATEGORY' => 'education',
            // 'SHOW_TYPE' => 'SMALL',
        ],
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->addCategories();

        return;// невыполняить это
        if (in_production()) {
            echo "Тестовые данные пропущены";
            return;
        }

        foreach ($this->source as $i => $article) {
            $this->source[$i]['TITLE'] = ($i + 1) . ' ' . $article['TITLE'];
        }

        // перевернем
        $this->source = array_reverse($this->source);

        $this->addArticles();
    }

    private function addCategories()
    {
        \App\Models\Blog\Category::create([
            'NAME' => 'guides',
            'CODE' => 'guides',
            'ACTIVE' => 'Y',
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'Гиды',
                'TITLE_EN' => 'Guides',
                'TITLE_CN' => 'Guides',
            ]
        ]);
        \App\Models\Blog\Category::create([
            'NAME' => 'diamonds',
            'CODE' => 'diamonds',
            'ACTIVE' => 'Y',
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'Бриллианты',
                'TITLE_EN' => 'Diamonds',
                'TITLE_CN' => 'Diamonds',
            ],
        ]);
        \App\Models\Blog\Category::create([
            'NAME' => 'care',
            'CODE' => 'care',
            'ACTIVE' => 'Y',
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'Забота',
                'TITLE_EN' => 'Care',
                'TITLE_CN' => 'Care',
            ],
        ]);
        \App\Models\Blog\Category::create([
            'NAME' => 'education',
            'CODE' => 'education',
            'ACTIVE' => "Y",
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'Образование',
                'TITLE_EN' => 'Education',
                'TITLE_CN' => 'education'
            ]
        ]);
    }

    private function addArticles(): void
    {
        $sort = 100;
        foreach ($this->source as $row) {

            $sectionId = $this->addGridElements($row);

            $sort++;

            $this->addArticle($row, $sort, $sectionId);
        }
    }

    private function addArticle($row, $sort, $sectionId): void
    {
        static $counter;


        Article::create([
            'NAME' => $row['TITLE'],
            'CODE' => $this->titleToCode($row['TITLE']),
            'SORT' => $sort,
            'ACTIVE' => 'Y',
            'PROPERTY_VALUES' => [
                'TITLE_RU' => $row['TITLE'],
                'TITLE_EN' => $row['TITLE'],
                'TITLE_CN' => $row['TITLE'],
                'PREVIEW_TEXT_RU' => [
                    'VALUE' => [
                        'TEXT' => $row['TEXT'],
                        'TYPE' => 'HTML'
                    ]
                ],
                'PREVIEW_TEXT_EN' => [
                    'VALUE' => [
                        'TEXT' => $row['TEXT'],
                        'TYPE' => 'HTML'
                    ]
                ],
                'PREVIEW_TEXT_CN' => [
                    'VALUE' => [
                        'TEXT' => $row['TEXT'],
                        'TYPE' => 'HTML'
                    ]
                ],
                'IMAGE_LIST_PAGE' => Array(
                    "n0" => Array(
                        "VALUE" => CFile::MakeFileArray($row['IMAGE_LIST_PAGE'])
                    )
                ),
                'IMAGE_DETAIL_PAGE' => Array(
                    "n0" => Array(
                        "VALUE" => CFile::MakeFileArray($row['IMAGE_DETAIL_PAGE'])
                    )
                ),
                'CATEGORY' => \App\Models\Blog\Category::getByCode($row['CATEGORY'])->id,
                /*'SHOW_TYPE' => (int)CIBlockPropertyEnum::GetList([],
                    [
                        'PROPRERTY_CODE' => 'SHOW_TYPE',
                        'XML_ID' => $row['SHOW_TYPE'],
                        'IBLOCK_ID' => iblock_id(Article::IBLOCK_CODE)
                    ]
                )->Fetch()['ID'],
                */
                'GRID_SECTION' => $sectionId,
            ],
        ]);

    }

    private function addGridElements($row)
    {

        $section = \App\Models\Blog\GridSection::create([
            'NAME' => $row['TITLE'],
            'CODE' => $this->titleToCode($row['TITLE'])
        ]);

        if ($row['gridElements']) {
            foreach ($row['gridElements'] as $gridEl) {
                $this->addGridElement($row, $gridEl);
            }
        } else {
            foreach ($this->gridElements as $gridEl) {
                $this->addGridElement($row, $gridEl);
            }
        }

        return $section->id;
    }

    private function addGridElement($article, $gridEl)
    {
        static $n = 0;
        $n++;

        $sectionId = \App\Models\Blog\GridSection::query()->getByCode($this->titleToCode($article['TITLE']))->id;

        // тут нам нужен уникальный код
        $gridElCode = $this->titleToCode($article['TITLE'] . $n);

        \App\Models\Blog\GridElement::create([
            'NAME' => $gridEl['TITLE'] ? $gridEl['TITLE'] : 'Просто текст',
            'CODE' => $gridElCode,
            'SORT' => $n,
            'PROPERTY_VALUES' => [
                'TITLE_RU' => $gridEl['TITLE'],
                'TITLE_EN' => '&nbsp;',
                'TITLE_CN' => '&nbsp;',
                'TEXT_RU' => [
                    'VALUE' => [
                        'TEXT' => $gridEl['TEXT'],
                        'TYPE' => 'HTML'
                    ]
                ],
                'TEXT_EN' => [
                    'VALUE' => [
                        'TEXT' => '&nbsp;',
                        'TYPE' => 'HTML'
                    ]
                ],
                'TEXT_CN' => [
                    'VALUE' => [
                        'TEXT' => '&nbsp;',
                        'TYPE' => 'HTML'
                    ]
                ],
                'IMAGE' => Array(
                    "n0" => Array(
                        "VALUE" => CFile::MakeFileArray($gridEl['IMAGE'])
                    )
                ),
                'SHOW_TYPE' => (int)CIBlockPropertyEnum::GetList([],
                    [
                        'PROPRERTY_CODE' => 'SHOW_TYPE',
                        'XML_ID' => $gridEl['SHOW_TYPE'],
                        'IBLOCK_ID' => iblock_id(\App\Models\Blog\GridElement::IBLOCK_CODE)
                    ]
                )->Fetch()['ID'],
                'BUTTON_TEXT' => $gridEl['BUTTON_TEXT'],
                'BUTTON_URL' => $gridEl['BUTTON_URL'],
                'VIDEO_URL' => $gridEl['VIDEO_URL'],
            ],
            'IBLOCK_SECTION_ID' => $sectionId,
        ]);
    }

    private function titleToCode($title)
    {
        $title = preg_replace('/[^a-zA-Z0-9 ]/', '', $title);
        return str_replace([' ', ':'], ['_'], mb_strtolower($title));
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        // не стоит удалять лишний раз ничего 
    }
}
