<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  </head>
  <body>

<div id="app">
  <div>
    <select name="asset" v-model="itemSelect">
      <option v-for="(item, index) of items" :key="item.index" :value="item.name">{{ item.name }}</option>
    </select>
  </div>
  <div>
    <select name="use" v-model="typeSelect">
        <option v-for="(type, index) of getTypes" :key="type.index" :value="type.use">{{ type.use }}</option>
    </select>
  </div>
  <div>
    <select name="detail" v-model="detailSelect">
        <option v-for="(detail, index) of getDetails" :key="detail.index" :value="detail.label">{{ detail.label }}</option>
    </select>
  </div>
  <!-- <input type="text" name="life" value="1"> -->
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
    <script type="text/javascript">

var app = new Vue({
    el: "#app",
    data: function() {
        return {
            itemSelect: '建物',
            typeSelect: '木造・合成樹脂造のもの',
            detailSelect: '事務所用のもの',
            items: [
                { id: 0, name: '建物', types: [
                    { tId: 0, use: '木造・合成樹脂造のもの', details: [
                        {label: '事務所用のもの', life: 24 },
                        {label: '店舗用・住宅用のもの', life: 22 },
                        {label: '飲食店用のもの', life: 20 },
                        {label: '旅館用・ホテル用・病院用・車庫用のもの', life: 17 },
                        {label: '公衆浴場用のもの', life: 12 },
                        {label: '工場用・倉庫用のもの（一般用）', life: 15 },
                    ]},
                    { tId: 1, use: '木骨モルタル造のもの', details: [
                        {label: '事務所用のもの', life: 22 },
                        {label: '店舗用・住宅用のもの', life: 20 },
                        {label: '飲食店用のもの', life: 19 },
                        {label: '旅館用・ホテル用・病院用・車庫用のもの', life: 15 },
                        {label: '公衆浴場用のもの', life: 11 },
                        {label: '工場用・倉庫用のもの（一般用）', life: 15 },
                    ]},
                ]},
                { id: 1, name: '建物附属設備', types: [
                    { tId: 0, use: 'アーケード・日よけ設備', details: [
                        {label: '主として金属製のもの', life: 15 },
                        {label: 'その他のもの', life: 8 },
                    ]},
                    { tId: 1, use: '店舗簡易装備', details: [
                        {label: 'aaa', life: 3 },
                    ]},
                ]},
            ],
        }
    },
    computed: {
        getTypes: function() {
          // 選択中の情報を取得
               let itemName = this.itemSelect; // 1つ目のselect

        // items から選択中のnameを探す
        const itemResult = this.items.find((v) => v.name === itemName);

        // 2つ目のselectの初期値をセットする
        this.typeSelect = this.items[itemResult.id].types[0].use;

        // 2つ目のselectのoptionをセットする
        return this.items[itemResult.id].types;
      },
      getDetails: function() {
        // 選択中の情報を取得
        let itemName = this.itemSelect; // 1つ目のselect
        let typeName = this.typeSelect; // 2つ目のselect

        // items から選択中のuseを探す
        const itemResult = this.items.find((v) => v.name === itemName);
        const typeResult = this.items[itemResult.id].types.find((v) => v.use === typeName);

        // ３つ目のselectの初期値をセットする
        console.log(this.items[itemResult.id].types[typeResult.tId].details[0].label);
        this.detailSelect = this.items[itemResult.id].types[typeResult.tId].details[0].label;

        // 3つ目のselectのoptionをセットする
        return this.items[itemResult.id].types[typeResult.tId].details;

      }
    },
});

    </script>
  </body>
</html>
