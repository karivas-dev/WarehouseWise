<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import { Head } from '@inertiajs/vue3';
import {ref} from "vue";

const props = defineProps(['user']);
const loggedUser = ref(usePage().props.auth.user);

const destroy = () => {
    router.delete(route('users.destroy', { id: props.product.id }));
}
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">User details</h2>
        </template>

        <Card>
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center">
                    <!--<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" class="rounded-full w-20">-->
                    <img src="/img/human.png" class="rounded w-24">
                    <div class="ml-8">
                        <span class="inline text-5xl h-fit">{{ user.name }}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Role:</span> {{ user.role.type }}
                            <br>
                            <span class="font-semibold">Email:</span> {{ user.email }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <PrimaryButton :href="route('users.edit', { id: user.id })" class="w-full" v-if="(user.warehouse_id === loggedUser.warehouse?.id || loggedUser.role.type === 'director')">
                        Edit user
                    </PrimaryButton>
                    <PrimaryButton color="red" @click="destroy" class="mt-3 w-full" v-if="(user.warehouse_id === loggedUser.warehouse?.id || loggedUser.role.type === 'director')">
                        Delete user
                    </PrimaryButton>
                </div>
            </div>
        </Card>

        <Card>
            <Link as="button" :href="route('warehouses.show', { id: user.warehouse.id })" class="inline text-4xl hover:underline">Warehouse: {{ user.warehouse.name }}</Link>
            <div class="mt-4 text-lg">
                <span class="font-semibold">Phone:</span> {{ user.warehouse.phone }}
                <br>
                <span class="font-semibold">Email:</span> {{ user.warehouse.email }}
                <br>
                <span class="font-semibold">Location:</span> {{ user.warehouse.location }}
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
