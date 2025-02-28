<?php

/**
 * 图片展示页
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
$this->need('header.php'); ?>

<?php
$options = Typecho_Widget::widget('Widget_Options');
$pri_thumbs = explode("|", $options->bcool_cover); /*获取文章封面*/

$pub_thumbs = explode("|", $options->public_bcool_cover);
if ($this->user->hasLogin()) {
    if($options->bcool_cover!=""&&$options->public_bcool_cover!=""){
        $thumbs = array_merge($pri_thumbs, $pub_thumbs);
    }else if($options->bcool_cover!=""){
        $thumbs = $pri_thumbs;
    }else{
        $thumbs = $pub_thumbs;
    }
    
} else if($options->public_bcool_cover!=""){
    $thumbs = $pub_thumbs;
}

$num = count($thumbs);
// shuffle($thumbs);
//echo $_POST["cate"];

//use Modules\Auth\Signer;

//Signer::main();

if($this->user->hasLogin()){
    //$catearray = array("all"=>"全部","zqq"=>"仲秋秋","Nimo"=>"Nimo","lsy"=>"刘苏颖","minladuizhang"=>"敏啦队长","yuwen"=>"于雯","nana"=>"Nana","linyun"=>"林允","dm"=>"动漫");
    $select_arr = explode(";",$options->bcool_show);
    $catearray = array();
    foreach($select_arr as $i){
        $temp = explode(",",$i);
        $catearray[$temp[0]]=$temp[1];
    }
}else{
    $catearray = array("all"=>"全部");
}

$catenum = array("all"=>$num);
foreach($catearray as $key=>$value){
    if ($key!="all"){
        $catenum[$key]=0;
    }
}

for($i=0;$i<$num;$i++){
    foreach ($catenum as $key=>$value){
        if ($key!="all"){
            if (strpos($thumbs[$i],$key)!==false){
                $catenum[$key]++;
            }
        }
    }
}
//print_r($catenum);
if($this->user->hasLogin()){
    if ($_POST['cate']!=null&&$_POST['cate']!='all'){
        for($i=0;$i<$num;$i++){
            if(strpos($thumbs[$i],$_POST["cate"])===false){
                unset($thumbs[$i]); 
            }
        }
    }
}
$thumbs = array_values($thumbs);
$num = count($thumbs);
$init_flag=true;
if(!isset($_POST["pageNum"])||!isset($_POST["cate"])){
    $init_flag=false;
}
if (!isset($_POST["pageNum"])) {
    $pageNum = 1;
   
} else {
    $pageNum = intval($_POST["pageNum"]);
}
$begin = 100 * ($pageNum - 1);
$end = 100 * ($pageNum - 1) + 99;
if ($end >= $num) {
    $end = $num - 1;
}
?>

<script>
var catenum = {<?php
    foreach ($catenum as $key=>$value){
        echo $key.":".$value.",";
    }
?>};
var hasLogin = <?php if($this->user->hasLogin()){
    echo "true";
}else{
    echo "false";
} ?>;
var init_page = <?php if($init_flag){
    echo "false";
}else{
    echo "true";
} ?>;
</script>

<div class="pageNav">
    <div class="centerbox">
        <div class="discription">共计
            <?php echo $num; ?>张图片,当前
        </div>
        <form method="post">
        <select name="cate" id="cate" class="cate">
            <?php   
              foreach($catearray as $key => $value){
                if ($_POST["cate"]==$key){
                    echo "<option value=".$key." selected>".$value."</option>";
                }else{
                    echo "<option value=".$key.">".$value."</option>";
                }
                
              }
            ?>
        </select>
            <input type="number" name="pageNum" id="page" min="1" max="<?php if(ceil($num / 100)){echo ceil($num / 100);}else{echo 1;} ?>"
                placeholder="<?php echo $pageNum; ?>" required="required" onmousewheel="return false;" />

            <button type="submit" id="submit">/
                <?php echo ceil($num / 100); ?>页
            </button>
        </form>
    </div>
</div>

<script>
    const input = document.getElementById("page");
    input.addEventListener("wheel", function (event) {
        event.preventDefault();
    });
    
    
    $("#submit").click(function(){
        if($("#page").val()){
            if(parseInt($("#page").val()) > 0 && parseInt($("#page").val()) <= Math.ceil(catenum[$("#cate").val()]/100)){
                localStorage.setItem("pageNum",parseInt($("#page").val()));
            }
        }
        // console.log($("#page").val())
        if($("#cate").val()){
              localStorage.setItem("cate",$("#cate").val());
        }
        console.log($("#cate").val())
    })

    // $("#submit").click(function(){
        
    // })

    $(document).ready(function () {
        $("#submit").hover(function () {
            $("#submit").html("转到");
        }, function () {
            $("#submit").html("/<?php echo ceil($num / 100); ?>页");
        });
    //     if(){
    //     post("",{"pageNum":parseInt(localStorage.getItem("pageNum")),"cate":localStorage.getItem("cate")});
    // }
    if(init_page||($("#page").attr("placeholder")!=localStorage.getItem("pageNum")&&localStorage.getItem("pageNum")!=null)||($("#cate").val()!=localStorage.getItem("cate")&&localStorage.getItem("cate")!=null&&hasLogin)){
        post("",{"pageNum":parseInt(localStorage.getItem("pageNum")),"cate":localStorage.getItem("cate")});
    }
    });
    $(document).ready(function () {
        $("#cate").change(function(e){
            // console.log(e.target.value);
            // console.log(catenum[e.target.value]);
            // console.log($("#page").attr("max"));
            $("#page").attr("max",Math.ceil(catenum[e.target.value]/100));
            $("#submit").html("/"+Math.ceil(catenum[e.target.value]/100)+"页");
            //console.log($(".discription"))
            $(".discription").html("共计"+catenum[e.target.value]+"张图片,当前")
        })

    });
