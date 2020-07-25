<template>
    <div class="card">
        <div class="card-header">Register</div>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">用户名</label>
                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" v-model="name" value="">
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">邮箱</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" v-model="email" value="">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">密码</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" v-model="password">
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">确认密码</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary" v-on:click="doRegister()">
                        注册
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "register",
        methods: {
            doRegister: function() {
                let error_flag = 0;
                let error_message = '';
                let verify = /^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/;

                if(this.name.length < 2 || this.name.length > 16){
                    error_flag = 1;
                    error_message = '请输入2到16位的用户名';
                }else if (!verify.test(this.email)) {
                    error_flag = 1;
                    error_message = '邮箱格式错误, 请重新输入'
                }else if (this.password.length < 6) {
                    error_flag = 1;
                    error_message = '请输入6位以上密码'
                }else if (this.password.length < 6) {
                    error_flag = 1;
                    error_message = '请输入6位以上密码'
                }else if (this.password !== this.password_confirmation) {
                    error_flag = 1;
                    error_message = '两次密码输入不一致'
                }

                if(error_flag === 1){
                    this.$message({
                        type: 'warning',
                        message: error_message
                    });
                    return;
                }
                axios.post('/api/user/register',{
                    name: this.name,
                    email: this.email,
                    password: this.password
                }).then(response => {
                    let data = response.data;
                    if(data.code === 1001){
                        this.$message({
                            type: 'warning',
                            message: data.message
                        });
                        return;
                    }
                    localStorage.setItem('is_login', 1);
                    localStorage.setItem('name', data.data.name);
                    localStorage.setItem('token', data.data.token);
                    console.log(response.data)
                    this.$router.push('/');
                })
            }
        },
        data() {
            return {
                name : '',
                email : '',
                password : '',
                password_confirmation : '',
            }
        }
    }
</script>

<style scoped>

</style>
