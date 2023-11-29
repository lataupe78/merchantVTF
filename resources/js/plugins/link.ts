import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

/**
 * Add supoort for <inertia-link> component
 *
 * keep the reactivity of Inertia Link
 * with a custom directive to
 *
 * can be applied to btn or list-item
 *
 * see :  https://github.com/vuetifyjs/vuetify/issues/11573
 */
export default {
    install(app) {
        app.component("RouterLink", {
            useLink(props) {
                const href = props.to;
                const currentUrl = computed(() => usePage().url);
                const preserveScroll = !!props.preserve;
                // console.log({ preserveScroll, href, props });

                return {
                    route: computed(() => ({ href })),
                    isActive: computed(() => currentUrl.value.startsWith(href)),
                    isExactActive: computed(() => href === currentUrl),
                    navigate(e) {
                        if (e.shiftKey || e.metaKey || e.ctrlKey) return;
                        e.preventDefault();

                        const preserveScroll =
                            !!e.currentTarget.getAttribute("preserve-scroll");

                        console.log({ preserveScroll, href, props });

                        router.visit(href, { preserveScroll: preserveScroll });
                    },
                };
            },
        });
    },
};
