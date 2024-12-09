<script>
import { authComputed, authMethods } from "@/state/helpers";

import i18n from "../i18n";
import { authService } from "@/services/AuthService";
import Cookies from "js-cookie";
// import Cookies from "js-cookie";

/**
 * Nav-bar Component
 */
export default {
    data() {
        return {
            lang: "en",
            lan: i18n.locale,
            text: null,
            flag: null,
            value: null,
            locales: ["fr", "en", "ar"],
        };
    },
    created() {
        console.log(this.$store.state?.auth?.token);
    },
    methods: {
        ...authMethods,
        toggleMenu() {
            this.$parent.toggleMenu();
        },
        toggleRightSidebar() {
            this.$parent.toggleRightSidebar();
        },
        initFullScreen() {
            document.body.classList.toggle("fullscreen-enable");
            if (
                !document.fullscreenElement &&
                /* alternative standard method */ !document.mozFullScreenElement &&
                !document.webkitFullscreenElement
            ) {
                // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(
                        Element.ALLOW_KEYBOARD_INPUT
                    );
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        },

        setLanguage(locale, country, flag) {
            this.lan = locale;
            this.text = country;
            this.flag = flag;
            document
                .getElementById("header-lang-img")
                .setAttribute("src", flag);
            this.$i18n.locale = locale;
        },

        async logoutUser() {
            // eslint-disable-next-line no-unused-vars
            // axios.get("http://127.0.0.1:8000/api/logout").then((res) => {
            //     this.$router.push({
            //         name: "default",
            //     });
            // });

            const [err] = await authService.logout();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.isLoading = false;

                return;
            }
            // localStorage.removeItem("user");
            Cookies.remove("e-linen");
            this.logout();
            window.location.reload();
        },
    },
    computed: {
        ...authComputed,
        isLogin() {
            return this.$store.state?.auth?.token;
        },
    },
};
</script>

<template>
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <router-link to="/" class="logo logo-dark">
                        <span class="logo-sm">
                            <img
                                src="@/assets/images/logo.png"
                                alt
                                height="22"
                            />
                        </span>
                        <span class="logo-lg">
                            <!-- <img
                                src="@/assets/images/logo-dark.png"
                                alt
                                height="17"
                            /> -->
                            <strong class="font-size-14">RSGS</strong>
                        </span>
                    </router-link>

                    <router-link to="/" class="logo logo-light">
                        <span class="logo-sm">
                            <img
                                src="@/assets/images/logo.png"
                                alt
                                height="22"
                            />
                        </span>
                        <span class="logo-lg">
                            <!-- <img
                                src="@/assets/images/logo-light.png"
                                alt
                                height="19"
                            /> -->
                            <strong class="font-size-16 position-relative">
                                SIM Linen
                            </strong>
                        </span>
                    </router-link>
                </div>

                <button
                    id="vertical-menu-btn"
                    type="button"
                    class="btn btn-sm px-3 font-size-16 header-item"
                    @click="toggleMenu"
                >
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div class="d-flex">
                <b-dropdown
                    class="d-inline-block d-lg-none ms-2"
                    variant="black"
                    menu-class="dropdown-menu-lg p-0"
                    toggle-class="header-item noti-icon"
                    right
                >
                    <template v-slot:button-content>
                        <i class="mdi mdi-magnify"></i>
                    </template>

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search ..."
                                    aria-label="Recipient's username"
                                />
                                <div class="input-group-append">
                                    <button
                                        class="btn btn-primary"
                                        type="submit"
                                    >
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </b-dropdown>

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button
                        type="button"
                        class="btn header-item noti-icon"
                        @click="initFullScreen"
                    >
                        <i class="bx bx-fullscreen"></i>
                    </button>
                </div>

                <b-dropdown
                    v-if="isLogin"
                    right
                    variant="black"
                    toggle-class="header-item"
                    menu-class="dropdown-menu-end"
                >
                    <template v-slot:button-content>
                        <img
                            class="rounded-circle header-profile-user"
                            src="@/assets/images/user.png"
                            alt="Header Avatar"
                        />
                        <span class="d-none d-xl-inline-block ms-1">
                            {{ $store.state?.auth?.data?.name }}
                        </span>
                        <i
                            class="mdi mdi-chevron-down d-none d-xl-inline-block"
                        ></i>
                    </template>
                    <a
                        href="javascript(0)"
                        @click.prevent="logoutUser"
                        class="dropdown-item text-danger"
                    >
                        <i
                            class="bx bx-power-off font-size-16 align-middle me-1 text-danger"
                        ></i>
                        {{ $t("navbar.dropdown.henry.list.logout") }}
                    </a>
                </b-dropdown>

                <div v-else class="dropdown d-lg-inline-block ms-1 my-auto">
                    <router-link
                        :to="{
                            name: 'Login',
                            params: {
                                callback: $route.path,
                            },
                        }"
                        class="btn btn-primary"
                        >Login</router-link
                    >
                </div>

                <!-- <div class="dropdown d-inline-block">
                    <button
                        type="button"
                        class="btn header-item noti-icon right-bar-toggle toggle-right"
                        @click="toggleRightSidebar"
                    >
                        <i class="bx bx-cog bx-spin toggle-right"></i>
                    </button>
                </div> -->
            </div>
        </div>
    </header>
</template>
