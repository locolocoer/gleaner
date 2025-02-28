<?php

/**
 * 归档页
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php'); ?>

<?php

// 获取图片数据，复用原有逻辑
$options = Typecho_Widget::widget('Widget_Options');
$pri_thumbs = explode("|", $options->bcool_cover);
$pub_thumbs = explode("|", $options->public_bcool_cover);

if ($this->user->hasLogin()) {
    if($options->bcool_cover!="" && $options->public_bcool_cover!=""){
        $thumbs = array_merge($pri_thumbs, $pub_thumbs);
    }else if($options->bcool_cover!=""){
        $thumbs = $pri_thumbs;
    }else{
        $thumbs = $pub_thumbs;
    }
} else if($options->public_bcool_cover!=""){
    $thumbs = $pub_thumbs;
}

// 分页设置
$per_page = 100; // 每页显示100张图片
$total_images = count($thumbs);
$total_pages = ceil($total_images / $per_page);

// 获取当前页码
$current_page = isset($_GET['page']) ? max(1, min($total_pages, intval($_GET['page']))) : 1;

// 获取当前页的图片
$start = ($current_page - 1) * $per_page;
$current_thumbs = array_slice($thumbs, $start, $per_page);

// 生成分页导航链接
function generatePaginationLink($page) {
    $params = $_GET;
    $params['page'] = $page;
    return '?' . http_build_query($params);
}
?>

<!-- 轮播组件HTML结构 -->
<div class="carousel-container">
    <?php foreach ($current_thumbs as $index => $thumb): ?>
        <div class="carousel-slide loading<?php echo $index === 0 ? ' initial' : ''; ?>">
            <?php 
            if (substr($thumb, 0, 4) != "http") {
                if($this->options->bcool_select_origin === 0 || $this->options->bcool_select_origin == "default"){
                    $thumb = "https://www.flyingfry.cn/usr/uploads/" . $thumb;
                }elseif($this->options->bcool_select_origin==="tiktok"){
                    $thumb="/".$thumb. $this->options->bcool_select_origin_template;
                    require_once("Signer_tiktok.php");
                    $thumb=Signer::main($thumb);
                }elseif($this->options->bcool_select_origin==="github") {
                    $thumb = 'https://gcore.jsdelivr.net/gh/locolocoer/github_backup@master/'. $thumb;
                }elseif($this->options->bcool_select_origin==="123cloud"){
                    require_once("Signer_123cloud.php");
                    $thumb=Signer_123cloud::main($thumb);
                }
            }
            ?>
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                 data-src="<?php if(isset($_GET['page'])){echo $thumb;} ?>"
                 alt="Slide <?php echo $index + 1; ?>"
                 loading="lazy"
                 onload="if(this.src !== this.dataset.src) return; this.classList.add('loaded'); this.parentElement.classList.remove('loading');">
        </div>
    <?php endforeach; ?>
    
    <!-- 添加进度条 -->
    <div class="progress-container">
        <div class="progress-bar"></div>
    </div>
    
    <!-- 添加数字进度显示 -->
    <div class="slide-counter">
        <span class="current">1</span> / <?php echo count($current_thumbs); ?>
    </div>
    
    <button class="carousel-btn prev-btn">❮</button>
    <button class="carousel-btn next-btn">❯</button>
    
    <button class="play-pause-btn">
        <i class="fas fa-pause"></i>
        <span>暂停</span>
    </button>
    
    <!-- 删除原有的indicators部分 -->
</div>

<!-- 添加分页信息显示 -->
<div class="page-info">
    共 <?php echo $total_images; ?> 张图片，第 <?php echo $current_page; ?>/<?php echo $total_pages; ?> 页
</div>

<!-- 添加分页导航 -->
<div class="pagination">
    <?php if ($total_pages > 1): ?>
        <?php if ($current_page > 1): ?>
            <a href="<?php echo generatePaginationLink($current_page - 1); ?>">上一页</a>
        <?php endif; ?>

        <?php
        // 显示页码
        $start_page = max(1, $current_page - 2);
        $end_page = min($total_pages, $current_page + 2);

        if ($start_page > 1) {
            echo '<a href="' . generatePaginationLink(1) . '">1</a>';
            if ($start_page > 2) {
                echo '<span>...</span>';
            }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $current_page) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                echo '<a href="' . generatePaginationLink($i) . '">' . $i . '</a>';
            }
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
                echo '<span>...</span>';
            }
            echo '<a href="' . generatePaginationLink($total_pages) . '">' . $total_pages . '</a>';
        }

        if ($current_page < $total_pages): ?>
            <a href="<?php echo generatePaginationLink($current_page + 1); ?>">下一页</a>
        <?php endif; ?>
    <?php endif; ?>
</div>



<!-- 引入轮播功能JavaScript -->
<script src="<?php $this->options->themeUrl('assets/js/carousel.js'); ?>"></script>

<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/carousel.css'); ?>">

<!-- 在引入JS之前添加当前页码 -->
<script>
localStorage.setItem('carousel_current_page', '<?php echo $current_page; ?>');
</script>

    <div class="page-wrap">
    <div class="page-wrap-content">
    <div class="post-single-wrap sticky-parent">
        <div class="single-content wp-content">
            <div class="wrap wrap-center">
                <div class="wrap_float">

                    <section
                            class="articles-list <?php if ($this->options->bcool_animate !== "close" || !empty($this->options->bcool_animate)) : ?>animate__animated animate__<?php $this->options->bcool_animate() ?><?php endif; ?>">
                        <?php
                        $stat = Typecho_Widget::widget('Widget_Stat');
                        $this->widget('Widget_Contents_Post_Recent', 'pageSize=' . $stat->publishedPostsNum)->to($archives);
                        $year = 0;
                        $mon = 0;
                        $i = 0;
                        $j = 0;
                        $output = '<section>';
                        while ($archives->next()) {
                            $year_tmp = date('Y', $archives->created);
                            if ($year != $year_tmp) {
                                $year = $year_tmp;
                                $output .= '<h2>' . $year . '</h2>';
                            }
                            if ($this->options->PjaxOption && $archives->hidden) {
                                $output .= '<div class="post"><a href="' . $archives->permalink . '"><div class="post-row"><time>' . date('M j', $archives->created) . '</time><h3>' . $archives->title . '</h3></div></a></div>';
                            } else {
                                $output .= '<div class="post"><a href="' . $archives->permalink . '"><div class="post-row"><time>' . date('M j', $archives->created) . '</time><h3>' . $archives->title . '</h3></div></a></div>';
                            }
                        }
                        $output .= '</section>';
                        echo $output;
                        ?>
                    </section>

                </div>
            </div>
        </div>
    </div>

<?php $this->need('footer.php'); ?>