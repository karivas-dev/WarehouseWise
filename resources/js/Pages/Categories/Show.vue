<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(['category']);

const destroy = () => {
    router.delete(route('categories.destroy', { id: props.category.id }));
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Category</h2>
        </template>

        <Card>
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center">
                    <img src="/img/letters.png" class="rounded-full w-20">
                    <div class="ml-7">
                        <span class="inline text-5xl h-fit">{{ category.name }}</span>
                        <div class="mt-2 text-lg">
                            {{ category.description }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <PrimaryButton :href="route('categories.edit', { id: category.id })" class="w-full">
                        Edit category
                    </PrimaryButton>
                    <PrimaryButton color="red" @click="destroy" method="destroy" class="mt-3 w-full">
                        Disable category
                    </PrimaryButton>
                </div>
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
