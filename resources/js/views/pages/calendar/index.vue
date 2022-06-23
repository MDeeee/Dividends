<script>
import Layout from "../../layout/mainLayout";
import PageHeader from "@/components/page-header";

import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import bootstrapPlugin from "@fullcalendar/bootstrap";
import listPlugin from "@fullcalendar/list";
/**
 * Chart component
 */
export default {
  page: {
    title: "Calendar",
    meta: [{ name: "description" }],
  },
  components: { FullCalendar, Layout, PageHeader },
  data() {
    return {
      title: "Calendar",
      items: [],
      fetchLink: 'dividends/list',
      dividends: [],
      calendarOptions: {
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
        },
        plugins: [
          dayGridPlugin,
          timeGridPlugin,
          interactionPlugin,
          bootstrapPlugin,
          listPlugin,
        ],
        initialView: "dayGridMonth",
        themeSystem: "bootstrap",
        editable: false,
        droppable: false,
        eventResizableFromStart: false,
        eventClick: this.editEvent,
        weekends: true,
        selectable: false,
        selectMirror: false,
        dayMaxEvents: true,
        displayEventTime: false,
        eventDisplay: 'block',
        events: [],
      },
      total_revenue: 0,
      pending: false,
      showModal: false,
      eventModal: false,
      edit: {},
      editevent: {
        editTitle: "",
        editcategory: "",
        extendedProps: ""
      },
    };
  },
  methods: {
    async getDividendsList(){
        let that = this;
        that.pending = true;
        await that.axios.get(that.fetchLink).then(function(response){
            that.dividends = response.data;
            that.createEventsArray()
            that.pending = false;
        })
    },

    createEventsArray(){
      if (this.dividends.length > 0) {
        const newEventArr = [];
        
        this.dividends.forEach(dividend => {

          this.total_revenue += Number(dividend.distribution_amount);

          const item = [];

          item['id'] = dividend.id;
          item['title'] = dividend.company;
          item['start'] = this.getEventStartDate(dividend.distribution_date);
          item['className'] = this.getEventClassName(dividend.distribution_amount);

          item['extendedProps'] = [];
          item['extendedProps']['id'] = dividend.id;
          item['extendedProps']['announced_date'] = dividend.announced_date;
          item['extendedProps']['company'] = dividend.company;
          item['extendedProps']['distribution_method'] = dividend.distribution_method;
          item['extendedProps']['distribution_date'] = dividend.distribution_date;
          item['extendedProps']['amount'] = dividend.amount;
          item['extendedProps']['distribution_amount'] = dividend.distribution_amount;

          newEventArr.push(item);
        });

        this.calendarOptions.events = newEventArr;
      }
    },
    getEventClassName(distribution_amount){
      const owned_distribution = 'bg-success text-white';
      const non_owned_distribution = 'bg-primary text-white';
      return distribution_amount > 0 ? owned_distribution : non_owned_distribution;
    },

    getEventStartDate(distribution_date){
      return distribution_date === "N/A" ? new Date() : new Date(distribution_date)
    },
    /**
     * Modal open for edit event
     */
    editEvent(info) {
      this.edit = info.event;
      this.editevent.editTitle = this.edit.title;
      this.editevent.extendedProps = this.edit.extendedProps;
      this.eventModal = true;
    },
    closeModal() {
      this.eventModal = false;
    },
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
            <h5>The total revenue from dividends in 2022: <span class="font-weight-bold">{{ total_revenue }}</span></h5>
            <div class="app-calendar">
              <FullCalendar
                ref="fullCalendar"
                :options="calendarOptions"
              ></FullCalendar>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <b-modal
      v-model="eventModal"
      title="View Company"
      title-class="text-black font-18"
      hide-footer
      body-class="p-0"
    >
      <div class="p-3">
        <div class="table-responsive">
          <table class="table">
            <tbody>
              <tr>
                <th>Company</th>
                <td>{{ editevent.extendedProps.company }}</td>
              </tr>
              <tr>
                <th>Announced Date</th>
                <td>{{ editevent.extendedProps.announced_date }}</td>
              </tr>
              <tr>
                <th>Distribution Method</th>
                <td>{{ editevent.extendedProps.distribution_method }}</td>
              </tr>
              <tr :class="{ 'bg-warning': editevent.extendedProps.distribution_date === 'N/A' }">
                <th>Distribution Date</th>
                <td>{{ editevent.extendedProps.distribution_date }}</td>
              </tr>
              <tr>
                <th>Amount</th>
                <td>{{ editevent.extendedProps.amount }}</td>
              </tr>
              <tr>
                <th>Distribution Amount</th>
                <td>{{ editevent.extendedProps.distribution_amount }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="text-right p-3">
        <b-button variant="light" @click="closeModal">Close</b-button>
      </div>
    </b-modal>
  </Layout>
</template> 
