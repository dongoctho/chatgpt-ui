import axios from "axios";
import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            chats: [],
            threadId: null,
            threads: [],
            models: [],
            modelId: null,
            selectedModel: null,
            lastQuestionId: 0,
            profile: {},
        };
    },
    mutations: {
        setChats(state, data) {
            state.chats = data;
        },
        setThreadId(state, thread_id) {
            state.threadId = thread_id;
        },
        setThreads(state, data) {
            state.threads = data;
        },
        setModels(state, data) {
            state.models = data;
        },
        setModelId(state, model_id) {
            state.modelId = model_id;
        },
        setSelectedModel(state, data) {
            this.selectedModel = data;
            state.selectedModel = data;
        },
        setLastQuestionId(state, lastQuestionId) {
            state.lastQuestionId = lastQuestionId;
        },
        setProfile(state, data) {
            state.profile = data;
        },
        removeThread(state, threadId) {
            state.threads = state.threads.filter(thread => thread.id !== threadId);
        }
    },
    actions: {
        async fetchListModel({ commit }) {
            try {
                const response = await axios.get("/api/models");
                commit("setModels", response.data);

                return response.data;
            } catch (error) {
                console.error("Error fetching list chat:", error);
                throw error;
            }
        },
        async fetchListChat({ commit }) {
            try {
                const response = await axios.get("/api/threads");
                commit("setThreads", response.data);

                return response.data;
            } catch (error) {
                console.error("Error fetching list chat:", error);
                throw error;
            }
        },
        async fetchChatByThread({ commit }, id) {
            try {
                const response = await axios.get("/api/threads/" + id + "/messages", {
                    params: {
                        lastQuestionId: this.lastQuestionId
                    }
                });
                const threadId = id;

                if (response.data.length > 0) {
                    const thread = await axios.get("/api/threads/" + id);
                    commit("setThreadId", threadId);
                    commit("setChats", response.data);
                    commit("setModelId", thread.data.model_id);
                }
            } catch (error) {
                console.error("Error fetching chat:", error);
                throw error;
            }
        },
        async createThread(
            { commit },
            { instructions, title }
        ) {
            try {
                const response = await axios.post(
                    "api/threads",
                    {
                        instructions: instructions,
                        title: title,
                        model: this.selectedModel
                    },
                    {
                        timeout: 60000,
                    }
                );
                commit("setThreadId", response.data.id);

                console.log("create thread suscessfully");
            } catch (error) {
                console.error("Error creating chat:", error);
                throw error;
            }
        },
        async chat(
            { },
            { instructions, prompt, threadId }
        ) {
            try {
                const response = await axios.post(
                    "/api/threads/" + threadId + "/messages",
                    {
                        instructions: instructions,
                        prompt: prompt,
                        model: this.selectedModel
                    },
                    {
                        timeout: 60000,
                    }
                );

                return response;
            } catch (error) {
                console.error("Error creating chat:", error);
                throw error;
            }
        },
        async deleteChat({commit}, id) {
            try {
                await axios.delete("/api/threads/" + id);
                commit('removeThread', id);
                console.log("delete chat successfully")
            } catch (error) {
                console.error("Error delete chat:", error);
                throw error;
            }
        },
        async updateTitleThread({ }, {id, title}) {
            try {
                await axios.patch(
                    "/api/threads/" + id ,
                    {
                        title: title
                    }
                );

                console.log("rename chat successfully")
            } catch (error) {
                console.error("Error delete chat:", error);
                throw error;
            }
        },
        async logout({}) {
            try {
                await axios.post("/api/logout");
            } catch (error) {
                console.error('Error during logout:', error);
                throw error;
            }
        },
        async fetchProfile({ commit }) {
            try {
                const response = await axios.get("/api/profile");
                commit("setProfile", response.data);

                return response.data;
            } catch (error) {
                console.error("Error fetching profile:", error);
                throw error;
            }
        },
    },
    getters: {
        chats(state) {
            return state.chats;
        },
        threadId(state) {
            return state.threadId;
        },
        threads(state) {
            return state.threads;
        },
        models(state) {
            return state.models;
        },
        getModelId(state) {
            return state.modelId;
        },
        getSelectedModel(state) {
            return state.selectedModel;
        },
        getLastQuestionId(state) {
            return state.lastQuestionId;
        },
        profileData(state) {
            return state.profile;
        }
    },
});

export default store;
