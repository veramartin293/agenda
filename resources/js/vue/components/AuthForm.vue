<template>
    <form class="auth-form" @submit.prevent v-if="!$store.getters.user">
        <h2 v-if="formMode === 'login'">Iniciar sesión</h2>
        <h2 v-if="formMode === 'new'">Crear cuenta nueva</h2>

        <input type="text" placeholder="Nombre..." v-model="name" v-if="formMode === 'new'" @blur="checkValidation(name, 'name')">
        <p class="error form-error" v-if="formValidationErrors.name">{{formValidationErrors.name}}</p>

        <input type="email" placeholder="Correo electrónico..." v-model="email" @blur="checkValidation(email, 'email')">
        <p class="error form-error" v-if="formValidationErrors.email">{{formValidationErrors.email}}</p>

        <input type="password" placeholder="Contraseña..." v-model="password" @blur="checkValidation(password, 'password')">
        <p class="error form-error" v-if="formValidationErrors.password">{{formValidationErrors.password}}</p>

        <p v-if="reqError" class="error">{{reqError}}</p>

        <div class="buttons-container">
            <button @click="login" v-if="formMode === 'login'">Iniciar Sesión</button>
            <button @click="createAccount" v-if="formMode === 'new'">Crear cuenta</button>
        </div>
        <button class="btn-link" @click="changeFormMode" v-if="formMode === 'login'">¿No tienes una cuenta? Crea una</button>
        <button class="btn-link" @click="changeFormMode" v-if="formMode === 'new'">¿Ya tienes una cuenta? Inicia sesión</button>
    </form>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            name: '',
            formMode: 'login',
            formValidationErrors: {
                email: '',
                password: ''
            },
            reqError: false
        };
    },
    methods: {
        async login() {
            // Verify form validations
            if (!this.validateForm()) {
                return 
            }

            // Perform the request
            const response = await fetch('/api/login', {
                method: 'POST',
                body: JSON.stringify({
                    email: this.email,
                    password: this.password
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            // Get the request data
            const responseData = await response.json();

            // Check request status
            if (response.ok) {
                localStorage.setItem('token', responseData.token);
                localStorage.setItem('user', JSON.stringify(responseData.user));
                this.$store.commit('setUser', responseData.user);
            } else {
                this.reqError = responseData.message;
                setTimeout(() => {
                    this.reqError = ''
                }, 3000)
            }
        },
        async createAccount() {
            // Verify form validations
            if (!this.validateForm()){
                return;
            }

            // Perform the request
            const response = await fetch('/api/register', {
                method: 'POST',
                body: JSON.stringify({
                  name: this.name,
                  email: this.email,
                  password: this.password  
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            // Check request status
            if (response.ok) {
                const responseData = await response.json();
                localStorage.setItem('token', responseData.token);
                localStorage.setItem('user', JSON.stringify(responseData.user));
                this.$store.commit('setUser', responseData.user);
            } else {
                console.log('Hubo un problema en el seervidor');
            }
        },
        changeFormMode() {
            this.formValidationErrors.email = '';
            this.formValidationErrors.name = '';
            this.formValidationErrors.password = '';
            this.name = '';
            this.email = '';
            this.password = '';
            this.formMode = this.formMode === 'login' ? 'new' : 'login'
        },
        validateForm() {
            let formValidated = true;
            if (!this.email) {
                this.formValidationErrors.email = 'Por favor ingrese su correo electrónico';
                formValidated = false;
            } else {
                this.formValidationErrors.email = '';
            }
            if (!this.password) {
                this.formValidationErrors.password = 'Por favor ingrese su contraseña';
                formValidated = false;
            } else {
                this.formValidationErrors.password = '';
            }
            
            // Fields added when register form
            if (this.formMode === 'new') {
                if (!this.name) {
                    this.formValidationErrors.name = 'Por favor ingrese su nombre';
                    formValidated = false;
                }
            }

            return formValidated;
        },
        checkValidation(field, fieldString) {
            if (field) {
                this.formValidationErrors[fieldString] = '';
            }
        }
    }
}
</script>

<style scoped>
.auth-form {
    margin: 0 auto;
}

.btn-link {
    margin: 0 auto;
    background-color: transparent;
    border: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-link:hover {
    background-color: rgb(59, 59, 59);;
    color: white;
    cursor: pointer;
}
</style>