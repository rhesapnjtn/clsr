import { defineStore } from 'pinia';

let seed = 0;

export const useToastStore = defineStore('toast', {
    state: () => ({
        toasts: [],
    }),

    actions: {
        push(message, type = 'success') {
            const id = ++seed;
            this.toasts.push({ id, message, type });
            setTimeout(() => this.dismiss(id), 4000);
        },
        success(message) {
            this.push(message, 'success');
        },
        error(message) {
            this.push(message, 'error');
        },
        info(message) {
            this.push(message, 'info');
        },
        dismiss(id) {
            this.toasts = this.toasts.filter((t) => t.id !== id);
        },
    },
});