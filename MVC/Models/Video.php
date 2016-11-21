<?php
namespace MVC\Models;

use \MVC\Singleton;

class Video
{
    use Singleton;

    const FIELD_NAME_GROUP = 'video_group';
    const FIELD_NAME_CHILD = 'youtube_videos';

    const FIELD_NAME_LINK = 'video_link';
    const FIELD_NAME_THUMBNAIL = 'video_thumbnail';
    const FIELD_NAME_TITLE = 'video_title';
    const FIELD_NAME_DESCRIPTION = 'video_description';

    /**
     * @return array
     */
    public function getVideos()
    {
        $data = [];
        if (have_rows(self::FIELD_NAME_GROUP)) {
            while (have_rows(self::FIELD_NAME_GROUP)) {
                the_row();
                if (have_rows(self::FIELD_NAME_CHILD)) {
                    while (have_rows(self::FIELD_NAME_CHILD)) {
                        the_row();
                        $data[] = [
                            'link' => get_sub_field(self::FIELD_NAME_LINK),
                            'thumbnail' => get_sub_field(self::FIELD_NAME_THUMBNAIL),
                            'title' => get_sub_field(self::FIELD_NAME_TITLE),
                            'description' => get_sub_field(self::FIELD_NAME_DESCRIPTION),
                        ];
                    }
                }
            }
        }

        return $data;
    }


}