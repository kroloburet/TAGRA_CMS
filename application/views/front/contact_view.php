<!--
########### Main
-->

<main>
    <div class="container">

        <!-- заголовок -->
        <div class="main_headline">
            <h1><?= $data['title'] ?></h1>
            <button type="button" class="print_btn TUI_noprint" onclick="window.print()">
                <i class="fas fa-print"></i> <?= $lexic['contact']['print'] . PHP_EOL ?>
            </button>
        </div>

        <?php if ($data['content_l'] || $data['content_r'] || $data['content_t'] || $data['content_b']) { ?>
            <!-- контент материала -->
            <div id="content_layout">
                <?php if ($data['content_t']) { ?>
                    <div id="content_t"><?= $data['content_t'] ?></div>
                <?php } ?>
                <?php if ($data['content_l'] || $data['content_r']) { ?>
                    <div id="content_l"><?= $data['content_l'] ?></div>
                    <div id="content_r"><?= $data['content_r'] ?></div>
                <?php } ?>
                <?php if ($data['content_b']) { ?>
                    <div id="content_b"><?= $data['content_b'] ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($data['contacts']) { ?>

            <!-- контакты -->
            <dl class="TUI_Tab contacts">
                <dt><?= $lexic['contact']['list_view'] ?></dt>
                <dd>
                    <?php
                    $contacts = json_decode($data['contacts'], true);
                    foreach ($contacts as $v) {
                        $gps = $v['gps'] ? true : false
                        ?>

                        <div class="contacts_list">
                            <?= ($v['title'] ? '<h2>' . $v['title'] . '</h2>' : '') . PHP_EOL ?>
                            <?= ($v['mail'] ? '<div><i class="fas fa-envelope"></i>&nbsp;' . $v['mail'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['tel'] ? '<div><i class="fas fa-phone"></i>&nbsp;' . $v['tel'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['skype'] ? '<div><i class="fab fa-skype"></i>&nbsp;' . $v['skype'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['viber'] ? '<div><i class="fab fa-viber"></i>&nbsp;' . $v['viber'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['telegram'] ? '<div><i class="fab fa-telegram"></i>&nbsp;' . $v['telegram'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['address'] ? '<div><i class="fas fa-home"></i>&nbsp;' . $v['address'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['gps'] ? '<div><i class="fas fa-crosshairs"></i>&nbsp;' . $v['gps'] . '</div>' : '') . PHP_EOL ?>
                            <?= ($v['gps'] ? '<div><i class="fas fa-map-marked-alt"></i>&nbsp;'
                                . '<a href="https://www.google.com.ua/maps/place/' . $v['gps'] . '" target="_blank">'
                                . $lexic['contact']['big_map_view'] . '</a></div>' : '') . PHP_EOL ?>
                        </div>
                    <?php } ?>

                </dd>
                <?php if ($gps) { ?>

                    <dt><?= $lexic['contact']['map_view'] ?></dt>
                    <dd class="map_tab">
                        <div id="map"></div>
                    </dd>
                <?php } ?>

            </dl>
        <?php } ?>

        <?php if ($data['contact_form']) { ?>

            <!-- форма обратной связи -->
            <div id="send_mail_form" class="TUI_noprint">
                <h2><?= $lexic['contact']['form_title'] ?></h2>
                <form id="send_mail">
                    <label class="TUI_input">
                        <input type="text" name="mail"
                               placeholder="<?= htmlspecialchars($lexic['contact']['your_mail']) ?>">
                    </label>
                    <label class="TUI_input">
                        <input type="text" name="name" oninput="TUI.Lim(this, 50)"
                               placeholder="<?= htmlspecialchars($lexic['contact']['your_name']) ?>">
                    </label>
                    <label class="TUI_textarea">
                    <textarea name="text" rows="5" oninput="TUI.Lim(this, 500)"
                              placeholder="<?= htmlspecialchars($lexic['contact']['your_msg']) ?>"></textarea>
                    </label>
                    <input type="text" name="fuck_bot">
                    <div class="send_mail_actions">
                        <button type="submit"><?= $lexic['contact']['send_form'] ?></button>
                    </div>
                </form>
            </div>

            <script>
                /**
                 * Отправить форму обратной связи
                 */
                window.addEventListener('load', function () {
                    $('#send_mail').on('submit', function (e) {
                        e.preventDefault();
                        let f = $(this),
                            mail = f.find('[name="mail"]'),
                            name = f.find('[name="name"]'),
                            text = f.find('[name="text"]'),
                            actions_box = f.find('.send_mail_actions'),
                            actions = actions_box.html(),
                            delay = 5000,
                            msg = function (m) {
                                actions_box.html(m);
                                setTimeout(function () {
                                    actions_box.html(actions);
                                }, delay);
                            };
                        // валидация полей
                        if (!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(mail.val())) {
                            msg(`<p class="TUI_notice-error"><?= addslashes($lexic['contact']['novalid_field']) ?>"${mail.attr('placeholder')}"</p>`);
                            return;
                        }
                        if (!/\S/.test(name.val())) {
                            msg(`<p class="TUI_notice-error"><?= addslashes($lexic['contact']['novalid_field']) ?>"${name.attr('placeholder')}"</p>`);
                            return;
                        }
                        if (!/\S/.test(text.val())) {
                            msg(`<p class="TUI_notice-error"><?= addslashes($lexic['contact']['novalid_field']) ?>"${text.attr('placeholder')}"</p>`);
                            return;
                        }
                        // блокировать кнопку
                        actions_box.find('button').attr('disabled', true)
                            .html('<i class="fas fa-spin fa-spinner"></i><?= addslashes($lexic['basic']['loading']) ?>');
                        // отправка
                        $.ajax({
                            url: '/do/send_mail',
                            type: 'post',
                            data: f.serialize(),
                            dataType: 'text',
                            success: function (resp) {
                                switch (resp) {
                                    case'bot':
                                        msg('<p class="TUI_notice-error"><?= addslashes($lexic['basic']['bot']) ?></p>');
                                        break;
                                    case'nomail':
                                        msg('<p class="TUI_notice-error"><?= addslashes($lexic['contact']['nomail']) ?></p>');
                                        break;
                                    case'error':
                                        msg('<p class="TUI_notice-error"><?= addslashes($lexic['basic']['error']) ?></p>');
                                        break;
                                    case'ok':
                                        mail.add(name).add(text).val('');
                                        msg('<p class="TUI_notice-success"><?= addslashes($lexic['contact']['send_ok']) ?></p>');
                                        break;
                                    default:
                                        console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                                        msg('<p class="TUI_notice-error"><?= addslashes($lexic['basic']['error']) ?></p>');
                                }
                            },
                            error: function () {
                                msg('<p class="TUI_notice-error"><?= addslashes($lexic['basic']['server_error']) ?></p>');
                            }
                        });
                    });
                });
            </script>

        <?php }
        if ($data['contacts'] && $gps) { ?>

            <script src="https://maps.googleapis.com/maps/api/js?language=<?= $data['lang'] ?>&key=<?= $conf['gapi_key'] ?>"></script>
            <script>
                /**
                 * Google Maps
                 */
                window.addEventListener('load', function () {
                    let data = <?= $data['contacts'] ?>, // объект данных
                        mapOptions = {
                            zoom: 6,
                            scrollwheel: false,
                            center: new google.maps.LatLng(49.303, 31.203),
                            streetViewControl: true,
                            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
                        },
                        map = new google.maps.Map($('.contacts #map')[0], mapOptions),
                        infowindow = new google.maps.InfoWindow(), // создаю инфоокно
                        markers = [];
                    // создать маркеры, расставить по координатам
                    const init = function () {
                        for (let k in data) {
                            let LL = data[k].gps.split(',');
                            markers[k] = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(LL[0], LL[1])
                            });
                            iw(markers[k], data[k].address + (data[k].marker_desc ? '<hr>' + data[k].marker_desc : ''));
                        }
                    };
                    // показать\скрыть инфоокно маркера по клику
                    const iw = function (marker, content) {
                        google.maps.event.addListener(marker, 'click', function () {
                            infowindow.setContent(content); // контент в окне
                            infowindow.open(map, marker); // показать окно
                            map.panTo(marker.getPosition()); // маркер в центр карты
                        });
                        // клик на карте скрывает все окна
                        google.maps.event.addListener(map, 'click', function () {
                            infowindow.close();
                        });
                    };
                    // установить высоту карты
                    $('.contacts #map').height(($(window).height()) / 1.5);
                    // активировать карту
                    init();
                });
            </script>
        <?php } ?>

    </div>
</main>

