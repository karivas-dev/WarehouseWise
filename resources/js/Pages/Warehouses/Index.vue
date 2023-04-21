<script setup>
import Paginator from "@/Components/Paginator.vue";
import Card from "@/Components/Card.vue";
import InputSearch from "@/Components/InputSearch.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Head, usePage} from '@inertiajs/vue3';
import {Link, router} from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref, watch} from "vue";
import {throttle} from "lodash";

const props = defineProps(['warehouses', 'links', 'filters']);
const search = ref(props.filters.search);

const userWarehouse = ref(usePage().props.auth.user.warehouse != null ? props.warehouses.find(warehouse => warehouse.id === usePage().props.auth.user.warehouse.id) : null);
const deleted = ref(props.filters.deleted === "1");
const user = ref(usePage().props.auth.user);

watch(search, throttle(function (value) {
    router.get(route('warehouses.index'), {search: value, deleted: deleted.value}, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}, 1000))
</script>

<template>
    <Head title="Warehouses"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="w-100 flex justify-between">
                <h2 class="font-semibold text-xl leading-tight">Warehouses table</h2>
                <PrimaryButton :href="route('warehouses.index', { search: search, deleted: !deleted })" color="neutral"
                               v-if="user.role.type === 'director' || user.role.type === 'administrator'">
                    {{ !deleted ? 'Show disabled warehouses' : 'Show normal warehouses' }}
                </PrimaryButton>
            </div>
        </template>

        <Card v-if="user.role.type !== 'director' && userWarehouse != null">
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center">
                    <img src="/img/shop.png" class="rounded w-28">
                    <div class="ml-12">

                        <span class="inline text-5xl h-fit">
                            <Link :href="route('warehouses.show', { warehouse: userWarehouse.id })" class="hover:underline">
                                {{ userWarehouse.name }}
                            </Link>
                        </span>

                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Phone:</span> {{ userWarehouse.phone }}
                            <br>
                            <span class="font-semibold">Email:</span> {{ userWarehouse.email }}
                            <br>
                            <span class="font-semibold">Location:</span> {{ userWarehouse.location }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-center items-center">
                    <PrimaryButton :href="route('warehouses.edit', { warehouse: userWarehouse.id })" class="w-full" v-if="user.role.type === 'administrator' || user.role.type === 'director'">
                        Edit warehouse
                    </PrimaryButton>
                </div>
            </div>
        </Card>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 bg-grayC-400">
                <div class="w-100 my-8 flex justify-between">

                    <div v-if="user.role.type !== 'director'">

                    </div>

                    <PrimaryButton :href="route('warehouses.create')" v-if="user.role.type === 'director'">
                        Create new warehouse
                    </PrimaryButton>

                    <form class="flex items-center">
                        <InputSearch v-model="search"/>
                    </form>

                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <Table>
                        <template #heading>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Phone number
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3" v-if="user.role.type === 'director'">
                                <span class="sr-only">Edit</span>
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="warehouse in warehouses" :key="warehouse.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('warehouses.show', { id: warehouse.id })" class="hover:underline" v-if="warehouse.deleted_at === null">
                                    {{ warehouse.name }}
                                </Link>
                                <span v-else>
                                    {{ warehouse.name }}
                                </span>
                            </th>
                            <td class="px-6 py-4">
                                {{ warehouse.location }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                {{ warehouse.phone }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                {{ warehouse.email }}
                            </td>

                            <td class="px-6 py-4 text-right" v-if="user.role.type === 'director' && !deleted">
                                <Link :href="route('warehouses.edit', { warehouse: warehouse.id })"
                                      class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline"
                                as="button">
                                    Edit
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-right" v-if="user.role.type === 'director' && warehouse.deleted_at !== null">
                                <Link :href="route('warehouses.restore', { id: warehouse.id })"
                                      class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline"
                                      :method="'put'" as="button">
                                    Restore
                                </Link>
                            </td>
                        </tr>
                    </Table>
                </div>

                <Paginator :links="links"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
