<?php
session_start();

if (!empty($_POST)) {
	print_r($_POST);
	exit();
}
?>
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
    .btn-info[disabled] {
        /* background-color: #aaa; */
        cursor: not-allowed;
    }

</style>
</head>
<body>
    <div id="app" class="container mt-5">
        <h2>{{ title }}</h2>
        <div class="row">
            <div class="col-sm-8">
                <!-- <form @submit.prevent="submitForm"> -->
                <form action="" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="name">名前:</label>
                        <!-- <input type="name" id="name" v-model="name" @blur="$v.name.$touch()" :class="{ error : $v.name.$error,'form-control': true }"> -->
                        <input type="name" id="name" v-model="name" @blur="$v.name.$touch()" :class="{ error : $v.name.$error,'form-control': true }" v-on:change="fetchAsset">
                        <span v-if="$v.name.$error">名前が入力されていません。</span>
                    </div>                    
                    <div class="form-group">
                        <label for="age">年齢:</label>
                        <!-- <input type="age" class="form-control" id="age" v-model="age" @blur="$v.age.$touch()" :class="{ error : $v.age.$error,'form-control': true }" v-on:change="fetchAsset2"> -->
                        <input type="age" id="age" v-model="age" @blur="$v.age.$touch()" :class="{ error : $v.age.$error,'form-control': true }">
                        <!-- <div v-if="$v.age.$error"> -->
                            <!-- <span v-if="!$v.age.required">年齢が入力されていません。</span> -->
                            <!--<span v-if="!$v.age.integer">整数の数字以外は入力できません。</span> -->
                        <!-- </div> -->
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス:</label>
                        <!-- <input type="email" class="form-control" id="email" v-model="email" @blur="$v.email.$touch()" :class="{ error : $v.email.$error,'form-control': true }"> -->
                        <input type="text" id="email" v-model="email" @blur="$v.email.$touch()" :class="{ error : $v.email.$error,'form-control': true }" :required="this.required ? true : false" v-on:change="fetchAsset3">
                        <!-- <div v-if="$v.email.$error"> -->
                            <!-- <span v-if="!$v.email.required">メールドレスが入力されていません。</span> -->
                            <!-- <span v-if="!$v.email.email">メールアドレスの形式が正しくありません。</span> -->
                        <!-- </div> -->
                    </div>
                    <!-- <button type="submit" name="send2" class="btn btn-info">送信</button> -->
                    <button type="submit" name="send2" class="btn btn-info">送信</button>
                </form>
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

Vue.use(window.vuelidate.default);
// const {required,email,integer} = window.validators;
const {required} = window.validators;
// const {required,integer} = window.validators;

const app = new Vue({
    el: '#app',
    data: {
        title: '入力フォームバリデーション',
        name: '',
        age: '',
        email: '',
//        return {
//            touched: false,
//        };
    },
    validations: {
        name: {
//            required,
            required: false,
        },
        age: {
//            required,
            required: false,
//            integer
//            minLength: minLength(1)
        },
        email: {
//            required,
            required: false,
//            email
//            minLength: minLength(1)
        },
        hidden: {
            required,
        },
    },
    methods: {
        fetchAsset: function() {
            if(this.name == ""){
                this.$options.validations.age.required   = false;
                this.$options.validations.email.required = false;
                $('.btn').prop("disabled", false);
                console.log('fetchAsset false');
            }else{
                this.$options.validations.age.required   = this.$options.validations.hidden.required;
                this.$options.validations.email.required = this.$options.validations.hidden.required;
                $('.btn').prop("disabled", true);
                console.log('fetchAsset true');
            }
            this.$v.$touch();
        },
        fetchAsset2: function() {
            if(this.name != "") {
                if (this.age == "") {
                    this.$options.validations.age.required = this.$options.validations.hidden.required;
                    if (this.email == "") {
                        this.$options.validations.email.required = this.$options.validations.hidden.required;
                    }
        	    $('.btn').prop("disabled", true);
                    console.log('fetchAsset2 true');
                } else {
                    // this.$options.validations.age.required = false;
                    if (this.email == "") {
                        this.$options.validations.email.required = this.$options.validations.hidden.required;
                        $('.btn').prop("disabled", true);
                        console.log('fetchAsset2 true');
                    } else {
                        $('.btn').prop("disabled", false);
                    }
                }
            }
            this.$v.$touch();
        },
        fetchAsset3: function() {
            if(this.name != "") {
                if (this.email == ""){
                    this.$options.validations.email.required = this.$options.validations.hidden.required;
                    if (this.age == "") {
                        // this.$options.validations.age.required = this.$options.validations.hidden.required;
                    }
                    $('.btn').prop("disabled", true);
                    console.log('fetchAsset false');
                } else {
                    if (this.age == "") {
                        $('.btn').prop("disabled", true);
                    } else {
                        // this.$options.validations.email.required = false;
                        $('.btn').prop("disabled", false);
                    }
                    console.log('fetchAsset true');
                }
            }
            this.$v.$touch();
        },
        submitForm(){
            console.log(this);
            // console.log(this.$options);
            // console.log(this.$options.validations);
            // console.log(this.$options.validations.email);
            // console.log(this.$options.validations.email.email);
//            console.log(this.$v.name);
            // console.log("v.name", this.$v.name.$error);
//            console.log("v.invalid", this.$v.$invalid);
//            this.$v.age.required = true;
            // this.$v.name.$error = false;
            // this.$v.$invalid = true;
//            if (this.$v.age.required == false) {
//            if (this.$options.validations.age.required == false) {
//                this.$v.age.required = true;
//                this.$options.validations.age.required = true;
//                console.log(this.$options.validations.age.required);
// OK                this.$options.validations.age.required = this.$options.validations.email.required;
//                this.$options.validations.age.required = this.$options.validations.hidden.required;
//                console.log("false -> true:");
//                console.log(this.$options.validations.email.required);
//            } else {
//                this.$v.age.required = false;
//                this.$options.validations.age.required = false;
//                console.log("true -> false:");
//                console.log(this.$options.validations.age.required);
//            }
//            this.$options.validations.age.required = false;
            // this.$options.validations.age.required = true;
            // this.errors.push('Name required.');
//            console.log("v.invalid", this.$v.$invalid);

            // $(this.$options.validations.email.email).prev("label").addClass("required");
            // this.$v.age.push({required});
            // this.$v.age.required = "true";
            //// this.age = 10;  // OK
            // this.name = " ";  // OK
            // this.age.reset();
//            this.$v.$touch();
//            if(this.name == ""){
//                this.$options.validations.age.required   = false;
//                this.$options.validations.email.required = false;
//                console.log('required = false');
//            }else{
//                this.$options.validations.age.required   = this.$options.validations.hidden.required;
//                this.$options.validations.email.required = this.$options.validations.hidden.required;
//                // データ登録の処理をここに記述
//                console.log('required = true');
//            }

//            if(this.$v.$invalid){
//                // this.$children[0].columns.push({value: 'test'});
//                console.log('バリデーションエラー');
//            }else{
//                // データ登録の処理をここに記述
//                console.log('submit');
//            }
        }
    }
});

</script>
</body>
</html>
