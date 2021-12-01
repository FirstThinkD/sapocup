<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vuelidate</title>
<style>

body {
  background: #20262E;
  padding: 20px;
  font-family: Helvetica;
}

#app {
  background: #fff;
  border-radius: 4px;
  padding: 20px;
  transition: all 0.2s;
}

button:disabled {
  cursor:not-allowed;
}

.error {
  color: #f00;
}

/* .error {
	color: #8a0421;
	border-color: #dd0f3b;
	background-color: #ffd9d9;
} */

</style>
</head>
<body>

<script>window.Promise || document.write('<script src="https:\/\/www.promisejs.org\/polyfills\/promise-7.0.4.min.js"><\/script>')</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@3.0.11/dist/rules.umd.min.js"></script>

<div id="app">
  <validation-observer ref="obs" v-slot="ObserverProps">
    <label>名前</label>
    <validation-provider name="name" rules="required">
      <div slot-scope="ProviderProps">
        <input v-model="name">
        <p class="error">{{ ProviderProps.errors[0] }}</p>
      </div>
    </validation-provider>

    <label>メールアドレス</label>
    <validation-provider name="email" rules="required|email">
      <div slot-scope="ProviderProps">
        <input v-model="email">
        <p class="error">{{ ProviderProps.errors[0] }}</p>
      </div>
    </validation-provider>
    <!-- <button type="button" @click="submit" :disabled="ObserverProps.invalid || !ObserverProps.validated">送信</button> -->
    <button type="button" @click="submit">送信</button>
  </validation-observer>
</div>

<script>

const VeeValidate        = window.VeeValidate;
const ValidationProvider = VeeValidate.ValidationProvider;
const ValidationObserver = VeeValidate.ValidationObserver;
const VeeValidateRules   = window.VeeValidateRules;
const required           = VeeValidateRules.required;
const email              = VeeValidateRules.email;

required.message = '必須項目です';
email.message = 'メールの形式ではありません';

VeeValidate.extend('required', required);
// VeeValidate.extend('email', email);

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

let app = new Vue({
  el: '#app',
  data: {
    name: '',
    email: ''
  },
  methods: {
    submit: function(){
      console.log(this);
      if (this.name == "" || this.email == "") {
          alert('input エラー！');
      } else {
          alert('送信しました！');
      }
    }
  }
});

</script>
</body>
</html>
