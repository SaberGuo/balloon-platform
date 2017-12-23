<template>
<div class="container">
  <div class="timeline">
    <div v-for="device in devices">
      <div v-for="gallery in device.galleries" class="clearfix">
        <p class="date">{{gallery.date}} {{device.name}}</p>
        <div class="gallery row" style="margin-right: 5%">
          <span class="dot"></span>
          <div v-for="image in gallery.images" class="col-xs-12 col-md-6 col-lg-3">
            <div class="thumbnail">
              <img v-img:group :src="'http://thc-platfrom-storage.b0.upaiyun.com' + image.value" alt="alt" >
              <div><span>{{image.ts}}</span><span style="float: right;" @click="download" ><img height="16px" width="16px" src="/image/dl.png"></span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <viewer :images="testImages" index="1"></viewer> -->
  </div>
</div>
</template>

<script >
  export default {
    props: ['images'],
    data: function () {
      return {
        testImages: [
        {src: '/image/dl.png'},
        {src: '/image/dl.png'},
        {src: '/image/dl.png'},
        {src: '/image/dl.png'},
        ]
      };
    },
    computed: {
      devices: function () {
        return _.map(this.images, function (v,k) {
          var res = {};
          res.galleries = _.reduce(v.data, function (memo, value) {
            var ts = moment(value.ts).format('YYYY-MM-DD');
            memo[ts] = memo[ts] || {date: ts, images:[]};
            memo[ts].images.push(value)
            return memo;
          },{});
          res.name = v.name;
          return res;
        })
      }
    },
    methods: {
      download: function (e) {
        var url = $(e.target).parent().parent().siblings('img').attr('src');
        var a = $("<a>")
        .attr("href", url)
        .attr("download", "img.png")
        .appendTo("body");
        a[0].click();
        a.remove();
      }
    },
    created: function () {
    }
  }
</script>
