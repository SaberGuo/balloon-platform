<template>
  <div class="card">
    <!-- for Vue 2.0 -->
    <div style="margin-bottom: 10px;">
      <form class="form">
          <div class="form-group">

          <label>设备：</label>
          <select class="form-control col-xs-12 col-md-6 col-lg-3" v-model="selectedDevice">
            <option v-for="device in devices" :value="device">{{device.name}}</option>
          </select>
          </div>
          <div v-if="configDatas" class="form-group">
          <label>数据：</label>
          <select class="form-control col-xs-12 col-md-6 col-lg-3" v-model="selectedData">
            <option v-for="(v,k) in configDatas" :value="k">{{v.name}}</option>
          </select>
          </div>
        <div class="form-group">
          <label style="width: 100%;">时间：</label>
          <date-picker id="start_at" :date="start_at" :option="dp.option" :limit="limit"></date-picker> to
          <date-picker id="end_at" :date="end_at" :option="dp.option" :limit="limit"></date-picker>
        </div>

        <button type="button" class="btn btn-primary" @click="loadDeviceData(selectedType)">确定</button>
        <div class="btn-group">
          <button type="button" class="btn btn-default" v-for="shortcut in shortcuts" @click="loadDeviceData(selectedType, shortcut.offset)" style="margin-right: 5px;" >{{shortcut.name}}</button>
        </div>
      </form>
    </div>
    <highcharts v-for="chart in charts" :options="chart" ref="highcharts"></highcharts>
  </div>
</template>

<script>
  import myDatepicker from 'vue-datepicker'
  import api from '../api'
  export default {
    data () {
      return {
        devices: [],
        selectedDevice: null,
        dp: configs.datepicker,
        start_at: {time:moment().subtract(1,'day').format('YYYY-MM-DD')},
        end_at: {time:moment().format('YYYY-MM-DD')},
        charts: {},
        limit: [{
          type: 'fromto',
          from: '2016-01-01',
          // to: '2017-01-01',
          to: moment().add(1,'day').format('YYYY-MM-DD'),
        }],
        images: {},

        selectedData: null,
        configDatas:{},
        shortcuts: [
        {name: '1天',offset: 1,},
        {name: '3天',offset: 3,},
        {name: '7天',offset: 7,},
        {name: '15天',offset: 15,},
        {name: '30天',offset: 30,},
        ],
      }
    },
    components: {
      'date-picker': myDatepicker
    },
    created () {
      this.$http.get('/api/device?with=config').then(function (res) {
        // var d1 = res.body.items[0];
        // var d2 = _.cloneDeep(d1);
        // d2.name += '复制';
        // this.devices = [d1,d2];
        this.devices = res.body;
        this.selectedDevice =  this.devices[0];
        this.configDatas = this.selectedDevice.config.data;
        this.loadDeviceData(data);

      })
      var data = this.$route.query.data;
      this.selectedData = data;
    },
    methods: {
      loadDeviceData (data, offset) {
        this.images = {};
        this.charts = {};
        var query = {};
        if (offset) {
          query.start_at = moment().subtract(offset,'day').format('YYYY-MM-DD');
          query.end_at = moment().add(1,'day').format('YYYY-MM-DD');
          this.start_at = {time: query.start_at};
          this.end_at = {time: moment().format('YYYY-MM-DD')};
        } else {
          query.start_at = this.start_at.time;
          query.end_at = moment(this.end_at.time).add(1,'day').format('YYYY-MM-DD');
        }
        var self = this;
        api.getDeviceData(this.selectedDevice, query, function (err, data) {
          if (type == 'image') {
            self.images = _.filter(data, {type});
            console.log(self.images)
          } else {
            self.charts = api.data2charts(_.filter(data, {type: type}));
          }
        })
      },
    }
  }
</script>
