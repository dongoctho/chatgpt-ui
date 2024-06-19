<template>
    <div class="d-flex">
        <div class="sidebar-container color-sidebar h-100vh" :class="{ collapsed: isCollapsed }">
            <div class="flex-grow-1 overflow-auto d-flex flex-column justify-content-between h-100">
                <div class="top-thread overflow-y-auto custom-scrollbar flex-grow-1">
                    <div class="input-group mb-3 lh-5 sticky-top top-newchat">
                        <a href="" class="new-chat d-flex justify-content-between w-100 p-2 mt-3 me-2 ms-2 text-decoration-none">
                            <span class="p-1">
                                <img src="../../images/logo.svg" class="icon-chatgpt" alt="" />
                                New chat
                            </span>
                            <span class="p-1">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                        </a>
                    </div>
                    <div v-for="thread in threads" :key="thread.id" class="thread p-2">
                        <div class="sidebar-thread-container input-group d-flex justify-content-between">
                            <div v-if="editingThreadId !== thread.id" class="col-10 cursor-thread" @click="getChatByThread(thread.id)">
                                {{ thread.title }}
                            </div>
                            <input v-else v-model="editingTitle" type="text" class="form-control col-10" @blur="saveTitle(thread.id)" @keyup.enter="saveTitle(thread.id)">
                            <div class="dropdown">
                                <a class="col-2 dropdown-toggle setting-thread-dropdown" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <ul class="dropdown-menu col-3 dropdown-thread-list" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <a class="dropdown-item" @click="renameThread(thread.id)">
                                            <i class="bi bi-pen"></i>
                                            Rename
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" @click="deleteThread(thread.id)">
                                            <i class="bi bi-trash3"></i>
                                            Delete chat
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bot-thread p-2 new-chat m-2 flex-grow-2">
                    <a class="dropdown text-decoration-none">
                        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle user-dropdown" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                            <img :src="profileData.avatar" alt="" width="32" height="32" class="rounded-circle me-2" />
                            <strong class="user-text ">{{ profileData.name }}</strong>
                        </a>
                        <ul class="dropdown-menu text-small acount-item shadow" aria-labelledby="dropdownUser2">
                            <li>
                                <a class="dropdown-item" href="#">Profile</a>
                            </li>
                            <li><hr class="dropdown-divider bg-light" /></li>
                            <li>
                                <a class="dropdown-item" @click="logout">Sign out</a>
                            </li>
                        </ul>
                    </a>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center collapse-sidebar position-relative">
            <div class="toggle-button pl-10" @click="toggleCollapse">
                <span v-if="isCollapsed"><i class="bi bi-chevron-compact-right fw-bold fs-5"></i></span>
                <span v-else><i class="bi bi-chevron-compact-left fw-bold fs-5"></i></span>
            </div>
        </div>
    </div>
</template>


<script>
import { mapActions, mapGetters } from "vuex";

export default {
    name: 'SidebarComponent',
    data() {
        return {
            isCollapsed: false,
            editingThreadId: null,
            editingTitle: ''
        };
    },
    computed: {
        ...mapGetters(["getLastQuestionId", "threads", "profileData"]),
    },
    methods: {
        ...mapActions(["fetchChatByThread", "fetchListChat", "deleteChat", "updateTitleThread"]),
        toggleCollapse() {
            this.isCollapsed = !this.isCollapsed;
        },
        async getChatByThread(id) {
            try {
                this.fetchChatByThread(id);
            } catch (error) {
                console.log(error);
            }
        },
        async logout() {
            try {
                await this.$store.dispatch('logout');
                window.location.href = '/login';
            } catch (error) {
                console.error('Error during logout:', error);
            }
        },
        async deleteThread(id) {
            console.log(id);
            if (confirm("Bạn có chắc chắn muốn xóa cuộc trò chuyện này không?")) {
                try {
                    await this.$store.dispatch('deleteChat', parseInt(id));
                    window.location.href = '/';
                } catch (error) {
                    console.log(error);
                }
            } else {
                console.log("Hành động xóa đã được hủy bỏ.");
            }
        },
        renameThread(id) {
            const thread = this.threads.find(thread => thread.id === id);
            this.editingTitle = thread.title;
            this.editingThreadId = id;
        },
        saveTitle(id) {
            const thread = this.threads.find(thread => thread.id === id);
            thread.title = this.editingTitle;

            this.updateTitleThread({id: parseInt(id), title: this.editingTitle});
            this.editingThreadId = null;
        }
    },
    mounted() {
        this.$store.dispatch('fetchListChat');
        this.$store.dispatch('fetchProfile');
    },
    watch: {
        getLastQuestionId(newId) {
            if (newId) {
                this.getChatByThread(newId);
            }
        }
    }
};
</script>


<style scoped>
.cursor-thread {
    cursor: pointer;
}

.thread {
    color: white;
}

.sidebar-thread-container {
    padding: 5px;
}

.sidebar-thread-container a {
    color: white;
    padding: 5px;
}

.sidebar-thread-container:hover {
    background-color: rgb(40, 40, 40);
    border-radius: 7px;
}

.collapse-sidebar {
    background-color: rgb(33, 33, 33);
    color: white;
}

.toggle-button {
    cursor: pointer;
    z-index: 1;
}

.color-sidebar {
    background-color: rgb(23, 23, 23);
}

.sidebar-container {
    width: 280px;
    height: 100vh;
    transition: width 0.3s ease;
}

.top-newchat {
    background-color: rgb(23, 23, 23);
}

.new-chat {
    color: rgb(255, 255, 255);
}

.new-chat:hover {
    background-color: rgb(40, 40, 40);
    border-radius: 7px;
}

.icon-chatgpt {
    width: 25px;
    margin-right: 5px;
}

.sidebar-container.collapsed {
    width: 0;
}

.sidebar-container.collapsed .nav-link span {
    display: none;
}

.sidebar-container.collapsed .dropdown-toggle span {
    display: none;
}

.setting-thread-dropdown::after {
    content: none;
}

.setting-thread-dropdown {
    cursor: pointer;
}

.dropdown-thread-list {
    background-color: rgb(47, 47, 47);
    padding: 5px;
}

.dropdown-thread-list li a {
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
}

.dropdown-thread-list li i {
    margin-right: 10px;
    margin-left: 10px;
}

.dropdown-thread-list li a:hover {
    background-color: rgb(65, 65, 65);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgb(23, 23, 23);
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 5px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.user-dropdown::after {
    content: none;
}

.user-text {
    color: white;
}

.acount-item {
    background-color: rgb(47, 47, 47);
    padding: 5px;
    width: 265px;
    inset: auto 0px 15px -9px !important;
}

.acount-item li a:hover {
    background-color: rgb(65, 65, 65);
    border-radius: 5px;
}

.acount-item li a {
    color: white;
}

</style>
