<template>
<div v-if="device">
    <div id='qlink'>
      <router-link :to="devices_url">
        <img src="/image/tableg.png">
        设备列表
      </router-link>
    </div>
<!-- 设备基本信息 -->
    <div class="panel panel-default panel-primary">
        <div class="panel-heading" >
            <h3 class="panel-title">基本信息</h3>
        </div>
        <div class="panel-body">
            <form>
                <div class="form-group">
                    <label for="name">name</label>
                    <input :disabled="!editing" type="text" v-model="device.name" class="form-control" id="name" placeholder="name">
                </div>
                <div class="form-group">
                    <label for="sn">sn</label>
                    <input :disabled="!editing" type="text" v-model="device.sn" class="form-control" id="sn" placeholder="sn">
                </div>
                <template v-if="editable">
                    <button v-if="editing" type="submit" @click.prevent="save()" class="btn btn-primary">确定</button>
                    <button v-if="editing" type="submit" @click.prevent="cancel_device()" class="btn btn-default">取消</button>
                    <button v-if="!editing" type="submit" @click.prevent="editing = !editing;isCreate = false;" class="btn btn-primary">修改</button>
                    <!-- <button v-if="!editing" type="submit" @click.prevent="create" class="btn btn-default">创建</button> -->
                    <button v-if="!editing" type="submit" @click.prevent="remove" class="btn btn-danger">删除</button>
                </template>
            </form>
        </div>
    </div>
<!-- 设备配置 -->
    <div v-if="device.id" class="panel panel-default panel-primary">
        <div class="panel-heading" >
            <h3 class="panel-title">配置</h3>
        </div>
        <div class="panel-body">
            <form>
                <div class="form-group">
                </div>
                <div class="form-group">
                    <label for="data">Data</label>
                    <div style="overflow:auto">
                        <table v-if="activeConfig" class="table table-bordered table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th>key</th>
                                    <th>名称</th>
                                    <th>单位</th>
                                    <th>类型</th>
                                    <th>最大值</th>
                                    <th>最小值</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                <tr v-for="(v,k) in activeConfig.data">
                                    <template v-if="v !== null">
                                        <td>{{k}}</td>
                                        <td><input type="text" :disabled="!editing_config" v-model="activeConfig.data[k].name" class="form-control" :id="k" placeholder="k" ></td>
                                        <td><input type="text" :disabled="!editing_config" v-model="activeConfig.data[k].unit" class="form-control" :id="k" placeholder="单位" ></td>
                                        <td><input type="text" :disabled="!editing_config" v-model="activeConfig.data[k].type" class="form-control" :id="k" placeholder="类型" ></td>
                                        <td><input type="text" :disabled="!editing_config" v-model="activeConfig.data[k].max_v" class="form-control" :id="k" placeholder="最大值"></td>
                                        <td><input type="text" :disabled="!editing_config" v-model="activeConfig.data[k].min_v" class="form-control" :id="k" placeholder="最小值" ></td>
                                        <td><input type="text" :disabled="!editing_config" v-model="activeConfig.data[k].desc" class="form-control" :id="k" placeholder="备注" ></td>
                                        <td style="vertical-align: middle;"><div v-if="editing_config" @click="removeData('data', k)"><img width="16px" height="16px" src="/image/remove.png"></div></td>
                                    </template>
                                </tr>
                                <tr><td colspan="8">
                                    <div style="text-align: right;" ><img v-if="editing_config" @click="addDataConfig('data')" width="16px" height="16px" src="/image/add.png"></div>
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <template v-if="editable">
                    <button type="submit" v-if="editing_config" @click.prevent="addConfig()" class="btn btn-default btn-primary">确认</button>
                    <button type="submit" v-if="editing_config" @click.prevent="editing_config = !editing_config" class="btn btn-default btn-default">取消</button>
                    <button type="submit" v-if="!editing_config" @click.prevent="editing_config = !editing_config" class="btn btn-default btn-primary">修改</button>
                </template>
            </form>
        </div>
    </div>
</div>
</template>

<script >
import utils from '../utils'
import bootbox from 'bootbox'
export default {
    data: function() {
        return {
            device: null,
            editing: false,
            editing_config: false,
            editable: thc.can('sys_w'),
            isCreate: false,
            fillable: ['name', 'sn'],
            devices_url: '/device'
        };
    },
    computed: {
        activeConfig: function() {
            var res = _.last(this.device.configs);

            return res;
        },
        apiURI: function() {
            return '/api' + this.$route.path;
        },
    },
    created: function() {
        if (this.$route.query.op == 'create') {
            this.create();
        } else {
            this.load();
        }
    },
    watch: {
        '$route': 'load',
    },
    methods: {
        load: function() {
            this.$http.get(this.apiURI + '?with=configs').then(function(res) {
                if (res.body.configs.length == 0) {
                    res.body.configs.push({data:{}});
                }
                this.device = res.body;
            })
        },
        update: function() {
            this.$http.put(this.apiURI, _.pick(this.device, this.fillable), {params:{alert:'更新设备信息'}}).then(function() {
                this.editing = !this.editing;
            });
        },
        cancel_device: function() {
            if(this.$route.params.device == '0'){
                this.$router.go(-1);
            }
            else{
                this.editing = !this.editing;
            }
        },
        save: function() {
            if (this.isCreate) {
                this.$http.post('/api/device', this.device, {params:{alert:'修改设备信息'}}).then(function(res) {
                    this.editing = !this.editing;
                    this.$router.push({
                        name: 'device',
                        params: {
                            device: res.body.id,
                        }
                    })
                })
            } else {
                this.update();
            }
        },
        remove: function() {
            var self = this;
            bootbox.confirm('确认删除？', function(result) {
                if (result) {
                    self.$http.delete(self.apiURI).then(function() {
                      self.$router.push({
                          name: 'devices',
                      });

                    })
                }
            })
        },
        create: function() {
            this.device = _.reduce(this.device, function(carry, v) {
                carry[v] = '';
                return carry;
            }, {});
            this.isCreate = true;
            this.editing = !this.editing;
        },
        addConfig: function() {
            var self = this;
            var data = _.reduce(this.activeConfig.data, function(res, v, k) {
                res[k] = v;
                return res;
            }, {})

            var body = {
                data,
            }
            this.$http.post(this.apiURI + '/config', body, {params:{alert:'保存设备配置'}}).then(function(res) {
                this.device.configs.push(res.body)
                this.editing_config = !this.editing_config
            })
        },
        removeData: function(type, k) {
            Vue.delete(this.activeConfig[type], k);
        },
        addDataConfig: function(type) {
            var self = this;
            bootbox.prompt("输入配置项key：", function(result) {
                if (!result || self.activeConfig[type][result]) return;
                Vue.set(self.activeConfig[type], result, type == 'data' ? {
                    type: '',
                    unit: '',
                    name: '',
                    max_v: '',
                    min_v: '',
                    desc: '',
                } : {

                });
            });
        }
    }
}
</script>
