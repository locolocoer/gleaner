<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

/**屏蔽报错**/
error_reporting(0);

include_once('core/func.php');

function themeVersion()
{
    return '1.2.0.Alpha';
}


function themeConfig($form)
{
    $options = Helper::options(); ?>

    <link href="<?php $options->themeUrl('./assets/css/third/toastr.min.css'); ?>" type="text/css" rel="stylesheet" />
    <link href="<?php $options->themeUrl('./assets/semantic/semantic.min.css'); ?>" type="text/css" rel="stylesheet" />
    <link href="<?php $options->themeUrl('./assets/layui/css/layui.css'); ?>" type="text/css" rel="stylesheet" />
    <link href="<?php $options->themeUrl('./assets/css/third/bearui.min.css'); ?>" type="text/css" rel="stylesheet">

    <script src="<?php $options->themeUrl('./assets/js/jquery.min.js'); ?>" type="application/javascript"></script>
    <script src="<?php $options->themeUrl('./assets/js/toastr.min.js'); ?>" type="application/javascript"></script>
    <script src="<?php $options->themeUrl('./assets/layui/layui.js'); ?>" type="application/javascript"></script>
    <script src="<?php $options->themeUrl('./assets/js/bearui.min.js'); ?>" type="application/javascript"></script>

    <div class="bearui_config">
        <div>
            <div class="bearui_config__aside">
                <div class="logo">
                    <div class="ui blue big label">🎨 Gleaner - 拾穗</div>
                </div>
                <ul class="tabs">
                    <li class="item" data-current="bearui_notice"><i class="assistive listening systems icon"></i> 使用说明
                    </li>
                    <li class="item" data-current="bearui_global"><i class="american sign language interpreting icon"></i>
                        基础设置
                    </li>
                    <li class="item" data-current="bearui_high"><i class="industry icon"></i> 高级设置</li>
                    <li class="item" data-current="bearui_other"><i class="heading icon"></i> 其他设置</li>
                </ul>
            </div>
        </div>
        <div class="bearui_config__notice">

            <div class="ui blue message">
                <div class="header">
                    欢迎使用 Gleaner 主题，以下是使用须知~
                </div>
                <ul class="list">
                    <li>本主题如需使用 emoji 表情，安装时需将数据库编码设置为 <b>utf8mb4</b> 否则将导致某些不可预知的错误.
                    </li>
                    <li>本主题修改自开源项目：<a href="https://github.com/whitebearcode/typecho-bearcool"
                            target="_blank">typecho-bearcool</a>，遵从开源许可，同时感谢原作者.
                    </li>
                    <li>本主题完全免费，接受任何意见与反馈，但不做任何质量保证.</li>
                </ul>
            </div>
            <div class="ui large message">
                Gleaner 在原版主题上进行了较大修改，更趋向传统博客，也即文字内容为主<br>
                使用过程中发生任何问题可于项目主页提交 <a href="https://github.com/fordes123/gleaner/issues" target="_blank">issues</a> ，或移步<a
                    href="https://blog.fordes.top" target="_blank"></a>博客进行反馈<br>
                最后，祝您使用愉快:)
            </div>
            <center>
                <div class="ui labeled button" tabindex="0">

                    <div class="ui black button">
                        <i class="github icon"></i> 当前版本:
                    </div>
                    <a class="ui basic black left pointing label" href="https://github.com/fordes123/gleaner">
                        V<?php echo themeVersion(); ?>
                    </a>
                </div>
            </center>
            <?php require_once('core/backup.php'); ?>

        </div>
        <?php
        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
    <i class="chess rook icon"></i>
        <div class="content">头部设置
            <div class="sub header">设置头部信息，站点标题、SEO相关等</div>
        </div>
    </h2>
