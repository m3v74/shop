<template>
    <div class="main">
        <div class="category">
            <i class="fa fa-plus-circle plus" @click="createCategory" v-if="permissions"
               data-bs-toggle="tooltip" data-bs-placement="top"
               data-bs-custom-class="custom-tooltip"
               data-bs-title="Создать новую категорию."></i>

            <div class="category_list" v-for="(c, index) in massivCategory" @click="selectCategory(index)">
                <div>
                    <img class="image" :src="c.category_photo" height="70" alt="">
                    {{ c.category_name }}
                </div>
                <div class="icons" v-if="permissions">

                    <i class="fa fa-plus-circle plus-green" @click="createProduct(index)"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       data-bs-custom-class="custom-tooltip"
                       data-bs-title="Создать новый товар для этой категории."></i>

                    <i class="fa fa-minus-circle minus-red" @click="deleteCategory(index)"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       data-bs-custom-class="custom-tooltip"
                       data-bs-title="Удалить эту категорию со всеми ее товарами."></i>

                    <i class="fa fa-minus-circle minus-orange" @click="deleteProducts(index)"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       data-bs-custom-class="custom-tooltip"
                       data-bs-title="Удалить все товары этой категории."></i>
                </div>
            </div>
        </div>

        <div class="all_cards">
            <div class="product-card" v-for="(p, index) in massivProduct">
                <i v-if="permissions" class="fa fa-minus-circle minus" @click="delProduct(index, p.category_id)"
                   data-bs-toggle="tooltip" data-bs-placement="top"
                   data-bs-custom-class="custom-tooltip"
                   data-bs-title="Удалить этот товар."></i>

                <div class="product-card_wrapper">
                    <h3 class="product-card_name" style="margin-top: 20px">{{ p.product_name }}</h3>
                    <img :src="p.product_photo" height="200" alt="">
                    <span class="product-card_price">{{ p.priceDiscount }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно-->
    <div id="CategoryModalWindows" class="modal fade" tabindex="-1" aria-labelledby="addTCategoryModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="name_themes" class="label-with-margin"> Придумайте имя категории </label>
                    <input id="name_themes" type="text" class="form-control" v-model="nameCategory">

                    <label for="upload"> Фото для категории </label>
                    <input id="upload" type="file" ref="upload" class="form-control">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" @click="saveCategory()">Сохранить изменения
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно-->
    <div id="ProductModalWindows" class="modal fade" tabindex="-1" aria-labelledby="addProductModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавление товара в категорию {{ nameCategory }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name_themes" class="form-label">Наименование товара</label>
                        <input type="text" class="form-control" id="name_themes" v-model="nameProduct">
                    </div>
                    <div class="mb-3">
                        <label for="upload2" class="form-label">Фото для товара</label>
                        <input type="file" class="form-control" ref="upload2" id="upload2">
                    </div>
                    <div class="mb-3">
                        <label for="price_product" class="label-with-margin">Цена</label>
                        <input id="price_product" type="number" class="form-control" v-model.number="priceProduct">
                    </div>
                    <div class="mb-3">
                        <label for="quantity_product" class="label-with-margin">Количество</label>
                        <input id="quantity_product" type="number" class="form-control"
                               v-model.number="quantityProduct">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="allow_product" v-model="allowdDiscount">
                        <label class="form-check-label" for="allow_product">Разрешить скидку</label>
                    </div>
                    <div class="mb-3" v-show="allowdDiscount">
                        <label class="titlesize" title="пшгнрапгю" for="quantity_product">Размер скидки</label>
                        <input id="quantity_product" type="number" class="form-control"
                               v-model.number="discountProduct">
                    </div>

                </div>
                <div class="modal-footer">
                    <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" @click="saveProduct">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>
    <error ref="error"></error>
</template>

<script>
import { Tooltip } from 'bootstrap';
export default {
    props: ['access'],
    data() {
        return {
            massivCategory: [],
            categoryModal: null,
            nameCategory: '',
            idCategory: '',

            massivProduct: [],
            productModal: null,
            nameProduct: '',
            quantityProduct: 0,
            priceProduct: 0,
            allowdDiscount: false,
            discountProduct: 0,
            permissions: false,
        }
    },
    methods: {
        async getCategories() {
            var requestOptions =
                {
                    method: 'POST',
                    redirect: 'follow'
                };
            var categories = await fetch("api/categories/get", requestOptions);
            if (categories.ok) {
                this.massivCategory = await categories.json();
            }
        },
        createCategory() {
            this.nameCategory = '';
            this.$refs.upload.value = '';
            this.categoryModal.show();
        },
        async saveCategory() {
            var formData = new FormData();
            formData.append("name", this.nameCategory);
            formData.append('file', this.$refs.upload.files[0])
            var requestOptions =
                {
                    method: 'POST',
                    body: formData,
                    redirect: 'follow'
                };
            var response = await fetch("api/category/save", requestOptions);
            this.categoryModal.hide();
            this.getCategories();
        },
        async selectCategory(index) {
            var idCategory = this.massivCategory[index].id;
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
            var urlencoded = new URLSearchParams();
            urlencoded.append("id_category", idCategory);
            var requestOptions =
                {
                    method: 'POST',
                    headers: myHeaders,
                    body: urlencoded,
                    redirect: 'follow'
                };
            var response = await fetch("api/category/select", requestOptions);
            if (response.ok) {
                this.massivProduct = await response.json();
            }
        },
        async deleteCategory(index) {
            var idCategory = this.massivCategory[index].id;
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
            var urlencoded = new URLSearchParams();
            urlencoded.append("idCategory", idCategory);
            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: urlencoded,
                redirect: 'follow'
            };
            var response = await fetch("api/category/delete", requestOptions);
            if (response.ok) {
                this.getCategories();/////////////////////////////////////////////////////////////
            }
        },
        async getProducts() {
            var requestOptions =
                {
                    method: 'POST',
                    redirect: 'follow'
                };
            var response = await fetch("api/products/get", requestOptions);

            if (response.ok) {
                this.massivProduct = await response.json();

                for (var i = 0; i < this.massivProduct.length; i++) {
                    var newPrice = this.massivProduct[i].unit_price / (this.massivProduct[i].discount / 100 + 1);
                    let options = {
                        minimumFractionDigits: 2, // указываем количество десятичных знаков
                        maximumFractionDigits: 2
                    };
                    this.massivProduct[i].priceDiscount = newPrice.toLocaleString('ru-RU', options);
                }
            }
        },
        createProduct(index) {
            this.nameProduct = '';
            this.priceProduct = '';
            this.quantityProduct = '';
            this.allowdDiscount = '';
            this.discountProduct = '';
            this.$refs.upload2.value = '';
            this.idCategory = this.massivCategory[index].id;
            this.nameCategory = this.massivCategory[index].category_name;
            this.productModal.show();
        },
        async saveProduct() {
            var formData = new FormData();
            formData.append("idCategory", this.idCategory);
            formData.append("nameProduct", this.nameProduct);
            formData.append("priceProduct", this.priceProduct);
            formData.append("quantityProduct", this.quantityProduct);
            formData.append("allowdDiscount", this.allowdDiscount);
            formData.append("discountProduct", this.discountProduct);
            formData.append('file', this.$refs.upload2.files[0]);
            var requestOptions =
                {
                    method: 'POST',
                    body: formData,
                    redirect: 'follow'
                };
            var response = await fetch("api/product/save", requestOptions);
            this.massivProduct = await response.json();
            this.productModal.hide();
        },
        async delProduct(index, idCategory) {
            var idProduct = this.massivProduct[index].id;
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
            var urlencoded = new URLSearchParams();
            urlencoded.append("idProduct", idProduct);
            urlencoded.append("idCategory", idCategory);
            var requestOptions =
                {
                    method: 'POST',
                    headers: myHeaders,
                    body: urlencoded,
                    redirect: 'follow'
                };
            var response = await fetch("api/product/del", requestOptions);
            this.massivProduct = await response.json();
        },
        async deleteProducts(index) {
            var idCategory = this.massivCategory[index].id;
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
            var urlencoded = new URLSearchParams();
            urlencoded.append("idCategory", idCategory);
            var requestOptions =
                {
                    method: 'POST',
                    headers: myHeaders,
                    body: urlencoded,
                    redirect: 'follow'
                };
            var response = await fetch("api/products/del", requestOptions);
            console.log(response);
            this.massivProduct = await response.json();
        },
    },
    mounted() {

        new Tooltip(document.body, {
            selector: "[data-bs-toggle='tooltip']",
        })

        var categoryAddModal = document.getElementById('CategoryModalWindows')
        this.categoryModal = bootstrap.Modal.getOrCreateInstance(categoryAddModal);

        var productAddModal = document.getElementById('ProductModalWindows')
        this.productModal = bootstrap.Modal.getOrCreateInstance(productAddModal);

        this.getCategories();
        var permission = this.$props.access;
        this.permissions = permission === '1';
    }
}
</script>
<style scoped lang="scss">
$border: #c1cfea;

