<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit; ?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>
        <?php $this->archiveTitle(
            array(
                'category' => _t('分类 %s 下的文章'),
                'search' => _t('包含关键字 %s 的文章'),
                'tag' => _t('标签 %s 下的文章'),
                'author' => _t('%s 发布的文章')
            ),
            '',
            ' - '
        ); ?><?php $this->options->bcool_title(); ?>
        <?php if (!empty($this->options->bcool_titleb)): ?> - <?php $this->options->bcool_titleb(); ?>
        <?php endif; ?>
    </title>

    <?php if ($this->options->debug_mode): ?>

        <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/iconfonts/iconfont.css'); ?>">


        <link href="<?php $this->options->themeUrl('./assets/css/styles.min.css'); ?>" type="text/css" rel="stylesheet" />
        <link href="<?php $this->options->themeUrl('./assets/css/third/animate.min.css'); ?>" type="text/css"
            rel="stylesheet" />
        <link href="<?php $this->options->themeUrl('./assets/fontawesome/css/all.min.css'); ?>" type="text/css"
            rel="stylesheet" />
        <link href="<?php $this->options->themeUrl('./assets/css/third/jquery.fancybox.min.css'); ?>" type="text/css"
            rel="stylesheet" />
        <link href="<?php $this->options->themeUrl('./assets/css/third/bootstrap-grid.min.css'); ?>" type="text/css"
            rel="stylesheet" />
        <script src="<?php $this->options->themeUrl('./assets/js/jquery.min.js'); ?>"
            type="application/javascript"></script>

    <?php else: ?>

        <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/css/pic_page.css'); ?>">
        <script src="<?php $this->options->themeUrl('./assets/js/pic_page.js'); ?>"></script>
        <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/iconfonts/iconfont.css'); ?>">
        <link href="//lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/animate.css/4.1.1/animate.min.css" type="text/css"
            rel="stylesheet" />
        <link href="//lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/font-awesome/6.0.0/css/all.min.css" type="text/css"
            rel="stylesheet" />
        <link href="//lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/fancybox/3.5.7/jquery.fancybox.min.css" type="text/css"
            rel="stylesheet" />
        <link href="//fastly.jsdelivr.net/gh/locolocoer/gleanear@fengdaxian/assets/css/styles.min.css" type="text/css"
            rel="stylesheet" />
        <link href="//lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.0.0/css/bootstrap-grid.min.css" type="text/css"
            rel="stylesheet" />
        <script src="//lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/jquery/3.6.0/jquery.min.js"
            type="application/javascript"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/agate.min.css">
    <?php endif; ?>

    <link rel="icon" type="image/png" sizes="32x32" href="<?php if (empty($this->options->bcool_favicon)) {
        $this->options->themeUrl('/assets/img/favicons/favicon.ico');
    } else
        $this->options->bcool_favicon(); ?>">
    <script>
        $(document).ready(function () {
            var DarkModeText = $(".mode-checkbox-label").attr("data-dark-title"),
                LightModeText = $(".mode-checkbox-label").attr("data-light-title");
            var nightMode;
            <?php if ($this->options->bcool_darkmode): ?>
                    const themeMedia = window.matchMedia("(prefers-color-scheme: light)");
                nightMode = !themeMedia.matches;
            <?php else: ?>
                    nightMode = localStorage.getItem("nightMode") === 'true';
            <?php endif; ?>
                changeTheme(nightMode);
            <?php if ($this->options->bcool_darkmode): ?>
                    themeMedia.addListener((e) => {
                        console.log("跟随系统切换至：" + e ? "浅色模式" : "深色模式");
                        changeTheme(!e.matches)
                    });
            <?php else: ?>
                    $('#mode-checkbox').prop("checked", nightMode)
                $(".mode-checkbox-label").text(nightMode ? DarkModeText : LightModeText);
                $("#mode-checkbox").change(function (e) {
                    var val = $("#mode-checkbox").prop('checked')
                    $(".mode-checkbox-label").text(val ? DarkModeText : LightModeText);
                    changeTheme(val)
                });
            <?php endif; ?>
                function changeTheme(night) {
                    localStorage.setItem("nightMode", night);
                    if (night) {
                        $("body").addClass("dark");
                        $("#feng2logo").attr('src', "<?php $this->options->themeUrl('./assets/img/fdxlogow.png');?>");
                        <?php if(!$this->is('index')): ?>
                        $("#fenglogo").attr('src', "<?php $this->options->themeUrl('./assets/img/fdxlogow.png');?>");
                        <?php endif;?>
                    } else {
                        $("body").removeClass("dark");
                        $("#feng2logo").attr('src', "<?php $this->options->bcool_mylogo();?>");
                        <?php if(!$this->is('index')): ?>
                        $("#fenglogo").attr('src', "<?php $this->options->bcool_mylogo();?>");
                        <?php endif;?>
                    }
                    setTimeout(function () {
                        $(".mode-check label").addClass("transition");
                    }, 100);
                }
                <?php  $this->widget('Widget_Contents_Page_List')->to($pages);
                            while($pages->next()){
                                if($pages->template == "pic-nav.php"){
                                    $totalLink = $pages->permalink;
                                }
                            }
                    ?>
                $("#js-menu>ul>li>a").each(function(index,elem){
                    if($(elem).attr("href")=="<?php echo $totalLink;?>"){
                        elem.setAttribute("onclick","return false;");
                        elem.addEventListener("click",function(evt){
                            if(localStorage.getItem("pageNum")===null){
                                post("<?php echo $totalLink;?>",{ "pageNum":"1"});
                            }else{
                                post("<?php echo $totalLink;?>",{ "pageNum":localStorage.getItem("pageNum")});
                            }
                        })
                    }
                    
                })
        })
    </script>
    <?php if ($this->options->bcool_gray): ?>
        <style>
            html {
                filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
                -webkit-filter: grayscale(100%);
            }
        </style>
    <?php endif; ?>
    <!--[if lt IE 9]>
    <script src="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
    <!-- <style>
        .MathJax span {
            font-size: 98%;
        }
    </style>        -->
    <style>
        .icon {
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
    <style>
        .portfolio .portfolio-item .clearbg::before {
            background: rgba(0, 0, 0, 0.2);
        }

        .mylogo {
            float: left;
            position: relative;
            top: -10px;
            left: -20px;
            width: 15%;
        }

        .w85 {
            width: 85%
        }

        @media screen and (max-width:1000px) {
            .wp-none {
                display: none;
            }
        }

        .visible-xs {
            display: none;
        }

        @media (max-width: 1000px) {
            .visible-xs {
                display: block !important;
                position: absolute;
                top: 38px;
                left: 20px;
            }
        }

        .visible-xs .mylogo {
            width: 40%;
            position: relative;
            top: 0px;
            left: 0px;
        }
    </style>

</head>

<body>
    <!--[if lt IE 8]>
    <div class="browsehappy" role="dialog"><?php _e('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="http://browsehappy.com/">升级你的浏览器</a>'); ?>.</div>
<![endif]-->

    <body id="t" class="transition-none offline">
        <div class="search-section">
            <div class="wrap">
                <div class="wrap_float">
                    <div class="search-form">
                        <form role="search" method="get">
                            <input autocomplete="off" type="text" checked name="s" class="search-input"
                                placeholder="输入关键词进行搜索">
                            <button type="submit" class="search-submit"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                    <div class="search-close" id="search-close">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-container page">
            <div class="content-container-wrap <?php if ($this->is('index')): ?>index-anchor<?php endif; ?> ">
                <div class="mobile-panel">
                    <div class="wrap">
                        <div class="wrap_float">
                            <div class="mobile-btn" id="js-menu-open">
                                <i class="fa-solid fa-bars"></i>
                            </div>
                            <a class="logo" href="/">
                                <?php $this->options->bcool_title() ?>
                            </a>
                            <div class="search-button"><i class="fa-solid fa-magnifying-glass"></i></div>
                        </div>
                    </div>
                </div>
                <div class="content-container-wrap--dummy"></div>
                <div class="top-panel" id="js-panel">
                    <div class="wrap">
                        <div class="wp-none">
                            <img id="fenglogo" src="<?php if ($this->is('index')) {
                                $this->options->themeUrl('./assets/img/fdxlogow.png');
                            } else {
                                $this->options->bcool_mylogo();
                            } ?>" alt="" class="mylogo">
                        </div>
                        <div class="wrap_float w85">
                            <?php if (!$this->options->bcool_darkmode): ?>
                                <div class="mode-check">
                                    <input type="checkbox" id="mode-checkbox" class="mode-checkbox-input">
                                    <label for="mode-checkbox" class="mode-checkbox-label" data-dark-title="夜间模式"
                                        data-light-title="昼间模式"></label>
                                </div>
                            <?php endif; ?>
                            <div class="wrap-center">

                                <div class="menu" id="js-menu">
                                    <ul>
                                        <li>
                                            <a href="<?php $this->options->siteUrl(); ?>">
                                                <?php _e('首页'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a>分类</a>
                                            <ul>
                                                <?php $this->widget('Widget_Metas_Category_List')->to($categorys); ?>
                                                <?php while ($categorys->next()): ?>
                                                    <?php if ($categorys->levels === 0): ?>
                                                        <?php $children = $categorys->getAllChildren($categorys->mid); ?>
                                                        <li>
                                                            <!-- <a href="<?php //$categorys->permalink(); ?>"
                                                                       title="<?php //$categorys->slug(); ?>"
                                                                       style="word-break:break-all;"><span class="iconfont icon-<?php //$categorys->slug() ?>"></span>&nbsp<?php $categorys->name(); ?></a>
                                                
                                                                </li> -->
                                                            <a href="<?php $categorys->permalink(); ?>"
                                                                title="<?php $categorys->slug(); ?>"
                                                                style="word-break:break-all;">
                                                                <svg class="icon" aria-hidden="true">
                                                                    <use xlink:href="#icon-<?php $categorys->slug(); ?>"></use>
                                                                </svg>&nbsp<?php $categorys->name(); ?></a>

                                                        </li>
                                                    <?php endif; ?><?php endwhile; ?>
                                            </ul>
                                        </li>

                                        <?php if ($this->options->bcool_menu): ?>
                                            <li>
                                                <a>页面</a>
                                                <ul>
                                                <?php endif;
                                        $this->widget('Widget_Contents_Page_List')->to($pages);
                                        while ($pages->next()): ?>
                                                    <li><a href="<?php $pages->permalink(); ?>"
                                                            title="<?php $pages->title(); ?>" style="word-break:break-all;">
                                                            <?php $pages->title(); ?>
                                                        </a></li>
                                                <?php endwhile;
                                        if ($this->options->bcool_menu): ?>
                                                </ul>
                                            </li>

                                        <?php endif; ?>
                                        <?php if ($this->user->hasLogin()): ?>
                                            <li>
                                                <a href="<?php $this->options->adminUrl(); ?>" title="进入后台">后台</a>
                                            </li>
                                        <?php endif; ?>
                                        <li><a class="split">|</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="search-button" id="btn-search"><i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                        </div>
                        <div class="visible-xs">
                            <img id="feng2logo" src="<?php $this->options->bcool_mylogo(); ?>" alt="" class="mylogo">
                        </div>
                        <div class="menu-close" id="js-menu-close">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                </div>