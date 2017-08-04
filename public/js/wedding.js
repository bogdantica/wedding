/**
 * Created by tkagnus on 04/08/2017.
 */

function notification(message, type) {

    new PNotify({
        text: message,
        type: type ? type : 'success'
    });
}