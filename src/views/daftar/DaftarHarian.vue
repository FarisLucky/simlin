<template>
    <div>
        <BCard no-body class="p-3">
            <div class="mb-1">
                <BRow
                    class="g-2 justify-content-between align-items-end border-bottom p-2 bg-primary-300 rounded"
                >
                    <BCol>
                        <h5 class="fs-16 d-inline-block">Data Harian</h5>
                    </BCol>
                    <BCol>
                        <div class="text-end">
                            <button
                                @click.prevent="onCreate('linen')"
                                class="btn btn-primary me-1"
                            >
                                <i class="mdi mdi-plus-circle-outline"></i>
                                Linen
                            </button>
                            <button
                                @click.prevent="onCreate('alat')"
                                class="btn btn-danger me-1"
                            >
                                <i class="mdi mdi-plus-circle-outline"></i>
                                Alat
                            </button>
                        </div>
                    </BCol>
                </BRow>
                <div class="py-3 border-bottom mb-2">
                    <BRow
                        class="g-2 justify-content-center justify-content-md-between"
                    >
                        <BCol cols="12" md="6">
                            <div
                                class="btn-group btn-group-example"
                                role="group"
                            >
                                <b-button
                                    variant="outline-primary"
                                    class="w-sm"
                                    :class="{ active: filter.jenis == 'semua' }"
                                    @click.prevent="onFilterJenisFn('semua')"
                                >
                                    Semua
                                </b-button>
                                <b-button
                                    variant="outline-primary"
                                    class="w-sm"
                                    :class="{ active: filter.jenis == 'linen' }"
                                    @click.prevent="onFilterJenisFn('linen')"
                                >
                                    Linen
                                </b-button>
                                <b-button
                                    variant="outline-primary"
                                    class="w-sm"
                                    :class="{ active: filter.jenis == 'alat' }"
                                    @click.prevent="onFilterJenisFn('alat')"
                                >
                                    Alat
                                </b-button>
                            </div>
                        </BCol>
                        <BCol cols="12" md="6">
                            <div class="text-end">
                                <div class="d-inline-block me-1">
                                    <div class="search-box">
                                        <div class="position-relative">
                                            <input
                                                type="text"
                                                class="form-control rounded bg-light border-light"
                                                placeholder="Search..."
                                                :value="filter.search"
                                                @input="
                                                    onFilterSearchFn(
                                                        $event.target.value
                                                    )
                                                "
                                                @keyup.enter.prevent="fetchData"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    class="btn btn-soft-primary me-1"
                                    @click.prevent="fetchData"
                                >
                                    <i class="mdi mdi-file-search-outline"></i>
                                    Cari
                                </button>
                                <button
                                    class="btn btn-soft-secondary me-1"
                                    @click.prevent="onReset"
                                >
                                    <i class="mdi mdi-refresh-circle"></i>
                                    Reset
                                </button>
                                <BButton
                                    variant="soft-success"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseExample"
                                >
                                    <i class="bx bx-show"></i>
                                    Alur Pengajuan
                                </BButton>
                            </div>
                        </BCol>
                    </BRow>
                </div>
                <BCollapse id="collapseExample">
                    <DaftarHarianAlur />
                </BCollapse>
                <div v-if="itemsSelected.length > 0" class="mb-1 text-end">
                    <button
                        type="button"
                        class="btn btn-sm btn-soft-danger"
                        @click.prevent="onDestroyAll"
                    >
                        <i class="mdi mdi-trash-can-outline"></i>
                        hapus
                    </button>
                </div>
                <div class="mb-1">
                    <EasyDataTable
                        v-model:server-options="paginateOpt"
                        v-model:items-selected="itemsSelected"
                        :server-items-length="rowsLength"
                        :loading="loading"
                        :headers="columns"
                        :items="rows"
                        buttons-pagination
                        show-index
                        table-class-name="customize-table"
                        must-sort
                        :body-row-class-name="bodyRowClassFn"
                    >
                        <template #empty-message>
                            <h5>Belum ada unit</h5>
                        </template>
                        <template #item-status_cast="item">
                            <div
                                class="bg-opacity-75 bg-light text-danger p-1 d-inline-block rounded"
                            >
                                <b>{{ item.status_cast }}</b>
                            </div>
                        </template>
                        <template #item-unit="item">
                            <span>{{ item?.unit?.nama }}</span>
                        </template>
                        <template #item-kode="item">
                            <strong>{{ item.kode }}</strong>
                        </template>
                        <template #item-aksi="item">
                            <BButton
                                variant="soft-primary"
                                size="sm"
                                @click.prevent="
                                    onShow({
                                        kode: item.kode,
                                        jenis: item.jenis.toLowerCase(),
                                    })
                                "
                                class="me-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Lihat"
                            >
                                <i class="bx bx-caret-right"></i>
                            </BButton>
                            <BButton
                                v-if="
                                    item.status === STATUS_DAFTAR.PENGAJUAN &&
                                    item.jenis === JENIS.ALAT &&
                                    isSuperAdmin
                                "
                                variant="success"
                                size="sm"
                                @click.prevent="onShowTerima(item)"
                                class="me-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Terima"
                            >
                                <i class="bx bxs-check-circle"></i>
                            </BButton>
                            <BButton
                                v-if="
                                    item.status === STATUS_DAFTAR.TERIMA &&
                                    item.jenis === JENIS.JENIS &&
                                    isSuperAdmin
                                "
                                variant="info"
                                size="sm"
                                @click.prevent="onKembalikan(item)"
                                class="me-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Kirim Ke Ruangan"
                            >
                                <i class="bx bx-send"></i>
                            </BButton>
                            <BButton
                                v-if="
                                    (item.status == STATUS_DAFTAR.NOTA &&
                                        item.created_by_name ===
                                            $store.state.auth.data.username) ||
                                    isSuperAdmin
                                "
                                type="button"
                                variant="soft-danger"
                                size="sm"
                                @click.prevent="
                                    onDestroy({
                                        id: item.id,
                                        nama: item.jenis,
                                    })
                                "
                            >
                                <i class="bx bx-trash"></i>
                            </BButton>
                        </template>
                        <template #loading>
                            <img
                                src="@/assets/images/loader.gif"
                                style="width: 100%; height: 40px"
                            />
                        </template>
                    </EasyDataTable>
                </div>
            </div>
            <DaftarHarianDelete
                ref="daftarHarianDeleteRef"
                @fetch="fetchData"
            />
            <DaftarHarianDeleteAll
                ref="daftarHarianDeleteAllRef"
                @fetch="fetchData"
            />
            <DaftarHarianSimpan
                ref="daftarHarianSimpanRef"
                @fetch="fetchData"
                @onSubmit="onUpdateStatus"
            />
        </BCard>
    </div>
