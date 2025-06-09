<template>
    <div>
        <BCard no-body class="p-2">
            <div class="mb-1">
                <div
                    class="d-flex justify-content-between align-itemns-emd py-2 border-bottom"
                >
                    <h5 class="fs-16">Data Bundel Alat</h5>
                    <button
                        v-if="isSuperAdmin"
                        class="btn btn-primary"
                        @click.prevent="onCreate"
                    >
                        <i class="mdi mdi-plus-circle-outline"></i>
                        Tambah
                    </button>
                </div>
                <form @submit.prevent="fetchData">
                    <div class="text-end py-2 border-bottom mb-2">
                        <div class="d-inline-block me-1">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input
                                        type="text"
                                        class="form-control rounded bg-light border-light"
                                        placeholder="Search..."
                                        :value="filter.search"
                                        @input="onFilterSearchFn"
                                    />
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-soft-primary me-1">
                            <i class="mdi mdi-file-search-outline"></i>
                            Cari
                        </button>
                        <button
                            class="btn btn-soft-secondary me-1"
                            @click.prevent="onReset"
                        >
                            <i class="mdi mdi-refresh-circle"></i>
                            Filter
                        </button>
                    </div>
                </form>
                <div class="mb-1">
                    <EasyDataTable
                        buttons-pagination
                        rows-per-page="10"
                        :loading="loading"
                        :headers="columns"
                        :items="rows"
                        theme-color="#556ee6"
                        show-index
                        table-class-name="no-border-table"
                        :body-row-class-name="bodyRowColor"
                        must-sort
                    >
                        <template #empty-message>
                            <h5>Belum ada bundel</h5> </template
                        ><template #item-m_bundle_detail="item">
                            <ul
                                v-if="item?.m_bundle_detail?.length > 0"
                                class="ps-3 my-2"
                            >
                                <li
                                    v-for="(
                                        detail, idx
                                    ) in item?.m_bundle_detail"
                                    :key="idx"
                                    class="m-0 mb-1"
                                >
                                    {{ `${detail?.alat}: ${detail?.jml}` }}
                                </li>
                            </ul>
                        </template>
                        <template #item-aksi="item">
                            <BButton
                                variant="soft-primary"
                                size="sm"
                                @click.prevent="onShow(item)"
                                class="me-1"
                            >
                                <i class="bx bx-play"></i>
                            </BButton>
                            <BButton
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
            </div>
            <BundleTambah ref="bundleTambahModalRef" @fetch="fetchData" />
            <BundleDelete ref="bundleDeleteModalRef" @fetch="fetchData" />
        </BCard>
    </div>
</template>
<script>
import { mBundleService } from "@/services/MBundleService";
import {
    mBundleMethods,
    mBundleState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import queryString from "query-string";
import BundleDelete from "./BundleDelete";
import BundleTambah from "./BundleTambah";
import { ROLE } from "@/helpers/utils";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "nama",
    sortType: "ASC",
});

export default {
    components: {
        BundleDelete,
        BundleTambah,
    },
    data() {
        return {
            columns: [
                {
                    text: "Kategori",
                    value: "kategori.nama",
                    sortable: true,
                },
                {
                    text: "Nama",
                    value: "nama",
                    sortable: true,
                },
                {
                    text: "Detail",
                    value: "m_bundle_detail",
                    sortable: false,
                },
                {
                    text: "Dipinjam",
                    value: "dipinjam",
                    sortable: false,
                },
                {
                    text: "Dibuat",
                    value: "created_at",
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
        ...mBundleState,
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        ...mBundleMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;
            console.log(this.filter);
            let query = queryString.stringify(
                Object.assign({}, this.filter, this.paginateOpt)
            );

            const [err, resp] = await mBundleService.all(query);
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
            this.rows = result;
            this.loading = false;
        },
        onCreate() {
            let modalTambah = this.$refs.bundleTambahModalRef;
            modalTambah.modalTitle = "Tambah Bundle";
            modalTambah.modalEditable = false;
            modalTambah.resetForm();
            modalTambah.showModal();
        },
        onShow(row) {
            console.log(row);
            let modalTambah = this.$refs.bundleTambahModalRef;
            modalTambah.modalTitle = "Ubah Bundle";
            modalTambah.modalForm.id = row.id;
            modalTambah.modalFormDetail.id_bundle = row.id;
            modalTambah.modalForm.nama = row.nama;
            modalTambah.modalForm.id_kategori = row.id_kategori;
            modalTambah.modalEditable = true;
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.bundleDeleteModalRef;
            modal.modalTitle = "Hapus Bundle";
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
            this.onFilterSearch(val);
            // console.log("func filter: " + this.filter.search);
        },
        onUpdateSortFn(val) {
            console.log(val);
        },
        bodyRowColor(item) {
            let color = "";
            console.log(item.dipinjam);
            if (item?.dipinjam !== null && item?.dipinjam !== undefined) {
                color = "dipinjam";
            } else {
                color = "";
            }

            return color;
        },
    },
};
</script>
<style>
.dipinjam {
    --easy-table-body-row-background-color: #f56c6c;
    --easy-table-body-row-font-color: #fff;
}
</style>