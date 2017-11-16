<ul class="nav">
    <div class="board-menu-div board-menu">
        <a class="btn btn-header">Português</a>
    </div>
</ul>
<div class="timers">
    <div class="user-time hide" {{Session::has('user_login_time') ? 'data-time=' . Session::get('user_login_time') .'000': '' }}>
        <i class="cp-clock"></i> <span></span>
    </div>
    <div class="server-time" data-time="{{Carbon\Carbon::now()->getTimestamp()}}000"></div>
</div>


