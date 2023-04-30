<script setup>
import Paginator from "@/Components/Paginator.vue";
import {ref, watch} from "vue";
import {throttle} from "lodash";
import {Link, router, usePage} from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputSearch from "@/Components/InputSearch.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps(['users', 'links', 'filters']);
const search = ref(props.filters.search);
const all = ref(props.filters.all === "1");
const deleted = ref(props.filters.deleted === "1");
const loggedUser = ref(usePage().props.auth.user);

watch(search, throttle(function (value) {
    router.get(route('users.index'), { search: value, all: all.value }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}));
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="w-100 flex justify-between">
                <h2 class="font-semibold text-xl leading-tight">Users table</h2>
                <PrimaryButton :href="route('users.index', { search: search, all: all, deleted: !deleted })" color="neutral"
                               v-if="loggedUser.role.type === 'director' || loggedUser.role.type === 'administrator'">
                    {{ !deleted ? 'Show disabled users' : 'Show normal users' }}
                </PrimaryButton>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 bg-grayC-400">
                <div class="w-100 my-8 flex justify-between">
                    <PrimaryButton :href="route('users.index', { search: search, all: !all })" v-if="loggedUser.role.type === 'administrator'">
                        {{ all ? "Show local users" : "Show all users" }}
                    </PrimaryButton>

                    <PrimaryButton :href="route('users.create')">
                        Create new user
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
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Warehouse
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="user in users" :key="user.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('users.show', { id: user.id })" class="hover:underline">
                                    {{ user.name }}
                                </Link>
                            </th>
                            <td class="px-6 py-4">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                {{ user.role.type }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                <span v-if="user.warehouse != null">
                                    {{ user.warehouse.name }}
                                </span>
                                <span v-else>---</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link :href="route('users.edit', { id: user.id })" v-if="(user.warehouse_id === loggedUser.warehouse?.id || loggedUser.role.type === 'director')"
                                      class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline">
                                    Edit
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
