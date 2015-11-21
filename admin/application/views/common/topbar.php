<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
<a id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar"></a>
  <div class="navbar-header pull-left"> <a class="navbar-brand" href="<?php echo site_url();?>">网加金服后台管理系统</a> </div>
  <ul class="nav navbar-nav pull-right toolbar">
    <li class="dropdown">
    <a href="#" class="dropdown-toggle username" data-toggle="dropdown"><span class="hidden-xs"><?php echo $this->session->userdata('admin_name');?><i class="fa fa-caret-down"></i></span><img src="/admin/assets/img/avatar.png" alt="Dangerfield" /></a>
      <ul class="dropdown-menu userinfo arrow">
        <li class="username"> <a href="#">
          <div class="pull-left"><img src="/admin/assets/img/avatar.png" alt="Jeff Dangerfield"/></div>
          <div class="pull-right">
            <h5><?php echo $this->session->userdata('admin_name');?></h5>
            <small>上次登录<span><?php echo my_date($this->session->userdata('last_date'), 2);?></span></small></div>
          </a> </li>
        <li class="userlinks">
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('proflie');?>">个人资料<i class="pull-right fa fa-pencil"></i></a></li>
            <li><a href="<?php echo site_url('proflie/password');?>">修改密码<i class="pull-right fa fa-cog"></i></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo site_url('passport/sign_out');?>" class="text-right">注销登录<i class="pull-right fa fa-sign-out"></i></a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="dropdown"> <a href="javascript:void(0);" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><i class="fa fa-envelope"></i><span class="badge"></span></a>
      <ul class="dropdown-menu messages arrow">
        <li class="dd-header"> <span>You have 1 new message(s)</span> <span><a href="#">Mark all Read</a></span> </li>
        <div class="scrollthis">
          <li><a href="#"> <span class="time">12 mins</span> <img src="/admin/assets/img/avatar.png" alt="avatar" />
            <div><span class="name">Polly Paton</span><span class="msg">Uploaded all the files to server. Take a look.</span></div>
            </a></li>
        </div>
        <li class="dd-footer"><a href="#">查看所有信息</a></li>
      </ul>
    </li>
    <li class="dropdown"> <a href="javascript:void(0);" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><i class="fa fa-bell"></i><span class="badge"></span></a>
      <ul class="dropdown-menu notifications arrow">
        <li class="dd-header"> <span>You have 3 new notification(s)</span> <span><a href="#">Mark all Seen</a></span> </li>
        <div class="scrollthis">
          <li> <a href="#" class="notification-user active"> <span class="time">4 mins</span> <i class="fa fa-user"></i> <span class="msg">New user Registered. </span> </a> </li>
        </div>
        <li class="dd-footer"><a href="#">查看所有提醒</a></li>
      </ul>
    </li>
  </ul>
</header>