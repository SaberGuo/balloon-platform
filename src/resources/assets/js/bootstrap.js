
window._ = require('lodash');
window.moment = require('moment');
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
} catch (e) {}

window.Vue = require('vue');
import VueResource from 'vue-resource';
Vue.use(VueResource);

window.Cookie = require('js-cookie');
Vue.http.interceptors.push((request, next) => {
    window.app.loading = true;
    next(()=> {
    window.app.loading = false;
    });
});
Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
    next();
});

Vue.http.interceptors.push((request, next) => {
    window.app.loading = true;
    next(()=> {
    window.app.loading = false;
    });
});

Object.assign = Object.assign || _.assign

function tips(type, msg) {
    $('.alert-'+type).text(msg).fadeTo(1000, 1).slideUp(2000, function() {
        $("#alert").slideUp(500);
    });
}

Vue.http.interceptors.push((request, next) => {
    var alert = request.params.alert;
    delete request.params.alert;
    next((response)=> {
        if (alert) {
            var type = response.ok ? 'success': 'warning';
            tips(type, alert + (response.ok ? '成功' : '失败'));
        }
        if (response.status == 403) {
            tips('warning', '权限不足')
        }
    });
});
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