</div>
HTML;
        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_title = new Typecho_Widget_Helper_Form_Element_Text('bcool_title', null, $options->title, '站点标题', '请填入站点标题，不要太长');
        $bcool_title->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_title);

        $bcool_titleb = new Typecho_Widget_Helper_Form_Element_Text('bcool_titleb', null, '', '站点副标题', '请填入站点副标题，不要太长，将显示在站点标题的后面，不填则不显示');
        $bcool_titleb->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_titleb);

        $bcool_keywords = new Typecho_Widget_Helper_Form_Element_Text('bcool_keywords', null, $options->keywords, '站点SEO关键词', '请填入站点SEO关键词,请以半角逗号 "," 分割多个关键字.');
        $bcool_keywords->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_keywords);

        $bcool_description = new Typecho_Widget_Helper_Form_Element_Text('bcool_description', null, $options->description, '站点SEO描述', '请填入站点SEO描述,不要太长.');
        $bcool_description->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_description);

        $bcool_favicon = new Typecho_Widget_Helper_Form_Element_Text('bcool_favicon', null, '', '站点Favicon', '请填入站点Favicon图标地址');
        $bcool_favicon->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_favicon);

        $bcool_menu = new Typecho_Widget_Helper_Form_Element_Radio('bcool_menu', array(false => _t('形成单独导航 '), true => _t(' 集合为下拉列表')), false, _t('独立页面在导航菜单的表现形式'), _t("默认将为每一个独立页面生成导航项"));
        $bcool_menu->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_menu);

        $bcool_select_origin = new Typecho_Widget_Helper_Form_Element_Select(
            'bcool_select_origin',
            array(
                'default' => '使用本地服务',
                'tiktok' => '使用火山引擎',
                'github' => '使用github+jsdevliver',
                '123cloud' => '使用123cloud'
            ),
            'on',
            '选择图片加载路径',
            ''
        );
        $bcool_select_origin->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_select_origin);

        $bcool_select_origin_template = new Typecho_Widget_Helper_Form_Element_Text('bcool_select_origin_template', null, _t("~tplv-ocfscsxaet-image.image"), '火山引擎模板', '请填入模板,默认：~tplv-ocfscsxaet-image.image');
        $bcool_select_origin_template->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_select_origin_template);
        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
    <i class="align justify icon"></i>
        <div class="content">
            首页设置
            <div class="sub header">设置首页APP、头像等</div>
        </div>
    </h2>
</div>
HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_avatar = new Typecho_Widget_Helper_Form_Element_Text('bcool_avatar', null, '', '首页头像', '首页头像地址');
        $bcool_avatar->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_avatar);

        $bcool_mylogo = new Typecho_Widget_Helper_Form_Element_Text('bcool_mylogo', null, '', '首页logo', '首页logo地址');
        $bcool_mylogo->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_mylogo);

        $bcool_nickname = new Typecho_Widget_Helper_Form_Element_Text('bcool_nickname', null, $options->title, '首页昵称', '如为空默认显示网站标题');
        $bcool_nickname->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_nickname);

        $bcool_slogan = new Typecho_Widget_Helper_Form_Element_Text('bcool_slogan', null, '黄花仍在，朱颜未衰，正好忘怀', '首页slogan', '随便说点什么，座右铭、金句之类');
        $bcool_slogan->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_slogan);

        $bcool_app = new Typecho_Widget_Helper_Form_Element_Textarea('bcool_app', null, '', '首页App展示', '请填入需要在首页展示的应用信息<br>格式：名称|图标名|链接，每行一条，前后不留空格<br>示例：Github|fa-brands fa-github|https://github.com/fordes123<br>图标为 FontAwsome6.2 免费图标全称，参考：https://fontawesome.com/search?m=free');
        $bcool_app->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_app);


        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
    <i class="hockey puck icon"></i>
        <div class="content">
            底部设置
            <div class="sub header">设置ICP备案号、自定义代码等信息</div>
        </div>
    </h2>
