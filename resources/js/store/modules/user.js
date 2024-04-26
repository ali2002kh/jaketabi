const state = () => ({
    data: null,
})

// getters
const getters = {
    data: state => state.data   
}

// actions
const actions = {
    async initState(context) {
        await axios.get('/api/user')
        .then((response) => {context.commit('startState', response.data.data)})
        // .catch((err) => console.log(err))
    }
}

// mutations
const mutations = {
    startState: (state, payload) => state.data = payload
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}