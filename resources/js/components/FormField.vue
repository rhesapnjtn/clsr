<template>
    <div>
        <label :for="id" class="mb-1.5 flex items-center gap-1 text-sm font-semibold text-slate-700">
            {{ label }}
            <span v-if="required" class="text-rose-500">*</span>
        </label>
        <slot></slot>
        <p v-if="error" class="mt-1 flex items-center gap-1 text-xs font-medium text-rose-600">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ error }}
        </p>
        <p v-else-if="hint" class="mt-1 text-xs text-slate-400">{{ hint }}</p>
    </div>
</template>

<script setup>
defineProps({
    id: { type: String, required: true },
    label: { type: String, required: true },
    required: { type: Boolean, default: false },
    error: { type: String, default: '' },
    hint: { type: String, default: '' },
});
</script>