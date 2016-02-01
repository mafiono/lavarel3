<!-- **********************************************************************************************************************************************************
TOP BAR CONTENT & NOTIFICATIONS
*********************************************************************************************************************************************************** -->
<!--header start-->
<header class="header black-bg">

    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    
    <!--logo start-->
    <a href="/admin-panel" class="logo">
        <b><span style="color: #f1c40f;">I</span><span style="color: #f1c40f;">B</span>ET<span style="color: #f1c40f;">U</span>P <small>beta</small></b></a>
    <!--logo end-->
    
    <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <ul class="nav top-menu">
        
            <!-- DEPOSITOS -->
            <li class="dropdown">
                <a title="Depositos" href="index.html#">
                    <i class="fa fa-money"></i>
                    <span class="badge bg-theme">4</span>
                </a>
            </li>
            <!-- DEPOSITOS end -->

            <!-- REGISTOS -->
            <li class="dropdown">
                <a title="Registos" href="index.html#">
                    <i class="fa fa-users"></i>
                    <span class="badge bg-theme">4</span>
                </a>
            </li>
            <!-- REGISTOS end -->

            <!-- EMAILS -->
            <li class="dropdown">
                <a title="Emails" href="index.html#">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-theme">4</span>
                </a>
            </li>
            <!-- EMAILS end -->

            <!-- LEVANTAMENTOS -->
            <li class="dropdown">
                <a title="Levantamentos" href="index.html#">
                    <i class="fa fa-random"></i>
                    <span class="badge bg-theme">4</span>
                </a>
            </li>
            <!-- LEVANTAMENTOS end -->

            <!-- NOTIFICAÇÕES -->
            <li class="dropdown">
                <a title="Notificações" href="index.html#">
                    <i class="fa fa-bell-o"></i>
                    <span class="badge bg-warning">4</span>
                </a>
            </li>
            <!-- NOTIFICAÇÕES end -->

        </ul>
        <!--  notification end -->
        
    </div>
    
    <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <ul class="nav top-menu">
            <!-- TOP USERS start -->
            <li  id="top_deposits" class="dropdown">
            </li>
            <!-- TOp USERS end -->
            <!-- inbox dropdown start-->
            <li id="top_registers" class="dropdown">
            </li>
            <!-- inbox dropdown end -->
            <!-- notification dropdown start-->
            <li id="top_emails" class="dropdown">
            </li>
            <!-- notification dropdown start-->
            <li id="top_withdraw" class="dropdown">
            </li>
            <!-- notification dropdown start-->
            <li id="top_client_email" class="dropdown">
            </li>
            <!-- notification dropdown end -->
        </ul>
        <!--  notification end -->
    </div>
    
    <div class="nav notify-row pull-right" id="top_menu">
        <!--  notification start -->
        <ul class="nav top-menu">
            <!--<li><a href="#">Search</a></li>-->
        </ul>
    </div>
</header>
<!--header end-->

<!-- Modal -->
<div aria-hidden="true" aria-labelledby="myBetaModal" role="dialog" tabindex="-1" id="betaModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Convidar amigos</h4>
            </div>
            <div class="modal-body">

                <form class="cmxform form-horizontal style-form" id="inviteationForm" method="POST" action="#">
                    <div class="form-group ">
                        <label for="nome" class="control-label col-lg-2">Nome</label>
                        <div class="col-lg-10">
                            <input class=" form-control" id="nome" name="nome" type="text" />
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="email" class="control-label col-lg-2">Email</label>
                        <div class="col-lg-10">
                            <input class="form-control " id="email" name="email" type="email" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
                <button class="btn btn-theme" type="button" id="btn_invitation">Convidar</button>
            </div>

        </div>
    </div>
</div>