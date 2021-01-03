<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie8" lang="en-US" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 9 ]><html class="ie9" lang="en-US" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if gt IE 9]><!--><html lang="en-US" prefix="og: http://ogp.me/ns#"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="skype_toolbar" content="skype_toolbar_parser_compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:url"           content="http://oh.diamonds/">
    <meta property="og:type"          content="website">
    <meta property="og:title"         content="Oh! Diamonds">
    <meta property="og:description"   content="Cooming Soon">
    <meta property="og:image"         content="http://oh.diamonds/img/stub_sm.jpg">
    <meta property="og:image:width"   content="400">
    <meta property="og:image:height"  content="400">

    <link rel="shortcut icon" type="image/x-icon" href="<?= frontend()->img('favicon.ico'); ?>">

    <link href="<?= frontend()->css('external.css'); ?>" rel="stylesheet">
    <link href="<?= frontend()->css('internal.css'); ?>" rel="stylesheet">

    <!-- jquery подключаем в шапке т.к. какой-то js может быть внутри компонентов -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

    <!-- Для ie10 -->
    <!--[if !IE]><!-->
    <script>
        if(/*@cc_on!@*/false){document.documentElement.className+=' ie10';}
    </script>
    <!--<![endif]-->
</head>

<body>
