<template>
    <!--    В заголовке - Ошибка-->
    <!--    В теле - Ошибка записи в БД-->
    <!--    Цвет окна - красный-->

    <!-- Модальное окно -->
    <div id="errorModal" className="modal fade" tabIndex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered modal-sm">
            <div className="modal-content">
                <div className="modal-header">
                    <h1 className="modal-title fs-5" id="errorModalLabel">Системное сообщение</h1>
                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div className="modal-body">
                    <h4 style="color: white">Ошибка записи в БД</h4>
                </div>
                <div className="modal-footer">
                    <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Error",
    data() {
        return {
            modal: null
        }
    },

    methods: {
        show() {
            this.modal.show();
        }
    },

    mounted() {
        var errorModal = document.getElementById('errorModal')
        this.modal = bootstrap.Modal.getOrCreateInstance(errorModal);
    }
}

</script>

<style scoped>
.modal-content {
    background: #9c2f2f;
}

.modal-title {
    color: white;
}
</style>
