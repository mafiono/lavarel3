<!DOCTYPE html>
<html>
<head>
    <title>Template Casino Portugal</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <style>
        /* defaults */
        html, body, div, p, ul, ol, li, h1, h2, h3, h4, h5, h6 {
            margin: 0;
            padding: 0;
        }
        body {
            font-size:10px;
            line-height:10px;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: Arial, sans-serif;
        }
        h1 {
            font-size: 28px;
            color: #FFFFFF;
            text-align: center;
            line-height: 32px;
            margin-bottom: 24px;
        }
        h2 {
            font-size: 24px;
            line-height: 28px;
            margin-bottom: 20px;
        }
        h3 {
            font-size: 20px;
            line-height: 24px;
            margin-bottom: 16px;
        }
        p {
            font-size: 14px;
            line-height: 20px;
            font-family: Arial, sans-serif;
        }
        /* colors */
        body {
            background-color: #F4F4F4;
        }
        .wrapper {
            background-color: #F4F4F4;
        }
        a {
            color: #58585A;
        }
        .bg-blue {
            background-color: #1E293E;
            color: #58585A;
        }
        .bg-white {
            background-color: #FFFFFF;
            color: #666666;
        }
        .bg-blue2 {
            background-color: #C69B66;
            color: #FFFFFF;
        }
        /* images */
        img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0;
        }
        /* spacings */
        .sm-padding {
            padding: 10px;
        }
        .m-padding {
            padding: 30px;
        }
        .m-padding-tb {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        /* layout */
        .container600 {
            width: 600px;
            margin: 0 auto;
        }
        @media all and (max-width: 599px) {
            .container600 {
                width: 100%;
            }
        }
        /* main structure */
        #header img {
            width: 158px;
        }
        #content a {
            display: block;
            width: 100%;
            font-size: 16px;
            text-align: center;
        }
        #content img {
            width: 118px;
            display: block;
            margin: auto;
        }
        #footer {
            text-align: center;
        }
        #footer img {
            width: 118px;
            display: block;
            margin: auto;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <div class="container600">
        <div class="sm-padding">
            <div id="header" class="m-padding bg-blue">
                <img alt="" src="https://www.casinoportugal.pt/assets/portal/img/main_logo.png" />
            </div>
            <div id="sub-header" class="sm-padding bg-blue">
                <h1>Bem-vindo ao seu Casino!</h1>
            </div>
            <div id="content" class="m-padding bg-white">
                <p>Olá {{ $data['name'] }},</p>
                <p>&nbsp;</p>
                <p>A sua identidade foi adicionada com sucesso.
                    Agora vai poder começar a apostar nos seus jogos favoritos, basta apenas confirmar o seu email:</p>
                <p>&nbsp;</p>
                <p><a href="{{ $url }}"><img alt="" src="http://tinyimg.io/i/kQl0e5h.png" /></a></p>
                <p>&nbsp;</p>
                <p>E para celebrar a sua adesão temos o prazer em oferecer-lhe um bónus especial que irá ser disponibilizado na sua conta após esta confirmação.</p>
                <p>&nbsp;</p>
                <p>A Equipa Casino Portugal deseja-lhe boa sorte!</p>
                <p>Conheça todas as novidades através das nossas redes sociais.</p>
            </div>
            <div id="footer" class="m-padding bg-blue2">
                <p><a href="https://www.casinoportugal.pt">casinoportugal.pt</a> © 2017</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>