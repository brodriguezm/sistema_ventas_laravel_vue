<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Roles
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion" v-model="typeFilter">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text"
                                       id="texto"
                                       name="texto"
                                       v-model.trim="textFilter"
                                       @keyup.enter="listarModelo(1, textFilter, typeFilter)"
                                       class="form-control"
                                       placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarModelo(1, textFilter, typeFilter)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="rol in listModel" :key="rol.id">
                                <td v-text="rol.nombre"></td>
                                <td v-text="rol.descripcion"></td>
                                <td>
                                    <span v-if="rol.condicion" class="badge badge-success">Activo</span>
                                    <span v-else class="badge badge-secondary">Inactivo</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"  @click.prevent="cambiarPagina(pagination.current_page-1, textFilter, typeFilter)">Ant</a>
                            </li>
                            <li class="page-item"
                                v-for="page in pagesNumber" :key="page"
                                :class="[page === isActivated ? 'activate' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, textFilter, typeFilter)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1, textFilter, typeFilter)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
    export default {
        data() {
            return {
                //path: '/laravel/mi-sistema/public/',
                path: '/rol',
                rolId : null,
                nombre: null,
                descripcion: null,
                condicion: null,
                listModel: [],
                titleModal: null,
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset: 3,
                textFilter: '',
                typeFilter: 'nombre'
            }
        },
        computed: {
            isActivated: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page)
                    to = this.pagination.last_page;

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods: {
            listarModelo(page, textFilter='', typeFilter='nombre'){
                let me = this,
                    url = "es?page="+page+'&textFilter='+textFilter+'&typeFilter='+typeFilter;

                axios.get(me.path+url)
                    .then(function (response) {
                        var resp = response.data;
                        me.listModel = resp.roles.data;
                        me.pagination = resp.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cambiarPagina(page, textFilter='', typeFilter='nombre'){
                this.pagination.current_page  = page;
                this.listarModelo(page, textFilter, typeFilter);
            }
        },
        mounted() {
            this.listarModelo(1);
        }
    }
</script>
<style>
    .modal-content{
        position: absolute!important;
        width: 100%!important;
    }

    .show-modal{
        display: block!important;
        opacity: 1;
        background-color: #d4d4d4a1;
    }

    .msj-error{
        color:red;
        font-weight: bold;
    }
</style>