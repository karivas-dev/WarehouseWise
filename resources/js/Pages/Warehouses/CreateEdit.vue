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


const props = defineProps(['warehouse']);

const form = useForm({
    name: props.warehouse?.name ?? '',
    location: props.warehouse?.location ?? '',
    phone: props.warehouse?.phone ?? '',
    email: props.warehouse?.email ?? '',
});

watch(() => props.warehouse?.email, function () {
    form.email = props.warehouse?.email ?? 0;
})

const store = () => {
    form.post(route('warehouses.store'), {
        replace: true,
    });
};

const update = () => {
    form.patch(route('warehouses.update', { id: props.warehouse.id }), {
        replace: true,
    });
};
</script>

<template>
    <Head title="Warehouse" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
                {{ warehouse !== undefined ? 'Edit Warehouse' : 'Add Warehouse' }}
            </h2>
        </template>

        <Card>
            <form @submit.prevent="(warehouse == null ? store() : update())" class="space-y-6">
                <div>
                    <InputLabel for="name" value="Name" />

                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                    maxlength="255" autofocus/>

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div>
                    <InputLabel for="" value="Location" />

                    <TextInput v-model="form.location"
                    class="mt-1 block w-full"
                    maxlength="5000"/>

                    <InputError class="mt-2" :message="form.errors.location" />
                </div>

                <div>
                    <InputLabel for="phone" value="Phone" />

                    <TextInput id="phone" type="tel" class="mt-1 block w-full"
                    v-model="form.phone" required
                    maxlength="255" step="0"/>

                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div >
                    <InputLabel for="email" value="Email" />

                    <TextInput id="email" type="email" class="mt-1 block w-full"
                    v-model="form.email" required/>

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex justify-end">
                    <PrimaryButton :back="true">
                        Return back
                    </PrimaryButton>

                    <PrimaryButton class="ml-4" :disabled="form.processing">
                        {{ warehouse === undefined ? 'Add' : 'Update' }}
                    </PrimaryButton>
                </div>
            </form>
        </Card>

    </AuthenticatedLayout>
</template>
