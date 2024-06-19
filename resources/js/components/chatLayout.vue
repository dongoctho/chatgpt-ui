<template>
    <div class="d-flex flex-column flex-grow-1 color-chatbox max-h-100vh">
        <div class="p-3">
            <slot></slot>
        </div>
        <div id="scrollbar" class="flex-grow-1 overflow-auto d-flex justify-content-center custom-scrollbar">
            <div class="d-flex flex-column align-items-start p-3 w-50 max-h-100vh">
                <listChat/>
            </div>
        </div>
        <div class="p-3 d-flex justify-content-center">
            <inputChat
                ref="inputChat"
                @sent="sendMessage"
                @click="scrollToBottom"
                @keyup.enter="scrollToBottom"
            />
        </div>
    </div>
</template>

<script>
import listChat from "./chatList.vue";
import inputChat from "./chatInput.vue";
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    name: "chatbox",
    components: {
        listChat,
        inputChat
    },
    computed: {
        ...mapGetters(["threadId"]),
        ...mapGetters(["threads"]),
        ...mapGetters(["chats"]),
        ...mapGetters(["profileData"]),
    },
    methods: {
        ...mapActions(["createThread", "chat", "fetchChatByThread"]),
        ...mapMutations(["setLastQuestionId"]),
        async sendMessage(prompt, instructions, lastQuestionId) {
            const userId = this.profileData.id;
            if (this.threadId == null) {
                await this.createThread({
                    instructions: instructions.trim(),
                    title: this.createTitleThread(),
                    userId: userId,
                });
                this.threads.unshift({
                    __pending: true,
                    title: this.createTitleThread(),
                    id: this.threadId,
                });
            }

            await this.chat({
                prompt: prompt.trim(),
                instructions: instructions.trim(),
                threadId: this.threadId,
                userId: userId,
            });

            this.fetchChatByThread(this.threadId);
            console.log("create chat successfully");
        },
        scrollToBottom() {
            const scrollbar = document.getElementById("scrollbar");

            if (scrollbar) {
                scrollbar.scrollTop = scrollbar.scrollHeight - scrollbar.clientHeight;
            }
        },
        fetchResponse(res) {
            this.$store.commit(
                "setChats",
                this.chats.filter((x) => !x.__pending)
            );

            this.chats.push({
                content: res.data.contentQuestion,
                answers: {
                    content: res.data.contentAnswer,
                },
                isProcessing: false,
                __pending: false,
            });
        },
        createTitleThread() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
            const day = ("0" + currentDate.getDate()).slice(-2);
            const hours = ("0" + currentDate.getHours()).slice(-2);
            const minutes = ("0" + currentDate.getMinutes()).slice(-2);
            const seconds = ("0" + currentDate.getSeconds()).slice(-2);
            const title = `Thread-${year}/${month}/${day}-${hours}:${minutes}:${seconds}`;

            return title;
        }
    },
    updated() {
        this.$nextTick(() => {
            this.scrollToBottom();
        });
    },
    mounted() {
        this.$nextTick(() => {
            this.scrollToBottom();
        });
    },

};
document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.querySelector("textarea");

    function autoResize() {
        textarea.style.height = "auto"; // Reset the height to allow for proper resizing
        textarea.style.height = `${textarea.scrollHeight}px`; // Set the height to the scrollHeight
    }

    textarea.addEventListener("input", autoResize);
});
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
