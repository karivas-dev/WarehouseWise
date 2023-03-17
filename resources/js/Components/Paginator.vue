<script setup>
import { Link } from "@inertiajs/vue3";
import {computed} from "vue";

const props = defineProps(['links']);

const linksLength = computed(() => {
    return props.links.length - 1;
});
</script>

<template>
    <div class="flex flex-row justify-center my-8">
        <ul class="inline-flex">
            <li v-for="(link, index) in links">
                <Link :href="link.url"
                        class="px-3 py-2 leading-tight border dark:bg-grayC-500 dark:hover:bg-grayC-300"
                        :class="{
                            'ml-0 rounded-l-lg': index == 0,
                            'rounded-r-lg': index == linksLength,
                            'text dark:border-grayC-400 dark:text-gray-400 dark:hover:text-white': !link.active,
                            'text dark:border-grayC-300 dark:bg-gray-700 font-bold': link.active,
                        }"
                      v-html="link.label" preserve-scroll
                >
                </Link>
            </li>
        </ul>
    </div>
</template>
