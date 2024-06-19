import hljs from "highlight.js";
import "highlight.js/styles/atom-one-dark.css";

const highlight = {
    beforeMount(el) {
        let blocks = el.querySelectorAll("pre code");
        blocks.forEach((block) => {
            hljs.highlightElement(block);
        });
    },
    updated(el) {
        let blocks = el.querySelectorAll("pre code");
        blocks.forEach((block) => {
            hljs.highlightElement(block);
        });
    },
};

export default highlight;
