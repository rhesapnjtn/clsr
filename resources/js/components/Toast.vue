<template>
    <div class="pointer-events-none fixed inset-x-0 top-4 z-[100] flex flex-col items-center gap-2 px-4">
        <transition-group name="toast">
            <div
                v-for="t in store.toasts"
                :key="t.id"
                class="pointer-events-auto flex w-full max-w-md items-start gap-3 rounded-xl border px-4 py-3 shadow-xl backdrop-blur"
                :class="{
                    'border-emerald-200 bg-emerald-50/95 text-emerald-800': t.type === 'success',
                    'border-rose-200 bg-rose-50/95 text-rose-800': t.type === 'error',
                    'border-sky-200 bg-sky-50/95 text-sky-800': t.type === 'info',
                }"
            >
                <span class="mt-0.5 shrink-0">
                    <svg v-if="t.type === 'success'" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <svg v-else-if="t.type === 'error'" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <svg v-else class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <p class="flex-1 text-sm font-medium">{{ t.message }}</p>
                <button @click="store.dismiss(t.id)" class="shrink-0 opacity-50 hover:opacity-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </transition-group>
    </div>
</template>

<script setup>
import { useToastStore } from '../stores/toast';

const store = useToastStore();
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateY(-16px) scale(0.96);
}
.toast-leave-to {
    opacity: 0;
    transform: translateY(-12px) scale(0.96);
}
</style>