// Vuetify
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";


const dark = {
    dark: true,
    colors: {
        background: "#15202b",
        surface: "#15202b",
        primary: "#3f51b5",
        secondary: "#03dac6",
        error: "#ff5722",
    },
};

const light = {
    dark: false,
    colors: {
        background: "#ccc",
        surface: "#15202b",
        primary: "#3f51b5",
        secondary: "#00ccff",
        error: "#ffcc00",
    },
};



const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "dark",
        themes: {
            //  light,
            dark,
        },
    },
});

export default vuetify;