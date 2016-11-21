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
        border:none;
        padding: 0 !important;
        background-color: #ececec;
        text-decoration: none;
        font-size: 18px;
        color: #FFF;
        min-width: 33px!important;
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
    .small-social-shrd {
        width: 102px!important;
        margin-bottom: 10px;
        margin-right: 15px;
    }
</style>

    <div class="pull-right small-social-shrd">
        <!-- Facebook Share Button -->
        <a class="pull-right btnz share facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(get_the_ID()); ?> ?>"><i class="fa fa-facebook"></i> </a>
        <!-- Twitter Share Button -->
        <a class="pull-right btnz share twitter" href="https://twitter.com/intent/tweet?text=<?php the_title()?>&url=<?php echo get_permalink(get_the_ID()); ?>"><i class="fa fa-twitter"></i> </a>
        <!-- LinkedIn Share Button -->
        <a class="pull-right btnz share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(get_the_ID()); ?>&title=<?php the_title()?>&source=YOUR-URL"><i class="fa fa-linkedin"></i> </a>
    </div>