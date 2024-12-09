<template>
    <div>
        <BRow class="align-items-end">
            <BCol cols="6">
                <h5 class="p-1 border-bottom d-inline-block">
                    <i class="bx bx-detail me-1"></i>
                    Detail
                </h5>
            </BCol>
            <BCol cols="6">
                <div class="mb-1 text-end">
                    <div class="d-inline-block">
                        <button
                            v-if="st_status === STATUS_DAFTAR.PENGAJUAN"
                            class="btn btn-primary"
                            @click.prevent="
                                onSubmit({
                                    kode: form.kode,
                                    progress: STATUS_DAFTAR.TERIMA,
                                })
                            "
                        >
                            <i class="mdi mdi-book-arrow-right-outline"></i>
                            Validasi
                        </button>
                        <button
                            v-else-if="st_status === STATUS_DAFTAR.TERIMA"
                            class="btn btn-primary"
                            @click.prevent="
                                onSubmit({
                                    kode: form.kode,
                                    progress: STATUS_DAFTAR.DIKEMBALIKAN,
                                })
                            "
                        >
                            <i class="mdi mdi-keyboard-return"></i>
                            Kembalikan ke Unit
                        </button>
                        <button
                            v-else-if="
                                st_status === STATUS_DAFTAR.DIKEMBALIKAN &&
                                isSuperAdmin
                            "
                            class="btn btn-primary"
                            @click.prevent="
                                onSubmit({
                                    kode: form.kode,
                                    progress: STATUS_DAFTAR.SELESAI,
                                })
                            "
                        >
                            <i class="mdi mdi-content-save-all"></i>
                            Selesaikan
                        </button>
                    </div>
                    <button
                        class="btn btn-soft-secondary ms-1"
                        type="button"
                        @click.prevent="fetchData"
                    >
                        <i class="mdi mdi-refresh"></i>
                    </button>
                </div>
                <div class="mb-1 text-end">
                    <small>
                        *
                        <b class="text-danger">ctrl+shift+enter</b> untuk
                        <b class="text-danger">simpan</b> setiap kolom
                    </small>
                </div>
            </BCol>
        </BRow>
        <DaftarEditListAlatNota
            v-if="st_status === STATUS_DAFTAR.NOTA"
            :status="st_status"
            ref="listAlatNotaRef"
        />
        <DaftarEditListAlatTerima
            v-else
            :status="st_status"
            ref="listTerimaNotaRef"
        />

        <DaftarEditDelete
            ref="deleteModalRef"
            @fetch="fetchData"
            :jenisTrans="JENIS.ALAT"
        />
        <DaftarEditTerimaAlat
            ref="terimaRef"
            :status="st_status"
            :pcreated_by="st_created_by"
            :jenisTrans="JENIS.ALAT"
        />
    </div>
</template>
<script>
import { spinnerMethods, toastMethods } from "@/state/helpers";
import { pinjamDetailService } from "@/services/PinjamDetailService";
import { JENIS, ROLE, STATUS_DAFTAR } from "@/helpers/utils";
import DaftarEditDelete from "./DaftarEditDelete.vue";
import DaftarEditTerimaAlat from "./DaftarEditTerimaAlat.vue";
import DaftarEditListAlatNota from "./DaftarEditListAlatNota.vue";
import DaftarEditListAlatTerima from "./DaftarEditListAlatTerima.vue";
import { daftarService } from "@/services/DaftarService";

export default {
    components: {
        DaftarEditDelete,
        DaftarEditTerimaAlat,
        DaftarEditListAlatNota,
        DaftarEditListAlatTerima,
    },
    props: [
        "st_pengajuan",
        "st_kembali",
        "st_terima",
        "st_status",
        "st_created_by",
    ],
    data() {
        return {
            STATUS_DAFTAR,
            JENIS,
            form: {
                kode: "",
            },
        };
    },
    computed: {
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    created() {
        this.form.kode = this.$route.params.kode;
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        async fetchData() {
            if (this.st_status === STATUS_DAFTAR.NOTA) {
                this.$refs.listAlatNotaRef.fetchData();
            } else {
                this.$refs.listTerimaNotaRef.fetchData();
            }
        },
        async updateQty(params, id) {
            this.show();

            const [err] = await pinjamDetailService.updateQty(params, id);
            if (err) {
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
        onShow(row) {
            let modalTambah = this.$refs.terimaRef;
            modalTambah.modalTitle = "Ubah Alat";
            modalTambah.form.kode = row.kode;
            modalTambah.form.nama = row.nama;
            modalTambah.modalEditable = true;
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.deleteModalRef;
            modal.modalTitle = "Hapus Alat";
            modal.form.id = row.id;
            modal.form.nama = row.nama;
            modal.showModal();
        },
        onFilterSearchFn(event) {
            let val = event.target.value;
            if (val.length >= 3) {
                this.onFilterSearch(val);
                this.fetchData();
            }
            if (val == "") {
                this.onFilterSearch("");
                this.fetchData();
            }
        },
        onTerima() {
            this.onShowTerima({ title: "Terima Alat", items: this.rows });
        },
        onShowTerima(params) {
            let modalTambah = this.$refs.terimaRef;
            modalTambah.modalTitle = params.title;
            modalTambah.form.kode = this.$route.params.kode;
            modalTambah.lists = params.items;
            modalTambah.showModal();
        },
        onRefreshNota() {
            this.fetchData();
        },
        async onSubmit(params) {
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
            this.$router.push({ name: "DaftarHarian" });
        },
    },
};
</script>