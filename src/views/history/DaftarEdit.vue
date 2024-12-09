<template>
    <BCard no-body class="p-1">
        <BCardBody class="border-bottom border-dashed border-2 py-2 px-3">
            <div class="p-1">
                <form @submit.prevent="onSubmit">
                    <BRow class="g-2 align-items-end">
                        <BCol sm="12">
                            <div
                                class="bg-light bg-opacity-75 border-start border-primary border-3 d-inline-block"
                            >
                                <h5 class="fs-22 p-1 m-1">
                                    <strong
                                        class="rounded"
                                        :class="{
                                            'text-info':
                                                form.status ===
                                                STATUS_DAFTAR.NOTA,
                                            'text-primary':
                                                form.status ===
                                                STATUS_DAFTAR.PENGAJUAN,
                                            'text-primary':
                                                form.status ===
                                                STATUS_DAFTAR.TERIMA,
                                            'text-primary':
                                                form.status ===
                                                STATUS_DAFTAR.DIKEMBALIKAN,
                                        }"
                                    >
                                        {{ form.status_cast }}
                                    </strong>
                                </h5>
                            </div>
                        </BCol>
                        <BCol sm="12">
                            <div
                                class="d-flex justify-content-between align-items-center pb-1"
                            >
                                <div>
                                    <h5
                                        class="fs-16 fw-bold mb-1 d-inline-block"
                                    >
                                        <i class="bx bx-notepad"></i>
                                        Nota
                                        {{
                                            capitalizeFirstLetter(
                                                $route.params.jenis
                                            )
                                        }}
                                    </h5>
                                </div>
                                <BButton
                                    variant="secondary"
                                    @click.prevent="() => $router.go(-1)"
                                >
                                    <i class="bx bx-left-arrow-alt"></i>
                                    Kembali
                                </BButton>
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Kode Daftar</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.kode"
                                    readonly
                                />
                                <span v-if="err?.kode" class="text-danger">
                                    {{ err.kode[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Pengajuan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.pengajuan_cast"
                                    readonly
                                />
                                <span
                                    v-if="err?.pengajuan_cast"
                                    class="text-danger"
                                >
                                    {{ err.pengajuan_cast[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Validasi</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.terima_cast"
                                    readonly
                                />
                                <span
                                    v-if="err?.terima_cast"
                                    class="text-danger"
                                >
                                    {{ err.terima_cast[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Kembalikan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.kembalikan_cast"
                                    readonly
                                />
                                <span
                                    v-if="err?.kembalikan_cast"
                                    class="text-danger"
                                >
                                    {{ err.kembalikan_cast[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Selesai</label>
                                <input
                                    type="text"
                                    class="form-control border border-primary"
                                    :value="form.selesai_cast"
                                    readonly
                                />
                                <span
                                    v-if="err?.selesai_cast"
                                    class="text-danger"
                                >
                                    {{ err.selesai_cast[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Jenis</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.jenis"
                                    readonly
                                />
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Unit</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form?.unit?.nama"
                                    readonly
                                />
                            </div>
                        </BCol>
                        <BCol cols="6" md="2">
                            <div class="mb-1">
                                <label class="mb-1">Pembuat</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.created_by_name"
                                    readonly
                                />
                            </div>
                        </BCol>
                        <BCol cols="6" md="3">
                            <div class="mb-1">
                                <label class="mb-1">Keterangan</label>
                                <input
                                    type="text"
                                    class="form-control border-1 border-danger"
                                    :value="form.created_by_name"
                                    readonly
                                />
                            </div>
                        </BCol>
                        <BCol cols="5">
                            <div class="mb-1">
                                <label class="mb-1">Penerima</label>
                                <input
                                    type="text"
                                    class="form-control border-1 border-danger"
                                    :value="form.penerima"
                                    readonly
                                />
                            </div>
                        </BCol>
                    </BRow>
                </form>
            </div>
        </BCardBody>
        <BCardBody
            v-if="
                [JENIS.LINEN, JENIS.ALAT].includes(
                    $route.params.jenis.toUpperCase()
                )
            "
            no-body
            class="px-3 border-bottom border-dashed py-2"
        >
            <div
                v-if="$route.params.jenis == JENIS.LINEN.toLowerCase()"
                class="p-1"
            >
                <DaftarEditListLinen />
            </div>
            <div
                v-else-if="$route.params.jenis == JENIS.ALAT.toLowerCase()"
                class="p-1"
            >
                <DaftarEditListAlat />
            </div>
        </BCardBody>
    </BCard>
</template>
<script>
import { spinnerMethods, toastMethods } from "@/state/helpers";
import { JENIS, STATUS_DAFTAR } from "@/helpers/utils";
import queryString from "query-string";
import { daftarService } from "@/services/DaftarService";
import DaftarEditListLinen from "./DaftarEditListLinen.vue";
import DaftarEditListAlat from "./DaftarEditListAlat.vue";

export default {
    components: {
        DaftarEditListLinen,
        DaftarEditListAlat,
    },
    data() {
        return {
            form: {},
            err: [],
            JENIS,
            modal: false,
            STATUS_DAFTAR,
        };
    },
    async created() {
        await this.fetchData();
        if (this.$route.params.jenis == JENIS.LINEN.toLowerCase()) {
            this.form.nota = this.form?.linen?.nota;
        } else {
            this.form.nota = this.form?.pinjam_alat?.nota;
        }
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        resetForm() {
            this.form = {};
            this.err = [];
        },
        async fetchData() {
            this.show();

            let query = queryString.stringify(
                {
                    kode_daftar: this.$route.params.kode,
                    jenis: this.$route.params.jenis,
                },
                {
                    skipNull: true,
                    arrayFormat: "index",
                }
            );

            const [err, resp] = await daftarService.showBy(query);
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.err = err.response.data.errors;
                    this.hide();
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.show();
                return;
            }
            this.hide();
            this.form = resp.data;
        },
        capitalizeFirstLetter(charc) {
            return charc.charAt(0).toUpperCase() + charc.slice(1);
        },
    },
};
</script>