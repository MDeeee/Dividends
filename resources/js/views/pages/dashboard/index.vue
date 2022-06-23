<script>
import Layout from "../../layout/mainLayout.vue";
import PageHeader from "@/components/page-header";
import Dashboard1 from "./Dashboard1";
import Dashboard2 from "./Dashboard2";
/*
 * Dashboard page
 */
export default {
  page: {
    title: "Dashboard",
    meta: [{ name: "description" }],
  },
  components: {
    Layout,
    PageHeader,
    Dashboard1,
    Dashboard2,
  },
  data() {
    return {
      title: "Dashboard",
      dashboard1: [],
      dashboard2: [],
    };
  },
  methods: {
    async getDashboard1Data(){
      let that = this;
      await that.axios.get("dashboard").then(function (response) {
        that.dashboard1 = response.data.dashboard1;
        that.dashboard2 = response.data.dashboard2;
      });
    }
  },
  created() {
    this.getDashboard1Data();
  },
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="[]" />
    <div class="row">
      <div class="col-md-12">
        <Dashboard1 :data="dashboard1"></Dashboard1>
        <Dashboard2 :data="dashboard2"></Dashboard2>
      </div>
    </div>
  </Layout>
</template>