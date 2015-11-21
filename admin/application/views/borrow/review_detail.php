<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->load->view('common/header');?>
</head>
<body class="">
<?php $this->load->view('common/topbar');?>
<div id="page-container">
  <?php $this->load->view('common/sidebar');?>
  <div id="page-content">
    <div id='wrap'>
      <div id="page-heading">
        <ol class="breadcrumb">
          <li><a href="<?php echo site_url();?>" title="返回首页">首页</a></li>
          <li><a href="<?php echo site_url('member');?>" title="会员管理">会员管理</a></li>
          <li class="active">资料审核</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('borrow/review');?>" class="btn btn-default" title="返回列表"><i class="fa fa-reply-all"></i>返回列表</a>
        </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
          <?php echo (isset($user_name)) ? $user_name : '';?>
          <?php echo ( ! empty($real_name)) ? '['.$real_name.']' : '';?></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6"> <img src="<?php if( ! empty($avater)):?><?php echo $avater;?><?php else:?>/admin/assets/img/avatar.png<?php endif;?>" width="100" height="100" alt="" class="pull-left" style="margin: 0 20px 20px 0">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th width="30%"></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>单位性质：</td>
                        <td><?php echo (isset($nature)) ? nature($nature) : '-';?></td>
                      </tr>
                      <tr>
                        <td>性别：</td>
                        <td><?php echo (isset($gender)) ? gender($gender) : '-';?></td>
                      </tr>
                      <tr>
                        <td>手机号码：</td>
                        <td><?php echo (isset($mobile)) ? $mobile : '-';?></td>
                      </tr>
                      <tr>
                        <td>身份证号码：</td>
                        <td><?php echo (isset($nric)) ? $nric : '-';?></td>
                      </tr>
                      <tr>
                        <td>电话号码：</td>
                        <td><?php echo (isset($phone)) ? $phone : '-';?></td>
                      </tr>
                      <tr>
                        <td>邮件：</td>
                        <td><?php echo (isset($email)) ? $email : '-';?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <h3>其它信息</h3>
                <p>会员状态：<?php echo (isset($status)) ? user_status($status) : '';?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="tab-container tab-success">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#base" data-toggle="tab">基础资料</a></li>
                    <li class=""><a href="#company" data-toggle="tab">工作单位</a></li>
                    <li class=""><a href="#contacts" data-toggle="tab">联系人</a></li>
                    <li class=""><a href="#attachment" data-toggle="tab">证明材料</a></li>
                    <li><a href="#enterprise" data-toggle="tab">企业资料</a></li>
                  </ul>
                  <div class="tab-content" style="border:none;padding: 10px 0;">
                    <div class="tab-pane active" id="base">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <tbody>
                            <tr>
                              <td width="30%">最高学历</td>
                              <td><?php echo (isset($extend[2]['education'])) ? $extend[2]['education'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>毕业院校</td>
                              <td><?php echo (isset($extend[2]['school'])) ? $extend[2]['school'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>毕业时间</td>
                              <td><?php echo (isset($extend[2]['graduation_date'])) ? $extend[2]['graduation_date'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>工作几年</td>
                              <td><?php echo (isset($extend[2]['working_age'])) ? $extend[2]['working_age'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>月收入</td>
                              <td><?php echo (isset($extend[2]['income_range'])) ? $extend[2]['income_range'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>婚姻状况</td>
                              <td><?php echo (isset($extend[2]['is_marry'])) ? marry($extend[2]['is_marry']) : '-';?></td>
                            </tr>
                            <tr>
                              <td>有无子女</td>
                              <td><?php echo (isset($extend[2]['offspring'])) ? offspring($extend[2]['offspring']) : '-';?></td>
                            </tr>
                            <tr>
                              <td>是否有房</td>
                              <td><?php echo (isset($extend[2]['estates'])) ? estates($extend[2]['estates']) : '-';?></td>
                            </tr>
                            <tr>
                              <td>已经购车</td>
                              <td><?php echo (isset($extend[2]['vehicle'])) ? vehicle($extend[2]['vehicle']) : '-';?></td>
                            </tr>
                            <tr>
                              <td>车辆型号</td>
                              <td><?php echo (isset($extend[2]['vehicle_type'])) ? $extend[2]['vehicle_type'] : '-';?></td>
                            </tr>
                            <tr>
                                <td>户籍地址</td>
                                <td><?php echo (isset($extend[2]['registered'])) ? $extend[2]['registered'] : '-';?></td>
                            </tr>
                            <tr>
                                <td>居住地址</td>
                                <td><?php echo (isset($extend[2]['place'])) ? $extend[2]['place'] : '-';?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane" id="company">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <tbody>
                            <tr>
                              <td width="30%">单位名称</td>
                              <td><?php echo (isset($extend[3]['organization'])) ? $extend[3]['organization'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>公司行业</td>
                              <td><?php echo (isset($extend[3]['industry'])) ? $extend[3]['industry'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>公司性质</td>
                              <td><?php echo (isset($extend[3]['property'])) ? $extend[3]['property'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>公司规模</td>
                              <td><?php echo (isset($extend[3]['staff'])) ? $extend[3]['staff'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>入职时间</td>
                              <td><?php echo (isset($extend[3]['hiredate'])) ? $extend[3]['hiredate'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>职务</td>
                              <td><?php echo (isset($extend[3]['job'])) ? $extend[3]['job'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>单位地址</td>
                              <td><?php echo (isset($extend[3]['province'])) ? $extend[3]['province'].$extend[3]['city'].$extend[3]['district'].$extend[3]['address'] : '-';?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane" id="contacts">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <tbody>
                            <tr>
                              <td width="30%">直系亲属1姓名</td>
                              <td><?php echo (isset($extend[4]['name1'])) ? $extend[4]['name1'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>直系亲属1电话</td>
                              <td><?php echo (isset($extend[4]['phone1'])) ? $extend[4]['phone1'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>直系亲属2姓名</td>
                              <td><?php echo (isset($extend[4]['name2'])) ? $extend[4]['name2'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>直系亲属2电话</td>
                              <td><?php echo (isset($extend[4]['phone2'])) ? $extend[4]['phone2'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>配偶姓名</td>
                              <td><?php echo (isset($extend[4]['spouse_name'])) ? $extend[4]['spouse_name'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>配偶电话</td>
                              <td><?php echo (isset($extend[4]['spouse_phone'])) ? $extend[4]['spouse_phone'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>同事姓名</td>
                              <td><?php echo (isset($extend[4]['colleague_name'])) ? $extend[4]['colleague_name'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>同事电话</td>
                              <td><?php echo (isset($extend[4]['colleague_phone'])) ? $extend[4]['colleague_phone'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>朋友姓名</td>
                              <td><?php echo (isset($extend[4]['friend_name'])) ? $extend[4]['friend_name'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>朋友电话</td>
                              <td><?php echo (isset($extend[4]['friend_phone'])) ? $extend[4]['friend_phone'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>紧急联系人姓名</td>
                              <td><?php echo (isset($extend[4]['contact_name'])) ? $extend[4]['contact_name'] : '-';?></td>
                            </tr>
                            <tr>
                              <td>紧急联系人电话</td>
                              <td><?php echo (isset($extend[4]['contact_phone'])) ? $extend[4]['contact_phone'] : '-';?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane" id="attachment">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <tbody>
                            <tr>
                              <td width="30%">身份证</td>
                              <td><?php if(isset($extend[5]['nric'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['nric'];?>" title="身份证" target="_blank"><?php echo $extend[5]['nric'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>收入证明</td>
                              <td><?php if(isset($extend[5]['income'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['income'];?>" title="收入证明" target="_blank"><?php echo $extend[5]['income'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>银行卡流水</td>
                              <td><?php if(isset($extend[5]['bank'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['bank'];?>" title="银行卡流水" target="_blank"><?php echo $extend[5]['bank'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>央行信用报告</td>
                              <td><?php if(isset($extend[5]['credit'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['credit'];?>" title="央行信用报告" target="_blank"><?php echo $extend[5]['credit'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>住房证明</td>
                              <td><?php if(isset($extend[5]['certificate'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['certificate'];?>" title="住房证明" target="_blank"><?php echo $extend[5]['certificate'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>技术职称</td>
                              <td><?php if(isset($extend[5]['technica'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['technica'];?>" title="技术职称" target="_blank"><?php echo $extend[5]['technica'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>驾驶证</td>
                              <td><?php if(isset($extend[5]['driving'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['driving'];?>" title="驾驶证" target="_blank"><?php echo $extend[5]['driving'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>家属身份证</td>
                              <td><?php if(isset($extend[5]['relation'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['relation'];?>" title="家属身份证" target="_blank"><?php echo $extend[5]['relation'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>结婚证</td>
                              <td><?php if(isset($extend[5]['marriage'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['marriage'];?>" title="结婚证" target="_blank"><?php echo $extend[5]['marriage'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>股权证明</td>
                              <td><?php if(isset($extend[5]['stock'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['stock'];?>" title="股权证明" target="_blank"><?php echo $extend[5]['stock'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>营业执照副本</td>
                              <td><?php if(isset($extend[5]['license'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['license'];?>" title="营业执照副本" target="_blank"><?php echo $extend[5]['license'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>税务登记证副本</td>
                              <td><?php if(isset($extend[5]['tax'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['tax'];?>" title="税务登记证副本" target="_blank"><?php echo $extend[5]['tax'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                            <tr>
                              <td>其它资产证明</td>
                              <td><?php if(isset($extend[5]['assets'])):?>
                                <a href="<?php echo $this->config->item('application_domain').'/'.$extend[5]['assets'];?>" title="其它资产证明" target="_blank"><?php echo $extend[5]['assets'];?></a>
                                <?php else:?>
                                未上传
                                <?php endif;?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane" id="enterprise">
                          <div class="table-responsive">
                              <table class="table table-striped">
                                  <tbody>
                                  <tr>
                                      <td width="30%">单位名称</td>
                                      <td><?php echo (isset($extend[1]['organization'])) ? $extend[1]['organization'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>公司行业</td>
                                      <td><?php echo (isset($extend[1]['industry'])) ? $extend[1]['industry'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>单位性质</td>
                                      <td><?php echo (isset($extend[1]['property'])) ? $extend[1]['property'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>公司规模</td>
                                      <td><?php echo (isset($extend[1]['staff'])) ? $extend[1]['staff'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>成立时间</td>
                                      <td><?php echo (isset($extend[1]['reg_date'])) ? $extend[1]['reg_date'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>营业执照编号</td>
                                      <td><?php echo (isset($extend[1]['license'])) ? $extend[1]['license'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>税务登记证编号</td>
                                      <td><?php echo (isset($extend[1]['tax_no'])) ? $extend[1]['tax_no'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>年营业额</td>
                                      <td><?php echo (isset($extend[1]['turnover'])) ? $extend[1]['turnover'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>办公电话</td>
                                      <td><?php echo (isset($extend[1]['phone'])) ? $extend[1]['phone'] : '-';?></td>
                                  </tr>
                                  <tr>
                                      <td>公司地址</td>
                                      <td><?php echo (isset($extend[1]['address'])) ? $extend[1]['address'] : '-';?></td>
                                  </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
</body>
</html>