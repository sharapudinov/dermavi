<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class Task42065EngContent20190717154821016251 extends BitrixMigration
{

    /**
     * Список элементов "Русской огранки"
     * @var array
     */
    private $russianCutElements = [
        [
            'CODE' => 'russian-cut',
            'TITLE_RU' => 'Русская огранка',
            'TITLE_EN' => 'Russian Cut',
            'TITLE_CN' => '俄式切割',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text">Российские камни привлекают покупателей со всего мира не только благодаря высокому качеству алмазов, но и благодаря великолепной русской огранке. Понятие Russian Cut известно во всем мире и считается своеобразным «знаком качества» благодаря отточенным веками умениям наших мастеров. </p> <p class="page-russian-cut__text">Извлеченные из недр земли алмазы имеют довольно мало общего с ослепительными, притягивающими взгляд камнями в ювелирных изделиях. Для того чтобы сделать алмаз ключевой составляющей изящного кольца, кулона или подвески, кристалл должен подвергнуться специальной обработке - огранке, которая выведет на передний план все его достоинства и скроет возможные недостатки. Огранка позволяет выявить присущую алмазу скрытую красоту, раскрыть максимальную игру и блеск кристалла.</p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">Russian gemstones attract buyers from all over the world not only because of the high quality of the diamonds, but also because of the magnificent Russian cut. The concept of Russian Cut is known all over the world and is regarded as a kind of "quality mark" due to the skills of our masters honed by centuries.</p> <p class="page-russian-cut__text">Diamonds extracted from the bowels of the earth have very little in common with sparkling, eye-attracting gemstones seen in jewelry. In order to become a center piece of a graceful ring, a necklace or a pendant, a diamond is to undergo a special treatment – the cut that will bring all its advantages to the foreground and disguise possible shortcomings. The cut allows uncovering the hidden beauty inherent in a diamond, revealing the maximum trick of light and brilliance of the crystal.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'petr-i',
            'TITLE_RU' => 'эпоха петра первого',
            'TITLE_EN' => 'The Epoch of Peter the Great',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text">Если ювелирному искусству никак не меньше тысячи лет, то гранить бриллианты начали сравнительно поздно – в 15 веке в Европе. В России эту тенденцию переняли чуть позже, и уже с 16 века шлифованные алмазы начали венчать украшения царской семьи. Сначала работать с алмазами умели только несколько придворных мастеров, однако в начале 18 века Петр Первый издал указ о создании первой в стране гранильной фабрики. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">Though the jeweler’s art is leastwise a thousand years old, nonetheless, diamonds began to be cut relatively late – in the 15th century in Europe. In Russia, this trend was adopted after a little while: polished diamonds began to top off the jewelry of the royal family in the 16th century. At first, only a few court craftsmen were able to work with diamonds, but in the early 18th century Peter the Great issued a decree on the establishment of the country\'s first lapidary factory.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'gold-age',
            'TITLE_RU' => 'золотой век',
            'TITLE_EN' => 'Golden age',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> «Золотой век» российского ограночного и ювелириного искусства, конечно же, связан с двумя женщинами – Елизаветой I и Екатериной II. Увлеченные красотой этих камней, они искали лучших ювелиров и поощряли развитие их талантов. В том числе, из-за рубежа приглашались лучшие мастера для передачи опыта, они оставались в России и развивали свои умения, а потом – создавали целые династии. В 18 и 19 веке в России появляется множество великих имен: Позье, Экарт, Болин, Фаберже. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">
The “Golden age” of the Russian lapidary and jewelry art is admittedly associated with two women – Elizabeth of Russia and Catherine the Great. Fascinated by the beauty of these stones, they looked for the best jewelers and encouraged the development of their talents. The best masters, from abroad in particular, were invited to share their experience, they stayed in Russia and developed their skills, and afterwards they founded entire dynasties. In the 18th and 19th century, many great names of art arise in Russia: Pozier, Eckart, Bolin, Faberge.
</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'ideal-form',
            'TITLE_RU' => 'идеальная форма',
            'TITLE_EN' => 'Perfect shape',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text">Чуть позже немалую роль в развитии российской – да и мировой – огранки сыграет бельгиец русского происхождения Марсель Толковский. Инженер по образованию, он в 1919 году разработал для бриллианта идеальное сочетание углов и пропорций, при котором свет, проникающий в камень, создает максимальный блеск и игру. 
Бриллиант Толковского обязательно должен иметь круглую огранку и 57 граней – сегодня это стандарт, по которому работает весь мир. Огранка Толковского также предполагает точно выдержанные пропорции высоты и диаметра, выверенные углы наклона граней. Чем сильнее отступление от них, тем меньше в камне игры света. Создаваемые в середине 20 века российские гранильные заводы изначально стремились работать близко к этим параметрам.</p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">A while later, Marcel Tolkowsky, a Belgian of Russian origin, played a significant role in development of the Russian – and the world – cut. In 1919, an engineer by education, he invented the optimal combination of angles and proportions for a diamond so that light penetrating a stone would create maximum brilliance and fire.
The Tolkowsky diamond must have a round brilliant cut with 57 facets – today it is a standard for the whole world industry. Tolkowsky\'s cut also assumes precisely observed proportions of height and diameter, and adjusted angles of the facets. The more deviation from these proportions is done, the less sparkle in the stone is produced. Russian lapidary factories established in the mid-20th century pursued to work close to these parameters from the ground up.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'high-standards',
            'TITLE_RU' => 'высокие стандарты',
            'TITLE_EN' => 'High standards',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__photo-text"> В 1977 году российская отрасль получила «технические условия на бриллианты», которые выдвигали жесткие требования к огранке и полировке драгкамней. В это время окончательно формируется Russian Cut, символизирующий высокие стандарты качества. В отличие от зарубежных огранщиков, часто нарушающих пропорции в угоду тому, чтобы камень имел большую массу, русские огранщики работали в точном соответствии с заданными стандартами и не могли от них отступить. Доскональное соблюдение высоких стандартов во всем — в целом отличительная черта России того времени. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__photo-text">In 1977, the Russian industry got "Technical Conditions for diamonds”, which imposed stringent requirements for cutting and polishing gemstones. At that time, the build-up of the “Russian Cut” symbolizing high quality standards underwent the last stage. Unlike foreign diamond cutters, who often violated the proportions in favour of larger weight of a stone, Russian diamond cutters worked in strict accordance with the set standards and could not waive them. Thorough maintenance of high standards in everything was a distinctive feature of Russia at that time, in general.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'soviet-legend',
            'TITLE_RU' => 'советская легенда',
            'TITLE_EN' => 'Soviet legend',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> Советская школа огранки требовала, чтобы все возможные дефекты были исправлены, даже если для этого понадобится существенно снизить массу камня. Огранщик старался обеспечить бриллианту идеальную форму, зачастую не считаясь с возможными потерями, что казалось немыслимым его коллеге из Израиля или Бельгии. Кроме того, в огранку направлялись только наиболее крупное и дорогое алмазное сырье, завозилось современное оборудование. На каждом гранильном заводе работал отдел контроля, который возвращал бриллианты на доработку при наличии малейших недочетов. </p> <p class="page-russian-cut__text"> Задача делать лучшие бриллианты выполнялась в полной мере: российские бриллианты пользовались огромным спросом, предоставляя покупателям гарантию высочайшего качества. Собственно, и само понятие Russian Cut было придумано вовсе не огранщиками, а самими зарубежными клиентами. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">The Soviet diamond-cutting school demanded that all possible defects were corrected, even if it would require significant reduction of the stone weight. The cutter tried to put a diamond in a perfect shape, often disregarding possible losses, which seemed inconceivable to his colleagues from Israel or Belgium. In addition, only the largest and most expensive rough diamonds were sent to cut; factories were supplied with modern equipment. Each diamond-cutting plant possessed a quality control department, which returned diamonds for rework in the presence of the slightest flaws. The task to make the best diamonds was fully implemented: Russian diamonds were in great demand, providing customers with a guarantee of the highest quality. Actually, the very concept of the Russian Cut was invented not by diamond cutters at all, but by foreign clients themselves.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'footer',
            'TITLE_RU' => 'бриллианты алроса',
            'TITLE_EN' => 'Diamonds Alrosa',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__photo-text page-russian-cut__photo-text--cl-global"> Филиал «Бриллианты АЛРОСА», созданный на базе советских предприятий, продолжает эту традицию. Наши сегодняшние стандарты огранки еще жестче, чем требования к качеству бриллиантов 70-х годов. В «Бриллиантах АЛРОСА» работает около 500 высококлассных мастеров, которые еще помнят, что такое настоящий Russian Cut, либо учились у этих специалистов. Вот почему мы можем гарантировать исключительное качество бриллиантов, изготовленных АЛРОСА. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__photo-text page-russian-cut__photo-text--cl-global">Diamonds ALROSA division established on the basis of Soviet enterprises continues their tradition. Our current standards for cutting are even more rigorous than the quality requirements for diamonds in the 70s. Diamonds ALROSA employs about 500 highly skilled craftsmen who still remember what the real Russian Cut is or studied under such specialists. That is why we can guarantee the unparalleled quality of the diamonds manufactured by ALROSA.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
    ];

    /**
     * Список элементов "Экологическая ответственность"
     * @var array
     */
    private $environmentalResponsibilityElements = [
        [
            'CODE' => 'wild_deer',
            'TITLE_RU' => 'wild_deer',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text page-responsibility__photo-text--cl-global"> Мы помогаем защитить популяцию диких оленей, поэтому с помощью GPS следим за перемещением стад и останавливаем движение в том месте, где они переходят дорогу. А еще мы построили парк-заповедник «Живые алмазы», площадью с Финляндию, в котором больные или редкие северные животные могут жить в естественной среде. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__photo-text page-responsibility__photo-text--cl-global">We help protect the wild deer population, so with the help of GPS we monitor the movement of herds and stop the movement where they cross the road. We have also built the Living Diamonds Park, an area with Finland where sick or rare northern animals can live in a natural environment.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'nature_up',
            'TITLE_RU' => 'nature_up',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text"> Мы сохраняем живую природу. В водоемы Якутии мы регулярно заселяем рыб, которые очищают их и поддерживают хорошую биологическую среду. Нарушенные бурением или добычей земли — восстанавливаем и засаживаем цветами и деревьями. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We\'re keeping nature alive. In the reservoirs of Yakutia, we regularly infiltrate fish, which purify them and maintain a good biological environment. Disrupted by drilling or extraction of land - we restore and plant flowers and trees.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'garbage',
            'TITLE_RU' => 'garbage',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text">Мы стараемся минимизировать отходы и выбросы. К счастью, в части выбросов алмазодобывающая отрасль является одной из самых «чистых» в горной добыче. Наш производственный цикл не включает в себя пиролиза или других процессов, при которых могли бы испаряться вредные вещества. Основной источник наших выбросов – пыль и выхлопы машин, которые мы тоже стремимся перевести на газовое топливо.</p> <p class="page-responsibility__row-text">Мы также бережно утилизируем мусор. В основном, это пустая порода – камни и песок, оставшиеся после извлечения руды из земли. Они складируются в специально отведенных местах, а иногда и используются на благое дело – например, для отсыпки дорог.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We try to minimize waste and emissions. Fortunately, in terms of emissions, the diamond mining industry is one of the cleanest in mining. Our production cycle does not include pyrolysis or other processes that could vaporize harmful substances. The main source of our emissions is dust and machine emissions, which we also seek to convert to gas fuel.</p> <p class="page-responsibility__row-text">We\'re also carefully disposing of the garbage. These are mostly waste rock - rocks and sand left behind after extracting ore from the ground. They are stored in specially designated places, and sometimes used for good deeds - for example, for dumping roads.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'energy',
            'TITLE_RU' => 'energy',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text">Мы стараемся беречь энергию. Значительная часть энергии, которую мы используем, идет с гидроэлектростанции, но есть и «классические» источники. Мы переводим наши котельные на газовое топливо, поскольку оно более экологично и не создает выбросов в окружающую среду. Мы также модернизируем оборудование, чтобы оно потребляло меньше энергии на ту же самую работу. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__photo-text">We\'re trying to save energy. Much of the energy we use comes from a hydroelectric power plant, but there are also "classic" sources. We are converting our boilers to gas fuel, because it is more ecological and does not create emissions into the environment. We are also upgrading our equipment so that it consumes less energy for the same work.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'alrosa_and_nature',
            'TITLE_RU' => 'Алроса и природа',
            'TITLE_EN' => 'ALROSA and nature',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text page-responsibility__intro-text--special">  Мы стараемся сохранять и беречь воду. В России с ней нет проблем, но мы знаем, что в мире есть множество регионов, где бутылка воды на вес золота. Сегодня мы свели к минимуму забор воды из внешних источников. Большинство наших производственных мощностей оборудованы системой оборотного водоснабжения – то есть, используют одну и ту же воду по кругу, фильтруя ее после использования и заново запуская в производственную цепочку. Зачем брать новое, если можно использовать то, что уже есть?</p> <p class="page-responsibility__intro-text">Мы также стараемся очищать воду, и свою, и чужую. Являясь ключевой компанией для целого региона, мы оборудуем очистными сооружениями не только свои объекты, но и коммунальные службы городов.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text page-responsibility__intro-text--special">We\'re trying to preserve the water. There are no problems with it in Russia, but we know that there are many regions in the world where a bottle of water is worth its weight in gold. Today, we have minimized water intake from external sources. Most of our production facilities are equipped with a water recycling system - that is, they use the same water in a circle, filtering it after use and re-launching it into the production chain. Why take a new one if you can use what you already have?</p> <p class="page-responsibility__intro-text">We also try to purify water, both our own and someone else\'s. As a key company for the whole region, we are equipping treatment facilities not only with our own facilities, but also with municipal services.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'environmental_responsibility',
            'TITLE_RU' => 'Экологическая ответственность',
            'TITLE_EN' => 'Environmental responsibility',
            'TITLE_CN' => '生态责任',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text">Как говорит наш главный эколог, «Человек – не царь природы, а ее арендатор, причем арендует он ее у своих собственных детей». Мало кто мог бы сказать лучше. Вся работа компании выстроена вокруг этого подхода, поэтому АЛРОСА стремится минимизировать негативное воздействие на окружающую среду. Ежегодно мы направляем на эту работу порядка 5 млрд рублей.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">As our chief ecologist says, "Man is not the king of nature, but its tenant, and he rents it from his own children. Few people could have said it better. All the work of the company is built around this approach, so ALROSA strives to minimize the negative impact on the environment. Every year we allocate about 5 billion rubles for this work.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
    ];

    /**
     * Список элементов "Кимберлийский процесс"
     * @var array
     */
    private $kimberleyProcessElements = [
        [
            'CODE' => 'partitipation',
            'TITLE_RU' => 'участие в процессе',
            'TITLE_EN' => 'Participation in the process',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Сертификат – не единственный инструмент работы Кимберлийского Процесса. В случае наличия достоверных данных о нелегальной добыче алмазов в интересах гражданского конфликта, КП имеет право наложить запрет на вывоз алмазов из этого государства. В истории КП были случаи принятия соответствующих решений. Запрет не снимается до тех пор, пока государство не подтвердит выполнение всех требуемых условий.</p> <p class="page-kimberley__text"> Россия стояла у истоков создания Кимберлийского Процесса. В том числе, представители нашей страны присутствовали и на той исторической встрече в Кимберли, а также участвовали в разработке механизмов сертификации. Обзорные визиты Кимберлийского Процесса в Россию свидетельствуют о полном соответствии механизмов контроля всем возможным нормам, что гарантирует полную уверенность в неконфликтном происхождении алмазов АЛРОСА. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__text"> The certificate is not the only instrument of the Kimberley Process. If there are reliable data on illegal diamond mining in the interests of civil conflict, the KP has the right to impose a ban on the export of diamonds from this state. In the history of KP, there have been cases of relevant decisions being taken. The ban is not removed until the state confirms that all required conditions have been met.</p> <p class="page-kimberley__text">  Russia was at the origin of the Kimberley Process. In particular, representatives of our country were present at that historic meeting in Kimberley, and also participated in the development of certification mechanisms. The Kimberley Process review visits to Russia testify to the full compliance of the control mechanisms with all possible norms, which guarantees full confidence in the non-conflict origin of ALROSA diamonds.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'process_positions',
            'TITLE_RU' => 'положения процесса',
            'TITLE_EN' => 'Process Provisions',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__photo-text">По правилам КП, торговать друг с другом могут только страны – участники Процесса, то есть, только те, кто может подтвердить легальность происхождения алмазов. На сегодняшний день в состав Кимберлийского Процесса входит 54 государства (включая Евросоюз, учитывающий в себе все страны ЕС).<br>
   В Кимберлийском Процессе как организации не участвуют представители компаний или бирж – только представители стран (государственных органов). Это обеспечивает абсолютную независимость процессов и исключает принятие решений в пользу какой-то конкретной компании. По той же причине председательство в КП каждый год передается другой стране. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__photo-text"> According to KP rules, only countries participating in the Process can trade with each other, i.e. only those who can confirm the legality of the origin of diamonds. To date, the Kimberley Process has 54 member states (including the European Union, which includes all EU countries). <br>The Kimberley Process as an organization does not include representatives of companies or stock exchanges - only representatives of countries (government agencies). This ensures the absolute independence of the processes and excludes decision-making in favour of any particular company. For the same reason, the chairmanship of the KP is transferred to another country every year.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'diamond_sorting',
            'TITLE_RU' => 'СОРТИРОВКА АЛМАЗОВ',
            'TITLE_EN' => 'Sorting diamonds',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Кимберлийский Процесс, получивший поддержку ООН, был создан, чтобы помешать конфликтным алмазам попадать на рынок — соответственно, чтобы помешать людям, которые нелегально добывают алмазы, зарабатывать на этом деньги. Для этого была разработана схема сертификации. Каждая партия алмазов на рынке должна сопровождаться сертификатом, подтверждающим, что эти алмазы добыты вне зоны конфликтных действий. Только с таким сертификатом партия алмазов может пересечь любую границу. </p> <p class="page-kimberley__text"> Кроме того, внутри страны выстраивается механизм контроля, который следит за тем, чтобы конфликтные или «серые» алмазы не попадали в торговую цепочку. Кимберлийский Процесс регулярно организует выездные проверки, чтобы подтвердить соответствие той или иной страны этим требованиям, а также располагает всей информацией о добыче и экспорте-импорте алмазов каждой страны. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__text"> The Kimberley Process, supported by the UN, was created to prevent conflict diamonds from entering the market - accordingly, to prevent people who illegally mine diamonds from making money from doing so. For this purpose, a certification scheme was developed. Each batch of diamonds in the market must be accompanied by a certificate confirming that these diamonds are mined outside the conflict zone. Only with such a certificate can a lot of diamonds cross any border. </p> <p class="page-kimberley__text">In addition, a control mechanism is being built within the country to ensure that conflict or grey diamonds do not enter the trade chain. The Kimberley Process regularly organizes on-site inspections to confirm a country\'s compliance with these requirements and has all the information on each country\'s diamond production and export-import.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'process_creation',
            'TITLE_RU' => 'создание процесса',
            'TITLE_EN' => 'Creating a process.',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__photo-text page-kimberley__photo-text--center">В 2000 году представители алмазодобывающих и торговых стран, а также неправительственных организаций провели первую в истории встречу для того, чтобы придумать, как решить эту проблему. Встреча проходила в городе Кимберли (ЮАР), потому и образовавшаяся в результате организация получила название Кимберлийского Процесса. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__photo-text page-kimberley__photo-text--center">  In 2000, representatives of diamond-producing and trading countries, as well as non-governmental organizations, met for the first time in history to figure out how to address the problem. The meeting was held in the city of Kimberley (South Africa), so the resulting organization was called the Kimberley Process.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'kp',
            'TITLE_RU' => 'КИМБЕРЛИЙСКИЙ ПРОЦЕСС',
            'TITLE_EN' => 'Kimberley Process',
            'TITLE_CN' => '金伯利进程',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text">Некоторое время назад каждый человек знал фразу «кровавый алмаз». Конфликтные алмазы – те, которые добываются нелегально и финансируют терроризм и антиправительственные вооруженные конфликты – долгое время были большой проблемой стран Африки.
Сегодня это словосочетание начинает забываться, а проблема конфликтных камней искоренена практически полностью. Сейчас ваши шансы купить кольцо с конфликтным камнем сведены к нулю – если, конечно, вы покупаете его в магазине, а не в темном переулке у незнакомого человека за четверть цены. И произошло это благодаря Кимберлийскому Процессу. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__text">Some time ago, everyone knew the phrase "blood diamond". Conflict diamonds - those that are mined illegally and finance terrorism and anti-government armed conflicts - have long been a major problem in Africa.
Today, this combination of words is beginning to be forgotten, and the problem of conflict stones is almost completely eradicated. Now your chances to buy a ring with a conflict stone are reduced to zero - if, of course, you buy it in a store, not in a dark alley from a stranger for a quarter of the price. And it was thanks to the Kimberley Process. </p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
    ];

    /**
     * Список элементов "Социальная ответственность"
     * @var array
     */
    private $socialResponsibility = [
        [
            'CODE' => 'buildings',
            'TITLE_RU' => 'Мы строим жилые дома',
            'TITLE_EN' => 'We build houses',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text">Мы строим жилые дома, коммуникации и дороги, больницы и реабилитационные центры, спортивные объекты, школы и детские сады, которыми могут пользоваться все без исключения.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__photo-text">We build houses, communications and roads, hospitals and rehabilitation centers, sports facilities, schools and kindergartens, which can be used by everyone without exception.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'altruism',
            'TITLE_RU' => 'altruism',
            'TITLE_EN' => 'altruism',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text">Мы добываем алмазы в Якутии, поэтому считаем своим долгом помогать местному населению и региону. Мы поддерживаем экономические, социальные, инфраструктурные, инновационные проекты в Якутии, в том числе в тех мелких деревеньках, где вовсе нет никаких алмазов.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We mine diamonds in Yakutia, therefore, we consider it our duty to help local population and the region. We support economic, social, infrastructural, innovative projects in Yakutia, including in those small villages where there are no diamonds at all.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'social_comfort',
            'TITLE_RU' => 'social_comfort',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text">Еще один залог стабильности – достойные условия жизни, комфортная среда обитания для людей. Большинство сотрудников АЛРОСА вместе с их семьями живет в Якутии, в небольших моногородах, созданных вокруг алмазодобывающих предприятий, на значительном удалении от крупных населенных пунктов. Поэтому наша задача – сделать их жизнь не слишком отличающейся от жизни в крупных городах.</p> <p class="page-responsibility__row-text">АЛРОСА ежегодно реализует масштабные социальные программы для работников и членов их семей, в рамках которых обеспечивает их здравоохранение и отдых, возможность заниматься спортом и посещать творческие кружки, культурные мероприятия; помогает с жильем и ипотекой, выплачивает негосударственные пенсии. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">Another factor for stability is decent living conditions, comfortable living environment for people. Most of ALROSA employees, together with their families, live in Yakutia in small monotowns created around diamond-mining enterprises, at a considerable distance from major population centers. For this reason, our task is to make their life not too different from life in typical towns and cities.</p> <p class="page-responsibility__row-text">ALROSA annually implements large-scale social programs for workers and their families, within which we provide health care and recreation, opportunities to play sports, attend creative clubs and cultural events; as well as help with housing and mortgage, pay non-state pensions.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'growth_stability',
            'TITLE_RU' => 'Основы стабильности алроса',
            'TITLE_EN' => 'Anchor of stability in ALROSA',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text">Залогом успешного развития любой компании являются не только объемы запасов или технологии, но прежде всего – трудовой коллектив. Сегодня команда АЛРОСА – это 37 тысяч профессионалов, мастеров своего дела. Мы хотим быть компанией, которой гордятся ее сотрудники. Вот почему мы обеспечиваем для них лучшие условия труда. Сегодня средняя зарплата сотрудников АЛРОСА вдвое выше, чем средняя зарплата в Якутии, и втрое выше, чем средняя зарплата в России. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">The cornerstone of sustainability of any company is not only the stock size or technology, but above all – the workforce. Today ALROSA’s team consists of 37 thousand professionals, masters of their craft. We want to be a company that makes its employees proud. That is why we provide them with better working conditions. Today, the average salary of ALROSA employees is double its level in Yakutia, and three times as high as the average salary in Russia.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'social_responsibility',
            'TITLE_RU' => 'Социальная ответственность',
            'TITLE_EN' => 'Social responsibility',
            'TITLE_CN' => '社会责任',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text"> АЛРОСА по праву может называть себя не только самой крупной алмазодобывающей компанией, но и самой социально ответственной. По данным отчета PwC, АЛРОСА – лидер алмазодобывающей и золотодобывающей отрасли по доле отчислений на социальные программы. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">ALROSA can justly call itself not only the largest diamond mining company, but also the most socially responsible. According to the PwC report, ALROSA is the leader of the diamond mining and gold mining industry in terms of share of contributions to social programs.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
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
        db()->startTransaction();
        try {
            $this->updateRussianCutElements();
            $this->updateSocialResonsibilityElements();
            $this->updateKimberleyProcessElements();
            $this->updateEnvironmentalResponsibility();
            db()->commitTransaction();
        } catch (Exception $e) {
            db()->rollbackTransaction();
            throw $e;
        }

    }

    private function updateRussianCutElements()
    {
        $els = \App\Models\About\RussianCut::getList();
        foreach ($els as $el) {
            $el->delete();
        }

        $sort = 100;
        foreach ($this->russianCutElements as $element) {
            $sort += 100;
            \App\Models\About\RussianCut::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' => $element['TITLE_EN'],
                    'TITLE_CN' => $element['TITLE_CN'],
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_CN'], 'TYPE' => 'HTML',]],
                ],
            ]);
        }
    }

    private function updateSocialResonsibilityElements()
    {
        $els = \App\Models\About\SocialResponsibility::getList();
        foreach ($els as $el) {
            $el->delete();
        }

        $sort = 100;
        foreach ($this->socialResponsibility as $element) {
            $sort += 100;
            \App\Models\About\SocialResponsibility::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' => $element['TITLE_EN'],
                    'TITLE_CN' => $element['TITLE_CN'],
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_CN'], 'TYPE' => 'HTML',]],
                ],
            ]);
        }
    }

    private function updateKimberleyProcessElements()
    {
        $els = \App\Models\About\KimberleyProcess::getList();
        foreach ($els as $el) {
            $el->delete();
        }

        $sort = 100;
        foreach ($this->kimberleyProcessElements as $element) {
            $sort += 100;
            \App\Models\About\KimberleyProcess::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' => $element['TITLE_EN'],
                    'TITLE_CN' => $element['TITLE_CN'],
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_CN'], 'TYPE' => 'HTML',]],
                ],
            ]);
        }
    }

    private function updateEnvironmentalResponsibility()
    {
        $els = \App\Models\About\EnvironmentalResponsibility::getList();
        foreach ($els as $el) {
            $el->delete();
        }

        $sort = 100;
        foreach ($this->environmentalResponsibilityElements as $element) {
            $sort += 100;
            \App\Models\About\EnvironmentalResponsibility::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' => $element['TITLE_EN'],
                    'TITLE_CN' => $element['TITLE_CN'],
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_CN'], 'TYPE' => 'HTML',]],
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
        //
    }
}
