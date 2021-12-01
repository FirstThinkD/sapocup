<!DOCTYPE html>
<html>
  <head>
  </head>
<script src="https://unpkg.com/vue"></script>

<div id="app">
<tr v-for="(list, i) in lists" :key="i" v-cloak>
  <!-- Categories -->
  <label for="category">Category</label>
  <select name="category" v-model="selectedCategory[0]" v-on:change="fetchTags(0)">
    <option v-for="category in categories" v-bind:value="category.id">
      {{ category.name }}
    </option>
  </select>
  <br>
  <!-- Tags -->
  <div>
    <label for="tag">Tag</label>
    <select name="tag" v-model="selectedTag[0]">
      <!-- <option v-for="tag in tags" v-bind:value="tag.tid"> -->
      <option v-for="tag in lists[0].cats" v-bind:value="tag.tid">
        {{ tag.tname }}
      </option>
    </select>
  </div>
  <!-- Categories -->
  <label for="category">Category</label>
  <select name="category" v-model="selectedCategory[1]" v-on:change="fetchTags(1)">
    <option v-for="category in categories" v-bind:value="category.id">
      {{ category.name }}
    </option>
  </select>
  <br>
  <!-- Tags -->
  <div>
    <label for="tag">Tag</label>
    <select name="tag" v-model="selectedTag[1]">
      <!-- <option v-for="tag in tags1" v-bind:value="tag.tid"> -->
      <option v-for="tag in lists[1].cats" v-bind:value="tag.tid">
        {{ tag.tname }}
      </option>
    </select>
  </div>
</tr>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>

  <script>

var app = new Vue({
  el: '#app',
  data: {
    lists: [
      { id: "", name: "", tid:"", tname:"", cats: ""},
      { id: "", name: "", tid:"", tname:"", cats: ""},
      { id: "", name: "", tid:"", tname:"", cats: ""},
    ],

    selectedCategory:  [],
    selectedTag:       [],
    categories: [
      {id: 1, name: 'Tech'},
      {id: 2, name: 'Design'},
      {id: 3, name: 'Report'},
    ],
    tags:  [],
    tags1: []
  },
  methods: {
    fetchTags: function(index) {
      console.log("index", index, this);
      var tmp_tags = [];
      var tmp_selectedCategory;

      tmp_selectedCategory = this.selectedCategory[index];

      if (tmp_selectedCategory == 1) {
        tmp_tags = [
          {tid: 1, tname: 'iOS'},
          {tid: 2, tname: 'Android'},
          {tid: 3, tname: 'FrontEnd'},
          {tid: 4, tname: 'BackEnd'},
          {tid: 5, tname: 'Server'},
        ]
      } else if (tmp_selectedCategory == 2) {
        tmp_tags = [
          {tid: 6, tname: 'UI / UX'},
          {tid: 7, tname: 'Tools'},
        ]
      } else if (tmp_selectedCategory == 3) {
        tmp_tags = [
          {tid: 8, tname: 'Events'},
          {tid: 9, tname: 'Products'},
        ]      
      } else {
        alert('Invalid value!!')
      }

      if (index == 0) {
        this.tags = tmp_tags;
        this.lists[0].cats = tmp_tags;
      } else {
        this.tags1 = tmp_tags;
        this.lists[1].cats = tmp_tags;
      }
    }
  }
})

  </script>
  </body>
</html>
