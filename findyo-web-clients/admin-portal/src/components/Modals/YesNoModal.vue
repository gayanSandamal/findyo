<template>
  <div class="modal block">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" v-text="content.title"></h5>
          <button type="button" class="close" @click.stop="$emit('cancel')">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p v-html="content.msg"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :class="okButtonType" v-text="content.yes" @click.stop="$emit('ok-delete')"></button>
          <button type="button" class="btn btn-secondary" v-text="content.no" @click.stop="$emit('cancel')">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'yesno-modal',
  props: {
    content: {
      type: Object,
      default() {
        return {
          title: 'Modal title',
          msg: 'Modal body text goes here.',
          yes: 'Save changes',
          no: 'Close',
          type: 'primary'
        }
      }
    }
  },
  computed: {
    okButtonType() {
      let type = 'btn-primary' 
      if (this.content.type === 'primary') {
        type = 'btn-primary'
      } else if (this.content.type === 'danger') {
        type = 'btn-danger'
      } else if (this.content.type === 'success') {
        type = 'btn-success'
      }
      return type
    }
  }
}
</script>

<style scoped>
.block {
  display: block !important;
}
.modal {
  background-color: rgba(0,0,0,0.5);
}
</style>