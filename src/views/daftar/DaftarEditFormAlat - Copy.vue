<template>
    <div>
        <div class="mb-3">
            <form @submit.prevent="onSubmit">
                <h5 class="d-inline-block border-bottom pb-1">
                    <i class="bx bx-list-check me-1"></i>
                    Form Pinjam Alat
                </h5>
                <BRow class="g-2 align-items-end">
                    <BCol cols="6" md="2">
                        <div class="mb-1">
                            <label>Jenis</label>
                            <v-select
                                v-model="form.type"
                                :options="['SATUAN', 'BUNDLE']"
                                placeholder="Pilih Satuan/Bundle"
                            ></v-select>
                            <span v-if="err?.type" class="text-danger">
                                {{ err.type[0] }}
                            </span>
                        </div>
                    </BCol>
                    <BCol v-if="form.type === 'SATUAN'" cols="6" md="1">
                        <div class="mb-1">
                            <label>Kode Alat</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.kode_alat"
                                disabled
                            />
                            <span v-if="err?.kode_alat" class="text-danger">
                                {{ err.kode_alat[0] }}</span
                            >
                        </div>
                    </BCol>
                    <BCol v-if="form.type === 'SATUAN'" cols="6" md="3">
                        <div class="mb-1">
                            <label>Nama</label>
                            <v-select
                                v-model="form.nama"
                                :options="mAlatList"
                                @option:selected="onSelectAlat"
                                :reduce="(alat) => alat.nama"
                                placeholder="Pilih Alat"
                                ref="namaRef"
                                label="nama"
                            >
                            </v-select>
                            <span v-if="err?.nama" class="text-danger">
                                {{ err.nama[0] }}</span
                            >
                        </div>
                    </BCol>
                    <BCol v-if="form.type === 'SATUAN'" cols="6" md="1">
                        <div class="mb-1">
                            <label>Stock</label>
                            <input
                                type="number"
                                v-model="form.sisa"
                                class="form-control"
                                disabled
                            />
                        </div>
                    </BCol>
                    <BCol v-if="form.type === 'SATUAN'" cols="6" md="1">
                        <div class="mb-1">
                            <label>Qty</label>
                            <input
                                type="number"
                                v-model="form.jml"
                                class="form-control"
                            />
                            <span v-if="err?.jml" class="text-danger">
                                {{ err.jml[0] }}</span
                            >
                        </div>
                    </BCol>
                    <BCol v-if="form.type === 'BUNDLE'" cols="6" md="3">
                        <div class="mb-1">
                            <label>Kategori</label>
                            <v-select
                                v-model="form.id_kategori"
                                :options="mKategoriList"
                                :reduce="(kat) => kat.id"
                                @option:selected="onSelectKategori"
                                placeholder="Pilih Bundel"
                                ref="namaRef"
                                label="nama"
                            >
                            </v-select>
                            <span v-if="err?.id_kategori" class="text-danger">
                                {{ err.id_kategori[0] }}</span
                            >
                        </div>
                    </BCol>
                    <BCol
                        v-if="
                            (form.type !== null &&
                                mAlatDetailList.length > 0) ||
                            (form.type !== null && form.kode_alat !== '')
                        "
                    >
                        <div class="mb-1">
                            <BButton
                                type="submit"
                                variant="primary"
                                :disabled="modalSubmit"
                            >
                                {{ modalSubmit ? "Sedang Proses" : "Simpan" }}
                            </BButton>
                        </div>
                    </BCol>
                    <BCol
                        v-if="form.type === 'BUNDLE' && form.id_kategori !== ''"
                        sm="12"
                    >
                        <div class="p-2 border-start border-3 border-primary">
                            <label class="d-block">
                                List Alat <b>{{ form.kategori }}</b></label
                            >
                            <div
                                v-if="mAlatDetailList.length > 0"
                                class="mb-1 d-inline-block"
                            >
                                <BListGroup>
                                    <BListGroupItem
                                        v-for="(alat, idx) in mAlatDetailList"
                                        class="p-2"
                                        :variant="
                                            idx % 2 === 0 ? 'primary' : ''
                                        "
                                        :key="idx"
                                    >
                                        <i
                                            class="mdi mdi-check-bold align-middle lh-1 me-2"
                                        ></i>
                                        {{ alat.nama }}
                                        <b>@ {{ alat.jml }} item</b>
                                    </BListGroupItem>
                                </BListGroup>
                            </div>
                            <div v-else>
                                <h5 class="p-2">Tidak ada Alat</h5>
                            </div>
                        </div>
                    </BCol>
                </BRow>
            </form>
        </div>
        <BCol cols="12" class="mb-1">
            <div class="p-1">
                <DaftarEditListAlat
                    ref="daftarEditListAlatRef"
                    :st_pengajuan="pengajuan"
                    :st_terima="terima"
                    :st_kembali="kembalikan"
                    :st_status="status"
                    :st_created_by="created_by"
                />
            </div>
        </BCol>
    </div>
