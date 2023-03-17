<script setup>
defineProps(['value']);
</script>

<template>
    <label class="block font-medium text-sm text-whiteC-500
    font-raleway select-none">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
