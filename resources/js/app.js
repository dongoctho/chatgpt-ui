import './bootstrap';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle';
import 'bootstrap-icons/font/bootstrap-icons.css';
import { createApp } from 'vue';
import router from './routes/route';
import store from './store/store';
import App from './App.vue';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import highlight from './directive/highlight';

const app = createApp(App);
app.directive('highlight', highlight);
app.use(router)
   .use(store)
   .use(Toast)
   .mount('#app');
