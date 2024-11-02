import '../css/app.css';
import './bootstrap';
import PrimeVue from "primevue/config";
import "primeicons/primeicons.css";
import Aura from '@primevue/themes/aura';
import ConfirmationService from "primevue/confirmationservice";
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const noAuthRoutes = [
    "/api/v1/auth/login",
];

axios.interceptors.request.use(
    (config) => {
        if (!noAuthRoutes.includes(config.url)) {
            const authToken = localStorage.getItem("auth_token");
            if (authToken) {
                config.headers["Authorization"] = `Bearer ${authToken}`;
            }
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura
                }
             })
            .use(ConfirmationService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