</div>
HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_icp = new Typecho_Widget_Helper_Form_Element_Text('bcool_icp', null, '', 'ICP备案号', '请在这里填入站点ICP备案号');
        $bcool_icp->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_icp);

        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
        <i class="moon icon"></i>
        <div class="content">
            颜色设置
            <div class="sub header">设置深色/浅色模式相关功能</div>
        </div>
    </h2>
</div>
HTML;
HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_avatar = new Typecho_Widget_Helper_Form_Element_Text('bcool_avatar', null, '', '首页头像', '首页头像地址');
        $bcool_avatar->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_avatar);

        $bcool_mylogo = new Typecho_Widget_Helper_Form_Element_Text('bcool_mylogo', null, '', '首页logo', '首页logo地址');
        $bcool_mylogo->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_mylogo);

        $bcool_nickname = new Typecho_Widget_Helper_Form_Element_Text('bcool_nickname', null, $options->title, '首页昵称', '如为空默认显示网站标题');
        $bcool_nickname->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_nickname);

        $bcool_slogan = new Typecho_Widget_Helper_Form_Element_Text('bcool_slogan', null, '黄花仍在，朱颜未衰，正好忘怀', '首页slogan', '随便说点什么，座右铭、金句之类');
        $bcool_slogan->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_slogan);

        $bcool_app = new Typecho_Widget_Helper_Form_Element_Textarea('bcool_app', null, '', '首页App展示', '请填入需要在首页展示的应用信息<br>格式：名称|图标名|链接，每行一条，前后不留空格<br>示例：Github|fa-brands fa-github|https://github.com/fordes123<br>图标为 FontAwsome6.2 免费图标全称，参考：https://fontawesome.com/search?m=free');
        $bcool_app->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_app);


        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
    <i class="hockey puck icon"></i>
        <div class="content">
            底部设置
            <div class="sub header">设置ICP备案号、自定义代码等信息</div>
        </div>
    </h2>
</div>
HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_icp = new Typecho_Widget_Helper_Form_Element_Text('bcool_icp', null, '', 'ICP备案号', '请在这里填入站点ICP备案号');
        $bcool_icp->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_icp);

        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
        <i class="moon icon"></i>
        <div class="content">
            颜色设置
            <div class="sub header">设置深色/浅色模式相关功能</div>
        </div>
    </h2>
</div>
HTML;
        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_high');
        $form->addItem($layout);

        $bcool_darkmode = new Typecho_Widget_Helper_Form_Element_Radio('bcool_darkmode', array(true => _t('自动跟随系统 '), false => _t(' 手动开关切换')), true, _t('深/浅色切换模式'), _t("跟随系统将检测系统深色/浅色主题模式自动切换，手动则展示开关"));
        $bcool_darkmode->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($bcool_darkmode);

        $bcool_darkcolor = new Typecho_Widget_Helper_Form_Element_Text('bcool_darkcolor', null, 'linear-gradient(0deg,#ffa17f,#00223e)', '深色模式首页背景色', '建议使用渐变色，如：linear-gradient(0deg,#ffa17f,#00223e)');
        $bcool_darkcolor->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($bcool_darkcolor);

        $bcool_lightcolor = new Typecho_Widget_Helper_Form_Element_Text('bcool_lightcolor', null, 'linear-gradient(0deg, #4584b4, #1e4877)', '浅色模式首页背景色', '建议使用渐变色，如：linear-gradient(0deg, #4584b4, #1e4877)');
        $bcool_lightcolor->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($bcool_lightcolor);

        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
        <i class="ban icon"></i>
        <div class="content">
            评论过滤
            <div class="sub header">默认强制性过滤全空格评论、包含XSS危险内容评论，其余需过滤内容您可以通过以下项进行设置。</div>
        </div>
    </h2>
</div>

