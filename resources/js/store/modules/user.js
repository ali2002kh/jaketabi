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
        await axios.get('/api/user')
        .then((response) => {context.commit('fetchUser', response.data.data)})
        // .catch((err) => console.log(err))
    }
}

// mutations
const mutations = {
    fetchUser: (state, payload) => state.data = payload
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}