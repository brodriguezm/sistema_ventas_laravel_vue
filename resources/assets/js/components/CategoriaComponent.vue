<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button type="button" class="btn btn-secondary" @click="showModal('create')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoria in listaCategorias" :key="categoria.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="showModal('update',categoria)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalEliminar">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                                <td v-text="categoria.nombre"></td>
                                <td v-text="categoria.descripcion"></td>
                                <td>
                                    <span v-if="categoria.condicion" class="badge badge-success">Activo</span>
                                    <span v-else class="badge badge-secondary">Inactivo</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#">Ant</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" :class="{'show-modal':flagModal}" style="display:none;">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="titleModal"></h4>
                        <button type="button" class="close" @click="hiddeModal()">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre" v-model="nombre" class="form-control" placeholder="Ingrese un nombre de categoría">
                                    <small class="msj-error" v-if="msjError.nombre" v-text="msjError.nombre"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" id="descripcion" name="descripcion" v-model="descripcion" class="form-control" placeholder="Ingrese una descripción">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hiddeModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="typeModal==1" @click="createCategoria()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="typeModal==2" @click="updateCategoria()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!-- Inicio del modal Eliminar -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-danger" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Categoría</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Estas seguro de eliminar la categoría?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- Fin del modal Eliminar -->
    </main>
</template>

<script>
    export default {
        data() {
            return {
                path: '/laravel/mi-sistema/public/',
                categoriaId : null,
                nombre: null,
                descripcion: null,
                listaCategorias: [],
                flagModal: 0,
                typeModal: 0, //1 = create; 2 = update
                titleModal: null,
                msjError:{
                    nombre: null,
                    descripcion: null
                }
            }
        },
        methods: {
            listarCategorias(){
                let me = this;
                axios.get(me.path+'categorias')
                .then(function (response) {
                    me.listaCategorias = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validateCategoria(){
                this.msjError.nombre = null;
                this.msjError.descripcion = null;

                if(!this.nombre){
                    this.msjError.nombre = 'El campo nombre no puede ser nulo'
                    return true;
                }
            },
            createCategoria(){
                if(this.validateCategoria())
                    return;

                let me = this;
                let data = {
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }
                axios.post(me.path+'categorias/registrar', data)
                    .then(reponse => {
                        me.hiddeModal();
                        me.listarCategorias();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            updateCategoria(){
                if(this.validateCategoria())
                    return;
                    
                let me = this;
                let data = {
                    'id': this.categoriaId,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }
                axios.put(me.path+'categorias/actualizar', data)
                    .then(reponse => {
                        me.hiddeModal();
                        me.listarCategorias();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            showModal(action, data=null){
                if(action == "create"){
                    this.flagModal = 1;
                    this.typeModal = 1;
                    this.titleModal = "Agregar categoría";
                    
                }

                if(action == "update"){
                    this.flagModal = 1;
                    this.typeModal = 2;
                    this.titleModal = "Actualizar categoría";
                    this.categoriaId = data.id;
                    this.nombre = data.nombre;
                    this.descripcion = data.descripcion;

                }
            },
            hiddeModal(){
                this.flagModal = 0;
                this.typeModal = 1;
                this.categoriaId = null;
                this.nombre = null;
                this.descripcion = null;
            }
        },
        mounted() {
            this.listarCategorias();
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

