<script>
import ICountUp from 'vue-countup-v2';
/**
 * Dashboard Portfolios Component
 */
export default {
  props: {
    data: {type: Array},
  },
  components: {
    ICountUp
  },
  data() {
    const easingFn = function (t, b, c, d) {
      var ts = (t /= d) * t;
      var tc = ts * t;
      return b + c * (tc + -3 * ts + 3 * t);
    }
    return {
      options: {
        useEasing: true,
        useGrouping: true,
        duration: 5,
        easingFn,
      }
    };
  }
};
</script>
<template>
  <div>
    <div class="row">
        <div class="col-md-3" v-for="(dividend, index) in data" :key="dividend.id">
          <div class="card">
            <div class="card-body">
              <div class="media">
                <div class="media-body overflow-hidden">
                  <p class="text-truncate font-size-16 mb-2">{{ dividend.company }}</p>
                  <h4 class="mb-0">
                    <ICountUp
                      :delay="500"
                      :endVal="Number(dividend.distribution_amount)"
                      :options="options"
                    />
                  </h4>
                </div>
                <div class="text-primary">
                  <i 
                    class="font-size-24" 
                    :class="{'ri-stack-line': index % 2 === 0, 'ri-stack-fill': index % 2 !== 0 }"
                  ></i>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- end row -->
  </div>
</template>