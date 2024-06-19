<template>
    <div class="d-flex w-100">
        <ChatLayout>
            <ModalComponent />
        </ChatLayout>
    </div>
</template>

<script>
import ModalComponent from "./modal.vue";
import ChatLayout from "./chatLayout.vue";
export default {
    name: "App",
    components: {
        ModalComponent,
        ChatLayout,
    },
};
</script>
