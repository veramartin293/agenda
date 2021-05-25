<template>
    <header>
        <div class="extra-div" v-if="$store.getters.user"></div>
        <h1>Agenda</h1>
        <user-info v-if="$store.getters.user"></user-info>
    </header>

    <auth-form v-if="!$store.getters.user"></auth-form>

    <main v-if="$store.getters.user">
        <contacts-list></contacts-list>
    </main>
    
</template>

<script>
import AuthForm from '../components/AuthForm.vue';
import UserInfo from '../components/UserInfo.vue';
import ContactsList from '../components/ContactsList.vue';

export default {
    components: {
        AuthForm,
        UserInfo,
        ContactsList
    },
    created() {
        this.autoLogin()
    },
    methods: {
        async autoLogin() {
            // Check the local storage
            const user = JSON.parse(localStorage.getItem('user'));
            if (user) {
                this.$store.commit('setUser', user);
            }

            // // If token exists then get the user from databse
            // if (token) {
            //     const response = await fetch('/api/auth', {
            //         headers: {
            //             'Authorization': `Bearer ${token}`
            //         }
            //     });
            //     const responseData = await response.json();
            //     console.log(responseData);

            //     this.$store.commit('setUser', responseData);
            // }
        }
    }
}
</script>

<style>
header, main {
    padding-right: 30px;
    padding-left: 30px;
}

header {
    text-align: center;
    padding-top: 20px;
    padding-bottom: 20px;
    display: flex;
    justify-content: space-around;
}

header h1, header .extra-div {
    text-align: center;
    width: 33.33%;
}


main {
    display: flex;
}

/* Responsive */

@media (max-width: 600px) {
    .extra-div {
        display: none;
    }
    h1 {
        transform: scale(0.8);
    }
}
</style>