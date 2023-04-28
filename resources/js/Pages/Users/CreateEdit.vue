<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref} from "vue";

const props = defineProps(['user', 'warehouses', 'roles']);
const loggedUser = ref(usePage().props.auth.user);

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    password: props.user?.password,
    role_id: props.user?.role_id ?? props.roles[0].id,
    warehouse_id: props.user?.warehouse_id ?? props.warehouses[0].id,
});

if (loggedUser.value.role.type === 'administrator') {
    form.warehouse_id = loggedUser.value.warehouse.id;
}

const store = () => {
    form.post(route('users.store'))
}

const update = ()=> {
    form.patch(route('users.update', {id: props.user.id}));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
                {{ user !== undefined ? 'Edit user' : 'Add user' }}
            </h2>
        </template>

        <Card>
            <form @submit.prevent="(user === undefined ? store() : update())">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus/>
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus/>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Password" />
                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" autofocus/>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4" v-if="loggedUser.role.type !== 'administrator'">
                    <InputLabel for="category" value="Warehouse" />

                    <select class="select" v-model="form.warehouse_id">
                        <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">{{ warehouse.name }}</option>
                    </select>
                </div>

                <div class="my-4">
                    <InputLabel for="category" value="Role" />

                    <select class="select capitalize" v-model="form.role_id">
                        <template v-for="role in roles" :key="role.id">
                            <option :value="role.id" class="capitalize" v-if="(loggedUser.role.type === 'director' || role.type !== 'director')">{{ role.type }}</option>
                        </template>
                    </select>
                </div>

                <div class="flex justify-end">
                    <PrimaryButton :back="true">
                        Return back
                    </PrimaryButton>

                    <PrimaryButton class="ml-4" :disabled="form.processing">
                        {{ user === undefined ? 'Add' : 'Update' }}
                    </PrimaryButton>
                </div>
            </form>
        </Card>
    </AuthenticatedLayout>
</template>
