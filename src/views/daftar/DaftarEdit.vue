<template>
    <BCard no-body class="p-1">
        <BCardBody class="border-bottom border-dashed border-2 py-2 px-3">
            <div class="p-1">
                <form @submit.prevent="onUpdateSimpan(form)">
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
                                            $route.params.jenis.toLocaleUpperCase()
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
                                <label class="mb-1">Nota</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="form.kode"
                                    readonly
                                />
                                <span v-if="err?.kode" class="text-danger">
                                    {{ err.kode_daftar[0] }}</span
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
                                <label class="mb-1">Unit</label>
                                <v-select
                                    v-if="isSuperAdmin"
                                    :options="units"
                                    v-model="form.unit"
                                    :reduce="(unit) => unit.nama"
                                    label="nama"
                                />
                                <input
                                    v-else
                                    type="text"
                                    class="form-control"
                                    :value="form.unit"
                                    readonly
                                />
                            </div>
                        </BCol>
                        <BCol md="2">
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
                        <BCol
                            v-if="
                                $route.params.jenis?.toUpperCase() ==
                                    JENIS.LINEN && isSuperAdmin
                            "
                            cols="6"
                            md="2"
                        >
                            <div class="mb-1">
                                <label class="mb-1">Berat</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="8.12"
                                        v-model="form.berat"
                                        @input="onBeratI"
                                    />
                                    <span class="input-group-text">KG</span>
                                </div>
                                <span
                                    v-if="err?.berat?.length > 0"
                                    class="text-danger"
                                >
                                    {{ err.berat[0] }}</span
                                >
                            </div>
                        </BCol>
                        <BCol md="3">
                            <div class="mb-1">
                                <label class="mb-1">Keterangan Nota</label>
                                <input
                                    type="text"
                                    class="form-control border-danger"
                                    v-model="form.ket"
                                />
                            </div>
                        </BCol>
                        <BCol v-if="isSuperAdmin" md="3">
                            <div class="mb-1">
                                <label class="mb-1">Penerima</label>
                                <input
                                    type="text"
                                    class="form-control border-danger"
                                    v-model="form.penerima"
                                />
                            </div>
                        </BCol>
                        <BCol
                            v-if="
                                isSuperAdmin ||
                                form.created_by === $store.state.auth.data.id
                            "
                        >
                            <div class="mb-1">
                                <BButton type="submit" variant="danger">
                                    Simpan
                                </BButton>
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
                <DaftarEditFormLinen
                    ref="DaftarEditFormLinenRef"
                    :status="form?.status"
                    :nota="form?.nota"
                    :pengajuan="form?.pengajuan"
                    :terima="form?.terima"
                    :kembalikan="form?.kembalikan"
                    :created_by="form?.created_by"
                />
            </div>
            <div
                v-else-if="$route.params.jenis == JENIS.ALAT.toLowerCase()"
                class="p-1"
            >
                <DaftarEditFormAlat
                    ref="daftarTambahFormAlatRef"
                    :status="form?.status"
                    :nota="form?.nota"
                    :kodeDaftar="form?.kode"
                    :pengajuan="form?.pengajuan"
                    :terima="form?.terima"
                    :kembalikan="form?.kembalikan"
                    :created_by="form?.created_by"
                />
            </div>
        </BCardBody>
        <BCardBody no-body class="px-3 border-bottom border-dashed py-2">
            <div class="p-1 text-end">
                <button
                    v-if="form.status === STATUS_DAFTAR.NOTA"
                    class="btn btn-success"
                    @click.prevent="
                        onSubmit({
                            kode: form.kode,
                            progress: STATUS_DAFTAR.PENGAJUAN,
                        })
                    "
                >
                    <i class="mdi mdi-content-save"></i>
                    Ajukan
                </button>
            </div>
        </BCardBody>
        <DaftarHarianSimpan @onSubmit="onAjukan" ref="simpanModalRef" />
    </BCard>
</template>
<script>
import { spinnerMethods, toastMethods } from "@/state/helpers";
import DaftarEditFormAlat from "./DaftarEditFormAlat.vue";
import DaftarEditFormLinen from "./DaftarEditFormLinen.vue";
import { JENIS, ROLE, STATUS_DAFTAR } from "@/helpers/utils";
import queryString from "query-string";
import { daftarService } from "@/services/DaftarService";
import DaftarHarianSimpan from "./DaftarHarianSimpan.vue";
import { mUnitService } from "@/services/MUnitService";

export default {
    components: {
        DaftarEditFormAlat,
        DaftarEditFormLinen,
        DaftarHarianSimpan,
    },
    data() {
        return {
            form: {},
            err: [],
            units: [],
            JENIS,
            modal: false,
            STATUS_DAFTAR,
        };
    },
    async created() {
        await this.fetchData();
        this.getUnits();
        if (this.$route.params.jenis.toUpperCase() == JENIS.LINEN) {
            this.form.nota = this.form.linen?.nota;
        } else {
            this.form.nota = this.form.pinjam_alat?.nota;
        }
    },
    computed: {
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        resetForm() {
            this.form = {};
            this.err = [];
        },
        onBeratI() {
            const matches = this.form.berat.match(/[0-9]*\.?[0-9]*/);
            if (matches) {
                this.form.berat = matches[0];
                this.err.berat = [];
            }
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
            this.form.unit = resp.data?.unit?.nama;
            if (this.form.jenis === JENIS.LINEN) {
                this.form.berat = resp.data?.linen?.berat;
            }
        },
        onSubmit(form) {
            this.$refs.simpanModalRef.modalForm.kode = form.kode;
            this.$refs.simpanModalRef.modalForm.progress = form.progress;
            this.$refs.simpanModalRef.showModal();
        },
        async onAjukan(params) {
            this.show();
            const [err] = await daftarService.updatePengajuan(
                params.kode,
                params
            );
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
                this.hide();
                return;
            }
            this.hide();
            this.$refs.simpanModalRef.hideModal();
            this.$router.push({ name: "DaftarHarian" });
        },
        async onUpdateSimpan(params) {
            this.show();
            const [err] = await daftarService.update(params, params.kode);
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
                this.hide();
                return;
            }
            this.hide();
            this.fetchData();
        },
        async getUnits() {
            this.show();
            const [err, resp] = await mUnitService.data();
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
                this.hide();
                return;
            }
            this.units = resp.data;
            this.hide();
        },
    },
};
</script>