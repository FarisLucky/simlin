<script>
import Layout from "@/layouts/main";
import appConfig from "@/app.config";
import PageHeader from "@/components/page-header";

/**
 * Projects-list component
 */
export default {
    page: {
        title: "MLinen",
        meta: [{ name: "description", content: appConfig.description }],
    },
    components: {
        Layout,
        PageHeader,
    },
    data() {
        return {
            title: "Master Alat RS",
            items: [
                {
                    text: "master",
                    href: "/",
                },
                {
                    text: "unit",
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
        <router-view v-slot="{ Component, route }">
            <transition name="slide-fade" mode="out-in" appear>
                <component :is="Component" :key="route.path" />
            </transition>
        </router-view>
    </Layout>
</template>
