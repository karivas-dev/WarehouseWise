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
                <Link :href="link.url ?? ''"
                        class="px-3 py-2 leading-tight border dark:bg-gray-800 dark:hover:bg-gray-700"
                        :class="{
                            'ml-0 rounded-l-lg': index == 0,
                            'rounded-r-lg': index == linksLength,
                            'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white': !link.active,
                            'text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 font-bold  dark:hover:text-white dark:text-white': link.active,
                        }"
                      v-html="link.label" preserve-scroll
                >
                </Link>
            </li>
        </ul>
    </div>
</template>
