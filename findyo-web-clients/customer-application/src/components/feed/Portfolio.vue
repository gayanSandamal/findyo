<template>
  <div>
    <add-post/>
    <ul>
      <li>
        <Post/>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'timeline',
  data () {
    return {}
  },
  props: {
    user: undefined
  },
  components: {
    'add-post': () => import ('@/components/feed/AddPost'),
    Post: () => import ('@/components/feed/Post')
  }
}
</script>

<style scoped>
</style>
