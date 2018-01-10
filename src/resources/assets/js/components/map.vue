<template>
    <div>
    <a style="float:right;" @click="load()"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> 刷新</a>
    <div id="demoComponent" class="demo-component" style="height: 540px;">
      <baidu-map class="bm-view" :zoom="zoom" :center="center" :scroll-wheel-zoom="true">
        <bm-scale anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-scale>
        <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
        <bm-navigation anchor="BMAP_ANCHOR_TOP_LEFT"></bm-navigation>
        <bm-marker v-for="marker in markers" :position="marker.position" :click="marker.events.click" :title="marker.title" :dragging="marker.draggable"></bm-marker>
      </baidu-map>

<!--         <button type="button" name="button" v-on:click="toggleVisible">toggle first marker</button>
        <button type="button" name="button" v-on:click="changePosition">change position</button>
        <button type="button" name="button" v-on:click="chnageDraggle">change draggle</button>
        <button type="button" name="button" v-on:click="addMarker">add marker</button>
        <button type="button" name="button" v-on:click="removeMarker">remove marker</button>
 -->    </div>
 </div>
</template>
<script >
import Vue from "vue";
var bus = new Vue();
export default {

  data() {
    return {
      zoom: 5,
      // center: [121.5273285, 31.21515044],
      center: {lng:116.405285,lat:39.904989},
      markers: [

      ]
    };
  },
  created: function () {
    this.load();
    let that  = this;
    this.intervalLoad = setInterval(function(){that.load();}, 60);
  },
  watch: {
    '$route': 'load',
  },
  methods: {
    load: function () {
  	var self = this;
      this.$http.get('/api/device/').then(function (res) {
        var devices = res.body;
        this.markers = _.map(devices, function (v) {
          return {
            position: {lng:v.lon, lat:v.lat},
            events: {
              click: () => {
                self.$router.push('/device/'+v.id+'/dashboard');
                // self.$router.push('/station');
              },
            },
            visible: true,
            // content: 'xxxx',
            title: "名称："+v.name+"\nsn:"+v.sn+"\n经度："+v.lon+"\n纬度："+v.lat+"\n高度:"+v.alt,
          };
        });
        // self.center = [stations.items[0].lon,stations.items[0].lat];
      })
    },
    changePosition() {
      let position = this.markers[0].position;
      this.markers[0].position = [position[0] + 0.002, position[1] - 0.002];
    },
    chnageDraggle() {
      let draggable = this.markers[0].draggable;
      this.markers[0].draggable = !draggable;
    },
    toggleVisible() {
      let visableVar = this.markers[0].visible;
      this.markers[0].visible = !visableVar;
    },
    addMarker() {
      let marker = {
        position: [121.5273285 + (Math.random() - 0.5) * 0.02, 31.21515044 + (Math.random() - 0.5) * 0.02],
        title: 'xxx',
        content: '<h3>xxxx</h3>',
      };
      this.markers.push(marker);
    },
    removeMarker() {
      if (!this.markers.length) return;
      this.markers.splice(this.markers.length - 1, 1);
    }
  }
};
</script>
