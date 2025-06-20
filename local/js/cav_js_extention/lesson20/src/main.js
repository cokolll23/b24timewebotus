BX.namespace('Cav_js_extention.Lesson20');

BX.Cav_js_extention.Lesson20 = {
    showMessage: function (message = '') {
        // alert(message);
    },
    onStartWorkingDateAction: function (popupNodeId) {
        const objTimemanMain = BX('timeman_main');
        let objUiBtnTd = BX.findChild(objTimemanMain, {tag: 'td', className: 'tm-popup-timeman-layout-button'}, true);// tm-popup-timeman-layout-button
        let objUiBtnSuccess = BX.findChild(objUiBtnTd, {tag: 'button', className: 'ui-btn'}, true);// tm-popup-timeman-layout-button
        let bUiBtnSuccessHasClass = BX.hasClass(objUiBtnSuccess, 'ui-btn-icon-start');
        //alert(bUiBtnSuccessHasClass);

        if (bUiBtnSuccessHasClass == true) {
            var strTextTitle = 'Вы хотите начать рабочий день?';
            var strPopupWindowButton = 'Начать рабочий день';
            var strPopupWindowButtonClass = 'ui-btn-success PopupWindowButtonStart';
        } else {
            strTextTitle = 'Вы хотите закончить рабочий день?';
            strPopupWindowButton = 'Закончить рабочий день';
            strPopupWindowButtonClass = 'ui-btn-danger PopupWindowButtonStop';//ui-btn-danger
        }
        var popup = BX.PopupWindowManager.create("greeting-popup-message", BX(popupNodeId), {
            content: strTextTitle,
            width: 600, // ширина окна
            height: 400, // высота окна
            zIndex: 1100, // z-index
            offsetTop: 0,
            offsetLeft: 30,
            closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 1
            },
            titleBar: 'Начало рабочего дня',
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: true, // можно двигать или нет
            resizable: true, // можно ресайзить
            min_height: 100, // минимальная высота окна
            min_width: 100, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
            angle: true, // появится уголок
            overlay: {
                backgroundColor: 'black',
                opacity: 500
            },
            buttons: [
                new BX.PopupWindowButton({
                    text: strPopupWindowButton, // текст кнопки
                    id: 'save-btn', // идентификатор
                    className: 'ui-btn  ' + ' ' + strPopupWindowButtonClass, // доп. классы
                    events: {
                        click: function () {
                            if (bUiBtnSuccessHasClass) {
                                BX.Cav_js_extention.Lesson20.startDate();
                                popup.destroy();
                                popup.close();
                            } else {
                                BX.Cav_js_extention.Lesson20.endDate();
                                popup.destroy();
                                popup.close();
                            }
                        }
                    }
                }),
                new BX.PopupWindowButton({
                    text: 'Отмена',
                    id: 'copy-btn',
                    className: 'ui-btn ui-btn-primary',
                    events: {
                        click: function () {
                            popup.close();
                        }
                    }
                })
            ],
            events: {
                onPopupShow: function () {
                    BX.Cav_js_extention.Lesson20.showMessage('Вы открыли окно начала рабочего дня!');
                },
                onPopupClose: function () {
                    BX.Cav_js_extention.Lesson20.showMessage('Вы закрыли окно начала рабочего дня!');
                }
            }
        });

        popup.show().focus();
    },
    startDate: function (e) {
        $('#timeman_main .tm-popup-timeman-layout-button button.ui-btn.ui-btn-icon-start').click();
    },
    endDate: function (e) {
        $('#timeman_main .tm-popup-timeman-layout-button button.ui-btn.ui-btn-icon-stop').click();
    }
};
BX.addCustomEvent('onTimeManWindowBuild', function (e) {
    // #timeman_main button ui-btn ui-btn-success     ui-btn-icon-start
    BX.Cav_js_extention.Lesson20.onStartWorkingDateAction()
});
BX.addCustomEvent('onTimeManWindowOpen', function (e) {
    // #timeman_main
    BX.Cav_js_extention.Lesson20.onStartWorkingDateAction()
});