</template>
<script>
import { mUnitService } from "@/services/MUnitService";
import { spinnerMethods, toastMethods } from "@/state/helpers";
import DaftarEditListAlat from "./DaftarEditListAlat.vue";
import { mAlatService } from "@/services/MAlatService";
import { mAlatDetailService } from "@/services/MBundleDetailService";
import { mKategoriService } from "@/services/MKategoriService";
import { pinjamDetailService } from "@/services/PinjamDetailService";

const initFormData = () => ({
    id: "",
    nota_pinjam: "",
    kode_daftar: "",
    kode_alat: "",
    nama: "",
    jml: "",
    id_kategori: "",
    kategori: "",
    type: null,
    sisa: 0,
});

export default {
    components: {
        DaftarEditListAlat,
    },
    props: [
        "nota",
        "kodeDaftar",
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
            mAlatDetailList: [],
            mAlatList: [],
            mKategoriList: [],
            modalSubmit: false,
        };
    },
    watch: {
        "form.type"(newValue) {
            this.resetForm();
            this.form.type = newValue;
            if (newValue === "BUNDLE") {
                this.getKategori();
            } else if (newValue == "SATUAN") {
                this.getAlat();
            }
        },
    },
    mounted() {
        this.form.nota_pinjam = this.nota;
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        resetForm() {
            this.form.id_kategori = "";
            this.err = {};
            this.mAlatDetailList = [];
        },
        onSubmit() {
            if (this.modalEditable) {
                this.onUpdate();
            } else {
                this.onStore();
            }
        },
        async showBundle(idKategori) {
            this.show();
            const [err, resp] = await mAlatDetailService.data({
                id_kategori: idKategori,
            });
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.mAlatDetailList = resp.data;
            this.hide();
        },
        async getAlat() {
            this.show();
            const [err, resp] = await mAlatService.data();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.mAlatList = resp.data;
            this.hide();
        },
        async getKategori() {
            this.show();
            const [err, resp] = await mKategoriService.data();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.mKategoriList = resp.data;
            this.hide();
        },
        async onStore() {
            this.show();
            this.form.nota_pinjam = this.nota;
            this.form.kode_daftar = this.kodeDaftar;
            const [err] = await pinjamDetailService.store(this.form);
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.err = err.response.data.errors;
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
            this.$refs.daftarEditListAlatRef.fetchData();
        },
        async onUpdate() {
            this.show();
            const [err] = await mUnitService.update(
                this.form,
                this.form.kode_alat
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
                this.show();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.hideModal();
        },
        onSelectAlat(row) {
            this.form.kode_alat = row.kode;
            this.form.sisa = row.sisa;
        },
        onSelectKategori(row) {
            this.form.kategori = row.nama;
            this.showBundle(row.id);
        },
    },
};
</script>
<style lang="">
</style>