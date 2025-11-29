import "./bootstrap";
// import "../css/app.css";//comentado para que no cargue los estilos por defecto y solo vuetify

// mis scripts
import "./assets/js/jquery.min.js";
import "./assets/js/bootstrap.bundle.js";

// css
import "./assets/css/all.min.css";
import "./assets/css/adminlte.min.css";
import "./assets/css/botones.css";
import "./assets/css/backgroundYcolor.css";
import "./assets/css/fonts.css";
import "./assets/css/config.css";
import "./assets/css/datatables.css";
import "./assets/css/form.css";
import "./assets/css/icheck-bootstrap.min.css";
import "./assets/css/miTable.css"; // mi-table

// import "./assets/js/adminlte.min.js";

// importar estilos de AOS
import "aos/dist/aos.css";
import AOS from "aos";

import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

// sweetalert2
import Swal from "sweetalert2";
window.Swal = Swal;

// Pinia
import { createPinia } from "pinia";
const pinia = createPinia();

// Element-UI plus
// import ElementPlus from "element-plus";
// import "element-plus/dist/index.css";
import {
    ElSelect,
    ElOption,
    ElInput,
    ElSwitch,
    ElTooltip,
    ElCarousel,
    ElCarouselItem,
    ElSkeleton,
    ElSkeletonItem,
    ElRadio,
    ElRadioButton,
    ElRadioGroup,
} from "element-plus";

// Import CSS de cada componente individual
import "element-plus/es/components/select/style/css";
import "element-plus/es/components/option/style/css";
import "element-plus/es/components/input/style/css";
import "element-plus/es/components/radio/style/css";
import "element-plus/es/components/switch/style/css";
import "element-plus/es/components/tooltip/style/css";
import "element-plus/es/components/carousel/style/css";
import "element-plus/es/components/carousel-item/style/css";
import "element-plus/es/components/skeleton/style/css";
import "element-plus/es/components/skeleton-item/style/css";

// Default Layout
import App from "@/Layouts/App.vue";

router.on("navigate", () => {
    axios.get(route("verificaLogin")).then((response) => {
        if (!response.data.sw) {
            window.location.href = route("portal.index");
        }
    });
    AOS.init({
        duration: 1000,
        easing: "ease-in-out",
        once: false,
        mirror: false,
    });
    setTimeout(() => {
        const hash = window.location.hash;
        if (hash) {
            const target = document.querySelector(hash);
            if (target) {
                target.scrollIntoView({ behavior: "smooth" });
            }
        }
    }, 300);
});

// App
const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        page.then((module) => {
            module.default.layout = module.default.layout ?? App;
        });
        return page;
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(pinia);
        // .use(ElementPlus);

        vueApp.component("ElSelect", ElSelect);
        vueApp.component("ElOption", ElOption);
        vueApp.component("ElInput", ElInput);
        vueApp.component("ElSwitch", ElSwitch);
        vueApp.component("ElTooltip", ElTooltip);
        vueApp.component("ElCarousel", ElCarousel);
        vueApp.component("ElCarouselItem", ElCarouselItem);
        vueApp.component("ElSkeleton", ElSkeleton);
        vueApp.component("ElSkeletonItem", ElSkeletonItem);
        vueApp.component("ElRadio", ElRadio);
        vueApp.component("ElRadioButton", ElRadioButton);
        vueApp.component("ElRadioGroup", ElRadioGroup);

        vueApp.mount(el);
        return vueApp;
    },
    progress: {
        color: "#aac13f",
    },
});
