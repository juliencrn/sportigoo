<?php
/**
 * Activity preview
 *
 * This template is only a skeleton template who receive data
 * form Ajax & Javascript when preview's called
 *
 * It must be placed after .activities__row element
 */
?>
<div class="activity-preview" style="">
    <div class="container">

        <div class="activity-preview__container">
            <div class="activity-preview__left">

            </div>


            <div class="activity-preview__right">
                <div class="activity-preview__video-button">
                    <svg width="75" height="75">
                        <use xlink:href="#play"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="activity-preview__video">
        <div class="activity-preview__video-container">
            <div class="activity-preview__close-video">
                <svg width="25" height="25">
                    <use xlink:href="#cross"></use>
                </svg>
            </div>
            <div class="video-container">
                <iframe
                        width="1080"
                        height="610"
                        src=""
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen="allowfullscreen"
                ></iframe>
            </div>
        </div>
    </div>
    <div class="activity-preview__filter"></div>
</div>