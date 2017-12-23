<template>
    <div class="">
      <table class="table table-bordered table-striped table-hover">
        <tbody>
          <tr>
            <th>名称</th>
            <th>序列号</th>
            <th>经度</th>
            <th>纬度</th>
            <th>高度</th>
            <th>操作</th>
          </tr>
          <tr v-for="device in devices">
             <td>{{device.name}}</td>
             <td>{{device.sn}}</td>
             <td>{{device.lon}}</td>
             <td>{{device.lat}}</td>
             <td>{{device.alt}}</td>
             <td>
               <router-link :to="'/device/' + device.id"><img height="20" src="/image/info.png" class="signal"></router-link>
               <router-link :to="'/device/'+device.id+'/dashboard'"><img height="20" src="/image/dashboard.png" class="signal"></router-link>
             </td>
         </tr>
         <tr v-if="editable">
           <td style="text-align: right;" colspan="8">
             <button class="btn btn-primary" @click="go">添加</button>
           </td>
         </tr>
       </tbody>
     </table>
   </div>
</template>
<script >
    export default {
        data: function () {
            return {
                editable: thc.can('sys_w'),
                devices: [],
            }
        },
        created: function () {
            var self = this;
            this.$http.get('/api/device').then(function (res) {
                this.devices = res.body;
            })
        },
        methods:{
          go: function () {
              this.$router.push({name:'device', params:{station:this.station_id, device:0}, query: {op:'create'}})
          },

        }
    }
</script>
