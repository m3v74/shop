<template>
    <h1>Редактирование прав</h1>
    <a href="/" class="btn btn-success mb-3" style="text-decoration: none">На главную</a>
    <div class="main">
        <div class="list-users border rounded">
            <div class="user" v-if="users" v-for="(u, i) in users" @click="getPermissions(i)"><span style="margin-left: 20px">{{ u.name }}</span></div>
        </div>
        <div class="permissions border rounded ps-4 pe-4 pb-4 ms-5">
            <input type="checkbox" class="mt-4" v-model="permissions.admin"><span class="ms-2">Администратор</span><br>
            <input type="checkbox" class="mt-4" v-model="permissions.create_category"><span class="ms-2">Создание категорий</span><br>
            <input type="checkbox" class="mt-4" v-model="permissions.delete_category"><span class="ms-2">Удаление категорий</span><br>
            <input type="checkbox" class="mt-4" v-model="permissions.create_product"><span class="ms-2">Добавление товара</span><br>
            <input type="checkbox" class="mt-4" v-model="permissions.delete_product"><span class="ms-2">Удаление товара</span><br>
            <input type="checkbox" class="mt-4" v-model="permissions.edit_product"><span class="ms-2">Изменение товара</span><br>
        </div>
    </div>
    <button class="btn btn-success mt-4" @click="savePermissions()">Сохранить</button>
</template>

<script>
export default {
    name: "Access",
    props: ['users'],
    data() {
        return {
            users: [],
            permissions: {}
        }
    },
    methods: {
        async getPermissions(i) {
            var urlencoded = new URLSearchParams();
            urlencoded.append("user_id", this.users[i].id);

            var requestOptions =
                {
                    method: 'POST',
                    redirect: 'follow',
                    body: urlencoded
                };
            var response = await fetch("api/user/permissions", requestOptions);
            if (response.ok) {
                this.permissions = await response.json();
            }
        },

        async savePermissions()
        {
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

            var urlencoded = new URLSearchParams();
            urlencoded.append("admin", this.permissions.admin);
            urlencoded.append("create_category", this.permissions.create_category);
            urlencoded.append("delete_category", this.permissions.delete_category);
            urlencoded.append("create_product", this.permissions.create_product);
            urlencoded.append("delete_product", this.permissions.delete_product);
            urlencoded.append("edit_product", this.permissions.edit_product);

            var requestOptions =
                {
                    method: 'POST',
                    headers: myHeaders,
                    body: urlencoded,
                    redirect: 'follow'
                };
            var response = await fetch("api/access/save", requestOptions);

            if (response.ok)
            {

            }
        },
    },
    mounted() {
        this.users = JSON.parse(this.$props.users);
    }
}
</script>

<style scoped>
.permissions {
}
.list-users {
    width: 200px;
    line-height: 40px;
    overflow: auto;
    cursor: pointer;
    max-height: 18rem;
}
.main {
    display: flex;
}
.user {
}
.user:hover {
    background: #0a53be;
    color: white;
}
</style>
