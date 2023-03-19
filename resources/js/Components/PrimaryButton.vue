<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    href: {
        type: String,
        default: null,
    },
    class: {
        type: String,
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: 'pink'
    }
});

const emit = defineEmits(['click']);

const classes = {
    class: {
        "px-8 py-2.5 border border-whiteC-400 rounded-full font-extrabold text-sm text-white font-mukta uppercase": true,
        "tracking-widest hover:bg-pinkC-400 focus:bg-pinkC-300 active:bg-pinkC-300": true,
        "focus:outline-none focus:ring-2 focus:ring-pinkC-400 focus:ring-offset-2": true,
        "transition ease-in-out duration-150 text-center": true,
        "bg-pinkC-400": props.color === 'pink',
        "bg-red-800": props.color === 'red',
    }
};
</script>

<template>
    <Link v-if="href != null" :href="href" :type="type" as="button" @click="emit('click')"
          :="classes" :class="props.class"
    >
        <slot />
    </Link>

    <button v-if="href == null" :type="type" as="button" @click="emit('click')"
        :="classes" :class="props.class" :disabled="disabled"
    >
        <slot />
    </button>
</template>
