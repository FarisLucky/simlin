<template>
    <div>
        <h4 class="mb-1">
            Range:
            {{
                `${this.filter.awal
                    .locale("id")
                    .format("ddd, D MMM YYYY")} - ${this.filter.akhir
                    .locale("id")
                    .format("ddd, D MMM YYYY")}`
            }}
        </h4>
        <small class="text-danger">
            * Jumlah Permintaan kassa yang sudah selesai
        </small>
        <v-chart
            class="chart"
            :option="option"
            autoresize
            style="width: 100%; height: 340px"
        />
    </div>
</template>
<script>
import { daftarService } from "@/services/DaftarService";
import "dayjs/locale/id";
import dayjs from "dayjs";
import { LineChart } from "echarts/charts";
// import * as echarts from "echarts/core";

import { use } from "echarts/core";
import {
    TitleComponent,
    TooltipComponent,
    GridComponent,
    ToolboxComponent,
    LegendComponent,
} from "echarts/components";
import { BarChart } from "echarts/charts";
import { CanvasRenderer } from "echarts/renderers";
import VChart from "vue-echarts";
import { spinnerMethods } from "@/state/helpers";
import queryString from "query-string";

export default {
    components: {
        VChart,
    },
    setup() {
        use([
            LineChart,
            TitleComponent,
            TooltipComponent,
            GridComponent,
            ToolboxComponent,
            LegendComponent,
            BarChart,
            CanvasRenderer,
        ]);
    },
    data() {
        return {
            option: {
                color: ["#FFBF00", "#FF0087", "#80FFA5", "#00DDFF", "#37A2FF"],
                tooltip: {
                    trigger: "axis",
                    axisPointer: {
                        type: "cross",
                        label: {
                            backgroundColor: "#6a7985",
                        },
                    },
                },
                grid: {
                    left: "3%",
                    right: "4%",
                    bottom: "3%",
                    containLabel: true,
                },
                legend: {
                    data: ["Linen", "Alat"],
                },
                toolbox: {
                    feature: {
                        saveAsImage: {},
                    },
                },
                xAxis: {
                    type: "category",
                    data: [],
                },
                yAxis: {
                    type: "value",
                },
                series: [],
            },
            filter: {
                awal: dayjs().set("day", -7),
                akhir: dayjs(),
            },
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        ...spinnerMethods,
        async fetchData() {
            this.show();
            const query = queryString.stringify(
                {
                    awal: this.filter.awal.format("YYYY-MM-DD"),
                    akhir: this.filter.akhir.format("YYYY-MM-DD"),
                },
                {
                    arrayFormat: "index",
                }
            );
            const [err, resp] = await daftarService.grafikTrendKasa(query);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();

                return;
            }
            if (resp.data.label.length > 0) {
                this.option.xAxis.data = resp.data.label;
                // console.log(Object.entries(resp.data.series));
                Object.entries(resp.data.series).forEach((series) => {
                    let nama = series[0];
                    this.option.series.push({
                        data: series[1],
                        name: nama,
                        type: "line",
                        smooth: true,
                        areaStyle: {
                            opacity: 0.8,
                        },
                        emphasis: {
                            focus: "series",
                        },
                        label: {
                            show: true,
                        },
                        endLabel: {
                            show: true,
                            formatter: function (params) {
                                return params.seriesName;
                            },
                        },
                    });
                });
            }
            this.hide();
        },
    },
};
</script>