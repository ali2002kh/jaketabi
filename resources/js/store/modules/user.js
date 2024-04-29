const state = () => ({
    data: null,
    loggedIn: false,
})

// getters
const getters = {
    data: state => state.data,
    loggedIn: state => state.loggedIn 
}

// actions
const actions = {
    async loadUser(context) {
        await axios.get('/api/self')
        .then((response) => {
            context.commit('fetchUser', response.data.data)
            context.commit('LoggedIn')
        })
        .catch((error) => {
            if (error.response && 
                error.response.status && 
                error.response.status == 401) {
                    context.commit('notLoggedIn')
                    context.commit('logout')
                };
        })
    },
    async logout(context) {
        await axios.post('/api/logout')
        .then(() => {
            context.commit('notLoggedIn')
            context.commit('logout')
        })
    }
}

// mutations
const mutations = {
    fetchUser: (state, payload) => state.data = payload,
    logout: (state) => state.data = null,
    LoggedIn: (state) => state.loggedIn = true,
    notLoggedIn: (state) => state.loggedIn = false,
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}