</script>
<br>
<div
    class="page-wrap archive-page <?php if ($this->options->bcool_animate !== "close" || !empty($this->options->bcool_animate)): ?>animate__animated animate__<?php $this->options->bcool_animate() ?><?php endif; ?>">


    <div class="archive-body">
        <div class="wrap">
            <div class="wrap_float grid">
                <div class="portfolio">
                    <?php if($init_flag): ?>
                    <?php for ($i = $begin; $i <= $end; $i++): ?>
                        <?php 
                        if (substr($thumbs[$i],0,4)!="http"){
                            if($this->options->bcool_select_origin===0||$this->options->bcool_select_origin=="default"){
                                $thumbs[$i]="https://www.flyingfry.cn/usr/uploads/" . $thumbs[$i];
                            }elseif($this->options->bcool_select_origin==="tiktok"){
                                $thumbs[$i]="/".$thumbs[$i]. $this->options->bcool_select_origin_template;
                                require_once("Signer_tiktok.php");
                                $signedPath = Signer::main($thumbs[$i]); // 使用默认鉴权方式 A
                                $thumbs[$i] = $signedPath;
                                
                            }elseif ($this->options->bcool_select_origin==="github"){
                                $thumbs[$i]='https://gcore.jsdelivr.net/gh/locolocoer/github_backup@master/'. $thumbs[$i];
                            }elseif ($this->options->bcool_select_origin==="123cloud"){
                                require_once("Signer_123cloud.php");
                                $thumbs[$i]=Signer_123cloud::main($thumbs[$i]);
                            }
                            
                        }
                            ?>
                        <a href="<?php echo $thumbs[$i]; ?>" data-fancybox="images">
                        <div class="portfolio-item p-2 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="content">
                                <div class="thumb clearbg">
                                    <img class="lazy" src="<?php $this->options->themeUrl('./assets/img/loading.gif'); ?>"
                                        data-src="<?php 
                                        
                                        echo $thumbs[$i]; ?>"
                                        error-src="<?php $this->options->themeUrl('./assets/img/404.jpg'); ?>" alt="" />
                                </div>
                            </div>
                        </div>
                        </a>
                    <?php endfor; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script>
        // addExpand();
    </script>
    <script>
        $(document).ready(function () {
            $("#prevPage").click(function () {
                if (<?php echo $pageNum ?> > 1) {
                    post("",{"pageNum":<?php echo $pageNum - 1; ?>,"cate":localStorage.getItem("cate")});
                    localStorage.setItem("pageNum","<?php echo $pageNum - 1; ?>");
                }
            });
        });
        $(document).ready(function () {

            $("#nextPage").click(function () {
                if (<?php echo $pageNum ?> < <?php echo ceil($num / 100) ?>) {
                    post("",{"pageNum":<?php echo $pageNum + 1; ?>,"cate":localStorage.getItem("cate")});
                    localStorage.setItem("pageNum","<?php echo $pageNum + 1; ?>");
                }
            });
        });
    </script>
    <div class="pages-controls">


        <div id="prevPage" class="pages-controls-item prev-article">
            <div class="pages-controls-item-wrapper">
                <div class="control-direction"><i class="fa-solid fa-arrow-left fa-lg"></i> 上一页</div>
                <h3 class="post-title">
                    <?php if ($pageNum == 1): ?>没有了~
                    <?php else: ?>第
                        <?php echo $pageNum - 1; ?>页
                    <?php endif; ?>
                </h3>
            </div>
        </div>

        <div class="pages-controls-item next-article" id="nextPage">
            <div class="pages-controls-item-wrapper">
                <div class="control-direction">下一页 <i class="fa-solid fa-arrow-right fa-lg"></i></div>
                <h3 class="post-title">
                    <?php if ($pageNum == ceil($num / 100)): ?>没有了~
                    <?php else: ?>第
                        <?php echo $pageNum + 1; ?>页
                    <?php endif; ?>
                </h3>
            </div>
        </div>

    </div>

    <?php $this->need('footer.php'); ?>