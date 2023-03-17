<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputSelect from '@/Components/InputSelect.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps(['categories', 'product', 'selected_categories']);

const form = useForm({
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    unit_price: props.product?.unit_price ?? 0.00,
    categories: props.selected_categories ?? [],
    quantity: props.product?.quantity ?? 0,
});

const store = () => {
    form.post(route('products.store'), {
        onSuccess: () => {
            form.reset();
            alert(usePage().props.flash.message);
        }
    });
};

const update = () => {
    form.patch(route('products.update', { id: props.product.id }), {
        onSuccess: () => {
            alert(usePage().props.flash.message);
        }
    });
};

const destroy = () => {
    form.delete(route('products.destroy', { id: props.product.id }), {
        onSuccess: () => {
            alert(usePage().props.flash.message);
        }
    });
}

const remove = () => {
    form.delete(route('products.remove', { id: props.product.id }), {
        onSuccess: () => {
            alert(usePage().props.flash.message);
        }
    });
}

</script>

<template>
    <Head title="Product Edit" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Edit product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 rounded-lg bg-grayC-400 space-y-6 ">

                    <form @submit.prevent="(product == null ? store() : update())" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="name" value="Name" />

                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                            maxlength="255" autofocus/>

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="" value="Description" />

                            <TextInput v-model="form.description"
                            class="mt-1 block w-full"
                            maxlength="5000"/>
                        </div>
                        <div>
                            <InputLabel for="name" value="Unit Price" />

                            <TextInput id="unitPrice" type="number" class="mt-1 block w-full"
                            v-model="form.unit_price" required
                            maxlength="255" step="0.01" min="0.01"/>
                        </div>
                        <div>
                            <InputLabel for="name" value="Quantity" />

                            <TextInput id="unitPrice" type="number" class="mt-1 block w-full"
                            v-model="form.quantity" required
                            min="0"/>
                        </div>
                        <div>
                            <InputLabel for="category" value="Category" />

                            <InputSelect v-model="form.categories">
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </InputSelect>

                            <select class="select" v-model="form.categories" multiple>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <PrimaryButton>
                            Update
                        </PrimaryButton>
                    </form>
                    <PrimaryButton @click="destroy" v-if="product != null">Global Delete</PrimaryButton>
                    <PrimaryButton @click="remove" v-if="product != null">Delete From This Warehouse</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
