<template>
    <BCard no-body>
        <BCardBody>
            <BTabs
                justified
                nav-class="nav-tabs-custom"
                content-class="p-2 text-muted border rounded"
                v-model="routeReady"
            >
                <BTab
                    v-for="(tab, idx) in tabs"
                    :key="idx"
                    :id="'tab_' + tab.id"
                    @click.prevent="selectTab(tab)"
                >
                    <template v-slot:title>
                        <span class="d-inline-block d-sm-none">
                            <i :class="tab.icon" class="me-1"></i>
                            {{ tab.title }}
                        </span>
                        <strong class="d-none d-sm-inline-block">
                            {{ tab.title }}
                        </strong>
                    </template>
                    <div class="pt-1">
                        <component :is="routeName" :key="tab.routeName" />
                    </div>
                </BTab>
            </BTabs>
        </BCardBody>
    </BCard>
</template>
<script>
import AlatList from "./AlatList.vue";
import KategoriList from "./KategoriList.vue";

export default {
    components: {
        AlatList,
        KategoriList,
    },
    data() {
        return {
            tabs: [
                {
                    id: 0,
                    title: "Alat",
                    component: "AlatList",
                    routeName: "AlatList",
                    icon: "far fa-file-alt",
                },
                {
                    id: 1,
                    title: "Kategori",
                    component: "KategoriList",
                    routeName: "KategoriList",
                    icon: "far fa-file-alt",
                },
            ],
            routeReady: 0,
            routeName: "AlatList",
        };
    },
    methods: {
        selectTab(tab) {
            this.routeName = tab.component;
        },
    },
};
</script>
<style lang="">
</style>