</template>
<script>
import {
    daftarMethods,
    daftarState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import queryString from "query-string";
import { daftarService } from "@/services/DaftarService";
import DaftarHarianDelete from "./DaftarHarianDelete.vue";
import DaftarHarianDeleteAll from "./DaftarHarianDeleteAll.vue";
import DaftarHarianSimpan from "./DaftarHarianSimpan.vue";
import DaftarHarianAlur from "./DaftarHarianAlur.vue";
import { JENIS, ROLE, STATUS_DAFTAR } from "@/helpers/utils";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "created_at",
    sortType: "DESC",
});

export default {
    components: {
        DaftarHarianDelete,
        DaftarHarianDeleteAll,
        DaftarHarianSimpan,
        DaftarHarianAlur,
    },
    data() {
        return {
            columns: [
                {
                    text: "Kode",
                    value: "kode",
                    sortable: true,
                },
                {
                    text: "Pengajuan",
                    value: "pengajuan_cast",
                    sortable: false,
                    fixed: true,
                },
                {
                    text: "Permintaan",
                    value: "jenis",
                    sortable: true,
                },
                {
                    text: "Keterangan",
                    value: "ket",
                    sortable: false,
                },
                {
                    text: "Status",
                    value: "status_cast",
                    sortable: false,
                },
                {
                    text: "Unit",
                    value: "unit",
                    sortable: false,
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
            itemsSelected: [],
            JENIS,
            STATUS_DAFTAR,
        };
    },
    created() {
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
            this.loading = true;
            let query = queryString.stringify(
                Object.assign({}, this.filter, this.paginateOpt)
            );

            const [err, resp] = await daftarService.all(query);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.loading = true;

                return;
            }
            let result = resp.data;
            this.rows = result.data;
            this.rowsLength = result.total;
            this.paginateOpt.page = result?.current_page;
            this.paginateOpt.rowsPerPage = result?.per_page;
            this.loading = false;
        },
        onShow({ kode, jenis }) {
            this.$router.push({
                name: "DaftarEdit",
                params: {
                    kode: kode,
                    jenis: jenis,
                },
            });
        },
        onDestroy(row) {
            let modal = this.$refs.daftarHarianDeleteRef;
            modal.modalTitle = "Hapus Daftar";
            modal.modalForm.id = row.id;
            modal.modalForm.nama = row.nama;
            modal.showModal();
        },
        onDestroyAll() {
            let modal = this.$refs.daftarHarianDeleteAllRef;
            modal.modalTitle = "Hapus Daftar";
            modal.items = this.itemsSelected;
            modal.showModal();
        },
        onReset() {
            this.resetFilter();
            Object.assign(this.paginateOpt, initTableOpt());
            this.fetchData();
        },
        onFilterSearchFn(val) {
            this.onFilterSearch(val);
        },
        onFilterJenisFn(val) {
            this.onFilterJenis(val);
            this.fetchData();
        },
        onUpdateSortFn(val) {
            console.log(val);
        },
        bodyRowClassFn(item) {
            console.log(item);
        },
        onShowTerima(params) {
            let modalRef = this.$refs.daftarHarianSimpanRef;
            modalRef.modalForm.kode = params.kode;
            modalRef.modalForm.progress = STATUS_DAFTAR.TERIMA;
            modalRef.modalForm.jenis = params.jenis;
            modalRef.showModal();
        },
        onKembalikan(params) {
            let modalRef = this.$refs.daftarHarianSimpanRef;
            modalRef.modalTitle = "Kembalikan permintaan";
            modalRef.modalForm.kode = params.kode;
            modalRef.modalForm.progress = STATUS_DAFTAR.DIKEMBALIKAN;
            modalRef.modalForm.jenis = params.jenis;
            modalRef.showModal();
        },
        async onCreate(jenis) {
            this.show();

            const [err, resp] = await daftarService.store({
                jenis: jenis,
            });

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
            this.$router.push({
                name: "DaftarEdit",
                params: {
                    kode: resp.data.kode,
                    jenis: jenis,
                },
            });
        },
        async onUpdateStatus(params) {
            this.show();
            const [err] = await daftarService.updatePengajuan(params.kode, {
                progress: params.progress,
            });
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
            this.$refs.daftarHarianSimpanRef.hideModal();
            this.$router.push({
                name: "DaftarEditTerima",
                params: {
                    kode: params.kode,
                    jenis: params.jenis.toLowerCase(),
                },
            });
        },
    },
};
</script>