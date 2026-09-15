import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
        checked: false,
    }),

    getters: {
        isAuthed: (state) => Boolean(state.user),
    },

    actions: {
        async login(email, password) {
            const { data } = await axios.post('/login', { email, password });
            this.user = data.user;
            return data.user;
        },

        async logout() {
            await axios.post('/logout');
            this.user = null;
        },

        async fetchMe() {
            this.loading = true;
            try {
                const { data } = await axios.get('/me');
                this.user = data.user;
            } catch {
                this.user = null;
            } finally {
                this.loading = false;
                this.checked = true;
            }
        },
    },
});