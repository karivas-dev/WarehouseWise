<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SingleOrder from "@/Pages/Orders/SingleOrder.vue";

const props = defineProps(['order']);
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Order</h2>
        </template>

        <SingleOrder :order="order"/>
    </AuthenticatedLayout>
</template>
