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
                    <button
                        v-if="
                            st_status !== STATUS_DAFTAR.NOTA &&
                            ($store.state.auth.data.id === st_created_by ||
                                isSuperAdmin)
                        "
                        class="btn btn-soft-danger me-1"
                        type="button"
                        @click.prevent="onTerimaLinen"
                    >
                        <i class="mdi mdi-book-check"></i>
                        Aksi Linen
                    </button>
                    <button
                        class="btn btn-soft-secondary"
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
        <div class="mb-1">
            <EasyDataTable
                :loading="loading"
                :headers="columns"
                :items="rows"
                theme-color="#556ee6"
                show-index
                table-class-name="customize-table"
                must-sort
            >
                <template #empty-message>
                    <h5>Belum ada detail</h5>
                </template>
                <template #item-jml="item">
                    <div
                        v-if="st_status === STATUS_DAFTAR.NOTA"
                        class="input-group"
                    >
                        <input
                            type="number"
                            class="form-control"
                            v-model="item.jml"
                            :disabled="st_status !== STATUS_DAFTAR.NOTA"
                            @keyup.enter="
                                updateQty(
                                    {
                                        jml: item.jml,
                                        jenis: 'pengajuan',
                                    },
                                    item.id
                                )
                            "
                        />
                        <button
                            v-if="st_status === STATUS_DAFTAR.NOTA"
                            class="btn btn-soft-primary"
                            type="button"
                            @click.prevent="
                                updateQty(
                                    {
                                        jml: item.jml,
                                        jenis: 'pengajuan',
                                    },
                                    item.id
                                )
                            "
                        >
                            <i class="mdi mdi-content-save"></i>
                        </button>
                    </div>
                    <div v-else>
                        <b>{{ item.jml }}</b>
                        item
                    </div>
                </template>
                <template #item-jml_terima="item">
                    <div
                        v-if="
                            isSuperAdmin &&
                            st_status === STATUS_DAFTAR.PENGAJUAN
                        "
                        class="input-group"
                    >
                        <input
                            type="number"
                            class="form-control form-control-sm"
                            v-model="item.jml_terima"
                            @keyup.enter="
                                updateQty(
                                    {
                                        jml: item.jml_terima,
                                        jenis: 'jml_terima',
                                    },
                                    item.id
                                )
                            "
                        />
                        <button
                            class="btn btn-sm btn-soft-primary"
                            type="button"
                            @click.prevent="
                                updateQty(
                                    {
                                        jml: item.jml_terima,
                                        jenis: 'jml_terima',
                                    },
                                    item.id
                                )
                            "
                        >
                            <i class="mdi mdi-content-save"></i>
                        </button>
                    </div>
                    <div v-else>
                        <b>{{ item.jml_terima }}</b>
                        item
                    </div>
                </template>
                <template #item-jml_kembali="item">
                    <div
                        v-if="
                            isSuperAdmin && st_status === STATUS_DAFTAR.TERIMA
                        "
                        class="input-group"
                    >
                        <input
                            type="number"
                            class="form-control"
                            v-model="item.jml_kembali"
                            @keyup.shift.enter="
                                updateQty(
                                    {
                                        jml: item.jml_kembali,
                                        jenis: 'jml_kembali',
                                    },
                                    item.id
                                )
                            "
                        />
                        <button
                            class="btn btn-soft-primary"
                            type="button"
                            @click.prevent="
                                updateQty(
                                    {
                                        jml: item.jml_kembali,
                                        jenis: 'jml_kembali',
                                    },
                                    item.id
                                )
                            "
                        >
                            <i class="mdi mdi-content-save"></i>
                        </button>
                    </div>
                    <div v-else>
                        <b>{{ item.jml_kembali }}</b>
                        item
                    </div>
                </template>
                <template #item-jml_akhir="item">
                    <div
                        v-if="
                            st_status === STATUS_DAFTAR.DIKEMBALIKAN &&
                            $store.state.auth.data.id == st_created_by
                        "
                        class="input-group"
                    >
                        <input
                            type="number"
                            class="form-control"
                            v-model="item.jml_akhir"
                            @keyup.shift.enter="
                                updateQty(
                                    {
                                        jml: item.jml_akhir,
                                        jenis: 'jml_akhir',
                                    },
                                    item.id
                                )
                            "
                        />
                        <button
                            class="btn btn-soft-primary"
                            type="button"
                            @click.prevent="
                                updateQty(
                                    {
                                        jml: item.jml_akhir,
                                        jenis: 'jml_akhir',
                                    },
                                    item.id
                                )
                            "
                        >
                            <i class="mdi mdi-content-save"></i>
                        </button>
                    </div>
                    <div v-else>
                        <b>{{ item.jml_akhir }}</b>
                        item
                    </div>
                </template>
                <template #item-aksi="item">
                    <BButton
                        v-if="
                            (item.daftar?.status === STATUS_DAFTAR.PENGAJUAN &&
                                item.daftar.created_by ===
                                    $store.state.auth.data.id) ||
                            isSuperAdmin
                        "
                        type="button"
                        variant="soft-danger"
                        size="sm"
                        @click.prevent="onDestroy(item)"
                    >
                        <i class="bx bx-trash"></i>
                    </BButton>
                </template>
            </EasyDataTable>
        </div>
        <DaftarEditDelete
            ref="transLinenDetailDeleteModalRef"
            @fetch="fetchData"
            :jenisTrans="JENIS.LINEN"
        />
        <DaftarEditTerima
            ref="editTerimaRef"
            :status="st_status"
            :pcreated_by="st_created_by"
            :jenisTrans="JENIS.LINEN"
        />
    </div>
