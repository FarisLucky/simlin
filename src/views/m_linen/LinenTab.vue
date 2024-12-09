<template>
    <BCard no-body class="p-2">
        <BTabs
            justified
            nav-class="nav-tabs-custom"
            content-class="p-2 text-muted border rounded"
            v-model="routeReady"
        >
            <BTab
                v-for="(tab, idx) in tabs"
                :key="idx"
                no-body
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
    </BCard>
</template>
<script>
import LinenList from "./LinenList.vue";
import LinenUnitList from "./LinenUnitList.vue";

export default {
    components: {
        LinenList,
        LinenUnitList,
    },
    data() {
        return {
            tabs: [
                {
                    id: 0,
                    title: "Linen Unit",
                    component: "LinenUnitList",
                    routeName: "LinenUnitList",
                    icon: "fas fa-newspaper",
                },
                {
                    id: 1,
                    title: "Linen",
                    component: "LinenList",
                    routeName: "LinenList",
                    icon: "far fa-file-alt",
                },
            ],
            routeReady: 0,
            routeName: "LinenUnitList",
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