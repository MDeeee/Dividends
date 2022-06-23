<script>
/*
* confirm-popover
*/
export default {
  props: {
    deleteFile: { type: Function },
  },
  data() { return { target_btn: '' }},
  methods: {
    targetBtn(e){
      this.target_btn = e.currentTarget;
    },
    onClose(){
      this.$root.$emit('bv::hide::popover');
    },
    onOk(){
      this.$root.$emit('bv::hide::popover');
      this.deleteFile();
    }
  }
};
</script>

<template>
  <div class="d-inline">
    <b-button
      id="delete-item-1"
      variant="primary"
      v-b-tooltip.hover
      title="Delete CSV File"
      @click="targetBtn"
    >
      <i class="fas fa-trash-alt"></i>
    </b-button>

    <b-popover
      target="delete-item-1"
      triggers="click blur"
      placement="left"
    >
      <div class="text-center">
        <p class="confirm_msg mb-0 pb-1">Confirm delete ?</p>
        <b-button @click="onClose" size="sm" variant="default">No</b-button>
        <b-button @click="onOk" size="sm" variant="primary" ref="confirm_del">Yes</b-button>
      </div>
    </b-popover>
  </div>
</template>