<template>
    <div class="d-flex">
        <SidebarComponent />
        <ContentChatGptVue />
    </div>
</template>

<script>
import ContentChatGptVue from './contentChatGpt.vue';
import SidebarComponent from "./sidebar.vue";

export default {
    name: "App",
    components: {
        SidebarComponent,
        ContentChatGptVue
    },
};
</script>
