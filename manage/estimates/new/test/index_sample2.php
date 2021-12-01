<!DOCTYPE html>
<html>
  <head>
  </head>
<script src="https://unpkg.com/vue"></script>

<div id="app">
<tr v-for="(list, i) in lists" :key="i" v-cloak>
  <!-- Categories -->
  <label for="category">Category</label>
  <select name="category" v-model="selectedCategory" v-on:change="fetchTags(0)">
    <option v-for="category in categories" v-bind:value="category.id">
      {{ category.name }}
    </option>
  </select>
  <br>
  <!-- Tags -->
  <div>
    <label for="tag">Tag</label>
    <select name="tag" v-model="selectedTag">
      <option v-for="tag in tags" v-bind:value="tag.tid">
        {{ tag.tname }}
      </option>
    </select>
  </div>
  <!-- Categories -->
  <label for="category1">Category</label>
  <select name="category1" v-model="selectedCategory1" v-on:change="fetchTags(1)">
    <option v-for="category1 in categories1" v-bind:value="category1.id">
      {{ category1.name }}
    </option>
  </select>
  <br>
  <!-- Tags -->
  <div>
    <label for="tag1">Tag</label>
    <select name="tag1" v-model="selectedTag1">
      <option v-for="tag1 in tags1" v-bind:value="tag1.tid">
        {{ tag1.tname }}
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
      { id: "", name: "", tid:"", tname:""},
      { id: "", name: "", tid:"", tname:""},
      { id: "", name: "", tid:"", tname:""},
    ],

    selectedCategory: '',
    selectedTag: '',
    selectedCategory1: '',
    selectedTag1: '',
    categories: [
      {id: 1, name: 'Tech'},
      {id: 2, name: 'Design'},
      {id: 3, name: 'Report'},
    ],
    categories1: [
      {id: 1, name: 'Tech'},
      {id: 2, name: 'Design'},
      {id: 3, name: 'Report'},
    ],
    tags: [],
    tags1: []
  },
  methods: {
    fetchTags: function(index) {
      console.log("index", index);
      var tmp_tags = [];
      var tmp_selectedCategory;

      if (index == 0) {
        tmp_selectedCategory = this.selectedCategory;
      } else {
        tmp_selectedCategory = this.selectedCategory1;
      }

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
      } else {
        this.tags1 = tmp_tags;
      }
    }
  }
})

  </script>
  </body>
</html>