.tooltip-arrow,
.red-tooltip + .tooltip > .tooltip-inner {background-color: #f00;}

.category_list {
    font-size: 20px;
    margin-bottom: 10px;
    padding: 5px;
    border-radius: 5px;
    cursor: pointer;
    padding-left: 40px;
    transition-duration: 100ms;
    display: flex;
    justify-content: space-between;

    .icons {
        display: flex;
        align-items: center;
        color: #13718d;
        i {
            margin-right: 5px;
        }
    }
}

.category_list:hover {
    background: linear-gradient(180deg, #DDE2F8FF, #B1B9CEFF);

    .icons {
        color: #d4ecfc;
    }
}

.main {
    display: flex;
    justify-content: space-between;
    margin-top: 26px;
}

.category {
    width: 300px;
    height: calc(100vh - 51px);
    border: 1px solid $border;
    border-radius: 10px;
    padding: 30px 20px 0 20px;
    overflow: auto;
    position: relative;
    .plus {
        position: absolute;
        left: 9px;
        top: 10px;
        font-size: 20px;
        color: cadetblue;
        cursor: pointer;
    }
}

.all_cards {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    width: 970px;
}

.product-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 220px;
    height: 318px;
    padding: 6px 17px;
    text-align: center;
    border: 1px solid $border;
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 20px;
    border-radius: 10px;
    transition-duration: 500ms;
    cursor: pointer;
    position: relative;

    .minus {
        position: absolute;
        left: 9px;
        top: 10px;
        font-size: 20px;
        color: cadetblue;
        cursor: pointer;
    }

}
.minus-red{
    color:red;
}

.minus-orange{
    color:orange;
}

.plus-green{
    color:green;
}
.product-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.product-card_wrapper {
    display: flex;
    flex-direction: column;
}

.product-card_name {
    margin: 0;
    padding: 0;
}

.product-card_image {
    order: -1;
    object-fit: cover;
    width: 100%;
    margin-bottom: 20px;
    border-radius: 5px;
}

.product-card span {
    font-weight: 700;
    font-size: 17px;
    line-height: 18px;
    text-transform: uppercase;
    color: #A9A9A9;
}
</style>
