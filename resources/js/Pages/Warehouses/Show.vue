<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import {Link, router} from "@inertiajs/vue3";
import { Head } from '@inertiajs/vue3';
import InputSearch from "@/Components/InputSearch.vue";
import Table from "@/Components/Table.vue";

const props = defineProps(['warehouse', 'staff']);

const destroy = () => {
    router.delete(route('warehouses.destroy', {id: props.warehouse.id}));
}
</script>

<template>
    <Head title="Warehouse" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Warehouse details</h2>
        </template>
        <Card>
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center">
                    <img src="/img/shop.png" class="rounded w-28">
                    <div class="ml-12">
                        <span class="inline text-5xl h-fit">{{ warehouse.name }}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Phone:</span>  {{ warehouse.phone }}
                            <br>
                            <span class="font-semibold">Email:</span> {{ warehouse.email }}
                            <br>
                            <span class="font-semibold">Location:</span> {{ warehouse.location }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-center items-center">
                    <PrimaryButton :href="route('warehouses.edit', { id: warehouse.id })" class="w-full">
                        Edit warehouse
                    </PrimaryButton>
                    <PrimaryButton type="button" color="red" @click="destroy" class="mt-3 w-full"
                                   v-if="$page.props.auth.user.role.type === 'administrator'">
                        Disable warehouse
                    </PrimaryButton>
                </div>
            </div>
        </Card>

        <div class="max-w-7xl mx-auto text-center my-4">
            <h2 class="text-3xl font-semibold">Warehouse staff</h2>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 py-7 bg-grayC-400">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <Table>
                        <template #heading>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Rol
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="user in staff" :key="staff.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('users.show', { id: user.id })" class="hover:underline">
                                    {{ user.name }}
                                </Link>
                            </th>
                            <td class="px-6 py-4 capitalize">
                                {{ user.role.type }}
                            </td>
                            <td class="px-6 py-4">
                                {{ user.email }}
                            </td>
                        </tr>
                    </Table>
                </div>

            </div>
        </div>



    </AuthenticatedLayout>
</template>
