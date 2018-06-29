<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\public\middle.html";i:1528888058;s:74:"E:\xampp\htdocs\xinjia\tp5\public/../application/admin\view\car\index.html";i:1530003927;}*/ ?>

{block name="content"}
{foreach name='articleData' item='vo'}
<article>
    <h1><a href="">{$vo['article_title']}</a></h1>
    <div class="info">
        <span>作者：<a href="">{$vo['article_author']}</a></span>
        <time> · {:date('Y-m-d',$vo['sendtime'])}</time>
    </div>
    <br />
    <div class="arti">
        <p>{$vo['article_content']}</p>
    </div>
    <br />
    <!--评论-->
    <div class="h-liuyanban"   >
        <h3>留言</h3>
        <form action="" id="boardForm"  style="margin-top: 20px">
            <input type="hidden" name="article_id" value="{$vo['article_id']}">
            <label>用户名</label>
            <input type="text" class="form-control" name="comment_user" id="comment_user"/><br>

            <label>留言</label>
            <textarea class="form-control" name="comment_content" id="comment_content"></textarea>
            <br>
            <input type="submit" class="btn btn-default pull-right" id="submit" style="background: #E67E22;color:white ">
            <p id="position" style="color: green;margin-top: 15px;font-size: 15px"></p>
        </form>
    </div>

<script type="text/javascript">
    $(function() {
        $("#submit").click(function() {
            console.log($("#boardForm").serialize());
            var u = $("#comment_user");
            var c=$("#comment_content");
            if (u.val() == ""&&c.val()== "") {
                alert("请输入完整的信息");
                u.focus();
                c.focus();
            }else {
                $.ajax({
                    type: "POST",
                    url: "{:url('admin/car/index')}",
                    data: $("#boardForm").serialize(),
//                    success: function(data) {
//                       // console.log(data);
//                        if (data !== false) {
//                            //$("#position").append("<div>"+"留言成功"+"</div>");
////                            $("#comment_content")
////                                    .append("<li class='comment-content' id='comment_detail'><span class='comment-f'></span><div class='comment-main'><p><a class='address' rel='nofollow'target='_blank' style='color: cornflowerblue;'>"
////                                            +data.comment_user
////                                    +"</a></p></div></li>");
//                        }else{
//                           // $("#position").append("<div>"+"留言失败"+"</div>");
//                        }
//                    }
                });
            }
            return false;
    });
    });
</script>


{/block}