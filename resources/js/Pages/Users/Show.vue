<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps(['user']);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">User</h2>
        </template>

        <Card>
            <div class="my-8">
                <div class="flex justify-between items-center">
                    <div class="flex justify-start items-center">
                        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" class="rounded-full w-20">
                        <div class="ml-7">
                            <span class="inline text-5xl h-fit">{{ user.name }}</span>
                            <div class="mt-2 text-lg">
                                Email: {{ user.email }}
                                <br>
                                Role: {{ user.role.type }}
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <PrimaryButton :href="route('users.edit', { id: user.id })" class="w-full">
                            Edit user
                        </PrimaryButton>
                        <PrimaryButton :href="route('users.destroy', { id: user.id })" class="mt-3 w-full">
                            Delete user
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Card>

        <Card>
            <div class="my-8">
                <Link as="button" :href="route('users.show', { id: user.warehouse.id })" class="inline text-5xl hover:underline">Warehouse: {{ user.warehouse.name }}</Link>
                <div class="mt-4 text-lg">
                    Phone: {{ user.warehouse.phone }}
                    <br>
                    Email: {{ user.warehouse.email }}
                    <br>
                    Location: {{ user.warehouse.location }}
                </div>
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