HTML;
HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_avatar = new Typecho_Widget_Helper_Form_Element_Text('bcool_avatar', null, '', '首页头像', '首页头像地址');
        $bcool_avatar->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_avatar);

        $bcool_mylogo = new Typecho_Widget_Helper_Form_Element_Text('bcool_mylogo', null, '', '首页logo', '首页logo地址');
        $bcool_mylogo->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_mylogo);

        $bcool_nickname = new Typecho_Widget_Helper_Form_Element_Text('bcool_nickname', null, $options->title, '首页昵称', '如为空默认显示网站标题');
        $bcool_nickname->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_nickname);

        $bcool_slogan = new Typecho_Widget_Helper_Form_Element_Text('bcool_slogan', null, '黄花仍在，朱颜未衰，正好忘怀', '首页slogan', '随便说点什么，座右铭、金句之类');
        $bcool_slogan->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_slogan);

        $bcool_app = new Typecho_Widget_Helper_Form_Element_Textarea('bcool_app', null, '', '首页App展示', '请填入需要在首页展示的应用信息<br>格式：名称|图标名|链接，每行一条，前后不留空格<br>示例：Github|fa-brands fa-github|https://github.com/fordes123<br>图标为 FontAwsome6.2 免费图标全称，参考：https://fontawesome.com/search?m=free');
        $bcool_app->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_app);


        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
    <i class="hockey puck icon"></i>
        <div class="content">
            底部设置
            <div class="sub header">设置ICP备案号、自定义代码等信息</div>
        </div>
    </h2>
</div>
HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_global');
        $form->addItem($layout);

        $bcool_icp = new Typecho_Widget_Helper_Form_Element_Text('bcool_icp', null, '', 'ICP备案号', '请在这里填入站点ICP备案号');
        $bcool_icp->setAttribute('class', 'bearui_content bearui_global');
        $form->addInput($bcool_icp);

        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
        <i class="moon icon"></i>
        <div class="content">
            颜色设置
            <div class="sub header">设置深色/浅色模式相关功能</div>
        </div>
    </h2>
</div>
HTML;
        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_high');
        $form->addItem($layout);

        $bcool_darkmode = new Typecho_Widget_Helper_Form_Element_Radio('bcool_darkmode', array(true => _t('自动跟随系统 '), false => _t(' 手动开关切换')), true, _t('深/浅色切换模式'), _t("跟随系统将检测系统深色/浅色主题模式自动切换，手动则展示开关"));
        $bcool_darkmode->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($bcool_darkmode);

        $bcool_darkcolor = new Typecho_Widget_Helper_Form_Element_Text('bcool_darkcolor', null, 'linear-gradient(0deg,#ffa17f,#00223e)', '深色模式首页背景色', '建议使用渐变色，如：linear-gradient(0deg,#ffa17f,#00223e)');
        $bcool_darkcolor->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($bcool_darkcolor);

        $bcool_lightcolor = new Typecho_Widget_Helper_Form_Element_Text('bcool_lightcolor', null, 'linear-gradient(0deg, #4584b4, #1e4877)', '浅色模式首页背景色', '建议使用渐变色，如：linear-gradient(0deg, #4584b4, #1e4877)');
        $bcool_lightcolor->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($bcool_lightcolor);

        $Html = <<<HTML
<div class="ui piled segment">
    <h2 class="ui icon header aligned center ">
        <i class="ban icon"></i>
        <div class="content">
            评论过滤
            <div class="sub header">默认强制性过滤全空格评论、包含XSS危险内容评论，其余需过滤内容您可以通过以下项进行设置。</div>
        </div>
    </h2>
</div>

