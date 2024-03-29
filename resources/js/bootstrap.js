window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
  console.error('ERROR DE BOOSTRAP.JS')

}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');
window.name = "Enterprise";
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

 import Echo from 'laravel-echo';
window.stripePublic = "pk_test_51HhtxnEZSaM3aAi1d0i564f4jbZeEBHtptsiC5VVt127QxDMnGEQedjNeP10ou2tvxs4GzXVc73IyEIFdgEfujw400oAwekUSt"
 window.Pusher = require('pusher-js');
 window.public_stripe = process.env.STRIPE_KEY
