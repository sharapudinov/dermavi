<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use \App\Models\About\ResponsibleJewelleryCouncil;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class UpdateRjcIblock20200731162351569629 extends BitrixMigration
{
    private $newElements = [
        [
            'CODE' => 'rjc-first-section',
            'TITLE_RU' => 'Совет по&nbsp;ответственной практике в&nbsp;ювелирном бизнесе (RJC)',
            'TITLE_EN' => 'Responsible jewellery council',
            'TITLE_CN' => '责任珠宝业委员会（RJC）',
            'DESCRIPTION_RU' => 'Сегодня мировая алмазно-бриллиантовая отрасль сталкивается с рядом вызовов в сфере 
            обеспечения неконфликтного происхождения алмазов и ответственных цепочек поставок, повышения прозрачности, 
            исправления неверных представлений общественности о ее деятельности. Они могут существенно подорвать 
            устойчивое развитие отрасли и поэтому требуют от всех ее участников согласованных действий. В связи с этим 
            АЛРОСА проводит активную работу, направленную на создание и повышение эффективности международных 
            отраслевых механизмов по продвижению ответственных бизнес-практик, развитию системы отраслевого 
            саморегулирования и укреплению доверия потребителей в рамках многостороннего сотрудничества в 
            международных отраслевых организациях.',
            'DESCRIPTION_EN' => 'Today the global diamond sector faces a number of challenges related to securing 
            conflict-free origin of diamonds and responsible sourcing, promoting transparency, dispelling industry 
            misperceptions and raising public awareness about its work. They can seriously undermine the sustainable 
            development of the industry and, therefore, call for concerted actions. Thus, ALROSA is engaged in 
            establishing and enhancing the efficiency of international industry mechanisms designed to promote 
            responsible business practices, develop industry self-regulation and strengthen consumer confidence 
            within the framework of multilateral cooperation in international industry organizations.',
            'DESCRIPTION_CN' => '今天，全球钻石业在确保钻石非冲突来源及负责任供应链，提高透明度以及纠正公众对其作用的误解方面面临许多挑战。它们可能严重破坏该行业的可持续发展，因此需要所有参与者协调一致行动。阿尔罗萨(ALROSA)为此正积极致力于创建和提高国际产业机制的有效性，以促进负责任的业务实践，建立行业自律制度，并通过国际工业组织的多边合作增强消费者信心。',
        ],
        [
            'CODE' => 'rjc-about-first-block',
            'TITLE_RU' => 'Об RJC',
            'TITLE_EN' => 'About RJC',
            'TITLE_CN' => '关于RJC',
            'DESCRIPTION_RU' => 'RJC&nbsp;&mdash; международная некоммерческая организация, занимающаяся сертификацией и&nbsp;установлением стандартов.
            В&nbsp;ее&nbsp;состав входят более 1 000&nbsp;компаний, охватывающих всю цепочку поставок ювелирных изделий
            от&nbsp;месторождения до&nbsp;розничной продажи.',
            'DESCRIPTION_EN' => 'The Responsible Jewellery Council is an international not-for-profit standards and 
            certification organisation. It has more than 1,000 member companies that span the jewellery supply chain 
            from mine to retail.',
            'DESCRIPTION_CN' => 'RJC是非营利性质的国际标准与认证机构。它由1000多家公司组成，涵盖从采矿至零售的整个珠宝供应链。',
        ],
        [
            'CODE' => 'rjc-about-second-block',
            'TITLE_RU' => 'Об RJC (второй блок)',
            'TITLE_EN' => 'About RJC',
            'TITLE_CN' => '关于RJC',
            'DESCRIPTION_RU' => 'Члены RJC обязуются следовать Кодексу ответственных практик – международному стандарту 
            ответственного ведения бизнеса по добыче алмазов, золота и металлов платиновой группы. Кодекс затрагивает 
            права человека, трудовые права, влияние на окружающую среду, добывающую деятельность, раскрытие информации 
            и множество других важных тем в цепочке поставок. RJC также занимается многосторонними инициативами по 
            ответственному подбору поставщиков и комплексной юридической проверке цепочки поставок. Сертификация 
            цепочки поставок драгоценных металлов, проводимая RJC, поддерживает эти инициативы и может быть 
            использована в качестве инструмента для обеспечения более широкого круга возможностей для членов и 
            заинтересованных сторон.',
            'DESCRIPTION_EN' => 'RJC Members commit to and are independently audited against the RJC Code of 
            Practices – an international standard on responsible business practices for diamonds, gold and platinum 
            group metals. The Code of Practices addresses human rights, labour rights, environmental impact, mining 
            practices, product disclosure and many more important topics in the jewellery supply chain. RJC also works 
            with multi-stakeholder initiatives on responsible sourcing and supply chain due diligence. The RJC’s 
            Chain-of-Custody Certification for precious metals supports these initiatives and can be used as a tool 
            to deliver broader Member and stakeholder benefit.',
            'DESCRIPTION_CN' => 'RJC成员恪守《实践准则》 – 这是钻石、黄金和铂族金属领域的负责任业务经营国际标准。该准则规范了供应链中人权、劳工权利、环境影响、开采活动、信息披露以及许多其他重要事宜。责任珠宝业委员会还参与多边计划，负责供应商的选择和供应链的全面法律尽职调查。责任珠宝业委员会的贵金属供应链认证支持这些举措，可用作为成员和利益相关者提供更广泛机会的工具。',
        ],
        [
            'CODE' => 'rjc-about-third-block',
            'TITLE_RU' => 'Система сертификации RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">Система сертификации RJC обеспечивает компании, вовлеченные в цепочку поставок алмазов, золота и металлов платиновой группы, возможностью продемонстрировать свою приверженность этическим нормам и практикам ответственного ведения бизнеса.</p>
                    <p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">RJC является полноправным участником ISEAL Alliance&nbsp;– мировой ассоциации по&nbsp;стандартам устойчивого развития. Дополнительная информация об&nbsp;участниках RJC, сертификации и&nbsp;стандартах&nbsp;- <a class="link" href="https://www.responsiblejewellery.com">www.responsiblejewellery.com</a></p>',
            'DESCRIPTION_EN' => '<p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">RJC certification provides companies engaged in the diamond, gold and platinum group metals supply chain with the means to demonstrate their commitment to ethical behavior and responsible business practices.</p>
                    <p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">The RJC is a Full Member of the ISEAL Alliance – the global association for sustainability standards. For more information on RJC Members, Certification, and Standards please visit <a class="link" href="https://www.responsiblejewellery.com">www.responsiblejewellery.com</a></p>',
            'DESCRIPTION_CN' => '<p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">RJC认证体系为参与钻石、黄金和铂族金属供应链的公司提供了展示其忠于道德标准和负责任业务实践的机会。</p>
                    <p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">RJC是ISEAL Alliance （全球可持续发展标准联盟）的正式成员。有关RJC成员、认证和标准的更多信息详见 – <a class="link" href="https://www.responsiblejewellery.com">www.responsiblejewellery.com</a></p>',
        ],
        [
            'CODE' => 'alrosa-in-rjc-first-block',
            'TITLE_RU' => 'АЛРОСА в RJC',
            'TITLE_EN' => 'ALROSA in RJC',
            'TITLE_CN' => '阿尔罗萨（ALROSA）在 RJC',
            'DESCRIPTION_RU' => '<h3 class="page-jewellery-council__rjc-history-year">В 2016 году</h3>
                                <p class="page-jewellery-council__rjc-history-text">АЛРОСА вошла <br> в&nbsp;состав RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">В 2017 году</h3>
                                <p class="page-jewellery-council__rjc-history-text">АЛРОСА была избрана <br> в&nbsp;Совет директоров RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">В 2018-2019 годах</h3>
                                <p class="page-jewellery-council__rjc-history-text">
                                    Представители АЛРОСА избраны на&nbsp;должности заместителя председателя и&nbsp;члена Комитета по&nbsp;стандартам
                                </p>',
            'DESCRIPTION_EN' => '<h3 class="page-jewellery-council__rjc-history-year">In 2016</h3>
                                <p class="page-jewellery-council__rjc-history-text">ALROSA joined <br> the Responsible Jewellery Council</p>------<h3 class="page-jewellery-council__rjc-history-year">In 2017</h3>
                                <p class="page-jewellery-council__rjc-history-text">ALROSA was elected <br> to the RJC board of directors</p>------<h3 class="page-jewellery-council__rjc-history-year">In 2018-2019</h3>
                                <p class="page-jewellery-council__rjc-history-text">
                                    ALROSA representatives have been elected as Vice Chair and as a member of the Standards Committee
                                </p>',
            'DESCRIPTION_CN' => '<h3 class="page-jewellery-council__rjc-history-year">2016年</h3>
                                <p class="page-jewellery-council__rjc-history-text">阿尔罗萨(ALROSA)加入RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">2017年</h3>
                                <p class="page-jewellery-council__rjc-history-text">阿尔罗萨(ALROSA)被选为RJC董事会成员</p>------<h3 class="page-jewellery-council__rjc-history-year">2018-2019年</h3>
                                <p class="page-jewellery-council__rjc-history-text">
                                    阿尔罗萨(ALROSA)代表当选为副主席和标准委员会成员。
                                </p>',
        ],
        [
            'CODE' => 'alrosa-in-rjc-second-block',
            'TITLE_RU' => 'АЛРОСА в RJC (второй блок)',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-jewellery-council__text-container">Для проверки соответствия 
                деятельности АЛРОСА Кодексу ответственных практик RJC была привлечена крупная независимая аудиторская 
                компания PwC.<br>В течение нескольких месяцев АЛРОСА прошла всесторонний аудит, охватывающий программы 
                социальной ответственности, защиту окружающей среды, а также этические принципы ведения бизнеса - 
                противодействие коррупции, соблюдение прав человека, обеспечение достойных условий труда. В рамках 
                аудита представители RJC и компании-аудитора посетили объекты АЛРОСА, включая основные производственные 
                площадки – Мирнинский, Айхальский, Удачнинский горно-обогатительные комбинаты, дочерние предприятия 
                компании – «АЛРОСА-Нюрба», «Алмазы Анабара» и «Севералмаз», центры сортировки алмазного сырья в Мирном 
                и Архангельске.
        </p>
        <p class="page-jewellery-council__text-container">По итогам аудита АЛРОСА получила сертификат на максимально возможный срок – 3 года.</p>
        <p class="page-jewellery-council__text-container">
            <a href="https://www.responsiblejewellery.com/blog/member/alrosa/" target="_blank" rel="noopener noreferer">
                С деталями сертификации АЛРОСА можно ознакомиться здесь
                <svg class="icon icon--external-link page-jewellery-council__link-icon">
                    <use xlink:href="#icon-external_link"></use>
                </svg>
            </a>
        </p>',
            'DESCRIPTION_EN' => '<p class="page-jewellery-council__text-container">A large independent audit company, 
                PwC, has been engaged to assess ALROSA’s compliance with RJC’s Code of Practices. It took several months for ALROSA 
                to undergo a comprehensive sustainability audit covering social responsibility, environmental protection, and best 
                ethical principles of business, including fight against corruption, respect for human rights, ensuring decent working 
                conditions. As part of the audit, representatives of the auditing company visited many of ALROSA’s facilities, 
                including its main production sites – Mirny, Aikhal and Udachny Mining and Processing Divisions, and the Company’s 
                subsidiaries – ALROSA-Nyurba, Almazy Anabara and Severalmaz, diamond sorting centers in Mirny and Arkhangelsk.
        </p>
        <p class="page-jewellery-council__text-container">Following the audit, ALROSA achieved RJC certification for 3 years, the maximum time period granted by the RJC.</p>
        <p class="page-jewellery-council__text-container">
            <a href="https://www.responsiblejewellery.com/blog/member/alrosa/" target="_blank" rel="noopener noreferer">
                ALROSA’s Certification scope can be seen here 
                <svg class="icon icon--external-link page-jewellery-council__link-icon">
                    <use xlink:href="#icon-external_link"></use>
                </svg>
            </a>
        </p>',
            'DESCRIPTION_CN' => '<p class="page-jewellery-council__text-container">为检验阿尔罗萨（ALROSA）的经营活动是否符合RJC的《实践准则》，特引入大型独立审计公司普华永道（PwC）参与其中。<br>在几个月的时间里，对阿尔罗萨进行了全面的审计，涵盖社会责任计划、环境保护以及开展业务的道德原则 - 打击腐败、尊重人权和确保体面的工作条件。作为审计的一部分，RJC和审计公司代表访问了阿尔罗萨（ALROSA）的各项工程设施，包括主要生产基地 – 米尔内、艾哈尔、乌达奇内采矿选矿联合企业，公司子公司 – 《阿尔罗萨-纽尔巴》、《阿娜巴尔金刚石》和《谢韦尔金刚石》以及位于米尔内和阿尔汉格尔斯克的金刚石原料分选中心。
        </p>
        <p class="page-jewellery-council__text-container">根据审计结果，阿尔罗萨获得了有效期为3年的证书，这也是该证书可能的最长期限。</p>
        <p class="page-jewellery-council__text-container">
            <a href="https://www.responsiblejewellery.com/blog/member/alrosa/" target="_blank" rel="noopener noreferer">
                 有关阿尔罗萨（ALROSA）认证的详细信息详见
                <svg class="icon icon--external-link page-jewellery-council__link-icon">
                    <use xlink:href="#icon-external_link"></use>
                </svg>
            </a>
        </p>',
        ],
        [
            'CODE' => 'rjc-standards',
            'TITLE_RU' => 'Развитие стандартов RJC',
            'TITLE_EN' => 'DEVELOPMENT OF THE RJC STANDARDS',
            'TITLE_CN' => 'RJC标准的发展',
            'DESCRIPTION_RU' => 'В настоящее время мы активно занимаемся продвижением и реформированием стандартов RJC. 
            В апреле 2019 года основной документ – Кодекс ответственных практик – был существенно обновлен. 
            К числу основных улучшений относятся приведение требований в соответствие с Руководящими принципами 
            ОЭСР по ответственным цепочкам поставок минеральных ресурсов, усиление обязательств по соблюдению 
            прав человека на основе Руководящих принципов ООН по предпринимательской деятельности в аспекте прав 
            человека и охраны окружающей среды, а также новые требования по обнаружению искусственно выращенных 
            алмазов для защиты прав потребителей.',
            'DESCRIPTION_EN' => 'Currently we are actively engaged in championing and reforming the RJC standards. 
            Its main instrument – Code of Practices – was significantly updated in April 2019. The major improvements 
            include the alignment of due diligence requirements with the OECD Guidance for Responsible Mineral Supply 
            Chains, stronger commitments to observe human rights based on the UN Guiding Principles on Business and 
            Human Rights and to protect the environment, as well as new requirements on the detection of 
            laboratory-grown diamonds to protect consumer rights.',
            'DESCRIPTION_CN' => '我们目前正在积极推动和改革RJC的各项标准。 2019年4月，作为主要文件的《实践准则》得到了实质性更新。主要改进包括使要求符合经济发展与合作组织关于负责任矿物供应链的指导原则，根据《联合国人权与环境保护工商业指导原则》加强人权义务，以及保护消费者权益的人造金刚石检测新要求。',
        ],
    ];

    private $oldElements = [
        [
            'CODE' => 'rjc-first-section',
            'TITLE_RU' => 'Совет по&nbsp;ответственной практике в&nbsp;ювелирном бизнесе',
            'TITLE_EN' => 'Responsible jewellery council',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'Сегодня мировая алмазно-бриллиантовая отрасль сталкивается с&nbsp;рядом вызовов в&nbsp;сфере обеспечения неконфликтного
        происхождения алмазов и&nbsp;ответственных цепочек поставок, повышения прозрачности, исправления неверных представлений
        общественности о&nbsp;ее&nbsp;деятельности. Они могут существенно подорвать устойчивое развитие отрасли и&nbsp;поэтому
        требуют от&nbsp;всех ее&nbsp;участников согласованных действий. В&nbsp;связи с&nbsp;этим АЛРОСА проводит активную
        работу, направленную на&nbsp;создание и&nbsp;повышение эффективности международных отраслевых механизмов
        по&nbsp;продвижению ответственных бизнес-практик, развитию системы отраслевого саморегулирования и&nbsp;укреплению
        доверия потребителей в&nbsp;рамках многостороннего сотрудничества в&nbsp;международных отраслевых организациях.',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-about-first-block',
            'TITLE_RU' => 'Об RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'RJC&nbsp;&mdash; международная некоммерческая организация, занимающаяся сертификацией и&nbsp;установлением стандартов.
            В&nbsp;ее&nbsp;состав входят более 1100&nbsp;компаний, охватывающих всю цепочку поставок ювелирных изделий
            от&nbsp;месторождения до&nbsp;розничной продажи.',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-about-second-block',
            'TITLE_RU' => 'Об RJC (второй блок)',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'Члены RJC обязуются следовать Кодексу ответственных практик&nbsp;&mdash; международному стандарту ответственного ведения
                    бизнеса по&nbsp;добыче алмазов, золота и&nbsp;металлов платиновой группы. Кодекс затрагивает права человека, трудовые
                    права, влияние на&nbsp;окружающую среду, добывающую деятельность, раскрытие информации и&nbsp;множество других важных
                    тем в&nbsp;цепочке поставок. RJC также занимается многосторонними инициативами по&nbsp;ответственному подбору
                    поставщиков и&nbsp;комплексной юридической проверке цепочки поставок. Сертификация цепочки поставок драгоценных
                    металлов, проводимая RJC, поддерживает эти инициативы и&nbsp;может быть использована в&nbsp;качестве инструмента для
                    обеспечения более широкого круга возможностей для членов и&nbsp;заинтересованных сторон.',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-about-third-block',
            'TITLE_RU' => 'Система сертификации RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">Система сертификации RJC обеспечивает компании, вовлеченные в&nbsp;цепочку поставок алмазов, золота и&nbsp;металлов платиновой группы, возможностью продемонстрировать свою приверженность этическим нормам и&nbsp;практикам ответственного ведения бизнеса.</p>
                    <p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">RJC является полноправным участником ISEAL Alliance&nbsp;&mdash; мировой ассоциации по&nbsp;стандартам устойчивого развития. Дополнительная информация об&nbsp;участниках RJC, сертификации и&nbsp;стандартах&nbsp;&mdash; <a class="link" href="mailto:responsiblejewellery.com">responsiblejewellery.com</a></p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'alrosa-in-rjc-first-block',
            'TITLE_RU' => 'АЛРОСА в RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<h3 class="page-jewellery-council__rjc-history-year">В 2016 году</h3>
                                <p class="page-jewellery-council__rjc-history-text">АЛРОСА вошла <br> в&nbsp;состав RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">В 2017 году</h3>
                                <p class="page-jewellery-council__rjc-history-text">АЛРОСА была избрана <br> в&nbsp;Совет директоров RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">В 2018-2019 годах</h3>
                                <p class="page-jewellery-council__rjc-history-text">
                                    Представители АЛРОСА избраны на&nbsp;должности заместителя председателя и&nbsp;члена Комитета по&nbsp;стандартам
                                </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'alrosa-in-rjc-second-block',
            'TITLE_RU' => 'АЛРОСА в RJC (второй блок)',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-jewellery-council__text-container">Для проверки соответствия деятельности АЛРОСА Кодексу ответственных практик RJC была привлечена крупная независимая аудиторская компания PwC. В&nbsp;течение нескольких месяцев АЛРОСА прошла всесторонний аудит, охватывающий программы социальной ответственности, защиту окружающей среды, а&nbsp;также этические принципы ведения бизнеса&nbsp;&mdash;
            противодействие коррупции, соблюдение прав человека, обеспечение достойных условий труда. В&nbsp;рамках аудита
            представители RJC и&nbsp;компании-аудитора посетили объекты АЛРОСА, включая основные производственные
            площадки&nbsp;&mdash; Мирнинский, Айхальский, Удачнинский горно-обогатительные комбинаты, дочерние предприятия
            компании&nbsp;&mdash; &laquo;АЛРОСА-Нюрба&raquo;, &laquo;Алмазы Анабара&raquo; и&nbsp;&laquo;Севералмаз&raquo;, центры
            сортировки алмазного сырья в&nbsp;Мирном и&nbsp;Архангельске.</p>
        <p class="page-jewellery-council__text-container">По&nbsp;итогам аудита АЛРОСА получила сертификат на&nbsp;максимально возможный срок&nbsp;&mdash; 3&nbsp;года.</p>
        <p class="page-jewellery-council__text-container">
            <a href="javascript:;" target="_blank" rel="noopener noreferer">
                Подробнее о&nbsp;сертификации АЛРОСА
                <svg class="icon icon--external-link page-jewellery-council__link-icon">
                    <use xlink:href="#icon-external_link"></use>
                </svg>
            </a>
        </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-standards',
            'TITLE_RU' => 'Развитие стандартов RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'В&nbsp;настоящее время мы&nbsp;активно занимаемся продвижением и&nbsp;реформированием стандартов RJC. В&nbsp;апреле 2019 года основной документ&nbsp;&mdash; Кодекс ответственных практик&nbsp;&mdash; был существенно обновлен. К&nbsp;числу основных улучшений относятся приведение требований в&nbsp;соответствие с&nbsp;Руководящими принципами ОЭСР
            по&nbsp;ответственным цепочкам поставок минеральных ресурсов, усиление обязательств по&nbsp;соблюдению прав человека
            на&nbsp;основе Руководящих принципов ООН по&nbsp;предпринимательской деятельности в&nbsp;аспекте прав человека
            и&nbsp;охраны окружающей среды, а&nbsp;также новые требования по&nbsp;обнаружению искусственно выращенных алмазов для
            защиты прав потребителей.',
            'DESCRIPTION_EN' => '&nbsp;',
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
        $oldElements = ResponsibleJewelleryCouncil::getList();
        foreach ($oldElements as $oldElement) {
            $oldElement->delete();
        }

        $this->addNewElements();
    }

    private function addNewElements()
    {
        $sort = 100;
        foreach ($this->newElements as $element) {
            $sort += 100;
            ResponsibleJewelleryCouncil::create([
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

    private function addOldElements()
    {
        $sort = 100;
        foreach ($this->oldElements as $element) {
            $sort += 100;
            ResponsibleJewelleryCouncil::create([
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
        $newElements = ResponsibleJewelleryCouncil::getList();
        foreach ($newElements as $newElement) {
            $newElement->delete();
        }

        $this->addOldElements();
    }
}
