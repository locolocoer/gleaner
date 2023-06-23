<?php
function get_post_view($archive, $display = true) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        if ($display) {
            echo 0;
        }
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie
        }
    }
    if ($display) {
        echo $row['views'];
    }
}/*Gravatar头像*/
function Gravatar($email)
{
    $options = Helper::options();
    $b = str_replace('@qq.com', '', $email);
    if (stristr($email, '@qq.com') && is_numeric($b) && strlen($b) < 11 && strlen($b) > 4) {
        // $nk = 'https://s.p.qq.com/pub/get_face?img_type=3&uin=' . $b;
        // $c = get_headers($nk, true);
        // $d = $c['Location'];
        // $q = json_encode($d);
        // $k = explode("&k=", $q)[1];
        echo '//q1.qlogo.cn/g?b=qq&nk=' . $b. '&s=100';
    } else {
        $email = md5($email);
        if ($options->bcool_Gravatar == '1') {
            echo "//cn.gravatar.com/gravatar/" . $email . "?";
        } else if ($options->bcool_Gravatar == '2') {
            echo "//gravatar.loli.top/avatar/" . $email . "?";
        } else if ($options->bcool_Gravatar == '3') {
            echo "//cdn.v2ex.com/gravatar/" . $email . "?";
        } else if ($options->bcool_Gravatar == '4') {
            echo "//gravatar.loli.net/avatar/" . $email . "?";
        } else if ($options->bcool_Gravatar == '5') {
            echo "//sdn.geekzu.org/avatar/" . $email . "?";
        } else if ($options->bcool_Gravatar == '6') {
            echo "//dn-qiniu-avatar.qbox.me/avatar/" . $email . "?";
        } else {
            echo "//gravatar.loli.top/avatar/" . $email . "?";
        }
    }
}

function fallbackGravatar()
{
    $options = Helper::options();
    $options->themeUrl('./assets/img/avatar/' . rand(1, 100) . '.png');
}/*简介图文获取图片*/
function thumb($obj,$hasLogin)
{/*获取附件首张图片*/
    $attach = $obj->attachments(1)->attachment;/*获取文章首张图片*/
    preg_match_all("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $obj->content, $thumbUrl);
    $img_src = $thumbUrl[1][0];/*获取自定义随机图片*/
    $options = Typecho_Widget::widget('Widget_Options');
    $pub_thumbs = explode("|", $options->public_bcool_cover);/*获取文章封面*/
    $pri_thumbs = explode("|", $options->bcool_cover);/*获取文章封面*/
    if($hasLogin){
        $thumbs = array_merge($pub_thumbs, $pri_thumbs);
    }else{
        $thumbs = $pub_thumbs;
    }
    $cover = $obj->fields->cover;
    if ($cover) {
        $thumb = $cover;
    } else if ($options->bcool_cover && count($thumbs) > 0) {
        $num = count($thumbs);

        if ($hasLogin && $options->bcool_select_show!=null && $options->bcool_select_show!='all'){
            for($i=0;$i<$num;$i++){
                if(strpos($thumbs[$i],$options->bcool_select_show)===false){
                    unset($thumbs[$i]); 
                }
            }
            $thumbs = array_values($thumbs);
        }
        
        $thumb = $thumbs[rand(0, count($thumbs) - 1)];
        
        if (substr($thumb,0,4)!="http"){
            if($options->bcool_select_originn===0||$options->bcool_select_origin=="default"){
                $thumb = "https://www.flyingfry.cn/usr/uploads/" . $thumb;
            }elseif($options->bcool_select_origin==="tiktok"){
                $thumb = "/" . $thumb. $options->bcool_select_origin_template;
                require_once("Signer.php");
                $thumb = Signer::main($thumb);
            }else{
                $thumb ='https://gcore.jsdelivr.net/gh/locolocoer/github_backup@master/'. $thumb;
            }
        }
    } elseif (isset($attach->isImage) && $attach->isImage == 1) {
        $thumb = $attach->url;
    } else if ($img_src) {
        $thumb = $img_src;
    }else {
        $thumb = 'https://tuapi.eees.cc/api.php?category={dongman,fengjing,meinv,biying}&type=302&t=' . rand(1, 1000);
    }
    return $thumb;
}

function sticky()
{
    $options = Typecho_Widget::widget('Widget_Options');
    $sticky = explode(",", $options->sticky_cid);
    return $sticky;
}

function pic_show()
{
    $options = Typecho_Widget::widget('Widget_Options');
    $picshows = explode("|", $options->bcool_pic);
    $picnum = count($picshows);
    $picshow = [];
    if ($picnum !== 7) {
        $rand = '400?t=' . rand(1, 1000);
        $picshow = 'https://picapi.bear-studio.net/200/' . $rand;
    } else {
        $picshow['type'] = 'no_auto';
        $picshow['url'] = $picshows;
        $picshow = json_encode($picshow);
    }
    return $picshow;
}

/** 显示上一篇 如果没有下一篇,返回null */
function thePrevCid($widget, $default = NULL)
{
    $db = Typecho_Db::get();
    $sql = $db->select()->from('table.contents')->where('table.contents.created < ?', $widget->created)->where('table.contents.status = ?', 'publish')->where('table.contents.type = ?', $widget->type)->where('table.contents.password IS NULL')->order('table.contents.created', Typecho_Db::SORT_DESC)->limit(1);
    $content = $db->fetchRow($sql);
    if ($content) {
        return $content["cid"];
    } else {
        return $default;
    }
}

;
/** 获取下一篇文章mid 如果没有下一篇,返回null */
function theNextCid($widget, $default = NULL)
{
    $db = Typecho_Db::get();
    $sql = $db->select()->from('table.contents')->where('table.contents.created > ?', $widget->created)->where('table.contents.status = ?', 'publish')->where('table.contents.type = ?', $widget->type)->where('table.contents.password IS NULL')->order('table.contents.created', Typecho_Db::SORT_ASC)->limit(1);
    $content = $db->fetchRow($sql);
    if ($content) {
        return $content["cid"];
    } else {
        return $default;
    }
}/*留言加@*/
function getPermalinkFromCoid($coid)
{
    $db = Typecho_Db::get();
    $row = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ? AND status = ?', $coid, 'approved'));
    if (empty($row)) return '';
    return '<a href="#comment-' . $coid . '">@' . $row['author'] . '</a>';
}/*第三方分享*/
function share($t, $type)
{
    if ($type == 'qq') {
        echo 'http://connect.qq.com/widget/shareqq/index.html?url=' . $t->permalink . '&sharesource=qzone&title=' . $t->title . '&pics=' . thumb($t,0) . '&summary=' . $t->title . '&desc=' . $t->title;
    } else if ($type == 'weibo') {
        echo 'https://service.weibo.com/share/share.php?url=' . $t->permalink . '&title=' . $t->title . '&pic=' . thumb($t,0);
    } else {
        echo 'https://www.facebook.com/sharer/sharer.php?u=' . $t->permalink;
    }
}/*首页图标输出*/
function indexAppInfo()
{
    $options = Helper::options();
    $apps = explode("\r\n", $options->bcool_app);
    foreach ($apps as $app) {
        if ($app != null) {
            $items = explode("|", $app);
            echo "<li><a href='" . $items[2] . "' target='_blank'><i class='" . $items[1] . "' aria-hidden='true' title='" . $items[0] . "'></i></a></li>";
        }
    }
}
