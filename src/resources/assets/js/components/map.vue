<template>
    <div>
      <button style="float:right; margin-bottom:10px;" type="submit" @click.prevent="load()" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> 刷新</button>
    <!--<a style="float:right;" @click="load()"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> 刷新</a>-->
    <div id="demoComponent" class="demo-component" style="height: 540px;">
      <baidu-map class="map" :zoom="zoom" :center="center" :scroll-wheel-zoom="true">
        <bm-scale anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-scale>
        <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
        <bm-navigation anchor="BMAP_ANCHOR_BOTTOM_LEFT"></bm-navigation>
        <bm-marker v-for="marker in markers" :position="marker.position" @click="marker.events.click" :title="marker.title" :dragging="marker.draggable"></bm-marker>
        <bm-polyline v-for="path in pathes" :path="path.datas" stroke-color="red" :stroke-weight="2"></bm-polyline>
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
import api from '../api';
var bus = new Vue();
export default {

  data() {
    return {
      zoom: 5,
      // center: [121.5273285, 31.21515044],
      center: {lng:116.405285,lat:39.904989},
      markers: [
      ],
      pathes: [
      ]
    };
  },
  created: function () {
    this.load();
    let that  = this;
    this.intervalLoad = setInterval(function(){that.refresh();}, 6000);
  },
  watch: {
    '$route': 'load',
  },
  methods: {
    refresh: function(){
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
        var query = {
          start_at: moment().format('YYYY-MM-DD'),
          end_at: moment().add(1,'day').format('YYYY-MM-DD'),
          limit: 10000,
        };
        _.map(self.pathes, function(p){
          api.getDeviceData(p.device , query, function (err, data) {
            if(data.length > 0){
              var lon = _.last(_.last(_.filter(data, {type:'lon'})).data);
              var lat = _.last(_.last(_.filter(data, {type:'lat'})).data);
              if(p.datas.length>0 && lon!=_.last(p.datas).lng && lat!=_.last(p.datas).lat){
                p.datas.push({lng: lon.value, lat: lat.value});
              }
            }

          });
        });
      });
    },
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
        var query = {
          start_at: moment().subtract(10,'day').format('YYYY-MM-DD'),
          end_at: moment().add(1,'day').format('YYYY-MM-DD'),
          limit: 10000,
        };
        this.pathes = _.map(devices, function(v){
          var pDatas = [];
          api.getDeviceData(v, query, function (err, data) {
            console.log(data);
            if(data.length>0){
              var lons = _.last(_.filter(data, {type:'lon'})).data;
              var lats = _.last(_.filter(data, {type:'lat'})).data;

              for(var i=0; i<lons.length;++i){
                pDatas.push({lng: lons[i].value, lat: lats[i].value});
              }
            }


          });

          console.log(pDatas);
          return {
            device: v,
            datas: pDatas
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
<style>
/* 地图容器必须设置宽和高属性 */
.map {
  width: auto;
  height: 540px;
}
</style>
