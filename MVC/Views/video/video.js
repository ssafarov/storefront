(function ($) {
    $(document).ready(function () {

        /*
         Carousel initialization
         */
        $('.jcarousel')
            .jcarousel({
                // Options go here
            });

        /*
         Prev control initialization
         */
        $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function () {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function () {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                // Options go here
                target: '-=1'
            });

        /*
         Next control initialization
         */
        $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function () {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function () {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                // Options go here
                target: '+=1'
            });

        /*
         Pagination initialization
         */
        $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function () {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function () {
                $(this).removeClass('active');
            })
            .jcarouselPagination({
                // Options go here
            });


        // custom

        /**
         *
         * @param self
         * @param autoplay
         */
        function vid_item_click(self, autoplay) {
            self = $(self);
            if (self.data('url')) {
                if (autoplay) $("#video_container").show();
                $("#video_container iframe").attr('src', self.data('url') + '?theme=light' + (autoplay ? '&autoplay=1&loop=1' : ''));
                $("#video_description .title").html(self.find('.vid_title').html());
                $("#video_description .description").html(self.find('.vid_description').html());
            }
        }

        /**
         * init
         */
        vid_item_click($(".vid_item")[0], false);

        /**
         * item click
         */
        $(".vid_item").click(function () {
            vid_item_click(this, true);
        });

    });
})(jQuery);