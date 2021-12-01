<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
<script src="https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/validators.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuelidate@0.7.4/dist/vuelidate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<title>Vuelidate</title>
<style>
    .error {
	color: #8a0421;
	border-color: #dd0f3b;
	background-color: #ffd9d9;
}
</style>
</head>
<body>
    <div id="app" class="container mt-5">
        <h2>{{ title }}</h2>
        <div class="row">
            <div class="col-sm-8">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="name">名前:</label>
                        <input type="name" id="name" v-model="name" @blur="$v.name.$touch()" :class="{ error : $v.name.$error,'form-control': true }">
                        <span v-if="$v.name.$error">名前が入力されていません。</span>
                    </div>                    
                    <div class="form-group">
                        <label for="age">年齢:</label>
                        <input type="age" class="form-control" id="age" v-model="age" @blur="$v.age.$touch()" :class="{ error : $v.age.$error,'form-control': true }">
                        <div v-if="$v.age.$error">
                            <span v-if="!$v.age.required">年齢が入力されていません。</span>
                            <span v-if="!$v.age.integer">整数の数字以外は入力できません。</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス:</label>
                        <input type="email" class="form-control" id="email" v-model="email" @blur="$v.email.$touch()" :class="{ error : $v.email.$error,'form-control': true }">
                        <div v-if="$v.email.$error">
                            <span v-if="!$v.email.required">メールドレスが入力されていません。</span>
                            <span v-if="!$v.email.email">メールアドレスの形式が正しくありません。</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">送信</button>
                </form>
            </div>
        </div>
    </div>

<script>

Vue.use(window.vuelidate.default);
const {required,email,integer} = window.validators;

const app = new Vue({
    el: '#app',
    data: {
        title: '入力フォームバリデーション',
        name: '',
        age: '',
        email: '',
    },
    validations: {
        name: {
            required
        },
        email: {
            required,
            email
        },
        age: {
            required,
            integer
        }
    },
    methods: {
        submitForm(){
            console.log(this);
            this.$v.$touch();
            if(this.$v.$invalid){
                console.log('バリデーションエラー');
            }else{
                // データ登録の処理をここに記述
                console.log('submit');
            }
        }
    }
});

</script>
</body>
</html>
