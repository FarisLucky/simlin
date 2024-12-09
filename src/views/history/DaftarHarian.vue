<template>
    <div>
        <BCard no-body class="p-3">
            <div class="mb-1">
                <BRow
                    class="g-2 justify-content-between align-items-end border-bottom p-2 bg-primary-300 rounded"
                >
                    <BCol>
                        <h5 class="fs-16 d-inline-block">
                            History Pendaftaran
                        </h5>
                    </BCol>
                </BRow>
                <div class="py-2 border-bottom mb-2">
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
                                                @keyup.enter="onFilterSearchFn"
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
                            </div>
                        </BCol>
                    </BRow>
                </div>
                <div class="mb-1">
                    <EasyDataTable
                        v-model:server-options="paginateOpt"
                        :server-items-length="rowsLength"
                        :loading="loading"
                        :headers="columns"
                        :items="rows"
                        show-index
                        table-class-name="no-border-table customize-table"
                        must-sort
                    >
                        <template #empty-message>
                            <h5>Belum ada unit</h5>
                        </template>
                        <template #item-status_cast="item">
                            <div
                                class="bg-light text-danger p-1 d-inline-block rounded"
                            >
                                <b>
                                    {{ item.status_cast }}
                                </b>
                            </div>
                        </template>
                        <template #item-unit="item">
                            <span>{{ item.unit.nama }}</span>
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
                            >
                                <i class="bx bx-caret-right"></i>
                            </BButton>
                            <BButton
                                v-if="item.jenis === JENIS.LINEN"
                                variant="soft-danger"
                                size="sm"
                                @click.prevent="
                                    onShowMutu({
                                        kode: item.kode,
                                        title: 'Perbaikan Mutu CSSD & LINEN',
                                    })
                                "
                                class="me-1"
                                title="IMP Unit Linen"
                            >
                                <i class="bx bx-purchase-tag-alt"></i>
                            </BButton>
                        </template>
                    </EasyDataTable>
                </div>
            </div>
        </BCard>
        <DaftarHarianMutu ref="daftarHarianMutuRef" />
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
import { JENIS, STATUS_DAFTAR } from "@/helpers/utils";
import { historyDaftarService } from "@/services/HistoryDaftarService";
import DaftarHarianMutu from "./DaftarHarianMutu.vue";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "created_at",
    sortType: "DESC",
});

export default {
    components: {
        DaftarHarianMutu,
    },
    data() {
        return {
            columns: [
                {
                    text: "Nota",
                    value: "kode",
                    sortable: true,
                },
                {
                    text: "Unit",
                    value: "unit",
                    sortable: false,
                    fixed: true,
                },
                {
                    text: "Pengajuan",
                    value: "pengajuan_cast",
                    sortable: false,
                },
                {
                    text: "Selesai",
                    value: "selesai_cast",
                    sortable: false,
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
                    text: "Aksi",
                    value: "aksi",
                    sortable: false,
                },
            ],
            rows: [],
            rowsLength: 0,
            paginateOpt: initTableOpt(),
            loading: false,
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
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        ...daftarMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;
            let query = queryString.stringify(
                Object.assign({}, this.filter, this.paginateOpt)
            );

            const [err, resp] = await historyDaftarService.all(query);
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
            let result = resp.data;
            this.rows = result.data;
            this.rowsLength = result.total;
            this.loading = false;
        },
        onShow({ kode, jenis }) {
            this.$router.push({
                name: "HDaftarEdit",
                params: {
                    kode: kode,
                    jenis: jenis,
                },
            });
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
        onFilterJenisFn(val) {
            this.onFilterJenis(val);
            this.fetchData();
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
        onShowMutu(params) {
            let modalTambah = this.$refs.daftarHarianMutuRef;
            modalTambah.modalTitle = params.title;
            modalTambah.modalForm.kode_daftar = params.kode;
            modalTambah.showModal();
        },
    },
};
</script>