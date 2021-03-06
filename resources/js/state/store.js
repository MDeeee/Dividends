import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'
import layout from './modules/layout'

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: process.env.NODE_ENV !== 'production',

    plugins: [
        createPersistedState()
    ],
    modules: {
        layout
    }
})
export default store
