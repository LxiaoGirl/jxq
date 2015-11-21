  <nav id="page-leftbar" role="navigation">
    <ul class="acc-menu" id="sidebar">
      <li id="search"> <a href="javascript:void(0);"><i class="fa fa-search opacity-control"></i></a>
        <form name="search_form" id="search_form" action="">
          <input type="text" class="search-query" placeholder="借款单号">
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>
      </li>
      <li class="divider"></li>
      <li><a href="<?php echo site_url();?>" title="返回首页"><i class="fa fa-home"></i> <span>系统首页</span></a></li>
	  <?php foreach($sidebar['link_url'] as $k1 => $v1):?>
	  <?php foreach($sidebar['data'] as $k => $v):?>
	  <?php   $authorized1=authorized($k);?>	  
	  <?php if($authorized1==$v1['link_url']):?>
	  <li><a href="javascript:void(0);" title="<?php echo $v1['node_name'];?>"><i class="fa <?php echo css_node($authorized1);?>"></i> <span><?php echo $v1['node_name'];?></span> </a>
	  <ul class="acc-menu">
	  <?php break;?>
	  <?php endif;?>	  
	  <?php endforeach;?>
	  <?php foreach($sidebar['data'] as $k => $v):?>
	  <?php   $authorized=authorized($k);?>
	  <?php if($authorized==$v1['link_url']):?>

      <li><a href="<?php echo site_url($k);?>" title="<?php echo interpret_node($k);?>"><?php echo interpret_node($k);?></a></li>
	  <?php endif;?>	
	  <?php endforeach;?>
	  <?php if($v1['link_url']==$authorized1):?>
	  </ul>
	  </li>	  
	  <?php endif;?>
	  <?php endforeach;?>    
	  </ul>
	  <!--
      <?php if(authorize('user/home,user/group,user/role,user/node')):?>
      <li><a href="javascript:void(0);" title="组织结构"><i class="fa fa-sitemap"></i> <span>组织结构</span> </a>
        <ul class="acc-menu">
          <?php if(authorize('user/home')):?>
          <li><a href="<?php echo site_url('user/home');?>" title="用户管理">用户管理</a></li>
          <?php endif;?>
          <?php if(authorize('user/group')):?>
          <li><a href="<?php echo site_url('user/group');?>" title="部门管理">部门管理</a></li>
          <?php endif;?>
          <?php if(authorize('user/role')):?>
          <li><a href="<?php echo site_url('user/role');?>" title="职位管理">职位管理</a></li>
          <?php endif;?>
          <?php if(authorize('user/node')):?>
          <li><a href="<?php echo site_url('user/node');?>" title="节点管理">节点管理</a></li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>
      <?php if(authorize('member/home,member/group,member/commission,member/card,member/log')):?>
      <li><a href="javascript:void(0);" title="会员管理"><i class="fa fa-comments"></i> <span>会员管理</span> </a>
        <ul class="acc-menu">
          <?php if(authorize('member/home')):?>
          <li><a href="<?php echo site_url('member/home');?>" title="会员列表">会员列表</a></li>
          <?php endif;?>
          <li><a href="<?php echo site_url('member/invite');?>" title="居间人列表">居间人列表</a></li>
          <?php if(authorize('member/group')):?>
          <li><a href="<?php echo site_url('member/group');?>" title="会员分组">会员分组</a></li>
          <?php endif;?>
          <?php if(authorize('member/commission')):?>
          <li><a href="<?php echo site_url('member/commission');?>" title="佣金提成">佣金提成</a></li>
          <?php endif;?>
          <?php if(authorize('member/card')):?>
          <li><a href="<?php echo site_url('member/card');?>" title="银行卡">银行卡</a></li>
          <?php endif;?>
          <?php if(authorize('member/log')):?>
          <li><a href="<?php echo site_url('member/log');?>" title="操作日志">操作日志</a></li>
          <?php endif;?>
          <?php if(authorize('member/authen')):?>

          <li><a href="<?php echo site_url('member/authen');?>" title="个人认证">个人认证</a></li>
		   <?php endif;?>
          <?php if(authorize('member/enterprise')):?>
           <li><a href="<?php echo site_url('member/enterprise');?>" title="企业认证">企业认证</a></li>
  		   <?php endif;?>
       </ul>
      </li>
      <?php endif;?>
      <?php if(authorize('borrow/home/create,borrow/home,borrow/apply,borrow/review')):?>
      <li><a href="javascript:void(0);" title="借款管理"><i class="fa fa-list-ol"></i> <span>借款管理</span></a>
        <ul class='acc-menu'>
		  <?php if(authorize('borrow/home/create')):?>
          <li><a href="<?php echo site_url('borrow/home/create');?>" title="发布标的">发布标的</a></li>
          <?php endif;?>
		  <?php if(authorize('borrow/home')):?>
		  <li><a href="<?php echo site_url('borrow/home');?>" title="借款记录">借款记录</a></li>
          <?php endif;?>
		  <?php if(authorize('borrow/apply')):?>
          <li><a href="<?php echo site_url('borrow/apply');?>" title="借款申请">借款申请</a></li>
          <?php endif;?>
		  <?php if(authorize('borrow/review')):?>
          <li><a href="<?php echo site_url('borrow/review');?>" title="资料审核">资料审核</a></li>
		  <?php endif;?>		  
		  </ul>
      </li>
      <?php endif;?>
      <?php if(authorize('finance/home,finance/trade,finance/payment,finance/recharge,finance/transaction')):?>
      <li><a href="javascript:void(0);" title="资金管理"><i class="fa fa-credit-card"></i> <span>资金管理</span></a>
        <ul class='acc-menu'>
          <?php if(authorize('finance/home')):?>
          <li><a href="<?php echo site_url('finance/home');?>" title="资金明细">资金明细</a></li>
          <?php endif;?>
          <?php if(authorize('finance/trade')):?>
          <li><a href="<?php echo site_url('finance/trade');?>" title="投资还款">投资还款</a></li>
          <?php endif;?>
          <?php if(authorize('finance/payment')):?>
          <li><a href="<?php echo site_url('finance/payment');?>" title="会员借款">会员借款</a></li>
          <?php endif;?>
          <?php if(authorize('finance/recharge')):?>
          <li><a href="<?php echo site_url('finance/recharge');?>" title="会员充值">会员充值</a></li>
          <?php endif;?>
          <?php if(authorize('finance/transaction')):?>
          <li><a href="<?php echo site_url('finance/transaction');?>" title="会员提现">会员提现</a></li>
          <?php endif;?>
		  <?php if(authorize('finance/payment')):?>
            <li><a href="<?php echo site_url('cron/repayment');?>" title="会员还款">会员还款</a></li>
          <?php endif;?>
          <?php if(authorize('finance/payment')):?>
            <li><a href="<?php echo site_url('finance/lianlian');?>" title="连连付转账到凯塔">连连付转账到凯塔</a></li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>
      <?php if(authorize('other/home,other/category,other/region,other/log')):?>
      <li><a href="javascript:void(0);" title="其它功能"><i class="fa fa-tasks"></i> <span>其它功能</span></a>
        <ul class="acc-menu">
          <?php if(authorize('other/home')):?>
          <li><a href="<?php echo site_url('other/home');?>" title="文章管理">文章管理</a></li>
          <?php endif;?>
          <?php if(authorize('other/category')):?>
          <li><a href="<?php echo site_url('other/category');?>" title="文章分类">文章分类</a></li>
          <?php endif;?>
          <?php if(authorize('other/region')):?>
          <li><a href="<?php echo site_url('other/region');?>" title="地区管理">地区管理</a></li>
          <?php endif;?>
          <?php if(authorize('other/log')):?>
          <li><a href="<?php echo site_url('other/log');?>" title="操作日志">操作日志</a></li>
          <?php endif;?>
		  <?php if(authorize('other/productcategory')):?>
          <li><a href="<?php echo site_url('other/productcategory');?>" title="产品类别管理">产品类别管理</a></li>
          <?php endif;?>
        </ul>
      </li>
      <?php endif;?>
    </ul>
	-->
  </nav>