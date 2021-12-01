<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  </head>
  <body>
    <!-- <tr v-for="(list, i) in lists" :key="i" v-cloak> -->
    <div id="app">
      <ul>
        <li v-for="(list, index) in stores"　v-bind:key="index">
          <div>{{ list.name }}</div>
          <select v-model="dataStore1[index]" v-on:change="showValue1(index, dataStore1[index])">
            <option v-for="item in list.con" v-bind:value="item">{{ item.name }}</option>
          </select>
          <div>{{ dataStore1[index].value }}</div>
        </li>
      </ul>
      <ul>
        <li v-for="(list, index) in stores"　v-bind:key="index">
          <div>{{ list.name }}</div>
          <select v-model="dataStore[index]" v-on:change="showValue(index, dataStore[index])">
            <option v-for="item in list.con" v-bind:value="item">{{ item.name }}</option>
          </select>
          <div>{{ dataStore[index].value }}</div>
        </li>
      </ul>
    </div>
    <!-- </tr> -->
    <script type="text/javascript">
const app = new Vue({
  el: '#app',
  data() {
    return {
      stores: [
        {
          name: "パン",
          con: [{
            name: "食パン",
            value: 20
            },
            {
              name: "メロンパン",
              value: 40
            }
          ]
        },
        {
          name: "家賃",
          con: [{
            name: "1K",
            value: 10
            },
            {
              name: "1LDK",
              value: 100
            }
          ]
        },
      ],
      dataStore: [],
      dataStore1: [],
    }
  },

  created: function(){
    for(var tmp in this.stores){
      this.dataStore.push({ name: "", value: 0 });
      this.dataStore1.push({ name: "", value: 0 });
    }
  },

  methods: {
    showValue: function (name, val) {
      var clone = { ...this.dataStore };
      clone[name] = val;
      this.dataStore = clone;
    },
    showValue1: function (name, val) {
      var clone = { ...this.dataStore1 };
      clone[name] = val;
      this.dataStore1 = clone;
    }
  }
});
    </script>
  </body>
</html>
