@extends('voyager::master')

@section('css')
    <script type="text/javascript" src="{{ voyager_asset('js/vue1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ voyager_asset('css/media/media.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ voyager_asset('js/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ voyager_asset('css/media/dropzone.css') }}"/>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">

                <div class="admin-section-title">
                    <h3><i class="voyager-images"></i> Медиа</h3>
                </div>
                <div class="clear"></div>

                <div id="filemanager">

                    <div id="toolbar">
                        <div class="btn-group offset-right">
                            <button type="button" class="btn btn-primary" id="upload"><i class="voyager-upload"></i>
                                Загрузить
                            </button>
                            <button type="button" class="btn btn-primary" id="new_folder"
                                    onclick="jQuery('#new_folder_modal').modal('show');"><i class="voyager-folder"></i>
                                Добавить папку
                            </button>
                        </div>
                        <button type="button" class="btn btn-default" id="refresh"><i class="voyager-refresh"></i>
                        </button>
                        <div class="btn-group offset-right">
                            <button type="button" class="btn btn-default" id="move"><i class="voyager-move"></i> Переместить
                            </button>
                            <button type="button" class="btn btn-default" id="rename"><i class="voyager-character"></i>
                                Переименовать
                            </button>
                            <button type="button" class="btn btn-default" id="delete"><i class="voyager-trash"></i>
                                Удалить
                            </button>
                        </div>
                    </div>

                    <div id="uploadPreview" style="display:none;"></div>

                    <div id="uploadProgress" class="progress active progress-striped">
                        <div class="progress-bar progress-bar-success" style="width: 0"></div>
                    </div>

                    <div id="content">


                        <div class="breadcrumb-container">
                            <ol class="breadcrumb filemanager">
                                <li data-folder="/" data-index="0"><span class="arrow"></span><strong>Медиа 
                                    библиотека</strong></li>
                                <template v-for="folder in folders">
                                    <li data-folder="@{{folder}}" data-index="@{{ $index+1 }}"><span
                                                class="arrow"></span>@{{ folder }}</li>
                                </template>
                            </ol>

                            <div class="toggle"><span>Свернуть</span><i class="voyager-double-right"></i></div>
                        </div>
                        <div class="flex">

                            <div id="left">

                                <ul id="files">

                                    <li v-for="file in files.items">
                                        <div class="file_link" data-folder="@{{file.name}}" data-index="@{{ $index }}">
                                            <div class="link_icon">
                                                <template v-if="file.type.includes('image')">
                                                    <div class="img_icon"
                                                         style="background-size: cover; background-image: url(@{{ encodeURI(file.path) }}); background-repeat:no-repeat; background-position:center center;display:inline-block; width:100%; height:100%;"></div>
                                                </template>
                                                <template v-if="file.type.includes('video')">
                                                    <i class="icon voyager-video"></i>
                                                </template>
                                                <template v-if="file.type.includes('audio')">
                                                    <i class="icon voyager-music"></i>
                                                </template>
                                                <template v-if="file.type == 'folder'">
                                                    <i class="icon voyager-folder"></i>
                                                </template>
                                                <template
                                                        v-if="file.type != 'folder' && !file.type.includes('image') && !file.type.includes('video') && !file.type.includes('audio')">
                                                    <i class="icon voyager-file-text"></i>
                                                </template>

                                            </div>
                                            <div class="details @{{ file.type }}"><h4>@{{ file.name }}</h4>
                                                <small>
                                                    <template v-if="file.type == 'folder'">
                                                    <!--span class="num_items">@{{ file.items }} file(s)</span-->
                                                    </template>
                                                    <template v-else>
                                                        <span class="file_size">@{{ file.size }}</span>
                                                    </template>
                                                </small>
                                            </div>
                                        </div>
                                    </li>

                                </ul>

                                <div id="file_loader">
                                    <?php $admin_loader_img = Voyager::setting('admin_loader', ''); ?>
                                    @if($admin_loader_img == '')
                                        <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">
                                    @else
                                        <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
                                    @endif
                                    <p>Загрузка файлов</p>
                                </div>

                                <div id="no_files">
                                    <h3><i class="voyager-meh"></i> Нет файлов в этой папке.</h3>
                                </div>

                            </div>

                            <div id="right">
                                <div class="right_none_selected">
                                    <i class="voyager-cursor"></i>
                                    <p>No File or Folder Selected</p>
                                </div>
                                <div class="right_details">
                                    <div class="detail_img @{{ selected_file.type }}">
                                        <template v-if="selected_file.type.includes('image')">
                                            <img src="@{{ selected_file.path }}"/>
                                        </template>
                                        <template v-if="selected_file.type.includes('video')">
                                            <video width="100%" height="auto" controls>
                                                <source src="@{{selected_file.path}}" type="video/mp4">
                                                <source src="@{{selected_file.path}}" type="video/ogg">
                                                <source src="@{{selected_file.path}}" type="video/webm">
                                                Ваш бразуер не поддерживает предпросмотр видео
                                            </video>
                                        </template>
                                        <template v-if="selected_file.type.includes('audio')">
                                            <audio controls style="width:100%; margin-top:5px;">
                                                <source src="@{{selected_file.path}}" type="audio/ogg">
                                                <source src="@{{selected_file.path}}" type="audio/mpeg">
                                                Ваш браузер не поддерживает прослушивание аудио
                                            </audio>
                                        </template>
                                        <template v-if="selected_file.type == 'folder'">
                                            <i class="voyager-folder"></i>
                                        </template>
                                        <template
                                                v-if="selected_file.type != 'folder' && !selected_file.type.includes('audio') && !selected_file.type.includes('video') && !selected_file.type.includes('image')">
                                            <i class="voyager-file-text-o"></i>
                                        </template>

                                    </div>
                                    <div class="detail_info @{{selected_file.type}}">
							<span><h4>Название:</h4>
							<p>@{{selected_file.name}}</p></span>
                                        <span><h4>Тип:</h4>
							<p>@{{selected_file.type}}</p></span>
                                        <template v-if="selected_file.type != 'folder'">
								<span><h4>Размер:</h4>
								<p><span class="selected_file_count">@{{ selected_file.items }} item(s)</span><span
                                            class="selected_file_size">@{{selected_file.size}}</span></p></span>
                                            <span><h4>Публичный URL:</h4>
								<p><a href="@{{ selected_file.path }}" target="_blank">Нажмите сюда</a></p></span>
                                            <span><h4>Изменен:</h4>
								<p>@{{selected_file.last_modified}}</p></span>
                                        </template>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="nothingfound">
                            <div class="nofiles"></div>
                            <span>Нет файлов здесь.</span>
                        </div>

                    </div>

                    <!-- Move File Modal -->
                    <div class="modal fade modal-warning" id="move_file_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><i class="voyager-move"></i>Переместить папку/файл</h4>
                                </div>

                                <div class="modal-body">
                                    <h4>Папка назначения</h4>
                                    <select id="move_folder_dropdown">
                                        <template v-if="folders.length">
                                            <option value="/../">../</option>
                                        </template>
                                        <template v-for="dir in directories">
                                            <option value="@{{ dir }}">@{{ dir }}</option>
                                        </template>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-warning" id="move_btn">Переместить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Move File Modal -->

                    <!-- Rename File Modal -->
                    <div class="modal fade modal-warning" id="rename_file_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><i class="voyager-character"></i> Переименовать папку/файл</h4>
                                </div>

                                <div class="modal-body">
                                    <h4>Новое имя файла/папки</h4>
                                    <input id="new_filename" class="form-control" type="text"
                                           value="@{{selected_file.name}}">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                                    <button type="button" class="btn btn-warning" id="rename_btn">Переименовать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Move File Modal -->

                </div><!-- #filemanager -->

                <!-- New Folder Modal -->
                <div class="modal fade modal-info" id="new_folder_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="voyager-folder"></i> Добавить новую папку</h4>
                            </div>

                            <div class="modal-body">
                                <input name="new_folder_name" id="new_folder_name" placeholder="Название новой папки"
                                       class="form-control" value=""/>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                <button type="button" class="btn btn-info" id="new_folder_submit">Создать новую папку
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End New Folder Modal -->

                <!-- Delete File Modal -->
                <div class="modal fade modal-danger" id="confirm_delete_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="voyager-warning"></i> Вы уверены?</h4>
                            </div>

                            <div class="modal-body">
                                <h4>Вы действительно хотите удалить '<span class="confirm_delete_name"></span>'</h4>
                                <h5 class="folder_warning"><i class="voyager-warning"></i> Удалив папку вы удалите все файлы внутри нее</h5>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                <button type="button" class="btn btn-danger" id="confirm_delete">Да, удалить!
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Delete File Modal -->

                <div id="dropzone"></div>
                <!-- Delete File Modal -->
                <div class="modal fade" id="upload_files_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="voyager-warning"></i> Перенесите файл в окно или нажмите для загрузки</h4>
                            </div>

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-dismiss="modal">Завершено</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Delete File Modal -->


            </div><!-- .row -->
        </div><!-- .col-md-12 -->
    </div><!-- .page-content container-fluid -->


    <input type="hidden" id="storage_path" value="{{ storage_path() }}">

@section('javascript')
<script src="{{ voyager_asset('js/media/dropzone.js') }}"></script>
<script src="{{ voyager_asset('js/media/media.js') }}"></script>
<script type="text/javascript">
    var media = new VoyagerMedia({
        baseUrl: "{{ route('voyager.dashboard') }}"
    });
    $(function () {
        media.init();
    });
</script>
@endsection
@stop
