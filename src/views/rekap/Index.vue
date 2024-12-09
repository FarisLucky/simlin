<template>
    <Layout>
        <PageHeader title="Rekap LINEN & CSSD" pageTitle="Rekap" />
        <BCard no-body>
            <BCardBody>
                <p class="text-muted">
                    Rekap Linen & CSSD berdasarkan data sistem.
                </p>
                <div style="min-height: 50vh">
                    <BTabs>
                        <BTab
                            v-for="(tab, idx) in list"
                            :title="tab.title"
                            :active="params === tab.route"
                            :key="idx"
                            @click.prevent="selectTab(tab.route)"
                        >
                            <component :is="tab.component" />
                        </BTab>
                    </BTabs>
                </div>
            </BCardBody>
        </BCard>
    </Layout>
</template>
<script>
import Layout from "@/layouts/main";
import appConfig from "@/app.config";
import PageHeader from "@/components/page-header";
import { defineAsyncComponent } from "vue";
// import { SUPER_ADMIN, KASUB, KABID, DIREKTUR } from "@/helpers/utils";

export default {
    page: {
        title: "MLinen",
        meta: [{ name: "description", content: appConfig.description }],
    },
    components: {
        Layout,
        PageHeader,
        RekapLinen: defineAsyncComponent(() => import("./RekapLinen.vue")),
    },
    data() {
        return {
            title: "Rekap Linen & CSSD RS",
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
            list: [
                {
                    title: "Linen",
                    component: "RekapLinen",
                    route: "RekapLinen",
                    //   for: this.isKaBid || this.isKaSub || this.isDir || this.isSuperAdmin,
                },
            ],
        };
    },
    methods: {
        selectTab(params) {
            console.log(params);
        },
    },
};
</script>
