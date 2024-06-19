<template>
    <div v-if="showMessage" class="container m-auto">
        <div class="p-3 d-flex justify-content-center">
            <img
                class="rounded-circle"
                src="../../images/logoChatgptWhite.svg"
                alt="avatar"
            />
        </div>
        <h3 class="text-center mb-5 color-text-white">
            How can I help you today?
        </h3>
    </div>
    <div
        class="d-flex flex-column align-items-start text-break"
        v-for="item in messages"
        :key="item.id"
        :class="{ 'justify-content-end': item.answers }"
    >
        <div class="d-flex flex-row justify-content-start mb-4">
            <div class="d-flex flex-column justify-content-start">
                <img
                    class="rounded-circle"
                    :src="profileData.avatar"
                    alt="avatar"
                    width="30"
                    height="30"
                />
            </div>
            <div class="ps-3 text-content-chat">
                <div class="">
                    <p class="small mb-0"><strong>{{ profileData.name }}</strong></p>
                </div>
                <div class="">
                    <p class="small mb-0 response-data">
                        {{ item.content }}
                    </p>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-start mb-4">
            <div class="d-flex flex-column justify-content-start">
                <img
                    class="rounded-circle"
                    src="../../images/Avatar ChatGPT.svg"
                    alt="avatar"
                />
            </div>
            <div class="ps-3 text-content-chat">
                <div class="">
                    <p class="small mb-0"><strong>ChatGPT</strong></p>
                </div>
                <div class="">
                    <div
                        class="small mb-0 response-data"
                        v-if="!item.isProcessing"
                        v-html="item.htmlContent"
                        v-highlight
                    ></div>
                    <div v-else class="skeleton-loader">
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line w-75"></div>
                        <div class="skeleton-line w-50"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
import MarkdownIt from "markdown-it";

export default {
    data() {
        return {
            messages: [],
        };
    },
    props: {
        isProcessing: {
            type: Boolean,
            default: true,
        },
    },
    computed: {
        ...mapGetters(["chats"]),
        ...mapGetters(["profileData"]),
        showMessage() {
            return !this.chats || this.chats.length === 0;
        },
    },
    methods: {
        fetchData() {
            this.messages = this.chats;
            const markdown = new MarkdownIt();

            this.messages.forEach((item) => {
                if (
                    !item.isProcessing &&
                    item.answers &&
                    item.answers.content
                ) {
                    item.htmlContent = markdown.render(item.answers.content);
                }
            });
        },
    },
    mounted() {
        this.fetchData();
    },
    watch: {
        chats: {
            handler(newData, oldData) {
                this.fetchData();
            },
            immediate: true,
        },
    },
};
</script>
<style scoped>
.skeleton-loader {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.skeleton-line {
    display: inline-block;
    height: 0.75rem;
    border-radius: 10px;
    width: 400px;
    background: linear-gradient(90deg, #4e4d4d 25%, #3d3d3d 37%, #2c2b2b 63%);
    background-size: 400% 400%;
    animation: skeleton-loading 1.2s ease infinite;
}

@keyframes skeleton-loading {
    0% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0 50%;
    }
}
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
