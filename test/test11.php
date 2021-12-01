<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ToDoApp - 【Vue.js】クラスとスタイルのバインディング学習</title>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script> -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.3/vue.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/vuelidate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/validators.min.js"></script>
  <!-- <script src="http://vuejs.org/js/vue.js"></script> -->
  <style>

body {
    font-family: Verdana, Geneva, "sans-serif";
}
input {
    border: 1px solid silver;
    border-radius: 4px;
    background: white;
    padding: 5px 10px;
    font-size: 1.2em;
}
.dirty {
    border-color: forestgreen;
    background: mintcream;
}
.error {
    border-color: red;
    background: mistyrose;
}

  </style>
</head>
<body>

<div id="app">
    <input v-model.trim="$v.text.$model" :class="status($v.text)">
    <div>入力: {{text}}</div>
    <pre>{{$v}}</pre>
</div>

<script>

Vue.use(window.vuelidate.default);
const {required, minLength} = window.validators;
new Vue({
    el: '#app',
    data: {
        text: ''
    },
    validations: {
        text: {
            required,
            minLength: minLength(3)
        }
    },
    methods: {
        status(validation) {
            console.log(this.$v);
            return {
                dirty: validation.$dirty,
                error: validation.$error
//                dirty: false,
//                error: false
            }
        }
    }
});

</script>

</body>
</html>
