<script>
import Layout from "@/layouts/main";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

/**
 * Projects-list component
 */
export default {
    page: {
        title: "MUnit",
        meta: [{ name: "description", content: appConfig.description }],
    },
    components: {
        Layout,
        PageHeader,
    },
    data() {
        return {
            title: "Master Unit RS",
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
        <router-view v-slot="{ Component }">
            <transition name="slide-fade" mode="out-in" appear>
                <component :is="Component" />
            </transition>
        </router-view>
    </Layout>
</template>
