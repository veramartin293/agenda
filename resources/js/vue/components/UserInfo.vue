<template>
    <div>
        <h2>{{$store.getters.user.name}}</h2>
        <button @click="logout">Cerrar sesión</button>
    </div>
</template>

<script>
export default {
    methods: {
        async logout() {
            const token = localStorage.getItem('token');
            const response = await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            if (response.ok) {
                this.$store.commit('setUser', null);
                localStorage.removeItem('user');
                localStorage.removeItem('token');
            } else {
                console.log('Ha occurido un error en el servidor')
            }
        }
    }
}
</script>

<style scoped>
div {
    display: flex;
    justify-content: flex-end;
    width: 33.33%;
    align-items: center;
}

h2 {
    margin-right: 5px;
}

button {
    background-color: transparent;
    border: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

button:hover {
    background-color: rgb(59, 59, 59);;
    color: white;
    cursor: pointer;
}

/* Responsive */
@media (max-width: 600px){
    h2, button {
        transform: scale(0.8);
    }
}
</style>

