<template>
  <div id="head" style="border-bottom: 1px solid #cacfda;margin-bottom: 1em;">
    <div >
      <div id="title" style="font-weight:bold; font-size: 16px;">
      <span>{{title}} </span>
      <a style="float:right;" @click="back()">
      <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> 返回</a>
        <!-- <h3>xxxxxx</h3> -->
      </div>
    </div>
    <div id="alert" class="alert" role="alert" ></div>

  </div>
</template>
<script >
  export default {
    props: ['title'],
    methods: {
      back: function () {
        // alert('ff')
        this.$router.go(-1);
      }
    }
  }
</script>
