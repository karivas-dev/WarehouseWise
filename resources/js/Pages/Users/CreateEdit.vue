<script setup>
import {useForm, usePage} from "@inertiajs/vue3";

const props = defineProps(['user', 'warehouses', 'roles']);

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    password: props.user?.password,
    role_id: props.user?.role_id ?? props.roles[0].id,
    warehouse_id: props.user?.warehouse_id ?? props.warehouses[0].id,
});

const store = () => {
    form.post(route('users.store'), {
        onSuccess: () => {
            form.reset();
            alert(usePage().props.flash.message);
        }
    })
}

const update = ()=> {
    form.patch(route('users.update', {id: props.user.id}), {
        onSuccess: () => {
            alert(usePage().props.flash.message);
        }
    });
};
</script>

<template>
    <form @submit.prevent="(user === undefined ? store() : update())">
        <input type="text" name="name" v-model="form.name">
        <input type="text" name="email" v-model="form.email">
        <input :type="(user === undefined ? 'text' : 'password')" name="password" v-model="form.password">
        <select name="role_id" v-model="form.role_id">
            <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">{{ warehouse.name }}</option>
        </select>
        <select name="warehouse_id" v-model="form.warehouse_id">
            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.type }}</option>
        </select>
        <input type="submit">
    </form>
</template>
