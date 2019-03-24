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
                    <i class="fa fa-align-justify"></i> Clientes
                    <button type="button" class="btn btn-secondary" @click="showModal('create')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
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
                            <!--nombre: null,
                        tipo_documento: 'DNI',
                        num_documento: null,
                        direccion: null,
                        telefono: null,
                        email: null,-->
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo documento</th>
                            <th>Id documento</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                        </tr>
                        <tr v-for="cliente in listModel" :key="cliente.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="showModal('update',cliente)">
                                    <i class="icon-pencil"></i>
                                </button>
                            </td>
                            <td v-text="cliente.nombre"></td>
                            <td v-text="cliente.tipo_documento"></td>
                            <td v-text="cliente.num_documento"></td>
                            <td v-text="cliente.direccion"></td>
                            <td v-text="cliente.telefono"></td>
                            <td v-text="cliente.email"></td>
                            <!--<td>
                                <button type="button" v-if="categoria.condicion" @click="changeState(categoria)" class="btn btn-danger btn-sm" >
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button type="button" v-else @click="changeState(categoria)" class="btn btn-success btn-sm" >
                                    <i class="fa fa-check"></i>
                                </button>
                            </td>-->
                            <!--<td>
                                <span v-if="categoria.condicion" class="badge badge-success">Activo</span>
                                <span v-else class="badge badge-secondary">Inactivo</span>
                            </td>-->
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"  @click.prevent="cambiarPagina(pagination.current_page-1, textFilter, typeFilter)">Ant</a>
                            </li>
                            <li class="page-item"
                                v-for="page in pagesNumber" :key="page"
                                :class="[page == isActivated ? 'activate' : '']">
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
                                    <input type="text" id="nombre" name="nombre"
                                           v-model="nombre"
                                           class="form-control"
                                           placeholder="Nombre cliente">
                                    <small class="msj-error" v-if="msjError.nombre" v-text="msjError.nombre"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo documento</label>
                                <div class="col-md-9">
                                    <input type="text" id="tipo_documento" name="tipo_documento"
                                           v-model="tipo_documento"
                                           class="form-control"
                                           placeholder="Tipo documento">
                                    <small class="msj-error" v-if="msjError.tipo_documento" v-text="msjError.tipo_documento"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Num. documento</label>
                                <div class="col-md-9">
                                    <input type="text" id="num_documento" name="num_documento"
                                           v-model="num_documento"
                                           class="form-control"
                                           placeholder="Id documento">
                                    <small class="msj-error" v-if="msjError.num_documento" v-text="msjError.num_documento"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" id="direccion" name="direccion"
                                           v-model="direccion"
                                           class="form-control"
                                           placeholder="Dirección cliente">
                                    <small class="msj-error" v-if="msjError.direccion" v-text="msjError.direccion"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="number" id="telefono" name="telefono"
                                           v-model.number="telefono"
                                           class="form-control"
                                           placeholder="Id documento">
                                    <small class="msj-error" v-if="msjError.telefono" v-text="msjError.telefono"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" id="email" name="email" v-model="email" class="form-control" placeholder="Email cliente">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hiddeModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="typeModal==1" @click="createModelo()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="typeModal==2" @click="updateModelo()">Actualizar</button>
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
                //path: '/laravel/mi-sistema/public/',
                path: '/cliente',
                clienteId : null,
                nombre: null,
                tipo_documento: 'DNI',
                num_documento: null,
                direccion: null,
                telefono: null,
                email: null,
                listModel: [],
                flagModal: 0,
                typeModal: 0, //1 = create; 2 = update
                titleModal: null,
                msjError:{
                    nombre: null,
                    tipo_documento: null,
                    num_documento: null,
                    direccion: null,
                    telefono: null,
                    email: null,
                },
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
                    url = "s?page="+page+'&textFilter='+textFilter+'&typeFilter='+typeFilter;

                axios.get(me.path+url)
                    .then(function (response) {
                        var resp = response.data;
                        me.listModel = resp.clientes.data;
                        //console.log('***response***');
                        //console.log(resp.pagination);
                        me.pagination = resp.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cambiarPagina(page, textFilter='', typeFilter='nombre'){
                this.pagination.current_page  = page;
                this.listarModelo(page, textFilter, typeFilter);
            },
            validateModelo(){
                for(let dato in this.msjError){ this.msjError[dato] = null;}
                let flagError = false;
                if(!this.nombre){ this.msjError.nombre = 'Debe ingresar el nombre'; flagError = true;}
                return flagError;
            },
            createModelo(){
                if(this.validateModelo())
                    return;

                let me = this;
                let data = {
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email': this.email
                };
                axios.post(me.path+'/registrar', data)
                    .then(reponse => {
                        me.hiddeModal();
                        me.listarModelo();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            updateModelo(){
                if(this.validateModelo())
                    return;

                let me = this;
                let data = {
                    'id': this.clienteId,
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email': this.email
                };
                axios.put(me.path+'/actualizar', data)
                    .then(reponse => {
                        me.hiddeModal();
                        me.listarModelo();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            changeState(model){
                //alert('eliminar'+ categoria.condicion);
                let me = this;
                let url = "",
                    textMsj = "";
                if(model.condicion){
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
                                me.listarModelo();
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
                    this.titleModal = "Agregar cliente";
                }

                if(action == "update"){
                    this.flagModal = 1;
                    this.typeModal = 2;
                    this.titleModal = "Actualizar cliente";
                    this.clienteId = data.id;
                    this.nombre = data.nombre;
                    this.tipo_documento = data.tipo_documento;
                    this.num_documento = data.num_documento;
                    this.direccion = data.direccion;
                    this.telefono = data.telefono;
                    this.email = data.email;
                }
            },
            hiddeModal(){
                this.flagModal = 0;
                this.typeModal = 1;
                this.clienteId = null;
                this.nombre = null;
                this.tipo_documento = null;
                this.num_documento = null;
                this.direccion = null;
                this.telefono = null;
                this.email = null;
            }
        },
        mounted() {
            this.listarModelo(1);
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