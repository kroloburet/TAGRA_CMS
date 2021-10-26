<!--
########### Footer
-->

<footer>
    <div class="container footer_container TUI_noprint">

        <div class="copy">
            <!-- Tagra installation date: <?= $this->config->item('tagra_instal_date') ?> -->
            <?= $conf['site_name'] ?>&nbsp;&copy;&nbsp;<?= date('Y') . PHP_EOL ?>
            <hr>
            <img src="/img/i.jpg" alt="kroloburet">&nbsp;development by
            <br><a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a>
        </div>

        <?php if ($conf['addthis']['follow'] && $data['addthis_follow']) { ?>

            <div class="follow_box">
                <?= $lexic['basic']['follow_title'] ?>
                <hr>
                <?= $conf['addthis']['follow'] . PHP_EOL ?>
            </div>

        <?php } ?>

    </div>
</footer>
<div class="go_top_btn TUI_noprint" onclick="TUI.GoTo('#body')"></div>

<!--
########### Отложенная загрузка
-->

<script>
    ;(function () {
        const head = document.getElementsByTagName('head')[0];
        // главная таблица стилей
        const gc = document.createElement('link');
        gc.rel = 'stylesheet';
        gc.href = "/css/front/general.css";
        head.insertBefore(gc, head.firstChild);
        // TUI
        const tu = document.createElement('link');
        tu.rel = 'stylesheet';
        tu.href = "/TUI/TUI.css";
        head.insertBefore(tu, head.firstChild);
        <?php
        if (
        (isset($data['addthis_share']) || isset($data['addthis_follow']))
        && ($data['addthis_share'] || $data['addthis_follow'])
        ) {
        if ($conf['addthis']['js']) { ?>
        // кнопки "addthis"
        setTimeout(() => {
            const at = document.createElement('script');
            at.src = "<?= $conf['addthis']['js'] ?>";
            head.insertBefore(at, head.firstChild);
        }, 3000);
        <?php }
        } ?>
    })();
</script>

<!--
########### Подключение JS
-->

<script src="/TUI/TUI.js" defer></script>
<script src="<?= htmlspecialchars($conf['jq']) ?>"></script>

<?php
if ( // если галерея
    (isset($data['gallery_type']) && $data['gallery_type'])
    && (isset($data['gallery_opt']) && $data['gallery_opt'])
) {
    if ( // фото или видео
        $data['gallery_type'] == 'img_folder'
        || $data['gallery_type'] == 'img_desc'
        || $data['gallery_type'] == 'video_yt'
    ) {
        ?>

        <!-- JS для фото или видео галереи -->
        <script src="/scripts/libs/FVGallery/FVGallery.js" defer></script>
        <script>
            window.addEventListener('load', function () {
                $('.FVG_item').FVGallery({type: '<?= $data['gallery_type'] ?>'});
            });
        </script>
    <?php } ?>

    <?php if ($data['gallery_type'] == 'audio') { // аудио ?>
        <!-- JS для аудио галереи -->
        <script>
            /**
             * Модуль управления аудио-плеером
             */
            ;(function ($) {
                let i = 1,
                    playing = false,
                    p = $('#a_player'),
                    action = p.find('.a_action'),
                    title = p.find('.a_title'),
                    tracks = <?= $data['gallery_opt'] ?>,
                    count = Object.keys(tracks).length,
                    audio = p.find('.a_audio')
                        .on('play.AP', function () {
                            playing = true;
                            action.text('<?= addslashes($lexic['gallery']['playing']) ?>');
                        })
                        .on('pause.AP', function () {
                            playing = false;
                            action.text('<?= addslashes($lexic['gallery']['paused']) ?>');
                        })
                        .on('ended.AP', function () {
                            if (i < count) {
                                i++;
                                playTrack(i);
                            } else {
                                loadTrack(1);
                            }
                        })
                        .on('error.AP', function () {
                            action.text('<?= addslashes($lexic['gallery']['error_load']) ?>');
                        }).get(0);
                // загрузка
                const loadTrack = function (id) {
                    i = id;
                    title.text(tracks[id].a_title);
                    p.find('.a_ready').removeClass('a_ready');
                    p.find('.a_item').eq(id - 1).addClass('a_ready');
                    p.find('.a_audio').attr('src', tracks[id].a_url);
                    audio.load();
                };
                // воспроизведение
                const playTrack = function (id) {
                    loadTrack(id);
                    audio.play();
                };
                // предыдущий трек
                p.find('.a_prev')
                    .on('click.AP', function () {
                        i = (i - 1) > 0 ? --i : count;
                        loadTrack(i);
                        if (playing) audio.play();
                    });
                // следующий трек
                p.find('.a_next')
                    .on('click.AP', function () {
                        i = (i + 1) <= count ? ++i : 1;
                        loadTrack(i);
                        if (playing) audio.play();
                    });
                // воспроизвести выбранный
                p.find('.a_item')
                    .on('click.AP', function () {
                        let id = $(this).index() + 1;
                        if (id !== i) playTrack(id);
                    });
                // загрузить первый в списке
                loadTrack(1);
            }(jQuery));
        </script>
    <?php }
} ?>

<?php if (isset($data['js']) && $data['js']) {
    echo "
<!--
########### Пользовательский JS для материала
-->
\n{$data['js']}\n";
} ?>

<!--
########### Вот ты сейчас в этой уйне копошишься,
########### а жизнь мимо летит..)
-->

</body>
</html>
