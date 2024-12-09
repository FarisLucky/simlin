<script>
import { layoutComputed } from "@/state/helpers";
import Vertical from "./vertical";
import Horizontal from "./horizontal";
import Notification from "@/components/notification.vue";
import VSpinner from "@/components/v-spinner.vue";

export default {
    components: {
        Vertical,
        Horizontal,
        Notification,
        VSpinner,
    },
    data() {
        return {};
    },
    computed: {
        ...layoutComputed,
    },
    methods: {},
    mounted() {
        // document.querySelector("html").setAttribute('dir', 'rtl');
    },
};
</script>

<template>
    <div>
        <Notification />
        <v-spinner />
        <Vertical v-if="layoutType === 'vertical'" :layout="layoutType">
            <slot />
        </Vertical>

        <Horizontal v-if="layoutType === 'horizontal'" :layout="layoutType">
            <slot />
        </Horizontal>
    </div>
</template>
