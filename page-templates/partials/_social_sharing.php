<?php
?>
<style>
    .btnz:active{
        color: #FFF;
    }
    .btnz:visited{
        color: #FFF;
    }
    .btnz {
        text-align: center;
        display: block;
        padding: 10px 15px;
        border:none;
        background-color: #ececec;
        text-decoration: none;
        font-size: 18px;
        color: #FFF;
    }
    .btnz:hover {
         color: #FFF;
         z-index: 999;
        -webkit-transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
        -ms-transition: all .2s ease-in-out;

        -webkit-transform: scale(1.11);
        -moz-transform: scale(1.1);
        -o-transform: scale(1.1);
        transform: scale(1.1);

    }
    .facebook {
        background-color: #3b5998;
    }
    .gplus {
        background-color: #dd4b39;
    }
    .twitter {
        background-color: #55acee;
    }
    .stumbleupon {
        background-color: #eb4924;
    }
    .pinterest {
        background-color: #cc2127;
    }
    .linkedin {
        background-color: #0077b5;
    }
    .buffer {
        background-color: #323b43;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <!-- Facebook Share Button -->
        <a class="col-xs-12 col-md-2 btnz share facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink( $post->ID ); ?>"><i class="fa fa-facebook"></i> Share</a>
        <!-- Googple Plus Share Button -->
        <a class="col-xs-12 col-md-2 btnz share gplus" href="https://plus.google.com/share?url=<?php echo get_permalink( $post->ID ); ?>"><i class="fa fa-google-plus"></i> Share</a>
        <!-- Twitter Share Button -->
        <a class="col-xs-12 col-md-2 btnz share twitter" href="https://twitter.com/intent/tweet?text=<?php the_title()?>&url=<?php echo get_permalink( $post->ID ); ?>"><i class="fa fa-twitter"></i> Tweet</a>
        <!-- Stumbleupon Share Button -->
        <a class="col-xs-12 col-md-2 btnz share stumbleupon" href="http://www.stumbleupon.com/submit?url=<?php echo get_permalink( $post->ID ); ?>&title=<?php the_title()?>"><i class="fa fa-stumbleupon"></i> Stumble</a>
        <!-- Pinterest Share Button -->
        <a class="col-xs-12 col-md-2 btnz share pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink( $post->ID ); ?>&description=<?php the_title()?>&media=YOUR-IMAGE-SRC"><i class="fa fa-pinterest"></i> Pin it</a>
        <!-- LinkedIn Share Button -->
        <a class="col-xs-12 col-md-2 btnz share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink( $post->ID ); ?>L&title=<?php the_title()?>&source=YOUR-URL"><i class="fa fa-linkedin"></i> LinkedIn</a>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.share').click(function () {
            PopupCenter($(this).prop('href'),'zz',500,600);
            return false;
        });
    });

    function PopupCenter(url, title, w, h) {
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }
</script>
