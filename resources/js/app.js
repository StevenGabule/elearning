require('./bootstrap');

window.Vue = require('vue');


Vue.config.ignoredElements = ['video-js'];
require('./components/lesson_uploads');

const app = new Vue({
    el: '#app',
});
