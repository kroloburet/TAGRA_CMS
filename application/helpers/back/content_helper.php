<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('content')) {

    /**
     * Контент материала
     *
     * @return void
     */
    function content()
    {
        $CI = &get_instance();
        $data = $CI->app('data');
        $conf = $CI->app('conf');
        ?>

        <!--
        ########### Контент
        -->

        <div class="touch" id="content">
            <h2>Контент</h2>
            <hr>
            <div class="layout_control">
                <label>
                    Ширина левой колонки макета
                    <input type="text" name="layout_l_width" size="3">%
                </label>
                <a href="#" onclick="TUI.Toggle('layout_info');return false">
                    О макете&nbsp;<i class="fas fa-angle-down"></i></a>
                <a href="#" class="layout_compact">Kомпактно <i class="fas fa-compress"></i></a>
            </div>
            <p id="layout_info" hidden>
                Чтобы основная часть страницы проще воспринималась визуально и была адаптивной, она представлена в виде
                макета. Сам макет разделен на 4 сегмента (колонки). Вы можете заполнять один и более этих сегментов
                своим контентом (содержимым). Чтобы разместить или редактировать контент в одном из сегментов, выберите
                его, кликнув по нему мышкой. Пустой сегмент, без контента, не будет отображаться на странице. Вы можете
                задать ширину левой колонки в процентном отношении к общей ширине шаблона. Значение ширины шаблона и
                ширина левой колонки по умолчанию для всех вновь создаваемых материалов устанавливается в настройках <q>Макет
                    и редактор</q> (в главном меню: Конфигурация). Чтобы вернуть макет к <q>компактному</q> виду,
                нажмите на <q>Компактно</q> в верхней части этого блока.
            </p>
            <div id="layouts">
                <div id="layout_t"><?= isset($data['layout_t']) ? $data['layout_t'] : '' ?></div>
                <div id="layout_l"><?= isset($data['layout_l']) ? $data['layout_l'] : '' ?></div>
                <div id="layout_r"><?= isset($data['layout_r']) ? $data['layout_r'] : '' ?></div>
                <div id="layout_b"><?= isset($data['layout_b']) ? $data['layout_b'] : '' ?></div>
            </div>
        </div>

        <style>
            #content .layout_control {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 10px;
            }

            #content .layout_control label {
                flex-grow: 1;
                margin-right: 10px;
            }

            #content .layout_control input {
                border: solid 1px var(--color-form-border);
                border-radius: var(--radius-border);
                margin: 0 .5em;
                padding: .2em;
                text-align: center;
                width: 3em;
            }

            #content .layout_control a {
                margin-right: 10px;
                flex-shrink: 0;
            }

            #content .layout_control a:last-child {
                margin-right: 0;
            }

            #content #layouts {
                display: grid;
                grid-template: 2.2em / 60% 1fr;
                grid-auto-rows: 2.2em;
                grid-gap: 10px 1.5em;
            }

            #content #layout_t, #content #layout_b {
                grid-column: 1 / -1;
            }

            #content #layout_t, #content #layout_l, #content #layout_r, #content #layout_b {
                border: solid 1px var(--color-form-border);
                border-radius: var(--radius-border);
                background-color: var(--color-base-bg);
                padding: .3em;
                overflow: auto;
            }

            #content #layout_t.layout_activated, #content #layout_l.layout_activated, #content #layout_r.layout_activated, #content #layout_b.layout_activated {
                grid-row: span 10;
                grid-column: 1 / -1;
                border: solid 1px #c0c0c0;
                outline: none;
            }
        </style>

        <script>
            ;(function () {
                const layoutsSelector = '#layout_t, #layout_l, #layout_r, #layout_b';
                const content = document.querySelector('#content');
                const layouts = content.querySelector('#layouts');
                const segments = layouts.querySelectorAll(layoutsSelector);
                const inputLWidth = content.querySelector('input[name="layout_l_width"]');
                const lWidth = '<?= isset($data['layout_l_width'])
                    ? htmlspecialchars($data['layout_l_width'])
                    : htmlspecialchars($conf['layout_l_width']) ?>';

                /**
                 * Ширина левого сегмента
                 */
                inputLWidth.addEventListener('input', ({target: input}) => {
                    if (!/^[1-9]\d?(\.\d+)?$/.test(input.value)) {
                        input.classList.add('TUI_novalid');
                        return;
                    }
                    input.classList.remove('TUI_novalid');
                    layouts.style.gridTemplateColumns = `${input.value}% 1fr`;
                });

                /**
                 * Активный сегмент
                 */
                segments.forEach(segment => {
                    segment.addEventListener('click', () => {
                        segments.forEach(el => el.classList.remove('layout_activated'));
                        segment.classList.add('layout_activated');
                    });
                });

                /**
                 * Макет в компактный вид
                 */
                content.querySelector('.layout_compact').addEventListener('click', (e) => {
                    e.preventDefault();
                    segments.forEach(segment => {
                        segment.classList.remove('layout_activated');
                    });
                });

                document.addEventListener('DOMContentLoaded', () => {
                    /**
                     * Ширина левого сегмента по умолчанию
                     */
                    inputLWidth.value = lWidth;
                    layouts.style.gridTemplateColumns = `${lWidth}% 1fr`;

                    /**
                     * Запуск текстового редактора по умолчанию
                     */
                    tinymce.init(Object.assign(mce_overall_conf, {
                        selector: layoutsSelector,
                        inline: true,// редактор появляется после клика в елементе
                    }));
                });
            })();
        </script>

        <?php
    }
}