HTML;

        $layout = new Typecho_Widget_Helper_Layout();
        $layout->html(_t($Html));
        $layout->setAttribute('class', 'bearui_content bearui_high');
        $form->addItem($layout);

        $BearSpam_IP = new Typecho_Widget_Helper_Form_Element_Textarea('BearSpam_IP', null, '', '过滤IP', '多条IP请用换行符隔开<br />支持用*号匹配IP段，如：192.168.*.*');
        $BearSpam_IP->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_IP);

        $BearSpam_EMAIL = new Typecho_Widget_Helper_Form_Element_Textarea('BearSpam_EMAIL', null, '', '过滤邮箱', '多个邮箱请用换行符隔开<br />可以是邮箱的全部，或者邮箱部分关键词');
        $BearSpam_EMAIL->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_EMAIL);

        $BearSpam_URL = new Typecho_Widget_Helper_Form_Element_Textarea('BearSpam_URL', null, '', '过滤网址', '多个网址请用换行符隔开<br />可以是网址的全部，或者网址部分关键词。如果网址为空，该项不会起作用。');
        $BearSpam_URL->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_URL);


        $BearSpam_ArticleTitle = new Typecho_Widget_Helper_Form_Element_Radio('BearSpam_ArticleTitle', array(true => _t(' 是 '), false => _t(' 否 ')), false, _t('过滤含有文章标题的评论'), _t("根据研究表明机器人发表的内容可能含有评论文章的标题"));
        $BearSpam_ArticleTitle->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_ArticleTitle);

        $BearSpam_NAME = new Typecho_Widget_Helper_Form_Element_Textarea('BearSpam_NAME', null, '', '过滤昵称', '如果评论发布者的昵称含有该关键词，将执行该操作，多个请直接换行');
        $BearSpam_NAME->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_NAME);

        $BearSpam_NAMEMIN = new Typecho_Widget_Helper_Form_Element_Text('BearSpam_NAMEMIN', null, '', '昵称允许的最短长度', '如果评论发布者的昵称小于该最短长度将拦截');
        $BearSpam_NAMEMIN->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_NAMEMIN);

        $BearSpam_NAMEMAX = new Typecho_Widget_Helper_Form_Element_Text('BearSpam_NAMEMAX', null, '', '昵称允许的最长长度', '如果评论发布者的昵称大于该最长长度将拦截');
        $BearSpam_NAMEMAX->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_NAMEMAX);

        $BearSpam_NAMEURL = new Typecho_Widget_Helper_Form_Element_Radio('BearSpam_NAMEURL', array(true => _t(' 是 '), false => _t('否 ')), false, _t('过滤昵称为网址的评论'), _t("根据研究表明机器人发表的评论，昵称很有可能为网址"));
        $BearSpam_NAMEURL->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_NAMEURL);


        $BearSpam_Chinese = new Typecho_Widget_Helper_Form_Element_Radio('BearSpam_Chinese', array(true => _t(' 是 '), false => _t(' 否 ')), false, _t('过滤不包含中文的评论'), _t("当评论内容中没有中文时进行拦截"));
        $BearSpam_Chinese->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_Chinese);

        $BearSpam_MIN = new Typecho_Widget_Helper_Form_Element_Text('BearSpam_MIN', null, '', '评论内容允许的最短长度', '如果评论发布者的评论内容小于该最短长度将拦截');
        $BearSpam_MIN->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_MIN);

        $BearSpam_MAX = new Typecho_Widget_Helper_Form_Element_Text('BearSpam_MAX', null, '', '评论内容允许的最大长度', '如果评论发布者的评论内容长度大于该最大长度将拦截');
        $BearSpam_MAX->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_MAX);

        $BearSpam_Words = new Typecho_Widget_Helper_Form_Element_Textarea('BearSpam_Words', null, '', '过滤敏感词', '多个词汇请用换行符隔开');
        $BearSpam_Words->setAttribute('class', 'bearui_content bearui_high');
        $form->addInput($BearSpam_Words);

        $bcool_cover = new Typecho_Widget_Helper_Form_Element_Textarea('bcool_cover', null, '', '文章自定义封面图片，私用版', '请填入首页、分类等页面输出文章时的文章自定义封面图片<br>优先级：文章封面->附件首图->文章首图->自定义随机图片，无图片时将随机显示<br>自定义图片链接可固定一张，多张随机使用|分割<br>例如:https://www.xxx.com/xxx.png|https://www.xxx.com/xxxx.png');
        $bcool_cover->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_cover);

        $public_bcool_cover = new Typecho_Widget_Helper_Form_Element_Textarea('public_bcool_cover', null, '', '文章自定义封面图片，公开版', '请填入首页、分类等页面输出文章时的文章自定义封面图片<br>优先级：文章封面->附件首图->文章首图->自定义随机图片，无图片时将随机显示<br>自定义图片链接可固定一张，多张随机使用|分割<br>例如:https://www.xxx.com/xxx.png|https://www.xxx.com/xxxx.png');
        $public_bcool_cover->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($public_bcool_cover);

        $bcool_show = new Typecho_Widget_Helper_Form_Element_Text('bcool_show', null, 'all,全部;else,不显示', '设置图片展示页筛选选项', '');
        $bcool_show->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_show);


        $options = Typecho_Widget::widget('Widget_Options');
        // echo $options->bcool_show;
        $select_arr = explode(";",$options->bcool_show);
        $catearray = array(
            'off' => '不显示',
            'all' => '全部'
        );
        // if(!empty($select_arr)){
        //     foreach($select_arr as $i){
        //         $temp = explode(",",$i);
        //         $catearray[$temp[0]]=$temp[1];
        //     }   
        // }

        foreach($select_arr as $i){
            $temp = explode(",",$i);
            if (count($temp) == 2) {
                $catearray[$temp[0]] = $temp[1];
            }
        } 
        
        $bcool_select_show = new Typecho_Widget_Helper_Form_Element_Select(
            'bcool_select_show',
            $catearray,
            'off',
            '选择文章预览图分类',
            '开启后，文章预览图将依据此选项'
        );
        $bcool_select_show->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_select_show->multiMode());

        $bcool_Gravatar = new Typecho_Widget_Helper_Form_Element_Select('bcool_Gravatar', array('1' => 'Gravatar官方源', '2' => 'LOLI.TOP*Gravatar镜像源', '3' => 'V2EX*Gravatar镜像源', '4' => 'LOLI.NET*Gravatar镜像源', '5' => '极客族*Gravatar镜像源', '6' => '七牛*Gravatar镜像源'), '2', 'Gravatar源选择', '因Gravatar官方在中国大陆地区被Q，导致在中国大陆访问使用Gravatar的站点时头像不显示,这里支持您自主选择合适的源<br>本功能适配QQ,当填写的邮箱为QQ邮箱时则显示QQ头像');
        $bcool_Gravatar->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_Gravatar->multiMode());


        $bcool_gray = new Typecho_Widget_Helper_Form_Element_Radio('bcool_gray', array(true => _t(' 是 '), false => _t(' 否 ')), false, _t('是否开启哀悼模式'), _t("开启后全站变灰"));
        $bcool_gray->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_gray);

        $debug_mode = new Typecho_Widget_Helper_Form_Element_Radio('debug_mode', array(false => '关闭', true => '开启'), false, '是否开启本地模式', '开启后使用css、js等将使用本地资源。');
        $debug_mode->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($debug_mode);

        $bcool_404 = new Typecho_Widget_Helper_Form_Element_Textarea('bcool_404', null, '🏠您可能需要<a href="https://blog.fordes.top">👉 返回首页</a><br>📌或者去发现些新东西<a href="https://item.ink">👉 网址导航</a>', '404页面提示语', '自定义提示语将出现在中间位置，建议使用HTML</br><a href="./404.html" target="_blank"> 👉 点此 </a>打开404页面以查看效果');
        $bcool_404->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_404);

        $bcool_views = new Typecho_Widget_Helper_Form_Element_Radio('bcool_views', array(true => '展示', false => '不展示'), false, '未登陆是否展示文章和页面阅读量', '人艰不拆~ 不希望被人看到阅读量可选择不展示');
        $bcool_views->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_views);

        $bcool_animate = new Typecho_Widget_Helper_Form_Element_Select(
            'bcool_animate',
            array(
                'close' => '关闭',
                'bounce' => 'bounce',
                'flash' => 'flash',
                'pulse' => 'pulse',
                'rubberBand' => 'rubberBand',
                'headShake' => 'headShake',
                'swing' => 'swing',
                'tada' => 'tada',
                'wobble' => 'wobble',
                'jello' => 'jello',
                'heartBeat' => 'heartBeat',
                'bounceIn' => 'bounceIn',
                'bounceInDown' => 'bounceInDown',
                'bounceInLeft' => 'bounceInLeft',
                'bounceInRight' => 'bounceInRight',
                'bounceInUp' => 'bounceInUp',
                'bounceOut' => 'bounceOut',
                'bounceOutDown' => 'bounceOutDown',
                'bounceOutLeft' => 'bounceOutLeft',
                'bounceOutRight' => 'bounceOutRight',
                'bounceOutUp' => 'bounceOutUp',
                'fadeIn' => 'fadeIn',
                'fadeInDown' => 'fadeInDown',
                'fadeInDownBig' => 'fadeInDownBig',
                'fadeInLeft' => 'fadeInLeft',
                'fadeInLeftBig' => 'fadeInLeftBig',
                'fadeInRight' => 'fadeInRight',
                'fadeInRightBig' => 'fadeInRightBig',
                'fadeInUp' => 'fadeInUp',
                'fadeInUpBig' => 'fadeInUpBig',
                'fadeOut' => 'fadeOut',
                'fadeOutDown' => 'fadeOutDown',
                'fadeOutDownBig' => 'fadeOutDownBig',
                'fadeOutLeft' => 'fadeOutLeft',
                'fadeOutLeftBig' => 'fadeOutLeftBig',
                'fadeOutRight' => 'fadeOutRight',
                'fadeOutRightBig' => 'fadeOutRightBig',
                'fadeOutUp' => 'fadeOutUp',
                'fadeOutUpBig' => 'fadeOutUpBig',
                'flip' => 'flip',
                'flipInX' => 'flipInX',
                'flipInY' => 'flipInY',
                'flipOutX' => 'flipOutX',
                'flipOutY' => 'flipOutY',
                'rotateIn' => 'rotateIn',
                'rotateInDownLeft' => 'rotateInDownLeft',
                'rotateInDownRight' => 'rotateInDownRight',
                'rotateInUpLeft' => 'rotateInUpLeft',
                'rotateInUpRight' => 'rotateInUpRight',
                'rotateOut' => 'rotateOut',
                'rotateOutDownLeft' => 'rotateOutDownLeft',
                'rotateOutDownRight' => 'rotateOutDownRight',
                'rotateOutUpLeft' => 'rotateOutUpLeft',
                'rotateOutUpRight' => 'rotateOutUpRight',
                'hinge' => 'hinge',
                'jackInTheBox' => 'jackInTheBox',
                'rollIn' => 'rollIn',
                'rollOut' => 'rollOut',
                'zoomIn' => 'zoomIn',
                'zoomInDown' => 'zoomInDown',
                'zoomInLeft' => 'zoomInLeft',
                'zoomInRight' => 'zoomInRight',
                'zoomInUp' => 'zoomInUp',
                'zoomOut' => 'zoomOut',
                'zoomOutDown' => 'zoomOutDown',
                'zoomOutLeft' => 'zoomOutLeft',
                'zoomOutRight' => 'zoomOutRight',
                'zoomOutUp' => 'zoomOutUp',
                'slideInDown' => 'slideInDown',
                'slideInLeft' => 'slideInLeft',
                'slideInRight' => 'slideInRight',
                'slideInUp' => 'slideInUp',
                'slideOutDown' => 'slideOutDown',
                'slideOutLeft' => 'slideOutLeft',
                'slideOutRight' => 'slideOutRight',
                'slideOutUp' => 'slideOutUp',
            ),
            'off',
            '选择一款显示动画',
            '开启后，首页等位置都将显示此动画'
        );
        $bcool_animate->setAttribute('class', 'bearui_content bearui_other');
        $form->addInput($bcool_animate->multiMode());
}