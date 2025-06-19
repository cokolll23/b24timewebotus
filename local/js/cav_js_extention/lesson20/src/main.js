BX.namespace('Cav_js_extention.Lesson20');

BX.Cav_js_extention.Lesson20 = {
    showMessage: function (message = '') {
       // alert('ok');
    },
    onStartWorkingDateAction: function (popupNodeId) {
        var popup = BX.PopupWindowManager.create("greeting-popup-message", BX(popupNodeId), {
            content: 'Вы хотите начать рабочий день?',
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
                    text: 'Начать рабочий день', // текст кнопки
                    id: 'save-btn', // идентификатор
                    className: 'ui-btn ui-btn-success', // доп. классы
                    events: {
                        click: function() {
                            BX.Cav_js_extention.Lesson20.startDate();
                            popup.close();
                        }
                    }
                }),
                new BX.PopupWindowButton({
                    text: 'Отмена',
                    id: 'copy-btn',
                    className: 'ui-btn ui-btn-primary',
                    events: {
                        click: function() {
                            popup.close();
                        }
                    }
                })
            ],
            events: {
                onPopupShow: function() {
                    BX.Cav_js_extention.Lesson20.showMessage('Вы открыли окно начала рабочего дня!');
                },
                onPopupClose: function() {
                    BX.Cav_js_extention.Lesson20.showMessage('Вы закрыли окно начала рабочего дня!');
                }
            }
        });

        popup.show().focus();
    },
    startDate: function (e) {
        $('#timeman_main .tm-popup-timeman-layout-button button.ui-btn.ui-btn-icon-start').click();
    }

};

BX.addCustomEvent('onTimeManWindowBuild', function (e) {
    // #timeman_main
    BX.Cav_js_extention.Lesson20.onStartWorkingDateAction()
    let startdayParentBlock =  BX('timeman_main ');
    let startdayButton = document.querySelector('button.ui-btn.ui-btn-icon-start');
   // todo отправка time date userId на handler и CTimeMan старт раб дня

    console.log(e);
});


/*$('body').on('click', '#greeting-popup-message #save-btn', function (e) {

    alert('ok');
});*/

// /bitrix/tools/timeman.php?action=check_module&site_id=s1&sessid=c295c74ff07b0b9f604eeb4c435851ed
function sendAjax(url, data = {}, node_target = '') {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data.success) {
                let strSuccess = ` Уважаемый ${data.fio} , вы записались на ${data.date} на процедуру ${data.proceduraName} `;
                $(' #popup-window-content-ajaxPopup ').html(strSuccess);
                $('#ajaxPopup').find('#save-btn').css('display','none');
                // alert(strSuccess);
            }
            //$( ' #popup-window-content-ajaxPopup form#popup').html(strSuccess);
        }
    })


}