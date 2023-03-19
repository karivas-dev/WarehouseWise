<script setup>
import {Link, router} from "@inertiajs/vue3";
import {computed, ref} from "vue";

const props = defineProps(['links']);
const scroll = ref(false);
const linksLength = computed(() => {
    return props.links.length - 1;
});

const scrollToTop = () => {
    scroll.value = true;
}

router.on('finish', (event) => {
    if (scroll.value === true) {
        scroll.value = false;
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
});
</script>

<template>
    <div class="flex flex-row justify-center mt-8">
        <ul class="inline-flex mb-8" v-if="links.length > 3">
            <li v-for="(link, index) in links">
                <Link :href="link.url ?? ''"
                        class="px-3 py-2 leading-tight border dark:bg-grayC-500 dark:hover:bg-grayC-300"
                        :class="{
                            'ml-0 rounded-l-lg': index == 0,
                            'rounded-r-lg': index == linksLength,
                            'text dark:border-grayC-400 dark:text-gray-400 dark:hover:text-white': !link.active,
                            'text dark:border-grayC-300 dark:bg-neutral-700 font-bold': link.active,
                        }"
                      v-html="link.label" preserve-scroll
                      @click="scrollToTop">
                </Link>
            </li>
        </ul>
    </div>
</template>