</template>
<script>
import {
    daftarMethods,
    daftarState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import { transLinenDetailService } from "@/services/TransLinenDetailService";
import DaftarEditDelete from "./DaftarEditDelete.vue";
import DaftarEditTerima from "./DaftarEditTerima.vue";
import { JENIS, ROLE, STATUS_DAFTAR } from "@/helpers/utils";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "created_at",
    sortType: "DESC",
});

export default {
    components: {
        DaftarEditDelete,
        DaftarEditTerima,
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
            columns: [
                {
                    text: "Kode",
                    value: "kode_linen_unit",
                    sortable: true,
                },
                {
                    text: "Nama",
                    value: "nama",
                    sortable: true,
                    fixed: true,
                },
                {
                    text: "Jumlah",
                    value: "jml",
                    sortable: false,
                    width: 120,
                },
                {
                    text: "Validasi",
                    value: "jml_terima",
                    sortable: false,
                    width: 120,
                },
                {
                    text: "Kembalikan",
                    value: "jml_kembali",
                    sortable: false,
                    width: 120,
                },
                {
                    text: "Selesai",
                    sortable: false,
                    value: "jml_akhir",
                    width: 120,
                },
                {
                    text: "Aksi",
                    value: "aksi",
                    sortable: false,
                },
            ],
            rows: [],
            rowsLength: 0,
            paginateOpt: initTableOpt(),
            loading: false,
            STATUS_DAFTAR,
            JENIS,
            err: [],
        };
    },
    mounted() {
        this.fetchData();
    },
    watch: {
        reload() {
            this.fetchData();
        },
        paginateOpt: {
            handler() {
                this.fetchData();
            },
            deep: true,
        },
    },
    computed: {
        ...daftarState,
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        ...daftarMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;

            const [err, resp] = await transLinenDetailService.showByDaftar(
                this.$route.params.kode
            );
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.isLoading = false;

                this.hide();
                return;
            }
            this.hide();
            this.rows = resp.data;
            this.loading = false;
        },
        async updateQty(params, id) {
            this.show();

            const [err] = await transLinenDetailService.updateQty(params, id);
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
            let modalTambah = this.$refs.unitTambahModalRef;
            modalTambah.modalTitle = "Ubah Linen";
            modalTambah.modalForm.kode = row.kode;
            modalTambah.modalForm.nama = row.nama;
            modalTambah.modalEditable = true;
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.transLinenDetailDeleteModalRef;
            modal.modalTitle = "Hapus Linen";
            modal.modalForm.id = row.id;
            modal.modalForm.nama = row.nama;
            modal.showModal();
        },
        onReset() {
            this.resetFilter();
            Object.assign(this.paginateOpt, initTableOpt());
            this.fetchData();
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
        onTerimaLinen() {
            let lists = this.rows.map((item) => {
                item.jml_terima = item.jml;

                return item;
            });
            this.onShowTerima({ title: "Terima Linen", items: lists });
        },
        onShowTerima({ title, items }) {
            let modalTambah = this.$refs.editTerimaRef;
            modalTambah.modalTitle = title;
            modalTambah.modalForm.kode = this.$route.params.kode;
            modalTambah.lists = items;
            modalTambah.showModal();
        },
    },
};
</script>