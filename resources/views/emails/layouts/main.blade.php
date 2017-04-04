<!DOCTYPE html>
<html>
<head>
    <title>{{$title}}</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    @include('emails.layouts.styles')
</head>
<body>
<div class="wrapper">
    <div class="container600">
        <div class="sm-padding">
            <div id="header" class="m-padding bg-blue">
                <a href="{{$host}}">
                    <img alt="" src="https://www.casinoportugal.pt/assets/portal/img/main_logo.png" />
                </a>
            </div>
            <div class="sep bg-blue"><hr></div>
            <div id="sub-header" class="sm-padding bg-blue">
                @yield('title')
            </div>
            <div id="content" class="m-padding bg-white">
                @yield('message')
                <p>A Equipa Casino Portugal</p>
            </div>
            <div class="sep bg-white"><hr></div>
            @include('emails.layouts.footer')
        </div>
    </div>
    {!! $debug or '' !!}
</div>
</body>
</html>