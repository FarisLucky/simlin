<template>
    <BCard no-body>
        <BCardHeader>
            <BCardTitle>History Ucapan ULTAH</BCardTitle>
        </BCardHeader>
        <BCardBody class="p-1">
            <BRow class="g-1 align-items-end justify-contents-between">
                <BCol :md="12">
                    <div class="text-end">
                        <div class="mb-1 d-inline-block me-1">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input
                                        type="text"
                                        class="form-control rounded bg-light border-light"
                                        placeholder="Search..."
                                        :value="searchTerm"
                                        @input="onSearch"
                                    />
                                </div>
                            </div>
                        </div>
                        <button
                            class="btn btn-soft-secondary"
                            @click.prevent="onRefresh"
                        >
                            <i class="mdi mdi-refresh-circle"></i>
                        </button>
                    </div>
                </BCol>
                <BCol :lg="12">
                    <EasyDataTable
                        v-model:server-options="paginateOpt"
                        :server-items-length="serverItemsLength"
                        :loading="loading"
                        :headers="columns"
                        :items="rows"
                        theme-color="#556ee6"
                        show-index
                    >
                        <template #empty-message>
                            <h5>Belum ada pengiriman ultah</h5>
                        </template>
                        <template #item-sex="item">
                            <div class="py-1">
                                <img
                                    v-if="item.sex == 'P'"
                                    src="@/assets/images/female.png"
                                    style="width: 42px"
                                />
                                <img
                                    v-else-if="item.sex == 'L'"
                                    src="@/assets/images/male.png"
                                    style="width: 42px"
                                />
                            </div>
                        </template>
                        <template #item-status="item">
                            <span v-if="item.status == 1">OK</span>
                        </template>
                        <template #item-created_at="item">
                            <strong>{{ item.created_at }}</strong>
                        </template>
                        <template #item-ke="item">
                            <span>{{ item.ke }} Tahun</span>
                        </template>
                    </EasyDataTable>
                </BCol>
            </BRow>
        </BCardBody>
    </BCard>
</template>
<script>
import { mUnitService } from "@/services/MUnitService";
import queryString from "query-string";

export default {
    data() {
        return {
            columns: [
                {
                    text: "RM",
                    value: "mr",
                    sortable: true,
                },
                {
                    text: "Nama",
                    value: "nama",
                },
                {
                    text: "Sex",
                    value: "sex",
                    sortable: false,
                },
                {
                    text: "Tgl Lahir",
                    value: "tgl_lahir",
                    sortable: false,
                },
                {
                    text: "Alamat",
                    value: "alamat",
                    sortable: false,
                },
                {
                    text: "Ultah Ke",
                    value: "ke",
                    sortable: false,
                },
                {
                    text: "Terkirim",
                    value: "created_at",
                    sortable: false,
                },
                {
                    text: "Status",
                    value: "status",
                    sortable: false,
                },
            ],
            rows: [],
            loading: false,
            serverItemsLength: 0,
            paginateOpt: {
                page: 1,
                rowsPerPage: 10,
                sortBy: "created_at",
                sortType: "DESC",
            },
            searchTerm: "",
            searchField: "",
        };
    },
    created() {
        this.fetchData();
    },
    watch: {
        paginateOpt: {
            handler() {
                this.fetchData();
            },
        },
    },
    methods: {
        async fetchData() {
            this.isLoading = true;

            let query = queryString.stringify(Object.assign({}, this.filter));

            const [err, resp] = await mUnitService.all(query);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.isLoading = false;

                return;
            }
            let result = resp.data;
            this.rows = result.data;
            this.paginateOpt.page = result.current_page;
            this.paginateOpt.rowsPerPage = result.per_page;
            this.serverItemsLength = result.total;
            this.loading = false;
        },
        onSearch(event) {
            let val = event.target.value;
            if (val.length > 2) {
                this.searchTerm = val;
                this.paginateOpt.page = 1;
                this.fetchData();
            } else if (val === "") {
                this.searchTerm = "";
                this.paginateOpt.page = 1;
                this.fetchData();
            }
        },
        onRefresh() {
            this.searchTerm = "";
            this.fetchData();
        },
    },
};
</script>