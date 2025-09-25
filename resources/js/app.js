

import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import EchoGlobal from './echo-global';
import { mask } from 'vue-the-mask'


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
    title: title => `${title} - ${appName}`,
    resolve: name =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const currentUser = props.initialPage.props.auth?.user
        EchoGlobal(currentUser)

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .use(Toast, {
                position: 'top-right',
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
            })
            .directive('mask', mask)

            .mount(el)
    },
    progress: {
        color: '#F59E0B ',
        style: {
            height: '10px',
            backgroundColor: '#F59E0B',
            borderRadius: '3px',
        },
    },
})
