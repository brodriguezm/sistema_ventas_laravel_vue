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
                                    <button type="button" v-if="categoria.condicion" @click="changeState(categoria)" class="btn btn-danger btn-sm" >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button type="button" v-else @click="changeState(categoria)" class="btn btn-danger btn-sm" >
                                        <i class="fa fa-check"></i>
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
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"  @click.prevent="cambiarPagina(pagination.current_page-1)">Ant</a>
                            </li>
                            <li class="page-item" 
                                v-for="page in pagesNumber" :key="page"
                                :class="[page == isActivated ? 'activate' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1)">Sig</a>
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
                },
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset: 3
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
            listarCategorias(page){
                let me = this,
                    url = "categorias?page="+page
                
                axios.get(me.path+url)
                .then(function (response) {
                    var resp = response.data;
                    me.listaCategorias = resp.categorias.data;
                    console.log('***response***');
                    console.log(resp.pagination);
                    me.pagination = resp.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                this.pagination.current_page  = page;
                this.listarCategorias(page);
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
            changeState(categoria){
                //alert('eliminar'+ categoria.condicion);
                let me = this;
                let url = "",
                    textMsj = "";
                if(categoria.condicion){
                    url = "categorias/desactivar";
                    textMsj = 'Se desactivará el registro'
                }
                else{
                    url = "categorias/activar";
                    textMsj = 'Se activará el registro'
                }

                Swal.fire({
                title: '¿Estás seguro?',
                text: textMsj,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.put(me.path+url, categoria)
                        .then(reponse => {
                            me.listarCategorias();
                            swalWithBootstrapButtons.fire(
                            'Hecho!',
                            'El registro a sido'+(categoria.condicion)?'desactivado':'activado',
                            'success'
                            );
                        })
                        .catch(error => {
                            console.log(error);
                        });
                    }
                })
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
            this.listarCategorias(1);
            //console.log(this.pagination);
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

