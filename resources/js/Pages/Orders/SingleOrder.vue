<script setup>
import Card from "@/Components/Card.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Link, router} from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";

const props = defineProps({
    order: {
        type: Object,
    },
    current: {
        type: Boolean,
        default: false
    }
});

const modifyingOrder = ref(false);
const removingFromOrder = ref(false);
const modalItem = ref(null);
const modalMinQuantity = ref(-1);
const modalMaxQuantity = ref(1);
const quantity = ref(0);
const showModal = (item) => {
    modalItem.value = item.product;
    modalMinQuantity.value = -(item.quantity);
    modalMaxQuantity.value = item.product.quantity;
    quantity.value = 0;
}

const showModifyModal = (item) => {
    showModal(item);
    modifyingOrder.value = true;
}

const showRemoveModal = (item) => {
    showModal(item);
    removingFromOrder.value = true;
}

const modifyOrder = () => {
    if (quantity.value == 0) {
        return;
    }

    router.post(route(quantity.value > 0 ? 'products.order.add' : 'products.order.remove',
        { product: modalItem.value.id, orderID: props.order.id}), {
        quantity: quantity.value > 0 ? quantity.value : -quantity.value,
    });
    modifyingOrder.value = false;
    removingFromOrder.value = false;
}

const removeFromOrder = () => {
    quantity.value = modalMinQuantity.value - 1;
    modifyOrder();
}

const finishingOrder = ref(false);
const destroyingOrder = ref(false);

const finish = () => {
    router.post(route('orders.finish', { order: props.order?.id }), {}, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });

    destroyingOrder.value = false;
}

const destroy = () => {
    router.delete(route('orders.destroy', { order: props.order?.id }), {}, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
    destroyingOrder.value = false;
}

const cancelingOrder = ref(false);

const cancel = () => {
    router.delete(route('orders.cancel', { order: props.order?.id }), {}, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
    cancelingOrder.value = false;
}
</script>


<template>
    <Card>
        <div class="flex justify-between items-center">
            <div class="flex justify-start items-center">
                <!--<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" class="rounded-full w-20">-->
                <img src="/img/truck.png" class="rounded w-24">
                <div class="ml-8">
                    <span class="inline text-5xl h-fit">{{ current ? 'Current Order' : `Order #${order.id}` }}</span>
                    <br>
                    <Link class="italic hover:underline" :href="current ? '' : route('users.show', { user: order.user.id })" v-if="$page.props.auth.user.role.type != 'employee'">
                        {{ order.user.name }}
                    </Link>
                    <span class="italic" v-else-if="!current">{{ order.user.name }}</span>
                    <div class="mt-2 text-lg">
                        <span class="block text-base">
                        <span class="font-semibold">Items quantity:</span> {{ order.item_count }}
                        <br>
                        <span class="font-semibold">Sub-Total:</span> $ {{ order.sub_total }}
                        <br>
                        <span class="font-semibold">Shipping Cost:</span> $ {{ order.shipping_cost }}
                        <br>
                            <span class="font-semibold">Taxes:</span> $ {{ order.taxes }}
                        </span>
                        <span class="block mt-2">
                            <span class="font-semibold">Total:</span> $ {{ order.total }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-center items-center">
                <PrimaryButton @click="finishingOrder = true" class="w-full" v-if="current && order.item_count > 0">
                    Finish Order
                </PrimaryButton>
                <br>
                <PrimaryButton @click="destroyingOrder = true" color="red" class="w-full" v-if="current && order.item_count > 0">
                    Delete Order
                </PrimaryButton>
                <PrimaryButton @click="cancelingOrder = true" color="red" class="w-full" v-if="$page.props.auth.user.role.type != 'employee' && order.canceled == false">
                    Cancel Order
                </PrimaryButton>
                <span class="text-red-400 text-3xl text-left" v-if="order.canceled">
                    CANCELED
                </span>
            </div>
            <Modal :show="finishingOrder" @close="finishingOrder = false" @true-response="finish" v-if="current">
                Do you want to finish this order?
                <br>
                <span class="text-sm">It will not be possible to modify later</span>
            </Modal>
            <Modal :show="destroyingOrder" @close="destroyingOrder = false" @true-response="destroy" v-if="current">
                Do you want to delete this order?
                <br>
                <span class="text-base">Product quantities will be returned to stock</span>
                <br><br>
                <span class="text-sm text-red-400">This action will not be reversible</span>
            </Modal>
            <Modal :show="cancelingOrder" @close="cancelingOrder = false" @trueResponse="cancel" v-if="order.canceled == false && $page.props.auth.user.role.type != 'employee'">
                Are you sure you want to cancel this order?
                <br>
                <span class="text-base">Products quantity will return to their stock</span>
                <br>
                <span class="text-sm text-red-400">This action cannot be undone</span>
            </Modal>
        </div>
        <div>
            <div class="mt-5 relative overflow-x-auto shadow-md sm:rounded-lg">
                <Table>
                    <template #heading>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Unit Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Item Total
                        </th>
                        <th scope="col" class="px-6 py-3" v-if="($page.props.auth.user.role.type !== 'employee' || current) && order.canceled == false">
                            <span class="sr-only">Action</span>
                        </th>
                        <th scope="col" class="px-6 py-3" v-if="current">
                            <span class="sr-only">Action</span>
                        </th>
                    </template>

                    <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                        v-for="item in order.line_items" :key="item.id">
                        <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                            <Link :href="route('products.show', { id: item.product.id })" class="hover:underline" v-if="item.product.deleted_at === null">
                                {{ item.product.name }}
                            </Link>
                            <span v-else>
                                {{ item.product.name }}
                            </span>
                        </th>
                        <td class="px-6 py-4">
                            {{ item.quantity}}
                        </td>
                        <td class="px-6 py-4">
                            $ {{ item.unit_price }}
                        </td>
                        <td class="px-6 py-4">
                            $ {{ item.item_total }}
                        </td>
                        <td class="py-4" v-if="($page.props.auth.user.role.type !== 'employee' || current) && order.canceled == false">
                            <span @click="showModifyModal(item)"
                                  class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline">
                                Modify
                            </span>
                        </td>
                        <td class="pr-4 py-4 text-right" v-if="current">
                            <span @click="showRemoveModal(item)"
                                  class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline">
                                Remove
                            </span>
                        </td>
                    </tr>
                </Table>
                <Modal :show="modifyingOrder" @close="modifyingOrder = false" @trueResponse="modifyOrder">
                    <span class="text-2xl text-white">
                        {{ modalItem?.name }}
                    </span>
                    <br><br>
                    Max quantity (to be added): {{ modalMaxQuantity }}
                    <br>
                    Max quantity (to be removed): {{ modalMinQuantity }}
                    <br><br>
                    To add more quantity of the product to the order, please use a positive number, instead if you want to remove use a negative one.
                    <br>
                    <TextInput type="number" v-model="quantity" :min="modalMinQuantity" :max="modalMaxQuantity" class="mt-5 mb-2"/>
                </Modal>
                <Modal :show="removingFromOrder" @close="removingFromOrder = false" @trueResponse="removeFromOrder" v-if="current">
                    <span class="text-2xl text-white">
                        {{ modalItem?.name }}
                    </span>
                    <br>
                    Do you want to remove this product from the order?
                </Modal>
            </div>
        </div>
    </Card>
</template>
