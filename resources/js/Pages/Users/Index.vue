<script setup>
import Paginator from "@/Components/Paginator.vue";
import {ref, watch} from "vue";
import {throttle} from "lodash";
import {router} from "@inertiajs/vue3";

const props = defineProps(['users', 'links', 'filters']);
const search = ref(props.filters.search);

watch(search, throttle(function (value) {
    router.get(route('users.index'), { search: value }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}));
</script>

<template>
    <input type="text" v-model="search"><br><br>

    {{ users }}

    <Paginator :links="links"/>
</template>
