<template>
    <div class="card">
        <div class="card-header">Login</div>

        <div class="card-body">
            <form method="POST" action="">

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">用户名</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">密码</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="password" value="">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            登录
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "login"
    }
</script>

<style scoped>

</style>
