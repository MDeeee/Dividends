<script>
    import Layout from "../../layout/mainLayout";
    import PageHeader from "@/components/page-header";
    import { required, url } from "vuelidate/lib/validators";
    import Loading from "@/components/table-loading";
    import ConfirmDelete from "@/components/confirm-delete-popover";

    /*
     *  Scrape component
     */
    export default {
        page: {
            title: "Scrape",
            meta: [{
                name: "description"
            }]
        },
        components: {
            Layout,
            PageHeader,
            Loading,
            ConfirmDelete
        },
        data() {
            return {
                // page header
                title: 'Scrape',
                items: [],
                // CRUD
                fetchLink: 'dividends/list',
                scrapeLink: 'dividends/scrape',
                downloadLink: 'dividends/download',
                deleteLink: 'dividends/delete',
                // component data
                url: 'https://www.saudiexchange.sa/wps/portal/tadawul/markets/reports-%26-publications/market-reports/dividends/!ut/p/z0/04_Sj9CPykssy0xPLMnMz0vMAfIjo8zi_Tx8nD0MLIy8DTyMXAwczVy9vV2cTY3cAw31C7IdFQEGMU7k/',
                dividends: [],
                errors: null,
                submitted: false,
                pending: false,
                requestHeaders: {
                    responseType: 'blob',
                },
                // table properties
                transProps: { name: 'table-list'},
                totalRows: 1,
                currentPage: 1,
                perPage: 20,
                filter: '',
                filterOn: [],
                sortDesc: false,
                primaryKey: 'id',
                sortBy: 'id',
                hover: true,
                isBusy: true,
                fields: [
                    {
                        key: 'id',
                        label: '#'
                    },
                    {
                        key: 'announced_date',
                        label: 'Announced Date'
                    },
                    {
                        key: 'company',
                        label: 'Company'
                    },
                    {
                        key: "distribution_method",
                        label: "Dist. Method"
                    },
                    {
                        key: "distribution_date",
                        label: "Dist. Date"
                    },
                    {
                        key: "amount",
                        label: "Amount"
                    },
                    {
                        key: "distribution_amount",
                        label: "Dist. Amount"
                    },
                ],
            };
        },
        validations: {
            url: { required, url }
        },
        computed: {
            hasDividends(){
                return this.dividends.length > 0;
            },
            rows() {
                return this.dividends.length // Total no. of records
            }
        },
        methods: {
            async getDividendsList(){
                let that = this;
                that.isBusy = true;
                await that.axios.get(that.fetchLink)
                .then(function(response){
                    that.dividends = response.data;
                    that.isBusy = false;
                })
            },
            async scrape(){
                let that = this;
                that.$v.$touch();

                if (that.$v.$invalid) {
                    return;
                } else {
                    that.pending = true;
                    that.dividends = [];

                    let formData = new FormData();
                    formData.append('url', that.url);

                    await that.axios.post(that.scrapeLink, formData)
                    .then(function(res){
                        that.pending = false;
                        that.dividends = res.data;
                    })
                    .catch(function(error){
                        that.pending = false;
                        if (error.response) {
                            that.errors = error.response.data.errors;
                        }
                    });
                    
                    that.$root.$emit('bv::hide::popover');
                }
            },
            async downloadCSVFile(){
                let that = this;
                that.pending = true;
                await that.axios({
                    url: that.downloadLink,
                    method: "GET",
                    responseType: "blob", // important
                }).then((response) => {
                    that.pending = false;
                    // Service that handles ajax call
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "dividends.csv");
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    that.$root.$emit('bv::hide::popover');
                });
            },
            async deleteCSVFile(){
                let that = this;
                that.pending = true;
                await that.axios.post(that.deleteLink).then(function(){
                    that.pending = false;
                    that.dividends = [];
                    that.$root.$emit('bv::hide::popover');
                })
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1;
            }
        },
        mounted() {
            this.getDividendsList()
        },
    };

</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <FormValidation :errors="errors" />
                            
                        <!-- Scrape Form Section -->
                        <fieldset :disabled="pending">

                            <div class="row">

                                <div class="col-md-10">

                                    <div class="form-group">
                                        <input 
                                            type="text" 
                                            v-model="url" 
                                            class="form-control" :class="{ 'is-invalid': $v.url.$error }" 
                                            placeholder="Write or paste URL here" 
                                        />
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.url.required">Required</span>
                                            <span v-if="!$v.url.url">URL not valid</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2 text-center">
                                    <b-button
                                        @click="scrape"
                                        variant="primary"
                                        v-b-tooltip.hover
                                        title="Scrape URL">
                                        <i class="fas fa-microscope"></i>
                                    </b-button>
                                    <b-button
                                        v-if="hasDividends"
                                        @click="downloadCSVFile"
                                        variant="primary"
                                        v-b-tooltip.hover
                                        title="Download CSV File">
                                        <i class="fas fa-download"></i>
                                    </b-button>
                                    <ConfirmDelete
                                        v-if="hasDividends"
                                        :deleteFile="deleteCSVFile" 
                                    ></ConfirmDelete>
                                </div>

                            </div>

                        </fieldset>
                        <!-- Scrape Form Section End -->

                        <hr>

                        <!-- Table Search Section -->
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div id="tickets-table_filter" class="dataTables_filter">
                                    <label class="d-inline-flex align-items-center">
                                        <b-form-input v-model="filter" type="search"
                                            placeholder="Search ..." class="form-control form-control-sm ml-2">
                                        </b-form-input>
                                    </label>
                                </div>
                            </div>
                            <!-- End search -->
                        </div>
                        <!-- Table Search Section End -->

                        <!-- Table -->
                        <div>
                            <b-table 
                                primary-key="id"
                                selected-variant="light"
                                :tbody-transition-props="transProps"
                                :items="dividends" 
                                :fields="fields" 
                                responsive="sm" 
                                :per-page="perPage" 
                                :current-page="currentPage" 
                                :sort-desc.sync="sortDesc"
                                :filter="filter"
                                :filter-included-fields="filterOn"
                                @filtered="onFiltered"
                            ></b-table>

                            <div class="table_feedback">
                                <div class="empty_records text-center" v-if="rows == 0 && !isBusy">
                                    <p>No records to display.</p>
                                </div>
                                <div class="empty_filtered text-center" v-if="rows > 0 && totalRows == 0">
                                    <p>No result found.</p>
                                </div>
                                <div class="loading" v-if="isBusy">
                                    <Loading></Loading>
                                </div>
                            </div>
                            <!-- table_feedback -->
                        </div>
                        <!-- table end  -->

                    </div>
                </div>
            </div>
        </div>

    </Layout>
</template>

<style scoped>
    .form-group {
        margin-bottom: 0 !important;
    }
</style>
