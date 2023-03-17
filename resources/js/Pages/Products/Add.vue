<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['categories']);
</script>

<template>
    <Head title="Product" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add product</h2>
        </template>

        <div>
            <InputLabel for="product_name" value="Product Name" />

            <TextInput id="product_name" ref=""
                maxlength="255"
                v-model="form.current_password"
                type="text"
                class="mt-1 block w-full" autocomplete="" required/>

            <InputError :message="form.errors.current_password" class="mt-2" />
        </div>

        <div>
            <textarea name="" id="" cols="30" rows="10" maxlength="5000">

            </textarea>
        </div>

        <div>
            <InputLabel for="product_name" value="Product Name" />

            <TextInput id="product_name" ref=""
                v-model="form.current_password"
                type="number"
                step="0.00"
                min="0.01"
                class="mt-1 block w-full" autocomplete="" required/>

            <InputError :message="form.errors.current_password" class="mt-2" />
        </div>

        <div>
            <InputLabel for="product_name" value="Quantity" />

            <TextInput id="product_name" ref=""
                v-model="form.current_password"
                type="number"
                step="0.00"
                min="1"
                class="mt-1 block w-full" autocomplete="" required/>

            <InputError :message="form.errors.current_password" class="mt-2" />
        </div>
        <div>
            <Label>Category</Label>
            <select name="" id="">
                <option v-for="category in categories"
                :value="category.id"
                :key="category.id">
                {{ category.name }}
            </option>
            </select>
        </div>
    </AuthenticatedLayout>
</template>
