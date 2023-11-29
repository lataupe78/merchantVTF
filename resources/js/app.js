import './bootstrap';

import '@mdi/font/css/materialdesignicons.css';



import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

import vuetify from './plugins/vuetify'


import useTranslation from "@/composables/useTranslation.js";
const { __, trans_choice } = useTranslation();

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import link from "@/plugins/link";
// import { translations } from '@/helpers/translationsMixin.js';
import PreDebug from '@/Components/Debug/PreDebug.vue'


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)

            .use(vuetify)
            .use(link)
            .use(__)
            //  .mixin(translations)
            .mixin({ methods: { __, trans_choice } })

            .mixin({ components: { PreDebug } })

            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
