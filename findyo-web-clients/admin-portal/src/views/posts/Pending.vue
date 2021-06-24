<template>
  <div>
    <h5 class="card-title">Pending posts</h5>
    <h6 class="card-subtitle mb-5 text-muted">Approve or reject pending posts</h6>
    <div class="row">
      <div class="col-sm-12">
        <div class="list-group">
          <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">List group item heading</h5>
              <small>3 days ago</small>
            </div>
            <div class="d-flex flex-wrap w-100 justify-content-between align-items-center">
              <div class="d-flex flex-wrap align-items-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="#">Audio production</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Audio mixing</li>
                  </ol>
                </nav>
                <div class="btn-group btn-group-toggle ml-3" data-toggle="buttons">
                  <label class="btn btn-primary active">
                    <input type="radio" name="options" id="option1" disabled autocomplete="off" checked> Consumer
                  </label>
                  <label class="btn btn-success">
                    <input type="radio" name="options" id="option2" autocomplete="off"> Freelancer
                  </label>
                </div>
              </div>
              <div class="mt-3 d-flex flex-wrap justify-content-end">
                <div class="btn-group btn-group-lg" role="group" aria-label="...">
                  <button type="button" class="btn btn-success" @click="approveModalState(true)">Approve</button>
                  <button type="button" class="btn btn-warning">Flag</button>
                  <button type="button" class="btn btn-danger" @click="rejectModalState(true)">Reject</button>
                </div>
              </div>
            </div>
            <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
            <small>Gayan Sandamal. / Asgiriya, Gamapaha</small>
            <div class="mt-3 d-flex flex-wrap w-100 justify-content-start">
              <img src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_17334aad411%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_17334aad411%22%3E%3Crect%20width%3D%22200%22%20height%3D%22200%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2274.4296875%22%20y%3D%22104.36875%22%3E200x200%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" class="rounded mr-4 mb-4" alt="...">
            </div>  
          </div>
        </div>
      </div>
    </div>
    <YesNoModal v-if="rejectModal" :content="{title: 'Reject post', msg: 'Do you want to reject this post?', yes: 'Reject', no: 'Cancel', type: 'danger'}" @ok-delete="reject" @cancel="rejectModalState(false)"/>
    <YesNoModal v-if="approveModal" :content="{title: 'Approve post', msg: 'Do you want to approve this post?', yes: 'Approve', no: 'Cancel', type: 'success'}" @ok-delete="reject" @cancel="approveModalState(false)"/>
  </div>
</template>

<script>
export default {
  name: 'pending',
  data() {
    return {
      rejectModal: false,
      approveModal: false
    }
  },
  components: {
    YesNoModal: () => import('@/components/Modals/YesNoModal')
  },
  methods: {
    rejectModalState(state) {
      this.rejectModal = state
    },
    approveModalState(state) {
      this.approveModal = state
    },
    reject() {

    }
  }
}
</script>