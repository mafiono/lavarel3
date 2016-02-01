<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Dashboard">
        <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
        <link rel="shortcut icon" href="http://alvarez.is/demo/dashio/favicon.png">

        <title>Casino dashboard</title>

        <!-- Bootstrap core CSS -->
        {!! HTML::style('assets/dashboard/css/bootstrap.css') !!}
        <!--external css-->
        {!! HTML::style('assets/dashboard/font-awesome/css/font-awesome.css') !!}
        {!! HTML::style('assets/dashboard/css/zabuto_calendar.css') !!}
        {!! HTML::style('assets/dashboard/js/gritter/css/jquery.gritter.css') !!}

        {!! HTML::style('assets/dashboard/css/datatables/dataTables.bootstrap.css'); !!}

        <!-- Custom styles for this template -->
        {!! HTML::style('assets/dashboard/css/style.css') !!}
        {!! HTML::style('assets/dashboard/css/style-responsive.css') !!}
        


    </head>

    <body>
        <section id="container" >
            @include('dashboard.header')

            <!-- **********************************************************************************************************************************************************
            MAIN SIDEBAR MENU
            *********************************************************************************************************************************************************** -->
            @include('dashboard.sidebar')

            <!-- **********************************************************************************************************************************************************
            MAIN CONTENT
            *********************************************************************************************************************************************************** -->
            <!--main content start-->
            
            @yield('content')
            
            <!--main content end-->
            <!--footer start-->
            @include('dashboard.footer')
            <!--footer end-->
        </section>

        <!-- js placed at the end of the document so the pages load faster -->
        {!! HTML::script('assets/dashboard/js/jquery.js') !!}
        {!! HTML::script('assets/dashboard/js/jquery-1.8.3.min.js') !!}
        {!! HTML::script('assets/dashboard/js/bootstrap.min.js') !!}
        {!! HTML::script('assets/dashboard/js/jquery.dcjqaccordion.2.7.js') !!}
        {!! HTML::script('assets/dashboard/js/jquery.scrollTo.min.js') !!}
        {!! HTML::script('assets/dashboard/js/jquery.nicescroll.js') !!}

        <!--common script for all pages-->
        {!! HTML::script('assets/dashboard/js/common-scripts.js') !!}

        {!! HTML::script('assets/dashboard/js/gritter/js/jquery.gritter.js') !!}
        {!! HTML::script('assets/dashboard/js/gritter-conf.js') !!}

        @yield('scripts')

    </body>
</html>
