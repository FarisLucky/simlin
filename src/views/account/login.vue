<template>
    <Layout>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-soft bg-primary">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary px-4 pt-3 pb-2">
                                    <h5 class="text-primary">
                                        Selamat Datang !
                                    </h5>
                                    <p>Silahkan masukkan kredential.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img alt class="img-fluid" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div
                            class="d-flex justify-contents-between bg-light p-2 mx-2 rounded"
                        >
                            <div class="text-end">
                                <div
                                    class="profile-user-wid me-2"
                                    style="max-width: 3.6rem"
                                >
                                    <span class="avatar-title">
                                        <div class="">
                                            <img
                                                src="@/assets/images/icon.png"
                                                alt
                                                width="40"
                                            />
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <h4 class="m-0 my-auto">
                                Sistem Informasi Linen & CSSD
                            </h4>
                        </div>
                        <div v-if="err?.message" class="alert alert-danger">
                            {{ err?.message }}
                        </div>

                        <form class="p-2" @submit.prevent="tryToLogIn">
                            <b-form-group
                                class="mb-1"
                                id="input-group-1"
                                label="Username"
                                label-for="input-1"
                            >
                                <b-form-input
                                    id="input-1"
                                    v-model="form.username"
                                    type="text"
                                    placeholder="Enter Username"
                                    required
                                ></b-form-input>
                            </b-form-group>

                            <b-form-group
                                class="mb-1"
                                id="input-group-2"
                                label="Password"
                                label-for="input-2"
                            >
                                <b-form-input
                                    id="input-2"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Enter password"
                                    required
                                ></b-form-input>
                            </b-form-group>
                            <div class="mt-3 d-grid">
                                <b-button
                                    type="submit"
                                    variant="primary"
                                    class="btn-block"
                                    :disabled="processing"
                                >
                                    {{ processing ? "Tunggu Dulu" : "Masuk" }}
                                </b-button>
                            </div>
                        </form>
                    </div>
                    <!-- end card-body -->
                </div>

                <!-- end row -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </Layout>
</template>
<script>
import Layout from "@/layouts/auth";
import { authMethods, notificationMethods } from "@/state/helpers";
import { mapState } from "vuex";

import appConfig from "@/app.config";
import Cookies from "js-cookie";
import { authService } from "@/services/AuthService";

/**
 * Login component
 */
export default {
    page: {
        title: "Login",
        meta: [
            {
                name: "description",
                content: appConfig.description,
            },
        ],
    },
    components: {
        Layout,
    },
    data() {
        return {
            form: {
                username: "",
                password: "",
            },
            err: {},
            submitted: false,
            tryingToLogIn: false,
            processing: false,
        };
    },
    computed: {
        ...mapState("authfack", ["status"]),
        notification() {
            return this.$store ? this.$store.state.notification : null;
        },
    },
    methods: {
        ...authMethods,
        ...notificationMethods,
        // Try to log the user in with the username
        // and password they provided.
        async tryToLogIn() {
            this.processing = true;
            this.submitted = true;
            // stop here if form is invalid
            this.$touch;

            const [err, resp] = await authService.store(this.form);
            if (err) {
                this.isLoading = false;
                this.processing = false;
                if (err.response.status == 500) {
                    this.err.message = err.response.data;
                }

                return;
            }
            this.login(resp);
            Cookies.set(
                "e-linen",
                JSON.stringify({
                    data: resp.user,
                    token: resp.token,
                }),
                {
                    expires: 90,
                    // sameSite: "none",
                    // secure: true,
                }
            );
            // localStorage.setItem(
            //     "user",
            //     JSON.stringify({
            //         data: resp.user,
            //         token: resp.token,
            //     })
            // );
            this.processing = false;

            if (
                this.$route.params.callback != "" &&
                this.$route.params.callback != undefined
            ) {
                // this.$router.back();
                await this.$router.replace({
                    path: this.$route.params.callback,
                });
                window.location.reload();
                return;
            }

            this.$router.go("/");
            // this.$router.push({ name: "Dashboard" });
        },
    },
    mounted() {},
};
</script>
