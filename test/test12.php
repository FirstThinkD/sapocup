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

  <!-- <input type="button" value="ボタン" ondblclick="myFnc();"> -->
  <div ondblclick="myFnc();">ここもダブルクリックできます。</div>

<script>

function myFnc(){
  alert("ダブルクリックされました。");
}

</script>

</body>
</html>
