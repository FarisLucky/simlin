<script>
import { layoutMethods, layoutComputed } from "@/state/helpers";
import { SimpleBar } from "simplebar-vue3";

/**
 * Right sidebar component
 */
export default {
    components: {
        SimpleBar,
    },
    data() {
        return {
            config: {
                handler: this.handleRightBarClick,
                middleware: this.middleware,
                events: ["click"],
            },
        };
    },
    methods: {
        ...layoutMethods,
        hide() {
            this.$parent.toggleRightSidebar();
        },
        handleRightBarClick() {
            this.$parent.hideRightSidebar();
        },
        middleware(event) {
            if (event.target.classList)
                return !event.target.classList.contains("toggle-right");
        },
    },
    computed: {
        ...layoutComputed,
        layout: {
            get() {
                return this.$store
                    ? this.$store.state.layout.layoutType
                    : {} || {};
            },
            set(layout) {
                this.changeLayoutType({
                    layoutType: layout,
                });
            },
        },
        width: {
            get() {
                return this.$store
                    ? this.$store.state.layout.layoutWidth
                    : {} || {};
            },
            set(width) {
                this.changeLayoutWidth({
                    layoutWidth: width,
                });

                if (width == "boxed") {
                    this.changeLeftSidebarType({
                        leftSidebarType: "icon",
                    });
                } else if (width == "fluid" || width == "scrollable") {
                    this.changeLeftSidebarType({
                        leftSidebarType: "dark",
                    });
                }
            },
        },
        topbar: {
            get() {
                return this.$store ? this.$store.state.layout.topbar : {} || {};
            },
            set(topbar) {
                this.changeTopbar({
                    topbar: topbar,
                });
            },
        },
        sidebarType: {
            get() {
                return this.$store
                    ? this.$store.state.layout.leftSidebarType
                    : {} || {};
            },
            set(type) {
                return this.changeLeftSidebarType({
                    leftSidebarType: type,
                });
            },
        },
        loader: {
            get() {
                return this.$store ? this.$store.state.layout.loader : {} || {};
            },
            set(value) {
                return this.changeLoaderValue({
                    loader: value,
                });
            },
        },
    },
};
</script>

<template>
    <div>
        <div v-click-outside="config" class="right-bar">
            <SimpleBar class="h-100">
                <div class="rightbar-title px-3 py-4 d-flex">
                    <h5 class="m-0">Settings</h5>
                    <a
                        href="javascript:void(0);"
                        class="right-bar-toggle ms-auto"
                    >
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>
                <hr class="mt-0" />
                <div class="p-3">
                    <h6 class="mb-0">Layout</h6>
                    <hr class="mt-1" />

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="layout"
                            id="layout-radio1"
                            value="vertical"
                            v-model="layout"
                        />
                        <label class="form-check-label" for="layout-radio1"
                            >Vertical</label
                        >
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="layout"
                            id="layout-radio2"
                            value="horizontal"
                            v-model="layout"
                        />
                        <label class="form-check-label" for="layout-radio2"
                            >Horizontal</label
                        >
                    </div>

                    <!-- Width -->
                    <h6 class="mt-3">Width</h6>
                    <hr class="mt-1" />

                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="widthradio"
                            id="width-radio1"
                            value="fluid"
                            v-model="width"
                        />
                        <label class="form-check-label" for="width-radio1">
                            Fluid
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="widthradio"
                            id="width-radio2"
                            value="boxed"
                            v-model="width"
                        />
                        <label class="form-check-label" for="width-radio2">
                            Boxed
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="widthradio"
                            id="width-radio3"
                            value="scrollable"
                            v-model="width"
                        />
                        <label class="form-check-label" for="width-radio3">
                            Scrollable
                        </label>
                    </div>

                    <!-- Sidebar -->
                    <div v-if="layout === 'vertical'">
                        <h6 class="mt-3">Sidebar</h6>
                        <hr class="mt-1" />

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="sidebar-radio"
                                id="sidebar-dark"
                                value="dark"
                                v-model="sidebarType"
                            />
                            <label class="form-check-label" for="sidebar-dark">
                                Dark
                            </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="sidebar-radio"
                                id="sidebar-light"
                                value="light"
                                v-model="sidebarType"
                            />
                            <label class="form-check-label" for="sidebar-light">
                                Light
                            </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="sidebar-radio"
                                id="sidebar-compact"
                                value="compact"
                                v-model="sidebarType"
                            />
                            <label
                                class="form-check-label"
                                for="sidebar-compact"
                            >
                                Compact
                            </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="sidebar-radio"
                                id="sidebar-icon"
                                value="icon"
                                v-model="sidebarType"
                            />
                            <label class="form-check-label" for="sidebar-icon">
                                Icon
                            </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="sidebar-radio"
                                id="sidebar-colored"
                                value="colored"
                                v-model="sidebarType"
                            />
                            <label
                                class="form-check-label"
                                for="sidebar-colored"
                            >
                                Colored
                            </label>
                        </div>
                    </div>

                    <!-- Topbar -->
                    <div v-if="layout === 'horizontal'">
                        <h6 class="mt-3">Topbar</h6>
                        <hr class="mt-1" />
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="sidebar-radio"
                                id="topbar-dark"
                                value="dark"
                                v-model="topbar"
                            />
                            <label class="form-check-label" for="topbar-dark">
                                Dark
                            </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="topbar-radio"
                                id="topbar-light"
                                value="light"
                                v-model="topbar"
                            />
                            <label class="form-check-label" for="topbar-light">
                                Light
                            </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="topbar-radio"
                                id="topbar-colored"
                                value="colored"
                                v-model="topbar"
                            />
                            <label
                                class="form-check-label"
                                for="topbar-colored"
                            >
                                Colored
                            </label>
                        </div>
                    </div>

                    <!-- Preloader -->
                    <h6 class="mt-3">Preloader</h6>
                    <hr class="mt-1" />

                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="is-loader"
                            v-model="loader"
                        />
                        <label class="form-check-label" for="is-loader"
                            >Preloader</label
                        >
                    </div>
                </div>
            </SimpleBar>
        </div>
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
    </div>
</template>