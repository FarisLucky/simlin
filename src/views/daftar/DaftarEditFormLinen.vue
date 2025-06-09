<template>
    <BRow class="g-2">
        <BCol
            v-if="status !== STATUS_DAFTAR.DIKEMBALIKAN"
            cols="12"
            class="mb-1 border-secondary border-opacity-25"
        >
            <div class="py-1 me-2">
                <div class="mb-1">
                    <h5 class="p-1 border-bottom d-inline-block">
                        <i class="bx bx-list-check me-1"></i>
                        Form {{ capitalizeFirstLetter($route.params.jenis) }}
                    </h5>
                </div>
                <form @submit.prevent="onStore">
                    <BRow class="g-2 align-items-end">
                        <BCol cols="6" md="2">
                            <label class="mb-1">Kode</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.kode"
                                readonly
                                ref="kodeRef"
                                placeholder="Otomatis"
                            />
                            <span v-if="err?.kode" class="text-danger">
                                {{ err.kode[0] }}</span
                            >
                        </BCol>
                        <BCol cols="6" md="3">
                            <label class="mb-1">Nama</label>
                            <v-select
                                v-model="form.nama"
                                :options="mLinenUnitList"
                                @option:selected="onSelectLinen"
                                placeholder="Pilih Linen"
                                ref="namaRef"
                            >
                            </v-select>
                            <span v-if="err?.nama" class="text-danger">
                                {{ err.nama[0] }}</span
                            >
                        </BCol>
                        <BCol cols="6" md="1">
                            <label class="mb-1">Jumlah</label>
                            <input
                                type="number"
                                class="form-control"
                                v-model="form.jml"
                                placeholder="Angka"
                            />
                            <span v-if="err?.jml" class="text-danger">
                                {{ err.jml[0] }}</span
                            >
                        </BCol>
                        <BCol>
                            <button
                                type="submit"
                                class="btn btn-soft-primary me-1"
                                :disabled="submit"
                                ref="btnRef"
                            >
                                <i class="mdi mdi-plus-circle-outline"></i>
                                {{ submit ? "Sedang Proses" : "Simpan" }}
                            </button>
                            <button
                                type="button"
                                class="btn btn-soft-secondary"
                                ref="btnRef"
                                @click.prevent="onReset"
                            >
                                <i class="mdi mdi-sync"></i>
                            </button>
                            <div v-if="errState" class="mt-3"></div>
                        </BCol>
                    </BRow>
                </form>
            </div>
        </BCol>
        <BCol cols="12" class="mb-1">
            <div class="p-1">
                <DaftarEditListLinen
                    ref="daftarEditListLinenRef"
                    :st_pengajuan="pengajuan"
                    :st_terima="terima"
                    :st_kembali="kembalikan"
                    :st_status="status"
                    :st_created_by="created_by"
                />
            </div>
        </BCol>
    </BRow>
</template>
<script>
import { mLinenUnitService } from "@/services/MLinenUnitService";
import { spinnerMethods, toastMethods } from "@/state/helpers";
import DaftarEditListLinen from "./DaftarEditListLinen.vue";
import { transLinenDetailService } from "@/services/TransLinenDetailService";
import { STATUS_DAFTAR } from "@/helpers/utils";

const initFormData = () => ({
    nota_linen: "",
    kode_daftar: "",
    kode: "",
    nama: "",
    jml: "",
});

export default {
    components: {
        DaftarEditListLinen,
    },
    props: [
        "nota",
        "pengajuan",
        "terima",
        "kembalikan",
        "status",
        "created_by",
    ],
    data() {
        return {
            form: initFormData(),
            err: {},
            errState: false,
            submit: false,
            mLinenUnitList: [],
            STATUS_DAFTAR,
        };
    },
    watch: {
        "form.kode"(newValue) {
            this.form.kode = newValue.toUpperCase();
        },
        nota() {
            this.form.nota_linen = this.nota;
        },
    },
    created() {
        this.getLinens();
    },
    mounted() {
        this.form.kode_daftar = this.$route.params.kode;
        this.form.nota_linen = this.nota;
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        resetForm() {
            let nota_linen = this.form.nota_linen;
            this.form = Object.assign({}, initFormData());
            this.form.kode_daftar = this.$route.params.kode;
            this.form.nota_linen = nota_linen;
            this.err = {};
        },
        async onStore() {
            this.show();
            const [err] = await transLinenDetailService.store(this.form);
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.err = err.response.data.errors;
                    this.errState = true;
                    this.hide(false);
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
            this.resetForm();
            this.$refs.daftarEditListLinenRef.fetchData();
        },
        async getLinens() {
            let query = null;
            const [err, resp] = await mLinenUnitService.data(query);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                return;
            }
            this.mLinenUnitList = resp.data.map((linen) => ({
                kode: linen.kode,
                nama: linen.nama,
                label: `${linen.nama}`,
            }));
            console.log(this.mLinenUnitList);
        },
        onSelectLinen(row) {
            this.form.kode = row.kode;
            this.form.nama = row.nama;
        },
        onReset() {
            this.form = {
                nota_linen: "",
                kode_daftar: "",
                kode: "",
                nama: "",
                jml: "",
            };
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
    },
};
</script>