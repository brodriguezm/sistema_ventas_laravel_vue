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
                    <i class="fa fa-align-justify"></i> Artículos
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
                                       @keyup.enter="listarArticulos(1, textFilter, typeFilter)"
                                       class="form-control"
                                       placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarArticulos(1, textFilter, typeFilter)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="articulo in listaArticulos" :key="articulo.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="showModal('update',articulo)">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <button type="button" v-if="articulo.condicion" @click="changeState(articulo)" class="btn btn-danger btn-sm" >
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button type="button" v-else @click="changeState(articulo)" class="btn btn-danger btn-sm" >
                                    <i class="fa fa-check"></i>
                                </button>
                            </td>
                            <td v-text="articulo.codigo"></td>
                            <td v-text="articulo.nombre"></td>
                            <td v-text="articulo.nombre_categoria"></td>
                            <td v-text="articulo.precio_venta"></td>
                            <td v-text="articulo.stock"></td>
                            <td v-text="articulo.descripcion"></td>
                            <td>
                                <span v-if="articulo.condicion" class="badge badge-success">Activo</span>
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
                                <label class="col-md-3 form-control-label" for="codigo-input">Codígo</label>
                                <div class="col-md-9">
                                    <input type="text" id="codigo" name="codigo"
                                           v-model.number="codigo"
                                           class="form-control"
                                           placeholder="Ingrese un codígo de artículo">
                                    <small class="msj-error" v-if="msjError.codigo" v-text="msjError.codigo"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="nombre-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre"
                                           v-model="nombre"
                                           class="form-control"
                                           placeholder="Ingrese un nombre de artíiulo">
                                    <small class="msj-error" v-if="msjError.nombre" v-text="msjError.nombre"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="categoria-input">Categoría</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="categoriaId" id="categoriaId" v-model="categoriaId">
                                        <option value="">--Seleccionar--</option>
                                        <option v-for="categoria in listaCategorias" :key="categoria.id" :value="categoria.id">{{categoria.nombre}}</option>
                                    </select>
                                    <small class="msj-error" v-if="msjError.categoria" v-text="msjError.categoria"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="precio-input">Precio</label>
                                <div class="col-md-9">
                                    <input type="number" id="precio_venta" name="precio_venta" v-model.number="precio_venta" class="form-control" placeholder="Ingrese el precio">
                                    <small class="msj-error" v-if="msjError.precio_venta" v-text="msjError.precio_venta"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="stock-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" id="stock" name="stock" v-model.number="stock" class="form-control" placeholder="Ingrese el # de stock">
                                    <small class="msj-error" v-if="msjError.stock" v-text="msjError.stock"></small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" v-model="descripcion" class="form-control" placeholder="Ingrese una descripción">
                                    <small class="msj-error" v-if="msjError.descripcion" v-text="msjError.descripcion"></small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hiddeModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="typeModal==1" @click="createArticulo()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="typeModal==2" @click="updateArticulo()">Actualizar</button>
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
                path: '/',
                articuloId: null,
                categoriaId : "",
                nombre_categoria: null,
                codigo: null,
                nombre: null,
                precio_venta: null,
                stock: null,
                descripcion: null,
                listaArticulos: [],
                listaCategorias: [],
                flagModal: 0,
                typeModal: 0, //1 = create; 2 = update
                titleModal: null,
                msjError:{
                    codigo: null,
                    nombre: null,
                    descripcion: null,
                    categoria: null,
                    precio_venta: null,
                    stock: null,
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
            listarArticulos(page, textFilter='', typeFilter='nombre'){
                let me = this,
                    url = "articulos?page="+page+'&textFilter='+textFilter+'&typeFilter='+typeFilter;

                axios.get(me.path+url)
                    .then(function (response) {
                        var resp = response.data;
                        me.listaArticulos = resp.articulos.data;
                        //console.log('***response***');
                        //console.log(resp.pagination);
                        me.pagination = resp.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            listarCategorias(){
                let me = this,
                    url = "categorias/selectCategorias";

                axios.get(me.path+url)
                    .then(function (response) {
                        console.log(response);
                        var resp = response.data;
                        me.listaCategorias = resp.categorias;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cambiarPagina(page, textFilter='', typeFilter='nombre'){
                this.pagination.current_page  = page;
                this.listarArticulos(page, textFilter, typeFilter);
            },
            validateArticulo(){
                //this.msjError.nombre = null;
                //this.msjError.descripcion = null;
                //this.msjError.forEach( datos => datos = null);
                for(let dato in this.msjError){ this.msjError[dato] = null;}
                let flagError = false;

                if(!this.codigo){ this.msjError.codigo = 'Debe ingresar el código del artículo'; flagError = true;}
                if(!this.nombre){ this.msjError.nombre = 'Debe ingresar el nombre del artículo'; flagError = true;}
                if(!this.categoriaId){ this.msjError.categoria = 'Debe seleccionar una categoría'; flagError = true;}
                if(!this.descripcion){ this.msjError.descripcion = 'Debe ingresar una descripción del artículo'; flagError = true;}
                if(!this.precio_venta || this.precio_venta <= 0){ this.msjError.precio_venta = 'Debe ingresar un precio valido para el artículo'; flagError = true;}
                if(!this.stock || this.stock <= 0){ this.msjError.stock = 'Debe ingresar un número de stock válido'; flagError = true;}

                return flagError;
            },
            createArticulo(){
                if(this.validateArticulo())
                    return;

                let me = this;
                let data = {
                    'idcategoria' : me.categoriaId,
                    'codigo': me.codigo,
                    'nombre': me.nombre,
                    'precio_venta': me.precio_venta,
                    'stock': me.stock,
                    'descripcion': me.descripcion
                };
                axios.post(me.path+'articulo/registrar', data)
                    .then(reponse => {
                        me.hiddeModal();
                        me.listarArticulos();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            updateArticulo(){
                if(this.validateArticulo())
                    return;

                let me = this;
                let data = {
                    'idarticulo' : me.articuloId,
                    'idcategoria' : me.categoriaId,
                    'codigo': me.codigo,
                    'nombre': me.nombre,
                    'precio_venta': me.precio_venta,
                    'stock': me.stock,
                    'descripcion': me.descripcion
                };
                axios.put(me.path+'articulo/actualizar', data)
                    .then(reponse => {
                        me.hiddeModal();
                        me.listarArticulos();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            changeState(articulo){
                //alert('eliminar'+ categoria.condicion);
                let me = this;
                let url = "",
                    textMsj = "";
                if(articulo.condicion){
                    url = "articulo/desactivar";
                    textMsj = 'Se desactivará el registro'
                }
                else{
                    url = "articulo/activar";
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
                        axios.put(me.path+url, articulo)
                            .then(reponse => {
                                me.listarArticulos();
                                swalWithBootstrapButtons.fire(
                                    'Hecho!',
                                    'El registro a sido'+(articulo.condicion)?'desactivado':'activado',
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
                if(action === "create"){
                    this.flagModal = 1;
                    this.typeModal = 1;
                    this.titleModal = "Agregar artículo";
                    this.precio_venta = 0;
                    this.stock = 0;
                    this.listarCategorias();
                }

                if(action === "update"){
                    this.flagModal = 1;
                    this.typeModal = 2;
                    this.titleModal = "Actualizar artículo";
                    this.articuloId = data.id;
                    this.categoriaId = data.idcategoria;
                    this.codigo = data.codigo;
                    this.nombre = data.nombre;
                    this.precio_venta = data.precio_venta;
                    this.stock = data.stock;
                    this.descripcion = data.descripcion;
                    this.listarCategorias();
                }
            },
            hiddeModal(){
                this.flagModal = 0;
                this.typeModal = 1;
                this.categoriaId = "";
                this.codigo = null;
                this.nombre = null;
                this.precio_venta = null;
                this.stock = null;
                this.descripcion = null;
            }
        },
        mounted() {
            this.listarArticulos(1);
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

