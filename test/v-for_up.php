<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ToDoApp - 【Vue.js】クラスとスタイルのバインディング学習</title>
  <style>
    body{
      font-size: 16px;
      margin: 0;
    }
    #app h1{
      font-size: 2em;
      background-color: #222;
      color: #fff;
      margin: 0;
      padding: .5em;
    }
    .todo-box ul{
      margin: 0;
      padding: 0;
      list-style: none;
    }
    .todo-box li{
      line-height: 2;
      border-bottom: 1px solid #eee;
      padding: 1em;
    }
    .todo-box li > label {
      font-size: 1em;
      cursor: pointer;
    }
    /* クラスバインディングの判定が真であれば.doneクラスが適用されます */
    .todo-box li > label.done {
      text-decoration: line-through;
      color: #ddd;
    }
    /* クラスバインディングの判定が真であれば.doneクラスが適用されます */
    .btn-del {
      font-size: .8em;
      color: #fff;
      display: inline-block;
      text-align: center;
      cursor: pointer;
      margin-left: .5em;
      background-color: #555;
      line-height: 1;
      padding: .45em 1em;
      box-sizing: border-box;
    }
    .btn-del:hover {
      color: #555;
      background-color: #fff;
      border: 1px solid #555;
    }
    form {
      background-color: #eee;
      padding: 1.5em 1em;
    }
  </style>
</head>
<body>
  <div id="app">
    <h1>ToDoApp</h1>
    <div class="todo-box">
      <ul>
        <li v-for="(todo, index) in todos">
          <input type="checkbox" v-model="todo.isDone" v-bind:id="index">
          <!-- ここでクラスバインディングを使っています -->
          <label v-bind:class="{ done: todo.isDone }" v-bind:for="index">
            {{ todo.title }}
          </label>
          <!-- ここでクラスバインディングを使っています -->
          <span v-on:click="deleteItem(index)" class="btn-del">Delete</span>
        </li>
      </ul>
      <form v-on:submit.prevent="addItem">
        <input type="text" v-model="newItem">
        <input type="submit" value="Add Item">
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/vue"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        newItem: '',
        todos: [
          {
            title: 'MyTask01',
            isDone: false, // クラスバインディングの真偽値
          },
          {
            title: 'MyTask02',
            isDone: false, // クラスバインディングの真偽値
          },
          {
            title: 'MyTask03',
            isDone: false, // クラスバインディングの真偽値
          },
        ],
      },
      methods: {
        addItem: function(){
          var item = {
            title: this.newItem,
            isdone: false,
          };
          this.todos.push(item);
          this.newItem = '';
        },
        deleteItem: function(index) {
          this.todos.splice(index, 1);
        },
      },
    });
  </script>
</body>
</html>
