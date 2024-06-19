<template>
    <form class="w-50">
        <div class="input-group">
            <textarea
                placeholder="Type your message..."
                name=""
                id=""
                cols="120"
                rows="1"
                class="custom-padding bg-transparent custom-scrollbar input-chatgpt"
                v-model="message"
                @keyup="handleKeyboard"
            ></textarea>
            <button
                type="button"
                class="btn btn-primary button-send-message bg-transparent bottom-0"
                @click="sendMessage"
            >
                <img src="../../images/send.svg" class="w-100" alt="" />
            </button>
        </div>
    </form>
</template>
<script>
import { mapGetters, mapMutations } from "vuex";

export default {
    data() {
        return {
            message: "",
            instructions: "",
            lastChatId: null
        };
    },
    computed: {
        ...mapGetters(["chats"]),
    },
    methods: {
        ...mapMutations(["setLastQuestionId"]),
        handleKeyboard(event) {
            if (event.key === 'Enter' && event.shiftKey) {
                console.log('breakdown');
            } else if (event.key === 'Enter') {
                this.sendMessage();
            }
        },
        sendMessage() {
            const textarea = document.querySelector("textarea");
            if (!this.message.trim()) {
                return;
            }

            this.$emit("sent", this.message, this.instructions);

            if (this.chats.length > 1) {
                this.$store.commit('setLastQuestionId', this.chats[this.chats.length - 1].id);
            }
            
            this.chats.push({
                isProcessing: true,
                __pending: true,
                content: this.message,
                answers: {},
            });

            this.message = "";
            textarea.style.height = "auto";
        }
    },
};
</script>
<style scoped>
.color-text-white {
    color: white;
}

.border-start {
    border: rgb(66, 66, 66) 1px solid;
}

.custom-padding {
    padding-left: 20px;
    padding-right: 45px;
    padding-top: 15px;
    padding-bottom: 15px;
}
.max-h-100vh {
    max-height: 100vh;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background-color: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgb(66, 66, 66);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #555;
    cursor: pointer;
}

.color-chatbox {
    background-color: rgb(33, 33, 33);
}

.input-chatgpt {
    border: 1px solid rgb(66, 66, 66);
    border-radius: 15px;
    color: #eee;
    resize: none;
    max-height: 200px;
}

.text-content-chat {
    color: white;
}

.input-chatgpt::placeholder {
    color: #eee;
}

.input-chatgpt:focus {
    outline: none;
    border: 1px solid rgb(85, 85, 85);
}

.button-send-message {
    border: 0;
    position: absolute;
    right: 0;
    width: 50px;
    height: 50px;
    margin-bottom: 5px;
    margin-right: 5px;
    z-index: 1000;
}

.input-group:not(.has-validation)
    > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not(
        .form-floating
    ),
.input-group:not(.has-validation) > .dropdown-toggle:nth-last-child(n + 3),
.input-group:not(.has-validation)
    > .form-floating:not(:last-child)
    > .form-control,
.input-group:not(.has-validation)
    > .form-floating:not(:last-child)
    > .form-select {
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
}
</style>
