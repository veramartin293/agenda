<template>
    <div class="backdrop" @click="cancelAction">
        <div class="confirmation-modal" @click.stop>
            <h2>¿Estas seguro de que deseas realizar esta acción?</h2>
            <div class="buttons-container">
                <button class="red-hover" @click="confirmAction">Eliminar</button>
                <button @click="cancelAction">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        confirmAction() {
            this.$emit('actionConfirmed');
        },
        cancelAction() {
            this.$emit('actionCanceled');
        }
    }
    
}
</script>

<style>
.confirmation-modal {
    background: white;
    width: 30%;
    padding: 20px 20px;
    text-align: center;
    box-shadow: 0px 0px 21px -3px rgba(52,52,52,1);
    color: rgba(52,52,52,1);
}

/* Responsive */
@media (max-width: 600px) {
    .confirmation-modal {
        width: 100%;
    }
}
</style>