<script>
import Layout from "@/layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

/**
 * Projects-list component
 */
export default {
    page: {
        title: "Daftar",
        meta: [{ name: "description", content: appConfig.description }],
    },
    components: {
        Layout,
        PageHeader,
    },
    data() {
        return {
            title: "Daftar Permintaan CSSD RS",
            items: [
                {
                    text: "daftar",
                    href: "/",
                },
                {
                    text: "harian",
                    active: true,
                },
            ],
        };
    },
};
</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <router-view v-slot="{ Component }">
            <transition name="slide-fade" mode="out-in" appear>
                <component :is="Component" />
            </transition>
        </router-view>
        <!-- <transition name="slide-fade" mode="out-in" appear>
            <router-view></router-view>
        </transition> -->
    </Layout>
</template>
