
import dashboard from './components/dashboard.vue'
import devices from './components/devices.vue'
import analyze from './components/analyze.vue'
import map from './components/map.vue'
import header from './components/header.vue'

const routes=[{
  path: '/',
  redirect: '/device/1/detail'
},
 {
  path: '/map',
  components: {
    default: map,
    header: header,
  },
  props: {
    header: {title: '站点地图'},
  }
}, {
  path: '/device/:device/dashboard',
  components: {
    default: dashboard,
    header: header,
  },
  props: {
    header: {title: '设备数据'}
  }
},{
  name: 'devices',
  path: '/device',
  components: {
    default: require('./components/devices.vue'),
    header: header,
  },
  props: {
    header: {title: '设备列表'}
  }
},{
  name: 'device',
  path: '/device/:device',
  components: {
    default: require('./components/device.vue'),
    header: header,
  },
  props: {
    header: {title: '设备信息'}
  }
},
{
  path: 'device/:device/detail',
  components: {
    default: require('./components/analyze.vue'),
    header: header,
  },
  props: {
    header: {title: '数据分析'}
  }
},  ]
export default routes;
