<script setup>
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

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
    },
    back: {
        type: Boolean,
        default: false,
    }
});
const finalColor = ref(props.color);
const emit = defineEmits(['click']);

if (props.back) {
    finalColor.value = 'neutral';
}

const classes = {
    class: {
        "px-8 py-2.5 border border-whiteC-400 rounded-full font-extrabold text-sm text-white font-mukta uppercase": true,
        "tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 text-center": true,
        "bg-pinkC-400 hover:bg-pinkC-300 active:bg-pinkC-300 focus:bg-pinkC-300 focus:ring-pinkC-400": finalColor.value === 'pink',
        "bg-red-800 hover:bg-red-600 active:bg-red-600 focus:bg-red-600 focus:ring-pinkC-400": finalColor.value === 'red',
        'bg-neutral-500 hover:bg-neutral-400 active:bg-neutral-600 focus:bg-gray-400 focus:ring-gray-600': finalColor.value === 'neutral',
    }
};

const clicked = () => {
    emit('click');
    if (props.back) {
        back();
    }
};

const back = () => {
    window.history.back();
}
</script>

<template>
    <Link v-if="href != null" :href="href" :type="type" as="button" @click="clicked"
          :="classes" :class="props.class"
    >
        <slot />
    </Link>

    <button v-if="href == null" :type="type" as="button" @click="clicked"
        :="classes" :class="props.class" :disabled="disabled"
    >
        <slot />
    </button>
</template>
