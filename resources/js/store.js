import { createStore } from 'vuex';

const store = createStore({
    state: {
        cart: []
    },
    mutations: {
        ADD_TO_CART(state, product) {
            state.cart.push(product);
        }
    },
    actions: {
        addToCart({ commit }, product) {
            commit('ADD_TO_CART', product);
        }
    },
    getters: {
        cartItems: state => state.cart,
        cartTotal: state => state.cart.reduce((total, product) => total + product.price, 0)
    }
});

export default store;
