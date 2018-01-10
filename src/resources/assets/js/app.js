
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueHighcharts from 'vue-highcharts';
import highcharts from 'highcharts';
import VueRouter from 'vue-router';
import routes from './routes';
import AMap from 'vue-amap';
import BaiduMap from 'vue-baidu-map';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
import VueImg from 'v-img';



Vue.use(BaiduMap, {
  // ak 是在百度地图开发者平台申请的密钥 详见 http://lbsyun.baidu.com/apiconsole/key */
  ak: 'upgAVMAni6rNgp1bM0xXU6LbCEg1lwrn'
})

Vue.use(AMap);
Vue.use(VueImg);

AMap.initAMapApiLoader({
  key: '5d7513412b713a3d6daf2fa69bc31c6e',
  plugin: ['AMap.Autocomplete', 'AMap.PlaceSearch', 'AMap.Scale', 'AMap.OverView', 'AMap.ToolBar', 'AMap.MapType', 'AMap.PolyEditor', 'AMap.CircleEditor']
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 highcharts.setOptions({
   global: {
     useUTC: false
   }
 });

Vue.use(VueHighcharts);
Vue.use(VueRouter);

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('gallery', require('./components/gallery.vue'));
const router = new VueRouter({
  // mode: 'history',
  routes
})

window.thc.can = function(permission_name) {
  console.log('app.js window.thc.can');

  for (var r in thc.user.roles) {
    var role = thc.user.roles[r]
    for (var p in role.permissions) {
      var permission = role.permissions[p]
      if (permission.name == permission_name) {
        return true;
      }
    }
  }
  return false;
}

const app = new Vue({
    el: '#app',
    data: {
      loading: false,
      user: thc.user,
    },
    methods: {
      canSysWrite: function () {
        return thc.can('sys_w');
      },
      canAppRead: function(){
        return thc.can('app_r');
      },
      canAppWrite: function(){
        return thc.can('app_w');
      }
    },
    components: {
      PulseLoader
    },
    router
});
