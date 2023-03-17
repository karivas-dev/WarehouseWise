<script setup>
import { useSlots, ref, computed } from 'vue';

defineProps(['modelValue']);
defineEmits(['update:modelValue']);

const options = ref([]);

const getOptionsFromVNodes = (vnodes) => {
  [...vnodes[0].children].forEach(vnode => {
    options.value.push(vnode);
  });
};

getOptionsFromVNodes(useSlots().default());

const selected = computed(() => {

});
</script>

<template>
    <select class="bg-grayC-300 border border-grayC-500 text-sm
            rounded-lg focus:ring-pinkC-400 focus:border-pinkC-400 block
            w-full p-2.5 placeholder-gray-400 text dark:focus:ring-pinkC-400 mt-1"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
            multiple>
            <slot/>
    </select>
</template>
