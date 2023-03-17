<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-8 py-2.5 bg-pinkC-400 border
        border-whiteC-400 rounded-full font-extrabold text-sm text-white font-mukta uppercase
        tracking-widest hover:bg-pinkC-400 focus:bg-pinkC-300 active:bg-pinkC-300
        focus:outline-none focus:ring-2 focus:ring-pinkC-400 focus:ring-offset-2
        transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
