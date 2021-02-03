<template>
  <div class="">
    <div style="margin-bottom:10px;">
      <label>设备：</label><select class="form-control" v-model="selectedDevice">
      <option v-for="device in devices" :value="device" >{{ device.name }}</option>
    </select>
    </div>
  <div class="panel panel-default panel-primary">
    <div class="panel-heading" >
        <h3 class="panel-title">基本信息</h3>
    </div>
    <div class="panel-body">
      <table class="table table-bordered table-striped table-hover">
        <tbody>
          <tr>
            <th>名称</th>
            <th>数值</th>
            <th>单位</th>
            <th>描述</th>
            <th>时间</th>
          </tr>
          <tr v-for="data in datas">
             <td>{{data.name}}</td>
             <td>{{data.value}}</td>
             <td>{{data.unit}}</td>
             <td>{{data.desc}}</td>
             <td>{{data.ts}}</td>
         </tr>
       </tbody>
     </table>
    </div>
  </div>
  <div class="panel panel-default panel-primary" v-for="(chart,type) in charts" >
    <div class="panel-heading" >
      <router-link :to="dataUrl + type">
        <!-- <h3 class="panel-title" style="color: #fff;text-align:right;">详细>></h3> -->
        <h3 class="panel-title" style="color: #fff;text-align:left;">{{ translations[type] + '(' + chart['yAxis']['title_v']['text'] + ')'}}</h3>
      </router-link>
    </div>
    <div class="panel-body">
      <highcharts :options="chart" ref="highcharts"></highcharts>
    </div>
  </div>
  <div v-if="selectedDevice!==null" class="panel panel-default panel-primary"  >
    <div class="panel-heading" >
      <router-link :to="dataUrl + 'image'">
        <!-- <h3 class="panel-title" style="color: #fff;text-align:right;">详细>></h3> -->
        <h3 class="panel-title" style="color: #fff;text-align:left;">图片</h3>
      </router-link>
    </div>
    <gallery :images="images"></gallery>
  </div>
</div>
</template>

<script>
  import api from '../api'
  import defaultOptions from '../chartOptions';
  import translations from '../translations'
  export default  {
    name: 'my-dashboard',
    data : function () {
      return {
        devices: [],
        selectedDevice: null,
        activeConfig: null,
        translations: translations,
        gallerise: [],
        charts: {},
        datas: [],
        images: {},
      }
    },
    computed: {
      dataUrl: function () {
        return this.selectedDevice ? '/device/' + this.selectedDevice.id + '/detail?&type=' : '';
      },

    },
    watch: {
      selectedDevice: function () {
        this.loadDeviceData(this.selectedDevice);
        this.activeConfig = _.last(this.selectedDevice.configs);
      }
    },
    methods: {
      load: function(){
        var query = {
          start_at: moment().subtract(2,'day').format('YYYY-MM-DD'),
          end_at: moment().add(1,'day').format('YYYY-MM-DD'),
          limit: 10,
        };
        var self = this;
        api.getDeviceData(this.selectedDevice, query, function (err, data) {
          console.log(data);
          _.forIn(data, function(v){
            var lastdata = _.last(v.data);
             console.log(lastdata);
            if(_.find(self.datas,function(d){
              return d.key == lastdata.key;
            })){
                _.set(_.find(self.datas, function(d){
                  return d.key == lastdata.key;
                }), 'value', lastdata.value);
            }
            else{
                  self.datas.push( lastdata);
            }
            var tmpdata = [];
            if(_.has(self.charts, lastdata.type)){
              let ct = self.$refs.highcharts;
              //tmpdata = _.clone(_.findLast(self.charts[lastdata.type].series,{'name': v.name}).data, false);
              //tmpdata.push([moment(lastdata.ts).toDate().getTime(), parseFloat(lastdata.value)]);
              //tmpdata = ct[0].chart.series[0].data;
              //tmpdata.push([moment(lastdata.ts).toDate().getTime(), parseFloat(lastdata.value)]);
              ct[0].chart.series[0].addPoint([moment(lastdata.ts).toDate().getTime(), parseFloat(lastdata.value)]);
              //console.log(tmpdata);
              //console.log(ct);
              //_.findLast(self.charts[lastdata.type].series,{'name': v.name}).data.push([moment(lastdata.ts).toDate().getTime(), parseFloat(lastdata.value)]);
            }
          });
        });

      },
      loadDeviceData: function(device) {
        var query = {
          start_at: moment().subtract(7,'day').format('YYYY-MM-DD'),
          end_at: moment().add(1,'day').format('YYYY-MM-DD'),
          limit: 10000,
        };
        var self = this;
        self.datas = [];
        api.getDeviceData(device , query, function (err, data) {
          _.forIn(data, function(v){
            self.datas.push( _.last(v.data));

          });
          self.charts = api.data2charts(data);
          self.images = _.filter(data, {type:'image'});
        });
      },
    },
    created:function () {
      var deviceId = this.$route.params.device;
      this.$http.get('/api/device?with=configs').then(function (res) {
        this.devices = res.body;
        this.selectedDevice = _.findLast(this.devices,function(d){return d.id == deviceId;});
      });
      var self = this;
      this.intervalLoad = setInterval(function(){self.load();}, 5000);
    }
  };

</script>
