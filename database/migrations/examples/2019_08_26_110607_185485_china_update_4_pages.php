<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ChinaUpdate4Pages20190826110607185485 extends BitrixMigration
{
    /*
    <p class="page-russian-cut__text"></p>
    */
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
            'DESCRIPTION_CN' => '<p class="page-russian-cut__text">俄罗斯钻石吸引了来自世界各地的买家，不仅因为钻石的高品质，还因为俄式的宏伟切割。俄罗斯切割的概念在全世界都是众所周知的，并且由于我们的工匠几个世纪以来磨练的技能被认为是一种“质量标志”。</p>
<p class="page-russian-cut__text">从地球内部中开采的钻石与令人眼花缭乱，引人注目的宝石几乎没有共同之处。为了使钻石成为戒指、吊坠或悬饰柱的主成分，钻石必须经过特殊处理 – 切割。切割将所有优点可带到前台并将隐藏钻石可能含有的缺点。切割可以展现钻石中固有的隐藏美感，展现其神奇地闪闪发光。</p>',
        ],
        [
            'CODE' => 'petr-i',
            'TITLE_RU' => 'Эпоха петра первого',
            'TITLE_EN' => 'The Epoch of Peter the Great',
            'TITLE_CN' => '彼得大帝的时代',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text">Если ювелирному искусству никак не меньше тысячи лет, то гранить бриллианты начали сравнительно поздно – в 15 веке в Европе. В России эту тенденцию переняли чуть позже, и уже с 16 века шлифованные алмазы начали венчать украшения царской семьи. Сначала работать с алмазами умели только несколько придворных мастеров, однако в начале 18 века Петр Первый издал указ о создании первой в стране гранильной фабрики. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">Though the jeweler’s art is leastwise a thousand years old, nonetheless, diamonds began to be cut relatively late – in the 15th century in Europe. In Russia, this trend was adopted after a little while: polished diamonds began to top off the jewelry of the royal family in the 16th century. At first, only a few court craftsmen were able to work with diamonds, but in the early 18th century Peter the Great issued a decree on the establishment of the country\'s first lapidary factory.</p>',
            'DESCRIPTION_CN' => '<p class="page-russian-cut__text">如果珠宝艺术有不低于千年的历史，那么钻石切割发现可追溯到相对较晚的时期 - 在15世纪的欧洲。在俄罗斯，这种趋势被采用晚一下。自16世纪起来，抛光钻石成为皇室珠宝的饰物。起初，只有少数皇室工匠能够使用钻石，但在18世纪早期，彼得大帝颁布了关于建立该国第一家切割工厂的法令。</p>',
        ],
        [
            'CODE' => 'gold-age',
            'TITLE_RU' => 'Золотой век',
            'TITLE_EN' => 'Golden age',
            'TITLE_CN' => '黄金时代',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> «Золотой век» российского ограночного и ювелириного искусства, конечно же, связан с двумя женщинами – Елизаветой I и Екатериной II. Увлеченные красотой этих камней, они искали лучших ювелиров и поощряли развитие их талантов. В том числе, из-за рубежа приглашались лучшие мастера для передачи опыта, они оставались в России и развивали свои умения, а потом – создавали целые династии. В 18 и 19 веке в России появляется множество великих имен: Позье, Экарт, Болин, Фаберже. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">
The “Golden age” of the Russian lapidary and jewelry art is admittedly associated with two women – Elizabeth of Russia and Catherine the Great. Fascinated by the beauty of these stones, they looked for the best jewelers and encouraged the development of their talents. The best masters, from abroad in particular, were invited to share their experience, they stayed in Russia and developed their skills, and afterwards they founded entire dynasties. In the 18th and 19th century, many great names of art arise in Russia: Pozier, Eckart, Bolin, Faberge.
</p>',
            'DESCRIPTION_CN' => '<p class="page-russian-cut__text">当然，俄罗斯切割和珠宝艺术的“黄金时代”与两位女性有关 - 伊丽莎白一世和凯瑟琳二世。着迷于这些宝石的美丽，她们寻找最好的珠宝匠，并鼓励他们的才能发展。包括从国外邀请最好的大师们转移经验，他们留在俄罗斯并发展他们的技能，然后他们创造了整个朝代。在十八和十九世纪的俄罗斯，有许多伟大的名字：保瑞、埃卡特、伯林、法贝热。</p>',
        ],
        [
            'CODE' => 'ideal-form',
            'TITLE_RU' => 'идеальная форма',
            'TITLE_EN' => 'Perfect shape',
            'TITLE_CN' => '完美的造型',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text">Чуть позже немалую роль в развитии российской – да и мировой – огранки сыграет бельгиец русского происхождения Марсель Толковский. Инженер по образованию, он в 1919 году разработал для бриллианта идеальное сочетание углов и пропорций, при котором свет, проникающий в камень, создает максимальный блеск и игру.
Бриллиант Толковского обязательно должен иметь круглую огранку и 57 граней – сегодня это стандарт, по которому работает весь мир. Огранка Толковского также предполагает точно выдержанные пропорции высоты и диаметра, выверенные углы наклона граней. Чем сильнее отступление от них, тем меньше в камне игры света. Создаваемые в середине 20 века российские гранильные заводы изначально стремились работать близко к этим параметрам.</p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">A while later, Marcel Tolkowsky, a Belgian of Russian origin, played a significant role in development of the Russian – and the world – cut. In 1919, an engineer by education, he invented the optimal combination of angles and proportions for a diamond so that light penetrating a stone would create maximum brilliance and fire.
The Tolkowsky diamond must have a round brilliant cut with 57 facets – today it is a standard for the whole world industry. Tolkowsky\'s cut also assumes precisely observed proportions of height and diameter, and adjusted angles of the facets. The more deviation from these proportions is done, the less sparkle in the stone is produced. Russian lapidary factories established in the mid-20th century pursued to work close to these parameters from the ground up.</p>',
            'DESCRIPTION_CN' => '<p class="page-russian-cut__text">不久之后，来自俄罗斯的比利时人马歇尔·托克夫斯基将在俄罗斯和世界切割艺术的发展中发挥重要作用。作为一名工程师，他于1919年开发了钻石角度和比例的最完美组合，在这种组合中，穿透石头的光线创造了最大的光彩和闪闪发光。
托克夫斯基钻石必须有圆形切割和57个切面 - 今天它是整个世界运作的标准。托克夫斯基式切割也包括高度和直径的精确比例、正确的切面倾斜角度。撤退越强，钻石发光就越少。俄罗斯切割工厂创建于20世纪中叶，最初努力保持这些标准。
</p>',
        ],
        [
            'CODE' => 'high-standards',
            'TITLE_RU' => 'Высокие стандарты',
            'TITLE_EN' => 'High standards',
            'TITLE_CN' => '高标准',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__photo-text"> В 1977 году российская отрасль получила «технические условия на бриллианты», которые выдвигали жесткие требования к огранке и полировке драгкамней. В это время окончательно формируется Russian Cut, символизирующий высокие стандарты качества. В отличие от зарубежных огранщиков, часто нарушающих пропорции в угоду тому, чтобы камень имел большую массу, русские огранщики работали в точном соответствии с заданными стандартами и не могли от них отступить. Доскональное соблюдение высоких стандартов во всем — в целом отличительная черта России того времени. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__photo-text">In 1977, the Russian industry got "Technical Conditions for diamonds”, which imposed stringent requirements for cutting and polishing gemstones. At that time, the build-up of the “Russian Cut” symbolizing high quality standards underwent the last stage. Unlike foreign diamond cutters, who often violated the proportions in favour of larger weight of a stone, Russian diamond cutters worked in strict accordance with the set standards and could not waive them. Thorough maintenance of high standards in everything was a distinctive feature of Russia at that time, in general.</p>',
            'DESCRIPTION_CN' => '<p class="page-russian-cut__photo-text">1977年俄罗斯工业获得了“钻石的技术条件”，对切割和抛光宝石提出了严格的要求。当时，完全形成了俄式切割艺术，象征着高质量的标准。与通常违反比例以获得大量石材的国外工匠不同，俄罗斯钻石工匠完全按照既定标准工作，无法退回。一般而言，彻底遵守一切中的高标准是当时俄罗斯的一个显着特征。</p>',
        ],
        [
            'CODE' => 'soviet-legend',
            'TITLE_RU' => 'Cоветская легенда',
            'TITLE_EN' => 'Soviet legend',
            'TITLE_CN' => '苏联传奇',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> Советская школа огранки требовала, чтобы все возможные дефекты были исправлены, даже если для этого понадобится существенно снизить массу камня. Огранщик старался обеспечить бриллианту идеальную форму, зачастую не считаясь с возможными потерями, что казалось немыслимым его коллеге из Израиля или Бельгии. Кроме того, в огранку направлялись только наиболее крупное и дорогое алмазное сырье, завозилось современное оборудование. На каждом гранильном заводе работал отдел контроля, который возвращал бриллианты на доработку при наличии малейших недочетов. </p> <p class="page-russian-cut__text"> Задача делать лучшие бриллианты выполнялась в полной мере: российские бриллианты пользовались огромным спросом, предоставляя покупателям гарантию высочайшего качества. Собственно, и само понятие Russian Cut было придумано вовсе не огранщиками, а самими зарубежными клиентами. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">The Soviet diamond-cutting school demanded that all possible defects were corrected, even if it would require significant reduction of the stone weight. The cutter tried to put a diamond in a perfect shape, often disregarding possible losses, which seemed inconceivable to his colleagues from Israel or Belgium. In addition, only the largest and most expensive rough diamonds were sent to cut; factories were supplied with modern equipment. Each diamond-cutting plant possessed a quality control department, which returned diamonds for rework in the presence of the slightest flaws. </p> <p class="page-russian-cut__text">The task to make the best diamonds was fully implemented: Russian diamonds were in great demand, providing customers with a guarantee of the highest quality. Actually, the very concept of the Russian Cut was invented not by diamond cutters at all, but by foreign clients themselves.</p>',
            'DESCRIPTION_CN' => '<p class="page-russian-cut__text">苏联式切割学派要求纠正所有可能的缺陷，即使它需要大幅减少石块的重量。苏联工匠试图提供完美的钻石形状，通常无视可能的损失，这对以色列或比利时的同事来说似乎是不可思议的。此外，只有最大和最昂贵的材料才被切割，因此苏联进口现代设备。每个切割工厂有一个监查部门，如诺发现最轻微的瑕疵就返回钻石进行修改 </p> <p class="page-russian-cut__text">制作最好的钻石的任务已经完全实现：俄罗斯钻石很有销售，为客户提供最高质量的保证。实际上，俄式切割的概念根本不是由钻石工匠想 出的，而是由外国客户自己想的。</p>',
        ],
        [
            'CODE' => 'footer',
            'TITLE_RU' => 'Бриллианты алроса',
            'TITLE_EN' => 'Diamonds Alrosa',
            'TITLE_CN' => '阿尔罗萨钻石',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__photo-text page-russian-cut__photo-text--cl-global"> Филиал «Бриллианты АЛРОСА», созданный на базе советских предприятий, продолжает эту традицию. Наши сегодняшние стандарты огранки еще жестче, чем требования к качеству бриллиантов 70-х годов. В «Бриллиантах АЛРОСА» работает около 500 высококлассных мастеров, которые еще помнят, что такое настоящий Russian Cut, либо учились у этих специалистов. Вот почему мы можем гарантировать исключительное качество бриллиантов, изготовленных АЛРОСА. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__photo-text page-russian-cut__photo-text--cl-global">Diamonds ALROSA division established on the basis of Soviet enterprises continues their tradition. Our current standards for cutting are even more rigorous than the quality requirements for diamonds in the 70s. Diamonds ALROSA employs about 500 highly skilled craftsmen who still remember what the real Russian Cut is or studied under such specialists. That is why we can guarantee the unparalleled quality of the diamonds manufactured by ALROSA.</p>',
            'DESCRIPTION_CN' => '<p class="page-russian-cut__photo-text page-russian-cut__photo-text--cl-global">在苏联企业的基础上建立的阿尔罗萨钻石
分公司延续了这种传统。我们目前的切割标准甚至比70年代钻石的质量要求更严格。阿尔罗萨钻石大约有500名技艺高超的工匠仍然掌握真正的俄式割艺术还是从专家学习。这就是我们可以保证《阿尔罗萨钻石》生产的钻石的卓越品质的原因。</p>',
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
            'DESCRIPTION_CN' => '<p class="page-responsibility__photo-text page-responsibility__photo-text--cl-global">我们帮助保护野鹿种群，因此我司用GPS监测畜群的移动并停止车在鹿过马路的地方的移动。 我们还建立了《生活钻石公园》保护区，公园面积同芬兰面积一样的，在那里生病或罕见的北方动物可以生活在自然环境中。
</p>',
        ],
        [
            'CODE' => 'nature_up',
            'TITLE_RU' => 'nature_up',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text"> Мы сохраняем живую природу. В водоемы Якутии мы регулярно заселяем рыб, которые очищают их и поддерживают хорошую биологическую среду. Нарушенные бурением или добычей земли — восстанавливаем и засаживаем цветами и деревьями. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We\'re keeping nature alive. In the reservoirs of Yakutia, we regularly infiltrate fish, which purify them and maintain a good biological environment. Disrupted by drilling or extraction of land - we restore and plant flowers and trees.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__row-text">我们保卫野生生物 。在雅库特的水域，我们经常栖息在清洁鱼类并保持良好生物环境的鱼类中。 钻井或采矿土地扰乱后我们恢复和种植花草树木。</p>',
        ],
        [
            'CODE' => 'garbage',
            'TITLE_RU' => 'garbage',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text">Мы стараемся минимизировать отходы и выбросы. К счастью, в части выбросов алмазодобывающая отрасль является одной из самых «чистых» в горной добыче. Наш производственный цикл не включает в себя пиролиза или других процессов, при которых могли бы испаряться вредные вещества. Основной источник наших выбросов – пыль и выхлопы машин, которые мы тоже стремимся перевести на газовое топливо.</p> <p class="page-responsibility__row-text">Мы также бережно утилизируем мусор. В основном, это пустая порода – камни и песок, оставшиеся после извлечения руды из земли. Они складируются в специально отведенных местах, а иногда и используются на благое дело – например, для отсыпки дорог.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We try to minimize waste and emissions. Fortunately, in terms of emissions, the diamond mining industry is one of the cleanest in mining. Our production cycle does not include pyrolysis or other processes that could vaporize harmful substances. The main source of our emissions is dust and machine emissions, which we also seek to convert to gas fuel.</p> <p class="page-responsibility__row-text">We\'re also carefully disposing of the garbage. These are mostly waste rock - rocks and sand left behind after extracting ore from the ground. They are stored in specially designated places, and sometimes used for good deeds - for example, for dumping roads.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__row-text">我们尽量减少浪费和排放。幸运的是，就排放而言，钻石行业是采矿业中“最干净”的行业之一。我们的生产周期不包括热解或其他有害物质蒸发的过程。我们排放的主要来源是灰尘和汽车尾气，我们也努力将其转化为气体燃料。</p>
<p class="page-responsibility__row-text">我们也小心处理垃圾。基本上，它是一块空岩石 - 从地球中提取矿石后留下的石头和沙子。它们存放在指定区域，有时用于正当理由 - 例如，用于倾倒道路。</p>
',
        ],
        [
            'CODE' => 'energy',
            'TITLE_RU' => 'energy',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text">Мы стараемся беречь энергию. Значительная часть энергии, которую мы используем, идет с гидроэлектростанции, но есть и «классические» источники. Мы переводим наши котельные на газовое топливо, поскольку оно более экологично и не создает выбросов в окружающую среду. Мы также модернизируем оборудование, чтобы оно потребляло меньше энергии на ту же самую работу. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__photo-text">We\'re trying to save energy. Much of the energy we use comes from a hydroelectric power plant, but there are also "classic" sources. We are converting our boilers to gas fuel, because it is more ecological and does not create emissions into the environment. We are also upgrading our equipment so that it consumes less energy for the same work.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__photo-text">我们努力节约能源。我们使用的大部分能源来自水力发电，但也有“经典”来源。我们将锅炉转换为气体燃料，因为它更环保，不会对环境产生排放。 我们还升级设备，以便为同一工作使用更少的能源。</p>',
        ],
        [
            'CODE' => 'alrosa_and_nature',
            'TITLE_RU' => 'Алроса и природа',
            'TITLE_EN' => 'ALROSA and nature',
            'TITLE_CN' => '阿尔罗萨和自然',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text page-responsibility__intro-text--special">  Мы стараемся сохранять и беречь воду. В России с ней нет проблем, но мы знаем, что в мире есть множество регионов, где бутылка воды на вес золота. Сегодня мы свели к минимуму забор воды из внешних источников. Большинство наших производственных мощностей оборудованы системой оборотного водоснабжения – то есть, используют одну и ту же воду по кругу, фильтруя ее после использования и заново запуская в производственную цепочку. Зачем брать новое, если можно использовать то, что уже есть?</p> <p class="page-responsibility__intro-text">Мы также стараемся очищать воду, и свою, и чужую. Являясь ключевой компанией для целого региона, мы оборудуем очистными сооружениями не только свои объекты, но и коммунальные службы городов.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text page-responsibility__intro-text--special">We\'re trying to preserve the water. There are no problems with it in Russia, but we know that there are many regions in the world where a bottle of water is worth its weight in gold. Today, we have minimized water intake from external sources. Most of our production facilities are equipped with a water recycling system - that is, they use the same water in a circle, filtering it after use and re-launching it into the production chain. Why take a new one if you can use what you already have?</p> <p class="page-responsibility__intro-text">We also try to purify water, both our own and someone else\'s. As a key company for the whole region, we are equipping treatment facilities not only with our own facilities, but also with municipal services.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__intro-text">我们还试图保存水。在俄罗斯，水的可得性没有任何问题，但我们知道世界上有许多地区，一瓶水的价值与黄金相当。如今我们已尽量减少从外部来源取的水。我们的大多数生产设施都配备了循环供水系统 - 也就是说，它们使用水圈，使用后过滤并重新启动到生产链中。如果你可以使用已经存在的东西，为什么要换一个新的？</p>
<p class="page-responsibility__intro-text">我们也尝试净化我们和他人的水。作为整个地区的重要企业，我们不仅配备了自己的设施，还配备了市政公用设施。</p> ',
        ],
        [
            'CODE' => 'environmental_responsibility',
            'TITLE_RU' => 'Экологическая ответственность',
            'TITLE_EN' => 'Environmental responsibility',
            'TITLE_CN' => '生态责任',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text">Как говорит наш главный эколог, «Человек – не царь природы, а ее арендатор, причем арендует он ее у своих собственных детей». Мало кто мог бы сказать лучше. Вся работа компании выстроена вокруг этого подхода, поэтому АЛРОСА стремится минимизировать негативное воздействие на окружающую среду. Ежегодно мы направляем на эту работу порядка 5 млрд рублей.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">As our chief ecologist says, "Man is not the king of nature, but its tenant, and he rents it from his own children. Few people could have said it better. All the work of the company is built around this approach, so ALROSA strives to minimize the negative impact on the environment. Every year we allocate about 5 billion rubles for this work.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__intro-text">正如我们的主要生态学家所说，“人不是自然界的王者，而是自己的房客，从自己的孩子那里租来的。”很少有人能说得比他更好。公司的所有工作都以这种方法为主，因此阿尔罗萨努力减少对环境的负面影响。每年我们都会向这项工作发送约50亿卢布。</p>',
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
            'TITLE_CN' => '参与进程',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Сертификат – не единственный инструмент работы Кимберлийского Процесса. В случае наличия достоверных данных о нелегальной добыче алмазов в интересах гражданского конфликта, КП имеет право наложить запрет на вывоз алмазов из этого государства. В истории КП были случаи принятия соответствующих решений. Запрет не снимается до тех пор, пока государство не подтвердит выполнение всех требуемых условий.</p> <p class="page-kimberley__text"> Россия стояла у истоков создания Кимберлийского Процесса. В том числе, представители нашей страны присутствовали и на той исторической встрече в Кимберли, а также участвовали в разработке механизмов сертификации. Обзорные визиты Кимберлийского Процесса в Россию свидетельствуют о полном соответствии механизмов контроля всем возможным нормам, что гарантирует полную уверенность в неконфликтном происхождении алмазов АЛРОСА. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__text"> The certificate is not the only instrument of the Kimberley Process. If there are reliable data on illegal diamond mining in the interests of civil conflict, the KP has the right to impose a ban on the export of diamonds from this state. In the history of KP, there have been cases of relevant decisions being taken. The ban is not removed until the state confirms that all required conditions have been met.</p> <p class="page-kimberley__text">  Russia was at the origin of the Kimberley Process. In particular, representatives of our country were present at that historic meeting in Kimberley, and also participated in the development of certification mechanisms. The Kimberley Process review visits to Russia testify to the full compliance of the control mechanisms with all possible norms, which guarantees full confidence in the non-conflict origin of ALROSA diamonds.</p>',
            'DESCRIPTION_CN' => '<p class="page-kimberley__text">
证书不是金伯利进程的唯一工具。如果有关于为国内冲突利益而非法开采钻石的可靠数据，金伯利进程有权禁止从该州出口钻石。在金伯利的历史中，有一些做出相关决定的案例。在国家确认满足所有必要条件之前，禁令不会解除。
</p>
<p class="page-kimberley__text">
俄罗斯是金伯利进程的起源上的成员。特别是，我国代表出席了在金伯利举行的这次历史性会议，并参加了认证机制的发展。金伯利进程对俄罗斯的访问调查证明，控制机制完全符合所有可能的规范，这保证了对《阿尔罗萨钻石》钻石非冲突起源的充分信任。
</p>
',
        ],
        [
            'CODE' => 'process_positions',
            'TITLE_RU' => 'положения процесса',
            'TITLE_EN' => 'Process Provisions',
            'TITLE_CN' => '进程规定',
            'DESCRIPTION_RU' => '<p class="page-kimberley__photo-text">По правилам КП, торговать друг с другом могут только страны – участники Процесса, то есть, только те, кто может подтвердить легальность происхождения алмазов. На сегодняшний день в состав Кимберлийского Процесса входит 54 государства (включая Евросоюз, учитывающий в себе все страны ЕС).<br>
   В Кимберлийском Процессе как организации не участвуют представители компаний или бирж – только представители стран (государственных органов). Это обеспечивает абсолютную независимость процессов и исключает принятие решений в пользу какой-то конкретной компании. По той же причине председательство в КП каждый год передается другой стране. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__photo-text"> According to KP rules, only countries participating in the Process can trade with each other, i.e. only those who can confirm the legality of the origin of diamonds. To date, the Kimberley Process has 54 member states (including the European Union, which includes all EU countries). <br>The Kimberley Process as an organization does not include representatives of companies or stock exchanges - only representatives of countries (government agencies). This ensures the absolute independence of the processes and excludes decision-making in favour of any particular company. For the same reason, the chairmanship of the KP is transferred to another country every year.</p>',
            'DESCRIPTION_CN' => '<p class="page-kimberley__photo-text">根据金伯利进程的规则，只有参与该进程的国家才能相互交易，即只能确认钻石原产地合法性的国家。迄今为止，金伯利进程包括54个州（包括考虑到所有欧盟国家的欧盟）。<br>
在作为一个组织的金伯利进程中，公司或证券交易所的代表不参与 - 只有国家（政府机构）的代表。这确保了进程的绝对独立性，并排除了有利于特定公司的决策。出于同样的原因，金伯利的主席职位每年都会转移到另一个国家。
</p>',
        ],
        [
            'CODE' => 'diamond_sorting',
            'TITLE_RU' => 'СОРТИРОВКА АЛМАЗОВ',
            'TITLE_EN' => 'Sorting diamonds',
            'TITLE_CN' => '钻石分选',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Кимберлийский Процесс, получивший поддержку ООН, был создан, чтобы помешать конфликтным алмазам попадать на рынок — соответственно, чтобы помешать людям, которые нелегально добывают алмазы, зарабатывать на этом деньги. Для этого была разработана схема сертификации. Каждая партия алмазов на рынке должна сопровождаться сертификатом, подтверждающим, что эти алмазы добыты вне зоны конфликтных действий. Только с таким сертификатом партия алмазов может пересечь любую границу. </p> <p class="page-kimberley__text"> Кроме того, внутри страны выстраивается механизм контроля, который следит за тем, чтобы конфликтные или «серые» алмазы не попадали в торговую цепочку. Кимберлийский Процесс регулярно организует выездные проверки, чтобы подтвердить соответствие той или иной страны этим требованиям, а также располагает всей информацией о добыче и экспорте-импорте алмазов каждой страны. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__text"> The Kimberley Process, supported by the UN, was created to prevent conflict diamonds from entering the market - accordingly, to prevent people who illegally mine diamonds from making money from doing so. For this purpose, a certification scheme was developed. Each batch of diamonds in the market must be accompanied by a certificate confirming that these diamonds are mined outside the conflict zone. Only with such a certificate can a lot of diamonds cross any border. </p> <p class="page-kimberley__text">In addition, a control mechanism is being built within the country to ensure that conflict or grey diamonds do not enter the trade chain. The Kimberley Process regularly organizes on-site inspections to confirm a country\'s compliance with these requirements and has all the information on each country\'s diamond production and export-import.</p>',
            'DESCRIPTION_CN' => '<p class="page-kimberley__text">金伯利进程得到了联合国的支持，旨在防止冲突钻石进入市场，同时防止非法开采钻石的人获取金钱。为此，制定了认证计划。市场上的每批钻石必须附有证书，证明这些钻石是在冲突区域外开采的。只有这样的证书才能使一批钻石穿过任何边界。</p>
<p class="page-kimberley__text">
此外，在国内建立了一种控制机制，确保冲突或“灰色”钻石不会落入贸易链。金伯利进程定期组织现场视察，以确认一个国家遵守这些要求，并提供有关每个国家钻石的采矿和进出口的所有信息。
</p>
',
        ],
        [
            'CODE' => 'process_creation',
            'TITLE_RU' => 'создание процесса',
            'TITLE_EN' => 'Creating a process.',
            'TITLE_CN' => '流程创建',
            'DESCRIPTION_RU' => '<p class="page-kimberley__photo-text page-kimberley__photo-text--center">В 2000 году представители алмазодобывающих и торговых стран, а также неправительственных организаций провели первую в истории встречу для того, чтобы придумать, как решить эту проблему. Встреча проходила в городе Кимберли (ЮАР), потому и образовавшаяся в результате организация получила название Кимберлийского Процесса. </p>',
            'DESCRIPTION_EN' => '<p class="page-kimberley__photo-text page-kimberley__photo-text--center">  In 2000, representatives of diamond-producing and trading countries, as well as non-governmental organizations, met for the first time in history to figure out how to address the problem. The meeting was held in the city of Kimberley (South Africa), so the resulting organization was called the Kimberley Process.</p>',
            'DESCRIPTION_CN' => '<p class="page-kimberley__photo-text page-kimberley__photo-text--center"> 2000年，钻石开采和贸易国家的代表以及非政府组织举行了历史上的第一次会议，以找出如何解决这一问题。会议在金伯利市（南非）举行，因此由此产生的组织被称为金伯利进程。</p>',
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
            'DESCRIPTION_CN' => '<p class="page-kimberley__text">前一段时间，每个人都知道“血钻”这个词。冲突的钻石是非法开采并资助恐怖主义和反政府武装冲突的钻石。长期以来这样的钻石一直是非洲国家面临的一个大问题。
目前这句话开始一步一步被遗忘，冲突石头的问题几乎已经完全消除。现在您购买带有冲突石的戒指的机会减少到零 - 当然，除非您在商店购买，而不是在陌生人的黑暗小巷中以四分之一的价格购买。这要归功于金伯利进程。</p>',
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
            'DESCRIPTION_CN' => '<p class="page-responsibility__row-text">我们在雅库特开采钻石，所以我们认为我们有责任帮助当地居民和地区。 我们支持在雅库特的经济，社会，基础设施和创新项目，包括那些没有钻石的小村庄。</p>',
        ],
        [
            'CODE' => 'social_comfort',
            'TITLE_RU' => 'social_comfort',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text">Еще один залог стабильности – достойные условия жизни, комфортная среда обитания для людей. Большинство сотрудников АЛРОСА вместе с их семьями живет в Якутии, в небольших моногородах, созданных вокруг алмазодобывающих предприятий, на значительном удалении от крупных населенных пунктов. Поэтому наша задача – сделать их жизнь не слишком отличающейся от жизни в крупных городах.</p> <p class="page-responsibility__row-text">АЛРОСА ежегодно реализует масштабные социальные программы для работников и членов их семей, в рамках которых обеспечивает их здравоохранение и отдых, возможность заниматься спортом и посещать творческие кружки, культурные мероприятия; помогает с жильем и ипотекой, выплачивает негосударственные пенсии. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">Another factor for stability is decent living conditions, comfortable living environment for people. Most of ALROSA employees, together with their families, live in Yakutia in small monotowns created around diamond-mining enterprises, at a considerable distance from major population centers. For this reason, our task is to make their life not too different from life in typical towns and cities.</p> <p class="page-responsibility__row-text">ALROSA annually implements large-scale social programs for workers and their families, within which we provide health care and recreation, opportunities to play sports, attend creative clubs and cultural events; as well as help with housing and mortgage, pay non-state pensions.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__row-text">稳定性的另一个因素是舒服的人员生活条件。 大多数《阿尔罗萨钻石》的员工和他们的家庭生活在雅库特，即是钻石开采企业周围的小型单一工业城镇，距离大型定居点相当远。 因此我们的任务是使他们的生活与大城市的生活没有太大的不同。 </p>
<p class="page-responsibility__row-text">每年我公司为员工和他们的家属实施大规模的社会计划，在这一计划框架内提供他医疗保健和娱乐，参与体育和参加创意圈，文化活动的机会;帮助住房和抵押贷款，支付非国家养老金。</p>
',
        ],
        [
            'CODE' => 'growth_stability',
            'TITLE_RU' => 'Основы стабильности алроса',
            'TITLE_EN' => 'Anchor of stability in ALROSA',
            'TITLE_CN' => '《阿尔罗萨钻石》的稳定性的基础',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text">Залогом успешного развития любой компании являются не только объемы запасов или технологии, но прежде всего – трудовой коллектив. Сегодня команда АЛРОСА – это 37 тысяч профессионалов, мастеров своего дела. Мы хотим быть компанией, которой гордятся ее сотрудники. Вот почему мы обеспечиваем для них лучшие условия труда. Сегодня средняя зарплата сотрудников АЛРОСА вдвое выше, чем средняя зарплата в Якутии, и втрое выше, чем средняя зарплата в России. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">The cornerstone of sustainability of any company is not only the stock size or technology, but above all – the workforce. Today ALROSA’s team consists of 37 thousand professionals, masters of their craft. We want to be a company that makes its employees proud. That is why we provide them with better working conditions. Today, the average salary of ALROSA employees is double its level in Yakutia, and three times as high as the average salary in Russia.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__intro-text">任何公司成功发展的关键不仅是储备量或技术，但最重要的部分是劳动力。 目前阿尔罗萨的团队是37000工艺大师。 我们希望成为一家员工感到自豪的公司。 因此我们为他们提供最好的工作条件。 如今《阿尔罗萨钻石》员工的平均工资比雅库特的平均工资高两倍，比俄罗斯的平均工资高三倍。</p>',
        ],
        [
            'CODE' => 'social_responsibility',
            'TITLE_RU' => 'Социальная ответственность',
            'TITLE_EN' => 'Social responsibility',
            'TITLE_CN' => '社会责任',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text"> АЛРОСА по праву может называть себя не только самой крупной алмазодобывающей компанией, но и самой социально ответственной. По данным отчета PwC, АЛРОСА – лидер алмазодобывающей и золотодобывающей отрасли по доле отчислений на социальные программы. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">ALROSA can justly call itself not only the largest diamond mining company, but also the most socially responsible. According to the PwC report, ALROSA is the leader of the diamond mining and gold mining industry in terms of share of contributions to social programs.</p>',
            'DESCRIPTION_CN' => '<p class="page-responsibility__intro-text">《阿尔罗萨钻石》可以称自己不仅是最大的钻石开采公司，也是最有社会责任感的。 根据PwC报告，《阿尔罗萨钻石》是在对社会项目做出贡献的钻石和黄金采矿业的领导者。</p>',
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
