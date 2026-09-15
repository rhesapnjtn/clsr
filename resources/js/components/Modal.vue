<template>
    <teleport to="body">
        <transition name="modal">
            <div v-if="open" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/50 p-0 backdrop-blur-sm sm:items-center sm:p-4" @click.self="$emit('close')">
                <div class="w-full max-w-lg overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h3 class="text-base font-extrabold text-slate-900">{{ title }}</h3>
                        <button @click="$emit('close')" class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="max-h-[75vh] overflow-y-auto px-6 py-5">
                        <slot></slot>
                    </div>
                    <div v-if="$slots.footer" class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
defineProps({
    open: { type: Boolean, default: false },
    title: { type: String, required: true },
});
defineEmits(['close']);
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-active .bg-white,
.modal-leave-active .bg-white {
    transition: transform 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .bg-white,
.modal-leave-to .bg-white {
    transform: translateY(20px);
}
</style>