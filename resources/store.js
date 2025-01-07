import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        tabs: [],
        activeTab: null,
        activeTabContent: null,
        loading: false,
    },
    mutations: {
        SET_TABS(state, tabs) {
            state.tabs = tabs;
        },
        SET_ACTIVE_TAB(state, tab) {
            state.activeTab = tab;
        },
        SET_ACTIVE_TAB_CONTENT(state, content) {
            state.activeTabContent = content;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
    },
    actions: {
        fetchTabs({ commit }, tabs) {
            commit('SET_TABS', tabs);
        },
        selectTab({ commit, dispatch }, tab) {
            commit('SET_ACTIVE_TAB', tab);
            dispatch('fetchTabContent', tab);
        },
        fetchTabContent({ commit, state }, tab) {
            if (state.activeTabContent && state.activeTabContent.id === tab.id) {
                return;
            }
            commit('SET_LOADING', true);
            axios
                .get(`/tabs/${tab.id}/content`)
                .then((response) => {
                    commit('SET_ACTIVE_TAB_CONTENT', response.data);
                })
                .catch(() => {
                    commit('SET_ACTIVE_TAB_CONTENT', {
                        title: "Error",
                        content: "Failed to load content.",
                        items: [],
                    });
                    console.log("Failed to load content.");
                })
                .finally(() => {
                    commit('SET_LOADING', false);
                });
        },
    },
});

store.dispatch('fetchTabs', [
    { title: 'Electronics', id: 1 },
    { title: 'Clothing', id: 2 },
    { title: 'Books', id: 3 },
    { title: 'Movies', id: 4 },
    { title: 'Travel', id: 5 },
    { title: 'Food', id: 6 },
    { title: 'Fitness', id: 7 },
    { title: 'Gaming', id: 8 },
    { title: 'Grocery', id: 9 },
    { title: 'Other', id: 10 },
]);

export default store;
