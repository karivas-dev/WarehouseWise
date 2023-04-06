<script setup>
import {router, useForm} from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {watch} from "vue";
import Card from "@/Components/Card.vue";


const props = defineProps(['categories', 'product', 'selected_categories']);

const form = useForm({
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    unit_price: props.product?.unit_price ?? 0.00,
    categories: props.selected_categories ?? [],
    quantity: props.product?.quantity ?? 0,
});

watch(() => props.product?.quantity, function () {
    form.quantity = props.product?.quantity ?? 0;
})

const store = () => {
    form.post(route('products.store'), {
        replace: true,
    });
};

const update = () => {
    form.patch(route('products.update', { id: props.product.id }), {
        replace: true,
    });
};
</script>

<template>
    <Head title="Product Edit" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
                {{ product !== undefined ? 'Edit Product' : 'Add Product' }}
            </h2>
        </template>

        <Card>
            <form @submit.prevent="(product == null ? store() : update())" class="space-y-6">
                <div v-if="$page.props.auth.user.role.type === 'administrator'">
                    <InputLabel for="name" value="Name" />

                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                    maxlength="255" autofocus/>

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div v-if="$page.props.auth.user.role.type === 'administrator'">
                    <InputLabel for="" value="Description" />

                    <TextInput v-model="form.description"
                    class="mt-1 block w-full"
                    maxlength="5000"/>

                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div v-if="$page.props.auth.user.role.type === 'administrator'">
                    <InputLabel for="name" value="Unit Price" />

                    <TextInput id="unitPrice" type="number" class="mt-1 block w-full"
                    v-model="form.unit_price" required
                    maxlength="255" step="0.01" min="0.01"/>

                    <InputError class="mt-2" :message="form.errors.unit_price" />
                </div>
                <div>
                    <InputLabel for="name" value="Quantity" />

                    <TextInput id="unitPrice" type="number" class="mt-1 block w-full"
                    v-model="form.quantity" required
                    min="0"/>

                    <InputError class="mt-2" :message="form.errors.quantity" />
                </div>
                <div v-if="$page.props.auth.user.role.type === 'administrator'">
                    <InputLabel for="category" value="Category" />

                    <select class="select h-24" v-model="form.categories" multiple>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.categories" />
                </div>

                <div class="flex justify-end">
                    <PrimaryButton :back="true">
                        Return back
                    </PrimaryButton>

                    <PrimaryButton class="ml-4" :disabled="form.processing">
                        {{ product === undefined ? 'Add' : 'Update' }}
                    </PrimaryButton>
                </div>
            </form>
        </Card>

    </AuthenticatedLayout>
</template>
